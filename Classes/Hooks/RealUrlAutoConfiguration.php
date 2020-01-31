<?php
declare(strict_types = 1);
namespace JWeiland\ServiceBw2\Hooks;

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

use DmitryDulepov\Realurl\Configuration\AutomaticConfigurator;
use JWeiland\ServiceBw2\RealUrl\TitleMapping;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Class RealUrlAutoConfiguration
 */
class RealUrlAutoConfiguration
{
    /**
     * Generates additional RealURL configuration and merges it with provided configuration
     *
     * @param array $parameters
     * @param AutomaticConfigurator $parentObject
     *
     * @return array Updated configuration
     */
    public function addConfig(array $parameters, AutomaticConfigurator $parentObject): array
    {
        ArrayUtility::mergeRecursiveWithOverrule(
            $parameters['config'],
            [
                'fileName' => [
                    'defaultToHTMLsuffixOnPrev' => true,
                ],
                'postVarSets' => [
                    '_DEFAULT' => [
                        'service-bw' => [
                            0 => [
                                'GETvar' => 'tx_servicebw2_servicebw[controller]',
                                'valueMap' => [
                                    'lebenslagen' => 'Lebenslagen',
                                    'leistungen' => 'Leistungen',
                                    'organisationseinheiten' => 'Organisationseinheiten'
                                ],
                                'noMatch' => 'null'
                            ],
                            1 => [
                                'GETvar' => 'tx_servicebw2_servicebw[action]'
                            ],
                            2 => [
                                'GETvar' => 'tx_servicebw2_servicebw[id]',
                                'userFunc' => TitleMapping::class . '->main'
                            ]
                        ],
                    ],
                ],
            ]
        );
        return $parameters['config'];
    }
}
