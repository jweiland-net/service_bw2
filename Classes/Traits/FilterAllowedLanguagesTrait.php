<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Traits;

use Symfony\Component\Console\Input\InputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait FilterAllowedLanguagesTrait
{
    /**
     * @return string[] Array of 2-letter language ISO codes
     */
    protected function filterAllowedLanguages(InputInterface $input, array $allAllowedLanguages): array
    {
        $allAllowedLanguages = array_keys($allAllowedLanguages);
        $requestedLanguages = GeneralUtility::trimExplode(
            ',',
            (string)$input->getOption('locales'),
            true,
        );

        $allowedLanguages = array_intersect(
            $requestedLanguages,
            $allAllowedLanguages,
        );

        if ($allowedLanguages === []) {
            $allowedLanguages = $allAllowedLanguages;
        }

        return $allowedLanguages;
    }
}
