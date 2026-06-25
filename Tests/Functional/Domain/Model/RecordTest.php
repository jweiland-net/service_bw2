<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Domain\Model;

use JWeiland\ServiceBw2\Domain\Model\Record;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case.
 */
class RecordTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    #[Test]
    public function getIdWillReturnId()
    {
        $subject = new Record(
            1234,
            '',
            '',
            '',
            [],
        );

        self::assertSame(
            1234,
            $subject->getId(),
        );
    }

    #[Test]
    public function getNameWillReturnName()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            '',
            '',
            [],
        );

        self::assertSame(
            'TYPO3',
            $subject->getName(),
        );
    }

    #[Test]
    public function getTypeWillReturnType()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            '',
            [],
        );

        self::assertSame(
            'orga',
            $subject->getType(),
        );
    }

    #[Test]
    public function getLanguageWillReturnLanguage()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [],
        );

        self::assertSame(
            'en',
            $subject->getLanguage(),
        );
    }

    #[Test]
    public function getDataWillReturnData()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
            ],
        );

        self::assertSame(
            [
                'foo' => 'bar',
            ],
            $subject->getData(),
        );
    }

    #[Test]
    public function getHasProzesseWithProzesseWillReturnTrue()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'prozesse' => [
                    'foo' => 'bar',
                ],
            ],
        );

        self::assertTrue(
            $subject->getHasProzesse(),
        );
    }

    #[Test]
    public function getHasProzesseWithOnlinedienstFormularWillReturnTrue()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'formulare' => [
                    [
                        'typ' => 'ONLINEDIENST',
                    ],
                ],
            ],
        );

        self::assertTrue(
            $subject->getHasProzesse(),
        );
    }

    #[Test]
    public function getHasProzesseWithEmptyProzesseWillReturnFalse()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'prozesse' => [],
            ],
        );

        self::assertFalse(
            $subject->getHasProzesse(),
        );
    }

    #[Test]
    public function getHasProzesseWithMissingProzesseWillReturnFalse()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
            ],
        );

        self::assertFalse(
            $subject->getHasProzesse(),
        );
    }

    #[Test]
    public function getHasFormulareWithEmptyFormulareWillReturnFalse()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'formulare' => [],
            ],
        );

        self::assertFalse(
            $subject->getHasFormulare(),
        );
    }

    #[Test]
    public function getHasFormulareWithMissingFormulareWillReturnFalse()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
            ],
        );

        self::assertFalse(
            $subject->getHasFormulare(),
        );
    }

    #[Test]
    public function getHasFormulareWithTypeOnlinedienstWillReturnFalse()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'formulare' => [
                    [
                        'typ' => 'ONLINEDIENST',
                    ],
                ],
            ],
        );

        self::assertFalse(
            $subject->getHasFormulare(),
        );
    }

    #[Test]
    public function getHasFormulareWithoutOnlinedienstWillReturnTrue()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'formulare' => [
                    [
                        'typ' => 'Whatever',
                    ],
                ],
            ],
        );

        self::assertTrue(
            $subject->getHasFormulare(),
        );
    }

    #[Test]
    public function getHasFormulareWithMultipleTypesWillReturnTrue()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'formulare' => [
                    [
                        'typ' => 'ONLINEDIENST',
                    ],
                    [
                        'typ' => 'Whatever',
                    ],
                ],
            ],
        );

        self::assertTrue(
            $subject->getHasFormulare(),
        );
    }

    #[Test]
    public function getTextBloeckeWillReturnEmptyArray()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
            ],
        );

        self::assertSame(
            [],
            $subject->getTextBloecke(),
        );
    }

    #[Test]
    public function getTextBloeckeWillReturnTextBloecke()
    {
        $textBloecke = [
            0 => ['text' => 'Hello <a href="https://jweiland.net">jweiland.net</a>'],
        ];

        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'textbloecke' => $textBloecke,
            ],
        );

        self::assertSame(
            $textBloecke,
            $subject->getTextBloecke(),
        );
    }

    #[Test]
    public function getProcessedTextBloeckeWillReturnEmptyString()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
            ],
        );

        self::assertSame(
            '',
            $subject->getProcessedTextBloecke(),
        );
    }

    #[Test]
    public function getProcessedTextBloeckeWillReturnTextBloeckeAsString()
    {
        $textBloecke = [
            0 => ['text' => 'Hello <a href="https://jweiland.net">jweiland.net</a>'],
            1 => ['text' => 'Hello <a href="https://typo3.org">TYPO3 CMS</a>'],
        ];

        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            [
                'foo' => 'bar',
                'textbloecke' => $textBloecke,
            ],
        );

        self::assertSame(
            'Hello jweiland.net,Hello TYPO3 CMS',
            $subject->getProcessedTextBloecke(),
        );
    }

    #[Test]
    public function asArrayWillReturnDataArray()
    {
        $data = [
            'id' => 1234,
            'name' => 'TYPO3',
            'type' => 'orga',
        ];

        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            $data,
        );

        self::assertSame(
            $data,
            $subject->asArray(),
        );
    }

    #[Test]
    public function asArrayWillReturnDataArrayWithTextBloecke()
    {
        $textBloecke = [
            0 => ['text' => 'Hello <a href="https://jweiland.net">jweiland.net</a>'],
            1 => ['text' => 'Hello <a href="https://typo3.org">TYPO3 CMS</a>'],
        ];

        $data = $expectedData = [
            'id' => 1234,
            'name' => 'TYPO3',
            'type' => 'orga',
            'textbloecke' => $textBloecke,
        ];

        $subject = new Record(
            1234,
            'TYPO3',
            'orga',
            'en',
            $data,
        );

        $expectedData['processed_textbloecke'] = 'Hello jweiland.net,Hello TYPO3 CMS';

        self::assertSame(
            $expectedData,
            $subject->asArray(),
        );
    }

    #[Test]
    public function getUntergeordneteOEsWithMissingKeyWillReturnEmptyArray()
    {
        $subject = new Record(1234, 'TYPO3', 'organisationseinheiten', 'de', []);

        self::assertSame([], $subject->getUntergeordneteOEs());
    }

    #[Test]
    public function getUntergeordneteOEsWithEmptyArrayWillReturnEmptyArray()
    {
        $subject = new Record(
            1234,
            'TYPO3',
            'organisationseinheiten',
            'de',
            ['untergeordneteOEs' => []],
        );

        self::assertSame([], $subject->getUntergeordneteOEs());
    }

    #[Test]
    public function getUntergeordneteOEsWillReturnRecordObjects()
    {
        $subject = new Record(
            1,
            'Root',
            'organisationseinheiten',
            'de',
            [
                'untergeordneteOEs' => [
                    ['id' => 2, 'name' => 'Child A', 'untergeordneteOEs' => []],
                    ['id' => 3, 'name' => 'Child B', 'untergeordneteOEs' => []],
                ],
            ],
        );

        $children = $subject->getUntergeordneteOEs();

        self::assertCount(2, $children);
        self::assertContainsOnlyInstancesOf(Record::class, $children);
        self::assertSame(2, $children[0]->getId());
        self::assertSame('Child A', $children[0]->getName());
        self::assertSame('organisationseinheiten', $children[0]->getType());
        self::assertSame('de', $children[0]->getLanguage());
        self::assertSame(3, $children[1]->getId());
    }

    #[Test]
    public function getUntergeordneteOEsSkipsNonArrayEntries()
    {
        $subject = new Record(
            1,
            'Root',
            'organisationseinheiten',
            'de',
            [
                'untergeordneteOEs' => [
                    ['id' => 2, 'name' => 'Valid'],
                    'invalid-string-entry',
                    null,
                ],
            ],
        );

        $children = $subject->getUntergeordneteOEs();

        self::assertCount(1, $children);
        self::assertSame(2, $children[0]->getId());
    }

    #[Test]
    public function getUntergeordneteOEsInheritsTypeAndLanguageFromParent()
    {
        $subject = new Record(
            1,
            'Root',
            'organisationseinheiten',
            'fr',
            [
                'untergeordneteOEs' => [
                    ['id' => 2, 'name' => 'Child'],
                ],
            ],
        );

        $children = $subject->getUntergeordneteOEs();

        self::assertSame('organisationseinheiten', $children[0]->getType());
        self::assertSame('fr', $children[0]->getLanguage());
    }

    #[Test]
    public function withDataWillReturnNewRecordWithReplacedData(): void
    {
        $original = new Record(1234, 'TYPO3', 'orga', 'en', ['foo' => 'bar']);
        $modified = $original->withData(['baz' => 'qux']);

        self::assertNotSame($original, $modified);
        self::assertSame(1234, $modified->getId());
        self::assertSame('TYPO3', $modified->getName());
        self::assertSame('orga', $modified->getType());
        self::assertSame('en', $modified->getLanguage());
        self::assertSame(['baz' => 'qux'], $modified->getData());
        self::assertSame(['foo' => 'bar'], $original->getData());
    }

    #[Test]
    public function getUebergeordneteOEWithMissingKeyWillReturnNull(): void
    {
        $subject = new Record(1, 'Test', 'organisationseinheiten', 'de', []);

        self::assertNull($subject->getUebergeordneteOE());
    }

    #[Test]
    public function getUebergeordneteOEWithNonArrayValueWillReturnNull(): void
    {
        $subject = new Record(1, 'Test', 'organisationseinheiten', 'de', ['uebergeordneteOE' => 'invalid']);

        self::assertNull($subject->getUebergeordneteOE());
    }

    #[Test]
    public function getUebergeordneteOEWillReturnParentAsRecord(): void
    {
        $subject = new Record(
            2,
            'Child',
            'organisationseinheiten',
            'de',
            ['uebergeordneteOE' => ['id' => 1, 'name' => 'Parent']],
        );

        $parent = $subject->getUebergeordneteOE();

        self::assertInstanceOf(Record::class, $parent);
        self::assertSame(1, $parent->getId());
        self::assertSame('Parent', $parent->getName());
    }

    #[Test]
    public function getUebergeordneteOEInheritsTypeAndLanguageFromChild(): void
    {
        $subject = new Record(
            2,
            'Child',
            'organisationseinheiten',
            'fr',
            ['uebergeordneteOE' => ['id' => 1, 'name' => 'Parent']],
        );

        $parent = $subject->getUebergeordneteOE();

        self::assertSame('organisationseinheiten', $parent->getType());
        self::assertSame('fr', $parent->getLanguage());
    }

    #[Test]
    public function getUebergeordneteOESupportsChainedParents(): void
    {
        $subject = new Record(
            3,
            'Grandchild',
            'organisationseinheiten',
            'de',
            [
                'uebergeordneteOE' => [
                    'id' => 2,
                    'name' => 'Child',
                    'uebergeordneteOE' => ['id' => 1, 'name' => 'Root'],
                ],
            ],
        );

        $parent = $subject->getUebergeordneteOE();
        $grandparent = $parent->getUebergeordneteOE();

        self::assertSame(2, $parent->getId());
        self::assertInstanceOf(Record::class, $grandparent);
        self::assertSame(1, $grandparent->getId());
    }
}
