<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\ViewHelpers;

/*
* This file is part of the TYPO3 CMS project.
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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to render opening hours provided by Service BW API
 * Service BW brings a very weird structure so you should check
 * organisationseinheit.openingHours (HTML opening hours) if you
 * don´t get structuredOpeningHours (this weird array) from the
 * API
 * Will return the following structure (multi language):
 * <dl class="extdl clearfix">
 *   <dt>Opening hours</dt>
 *   <dd>Mon 08-12</dd>
 *   <dd>Tues 08-12</dd>
 *     ...
 * </dl>
 * Displays all opening hours using 24 hours system
 *
 * @package JWeiland\ServiceBw2\ViewHelpers;
 */
class OpeningHoursViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * As this ViewHelper renders HTML, the output must not be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Days provided by Service BW API
     * Yes in german -_-
     */
    const DAYS = ['MONTAG', 'DIENSTAG', 'MITTWOCH', 'DONNERSTAG', 'FREITAG'];

    /**
     * Initializes the arguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('structuredOpeningHours', 'array', 'Opening hours array', true);
    }

    /**
     * Returns the opening hours for a given opening hours array
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $html = [];
        $allowedTypes = ['ALLGEMEINE_OEFFNUNGSZEIT', 'BESUCHSZEIT', 'TELEFONISCHE_ERREICHBARKEIT'];
        // This array inlcudes an array from type ALLGEMEINE_OEFFNUNGSZEIT
        foreach ($arguments['structuredOpeningHours'] as $structuredOpeningHours) {
            if (isset($structuredOpeningHours['type'])) {
                if (in_array($structuredOpeningHours['type'], $allowedTypes, true)) {
                    $html[] = self::getStructuredOpeningHoursHTML($structuredOpeningHours);
                }
            }
        }
        return implode('', $html);
    }

    /**
     * Get HTML for an structuredOpeningHours array
     *
     * @param array $structuredOpeningHours
     * @return string
     */
    protected static function getStructuredOpeningHoursHTML(array $structuredOpeningHours): string
    {
        $html = [];
        if (isset($structuredOpeningHours['regulaereZeiten']) && is_array($structuredOpeningHours['regulaereZeiten'])) {
            // Forenoon opening hours
            $forenoonOpeningHours = '';
            // Afternoon opening hours
            $afternoonOpeningHours = [];

            self::processOpeningHours(
                $structuredOpeningHours['regulaereZeiten'],
                $forenoonOpeningHours,
                $afternoonOpeningHours
            );

            $html[] = '<dl class="extdl clearfix">';
            $html[] = '<dt>';
            $html[] = LocalizationUtility::translate(
                'organisationseinheit.opening_hours.' . $structuredOpeningHours['type'],
                'service_bw2'
            );
            $html[] = '</dt>';
            foreach (self::DAYS as $dayInGerman) {
                $afternoon = isset($afternoonOpeningHours[$dayInGerman]) && count($afternoonOpeningHours[$dayInGerman]);
                if ($forenoonOpeningHours || $afternoon) {
                    $html[] = '<dd class="structured-opening-hours">';
                    $html[] = LocalizationUtility::translate('opening_hours.short-form.' . $dayInGerman, 'service_bw2');
                    if ($forenoonOpeningHours) {
                        $html[] = ' ' . $forenoonOpeningHours;
                    }
                    if ($forenoonOpeningHours && $afternoon) {
                        $html[] = ',';
                    }
                    if ($afternoon) {
                        // sort by key because the key should be the start time
                        ksort($afternoonOpeningHours[$dayInGerman]);
                        $html[] = ' ';
                        $html[] = implode(', ', $afternoonOpeningHours[$dayInGerman]);
                    }
                    $html[] = '</dd>';
                }
            }
            $html[] = '</dl>';
        }
        return implode('', $html);
    }

    /**
     * Process opening hours using $structuredOpeningHours['regulaereZeiten'] array
     *
     * @param array $regulaereZeiten
     * @param string $forenoonOpeningHours reference!
     * @param array $afternoonOpeningHours reference!
     * @return void
     */
    protected static function processOpeningHours(
        array $regulaereZeiten,
        string &$forenoonOpeningHours,
        array &$afternoonOpeningHours
    ) {
        foreach ($regulaereZeiten as $regulaereZeitenDay) {
            if (
                array_key_exists('tagesposition', $regulaereZeitenDay)
                && array_key_exists('tagestyp', $regulaereZeitenDay)
                && array_key_exists('start', $regulaereZeitenDay)
                && array_key_exists('end', $regulaereZeitenDay)
            ) {
                // Opening hours monday to friday forenoon
                if ($regulaereZeitenDay['tagestyp'] === 'ARBEITSTAG_MO_FR') {
                    $forenoonOpeningHours = self::getRegulaereZeitenHours($regulaereZeitenDay);
                    // Opening hours individual day afternoon
                } else {
                    $regulaereZeitenHours = self::getRegulaereZeitenHours($regulaereZeitenDay);
                    $afternoonOpeningHours[$regulaereZeitenDay['tagestyp']][(int)substr($regulaereZeitenHours, 0, 2)]
                        = $regulaereZeitenHours;
                }
            }
        }
    }

    /**
     * Get the opening hours as string e.g. 07:00 - 12:00
     *
     * @param array $regulaereZeiten
     * @return string
     */
    protected static function getRegulaereZeitenHours(array $regulaereZeiten): string
    {
        return date('H:i', $regulaereZeiten['start'] / 1000) . ' - ' . date('H:i', $regulaereZeiten['end'] / 1000);
    }
}
