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
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \JWeiland\ServiceBw2\Service\TranslationService
 *
 * @author Stefan Froemken <projects@jweiland.net>
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
    public function emptyArrayResultsInEmptyArraysForEachLanguage()
    {
        $emptyArray = [];
        $this->assertSame(
            [
                'de' => [
                    0 => []
                ],
                'en' => [
                    0 => []
                ],
                'fr' => [
                    0 => []
                ],
            ],
            $this->subject->translateRecords($emptyArray)
        );
    }

    /**
     * This test will also test, if single array will be sanitized to multi array
     *
     * @test
     */
    public function arrayWithIdAndNoTranslationResultsInArraysForEachLanguage()
    {
        $data = [
            'id' => 123
        ];
        $this->assertSame(
            [
                'de' => [
                    123 => ['id' => 123]
                ],
                'en' => [
                    123 => ['id' => 123]
                ],
                'fr' => [
                    123 => ['id' => 123]
                ],
            ],
            $this->subject->translateRecords($data)
        );
    }

    /**
     * @test
     */
    public function translateWithoutSpracheCreatesThreeEmptyArrayEntries()
    {
        $data = [
            'i18n' => [
                0 => [
                    'title' => 'category'
                ]
            ]
        ];

        $this->assertSame(
            [
                'de' => [
                    0 => []
                ],
                'en' => [
                    0 => []
                ],
                'fr' => [
                    0 => []
                ],
            ],
            $this->subject->translateRecords($data)
        );
    }


    /**
     * @test
     */
    public function translateWithOneTranslationCreatesThreeArrayEntries()
    {
        $data = [
            'i18n' => [
                0 => [
                    'title' => 'category',
                    'sprache' => 'de'
                ]
            ]
        ];
        $this->assertSame(
            [
                'de' => [
                    0 => [
                        'title' => 'category',
                        '_languageUid' => 0
                    ]
                ],
                'en' => [
                    0 => [
                        'title' => 'category',
                        '_languageUid' => 1
                    ]
                ],
                'fr' => [
                    0 => [
                        'title' => 'category',
                        '_languageUid' => 2
                    ]
                ],
            ],
            $this->subject->translateRecords($data)
        );
    }

    /**
     * @test
     */
    public function translateWithTwoLanguagesCreatesThreeArrayEntries()
    {
        $data = [
            'i18n' => [
                0 => [
                    'title' => 'Kategorie',
                    'sprache' => 'de'
                ],
                1 => [
                    'title' => 'category',
                    'sprache' => 'en'
                ]
            ]
        ];
        $this->assertSame(
            [
                'de' => [
                    0 => [
                        'title' => 'Kategorie',
                        '_languageUid' => 0
                    ]
                ],
                'en' => [
                    0 => [
                        'title' => 'category',
                        '_languageUid' => 1
                    ]
                ],
                'fr' => [
                    0 => [
                        'title' => 'Kategorie',
                        '_languageUid' => 2
                    ]
                ],
            ],
            $this->subject->translateRecords($data)
        );
    }

    /**
     * @test
     */
    public function mergeRecordWithTranslationsCreatesThreeArrayEntries()
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
        $this->assertSame(
            [
                'de' => [
                    0 => [
                        'firstName' => 'Stefan',
                        'gender' => 'Mann',
                        '_languageUid' => 0
                    ],
                    1 => [
                        'firstName' => 'Petra',
                        'gender' => 'Frau',
                        '_languageUid' => 0
                    ],
                ],
                'en' => [
                    0 => [
                        'firstName' => 'Stefan',
                        'gender' => 'man',
                        '_languageUid' => 1
                    ],
                    1 => [
                        'firstName' => 'Petra',
                        'gender' => 'woman',
                        '_languageUid' => 1
                    ],
                ],
                'fr' => [
                    0 => [
                        'firstName' => 'Stefan',
                        'gender' => 'Mann',
                        '_languageUid' => 2
                    ],
                    1 => [
                        'firstName' => 'Petra',
                        'gender' => 'Frau',
                        '_languageUid' => 2
                    ],
                ],
            ],
            $this->subject->translateRecords($data)
        );
    }
}
