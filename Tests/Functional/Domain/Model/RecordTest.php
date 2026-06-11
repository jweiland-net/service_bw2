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
}
