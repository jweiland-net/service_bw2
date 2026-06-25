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

            public function filterDescendants(array $oes, array $allowedParentIds, int $maxDepth = 2): array
            {
                return $this->filterOrganisationseinheitenDescendantsByParentIds($oes, $allowedParentIds, $maxDepth);
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

    // -------------------------------------------------------------------------
    // filterOrganisationseinheitenByParentIds (frontend: own ID only)
    // -------------------------------------------------------------------------

    #[Test]
    public function filterWithEmptyListReturnsEmptyArray(): void
    {
        self::assertSame([], $this->subject->filter([], [100]));
    }

    #[Test]
    public function filterWithEmptyAllowedIdsReturnsEmptyArray(): void
    {
        $oe = $this->makeRecord(100, 'Root');

        self::assertSame([], $this->subject->filter([$oe], []));
    }

    #[Test]
    public function filterIncludesOeWhoseOwnIdIsAllowed(): void
    {
        $oe = $this->makeRecord(100, 'Root');

        $result = $this->subject->filter([$oe], [100]);

        self::assertCount(1, $result);
        self::assertSame(100, $result[0]->getId());
    }

    #[Test]
    public function filterExcludesOeWhoseOwnIdIsNotAllowed(): void
    {
        $oe = $this->makeRecord(10, 'Child', 100, 'Root');

        self::assertSame([], $this->subject->filter([$oe], [100]));
    }

    #[Test]
    public function filterExcludesOeWithNoParentAndNonMatchingId(): void
    {
        $oe = $this->makeRecord(1, 'Root');

        self::assertSame([], $this->subject->filter([$oe], [100]));
    }

    #[Test]
    public function filterSortsResultsByName(): void
    {
        $b = $this->makeRecord(2, 'Beta');
        $a = $this->makeRecord(1, 'Alpha');
        $c = $this->makeRecord(3, 'Gamma');

        $result = $this->subject->filter([$b, $a, $c], [1, 2, 3]);

        self::assertSame('Alpha', $result[0]->getName());
        self::assertSame('Beta', $result[1]->getName());
        self::assertSame('Gamma', $result[2]->getName());
    }

    #[Test]
    public function filterPreservesNestedChildrenUpToMaxDepth(): void
    {
        // depth 0: matched OE (id=100, own ID in allowed list)
        // depth 1: child (id=11)
        // depth 2: grandchild (id=12)
        // depth 3: great-grandchild (id=13) — must be stripped at maxDepth=2
        $oe = new Record(
            100,
            'Root',
            'organisationseinheiten',
            'de',
            [
                'id' => 100,
                'name' => 'Root',
                'untergeordneteOEs' => [
                    [
                        'id' => 11,
                        'name' => 'Child',
                        'untergeordneteOEs' => [
                            [
                                'id' => 12,
                                'name' => 'Grandchild',
                                'untergeordneteOEs' => [
                                    ['id' => 13, 'name' => 'GreatGrandchild', 'untergeordneteOEs' => []],
                                ],
                            ],
                        ],
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

        self::assertSame([], $grandchildren[0]->getUntergeordneteOEs());
    }

    #[Test]
    public function filterStripsAllChildrenWhenMaxDepthIsZero(): void
    {
        $oe = $this->makeRecord(
            100,
            'Root',
            null,
            null,
            [['id' => 11, 'name' => 'Child', 'untergeordneteOEs' => []]],
        );

        $result = $this->subject->filter([$oe], [100], 0);

        self::assertCount(1, $result);
        self::assertSame([], $result[0]->getUntergeordneteOEs());
    }

    // -------------------------------------------------------------------------
    // filterOrganisationseinheitenDescendantsByParentIds (Solr: recursive chain)
    // -------------------------------------------------------------------------

    #[Test]
    public function filterDescendantsWithEmptyListReturnsEmptyArray(): void
    {
        self::assertSame([], $this->subject->filterDescendants([], [100]));
    }

    #[Test]
    public function filterDescendantsWithEmptyAllowedIdsReturnsEmptyArray(): void
    {
        $oe = $this->makeRecord(10, 'Child', 100, 'Root');

        self::assertSame([], $this->subject->filterDescendants([$oe], []));
    }

    #[Test]
    public function filterDescendantsIncludesOeWhoseOwnIdIsAllowed(): void
    {
        $oe = $this->makeRecord(100, 'Root');

        $result = $this->subject->filterDescendants([$oe], [100]);

        self::assertCount(1, $result);
        self::assertSame(100, $result[0]->getId());
    }

    #[Test]
    public function filterDescendantsIncludesOeWhoseDirectParentIsAllowed(): void
    {
        $oe = $this->makeRecord(10, 'Child', 100, 'Root');

        $result = $this->subject->filterDescendants([$oe], [100]);

        self::assertCount(1, $result);
        self::assertSame(10, $result[0]->getId());
    }

    #[Test]
    public function filterDescendantsIncludesOeWhoseGrandparentIsAllowed(): void
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

        $result = $this->subject->filterDescendants([$grandchild], [100]);

        self::assertCount(1, $result);
        self::assertSame(20, $result[0]->getId());
    }

    #[Test]
    public function filterDescendantsExcludesOeAtDepthBeyondMaxDepth(): void
    {
        // 3 levels below the allowed root → must be excluded with maxDepth=2
        $greatGrandchild = new Record(
            30,
            'GreatGrandchild',
            'organisationseinheiten',
            'de',
            [
                'id' => 30,
                'name' => 'GreatGrandchild',
                'uebergeordneteOE' => [
                    'id' => 20,
                    'name' => 'Grandchild',
                    'uebergeordneteOE' => [
                        'id' => 10,
                        'name' => 'Child',
                        'uebergeordneteOE' => ['id' => 100, 'name' => 'Root'],
                    ],
                ],
                'untergeordneteOEs' => [],
            ],
        );

        $result = $this->subject->filterDescendants([$greatGrandchild], [100], 2);

        self::assertSame([], $result);
    }

    #[Test]
    public function filterDescendantsExcludesOeWithUnrelatedAncestors(): void
    {
        $oe = $this->makeRecord(10, 'Child', 999, 'Other');

        self::assertSame([], $this->subject->filterDescendants([$oe], [100]));
    }
}