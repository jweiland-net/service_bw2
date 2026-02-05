<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Service;

/**
 * A Service to merge the TypoScript Settings (Framework) into the merged Settings (inkl. FlexForm),
 * if these are empty or 0
 */
readonly class TypoScriptService
{
    public function override(array &$mergedFlexFormSettings, array $typoScriptSettings): void
    {
        foreach ($typoScriptSettings as $property => $value) {
            if (isset($value) && is_array($value)) {
                $this->override($mergedFlexFormSettings[$property], $value);
            } elseif ($mergedFlexFormSettings[$property] === '0' || $mergedFlexFormSettings[$property] === '') {
                $mergedFlexFormSettings[$property] = $value;
            }
        }
    }
}
