<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Unit\Service;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Service\TranslationService;
use JWeiland\ServiceBw2\Tests\Unit\Configuration\ExtensionConfigurationMockTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class \JWeiland\ServiceBw2\Service\TranslationService
 */
class TranslationServiceTest extends UnitTestCase
{
    use ExtensionConfigurationMockTrait;

    /**
     * @var TranslationService
     */
    protected $subject;

    /**
     * set up.
     */
    public function setUp(): void
    {
        $this->addExtensionConfigurationMockToGeneralUtilityInstances();
        $extConf = GeneralUtility::makeInstance(ExtConf::class);
        $extConf->setAllowedLanguages('de=0;en=1;fr=2');

        $GLOBALS['TYPO3_REQUEST'] = new ServerRequest();

        $this->subject = new TranslationService();
        $this->subject->injectExtConf($extConf);
        $this->subject->initializeObject();
    }

    /**
     * tear down.
     */
    public function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function translateRecordsWithEmptyRecordsWillResultInEmptyTranslations()
    {
        $data = [];
        $this->subject->translateRecords($data);

        self::assertSame(
            [],
            $data
        );
    }

    /**
     * @test
     */
    public function translateRecordsWithTwoLanguagesWillReturnGermanLanguage()
    {
        $data = [
            0 => [
                'i18n' => [
                    0 => [
                        'title' => 'Kategorie',
                        'sprache' => 'de'
                    ],
                    1 => [
                        'title' => 'Category',
                        'sprache' => 'en'
                    ]
                ]
            ]
        ];
        $this->subject->translateRecords($data);

        self::assertSame(
            [
                0 => [
                    'title' => 'Kategorie'
                ]
            ],
            $data
        );
    }

    /**
     * @test
     */
    public function translateRecordsWillMergeParentKeysWithGermanTranslation()
    {
        $data = [
            0 => [
                'firstName' => 'Stefan',
                'i18n' => [
                    0 => [
                        'gender' => 'Mann',
                        'sprache' => 'de'
                    ],
                    1 => [
                        'gender' => 'man',
                        'sprache' => 'en'
                    ]
                ]
            ],
            1 => [
                'firstName' => 'Petra',
                'i18n' => [
                    0 => [
                        'gender' => 'Frau',
                        'sprache' => 'de'
                    ],
                    1 => [
                        'gender' => 'woman',
                        'sprache' => 'en'
                    ]
                ]
            ]
        ];
        $this->subject->translateRecords($data);

        self::assertSame(
            [
                0 => [
                    'firstName' => 'Stefan',
                    'gender' => 'Mann',
                ],
                1 => [
                    'firstName' => 'Petra',
                    'gender' => 'Frau',
                ],
            ],
            $data
        );
    }
}
