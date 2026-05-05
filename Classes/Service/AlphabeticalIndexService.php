<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Service;

use JWeiland\ServiceBw2\Domain\Model\Record;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AlphabeticalIndexService
{
    public const GERMAN_ALPHABET = [
        'A' => false,
        'B' => false,
        'C' => false,
        'D' => false,
        'E' => false,
        'F' => false,
        'G' => false,
        'H' => false,
        'I' => false,
        'J' => false,
        'K' => false,
        'L' => false,
        'M' => false,
        'N' => false,
        'O' => false,
        'P' => false,
        'Q' => false,
        'R' => false,
        'S' => false,
        'T' => false,
        'U' => false,
        'V' => false,
        'W' => false,
        'X' => false,
        'Y' => false,
        'Z' => false,
        'Ä' => false,
        'Ö' => false,
        'Ü' => false,
    ];

    /**
     * Char list for trim
     */
    public const TRIM_CHAR_LIST = ' "\'';

    /**
     * Creates an alphabetical index from the given records.
     *
     * Returns:
     * - letters: alphabetical navigation list, e.g. ['A' => true, 'B' => false]
     * - records: records grouped by their first letter, e.g. ['A' => [...], 'B' => [...]]
     *
     * @param \Generator<Record> $records
     * @return array{
     *     letters: array<string, bool>,
     *     records: array<string, array<int, array<string, mixed>>>
     * }
     */
    public static function createAlphabeticalIndex(
        \Generator $records,
        string $titleField,
    ): array {
        $letterList = self::GERMAN_ALPHABET;
        $recordList = [];
        $getterMethodName = 'get' . GeneralUtility::underscoredToUpperCamelCase($titleField);

        foreach ($records as $record) {
            if (!method_exists($record, $getterMethodName)) {
                continue;
            }

            $title = $record->$getterMethodName();
            if (!is_scalar($title)) {
                continue;
            }

            $firstLetter = self::getFirstLetterOfRecordTitle((string)$title);
            if ($firstLetter === '') {
                continue;
            }

            $letterList[$firstLetter] = true;
            $recordList[$firstLetter][] = $record;
        }

        ksort($recordList);

        return [
            'letters' => $letterList,
            'records' => $recordList,
        ];
    }

    /**
     * Returns the first letter of a record title as upper char.
     */
    protected static function getFirstLetterOfRecordTitle(string $recordTitle): string
    {
        return mb_strtoupper(mb_substr(trim($recordTitle, self::TRIM_CHAR_LIST), 0, 1));
    }
}
