<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Traits;

use JWeiland\ServiceBw2\Domain\Model\Record;
use JWeiland\ServiceBw2\Traits\FilterOrganisationseinheitenTrait;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FilterOrganisationseinheitenTraitTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    private object $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new class () {
            use FilterOrganisationseinheitenTrait;

            public function filter(array $oes, array $allowedParentIds, int $maxDepth = 2): array
            {
                return $this->filterOrganisationseinheitenByParentIds($oes, $allowedParentIds, $maxDepth);
            }
        };
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->subject);
    }

    private function makeRecord(int $id, string $name, ?int $parentId = null, ?string $parentName = null, array $children = []): Record
    {
        $data = ['id' => $id, 'name' => $name, 'untergeordneteOEs' => $children];
        if ($parentId !== null) {
            $data['uebergeordneteOE'] = ['id' => $parentId, 'name' => $parentName ?? ''];
        }

        return new Record($id, $name, 'organisationseinheiten', 'de', $data);
    }

    #[Test]
    public function filterWithEmptyListReturnsEmptyArray(): void
    {
        self::assertSame([], $this->subject->filter([], [100]));
    }

    #[Test]
    public function filterWithEmptyAllowedIdsReturnsEmptyArray(): void
    {
        $oe = $this->makeRecord(1, 'Child', 100, 'Parent');

        self::assertSame([], $this->subject->filter([$oe], []));
    }

    #[Test]
    public function filterIncludesOeWhoseDirectParentIsAllowed(): void
    {
        $oe = $this->makeRecord(10, 'Child', 100, 'Root');

        $result = $this->subject->filter([$oe], [100]);

        self::assertCount(1, $result);
        self::assertSame(10, $result[0]->getId());
    }

    #[Test]
    public function filterIncludesOeWhoseGrandparentIsAllowed(): void
    {
        $grandchild = new Record(
            20,
            'Grandchild',
            'organisationseinheiten',
            'de',
            [
                'id' => 20,
                'name' => 'Grandchild',
                'uebergeordneteOE' => [
                    'id' => 10,
                    'name' => 'Child',
                    'uebergeordneteOE' => ['id' => 100, 'name' => 'Root'],
                ],
                'untergeordneteOEs' => [],
            ],
        );

        $result = $this->subject->filter([$grandchild], [100]);

        self::assertCount(1, $result);
        self::assertSame(20, $result[0]->getId());
    }

    #[Test]
    public function filterExcludesOeWithNoParent(): void
    {
        $oe = $this->makeRecord(1, 'Root');

        self::assertSame([], $this->subject->filter([$oe], [100]));
    }

    #[Test]
    public function filterExcludesOeWhoseParentIsNotAllowed(): void
    {
        $oe = $this->makeRecord(10, 'Child', 999, 'Other');

        self::assertSame([], $this->subject->filter([$oe], [100]));
    }

    #[Test]
    public function filterSortsResultsByName(): void
    {
        $b = $this->makeRecord(2, 'Beta', 100, 'Root');
        $a = $this->makeRecord(1, 'Alpha', 100, 'Root');
        $c = $this->makeRecord(3, 'Gamma', 100, 'Root');

        $result = $this->subject->filter([$b, $a, $c], [100]);

        self::assertSame('Alpha', $result[0]->getName());
        self::assertSame('Beta', $result[1]->getName());
        self::assertSame('Gamma', $result[2]->getName());
    }

    #[Test]
    public function filterPreservesNestedChildrenUpToMaxDepth(): void
    {
        // depth 0: matched OE (id=10)
        // depth 1: child (id=11)
        // depth 2: grandchild (id=12)
        // depth 3: great-grandchild (id=13) — must be stripped at maxDepth=2
        $oe = new Record(
            10,
            'OE',
            'organisationseinheiten',
            'de',
            [
                'id' => 10,
                'name' => 'OE',
                'uebergeordneteOE' => ['id' => 100, 'name' => 'Root'],
                'untergeordneteOEs' => [
                    [
                        'id' => 11,
                        'name' => 'Child',
                        'untergeordneteOEs' => [
                            [
                                'id' => 12,
                                'name' => 'Grandchild',
                                'untergeordneteOEs' => [
                                    ['id' => 13, 'name' => 'GreatGrandchild', 'untergeordneteOEs' => [], 'uebergeordneteOE' => ['id' => 12, 'name' => 'Grandchild']],
                                ],
                                'uebergeordneteOE' => ['id' => 11, 'name' => 'Child'],
                            ],
                        ],
                        'uebergeordneteOE' => ['id' => 10, 'name' => 'OE'],
                    ],
                ],
            ],
        );

        $result = $this->subject->filter([$oe], [100], 2);

        self::assertCount(1, $result);
        $children = $result[0]->getUntergeordneteOEs();
        self::assertCount(1, $children);
        self::assertSame(11, $children[0]->getId());

        $grandchildren = $children[0]->getUntergeordneteOEs();
        self::assertCount(1, $grandchildren);
        self::assertSame(12, $grandchildren[0]->getId());

        // depth 3 must be stripped
        self::assertSame([], $grandchildren[0]->getUntergeordneteOEs());
    }

    #[Test]
    public function filterStripsAllChildrenWhenMaxDepthIsZero(): void
    {
        $oe = $this->makeRecord(
            10,
            'OE',
            100,
            'Root',
            [['id' => 11, 'name' => 'Child', 'untergeordneteOEs' => []]],
        );

        $result = $this->subject->filter([$oe], [100], 0);

        self::assertCount(1, $result);
        self::assertSame([], $result[0]->getUntergeordneteOEs());
    }
}
