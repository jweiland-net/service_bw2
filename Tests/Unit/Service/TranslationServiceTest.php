<?php
namespace JWeiland\ServiceBw2\Tests\Unit\Service;

/*
 * This file is part of the service_bw2 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Service\TranslationService;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \JWeiland\ServiceBw2\Service\TranslationService
 */
class TranslationServiceTest extends UnitTestCase
{
    /**
     * @var TranslationService
     */
    protected $subject;

    /**
     * set up.
     */
    public function setUp()
    {
        $extConf = new ExtConf();
        $extConf->setAllowedLanguages('de=0;en=1;fr=2');

        $this->subject = new TranslationService();
        $this->subject->injectExtConf($extConf);
        $this->subject->initializeObject();
    }

    /**
     * tear down.
     */
    public function tearDown()
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

        $this->assertSame(
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

        $this->assertSame(
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

        $this->assertSame(
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
