<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Utility;

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

/**
 * Class AlphabeticalIndexUtility
 *
 * @package JWeiland\ServiceBw2\Utility;
 */
class AlphabeticalIndexUtility
{
    /**
     * German alphabet
     */
    const GERMAN_ALPHABET = [
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
        'Ü' => false
    ];

    /**
     * Char list for trim
     */
    const TRIM_CHAR_LIST = ' "\'';

    /**
     * Get navigation array header for $records
     * will return an array with alphabetic letters
     * from passed alphabet (german by default) and
     * sets entries true if a record begins with that
     * letter
     * e.g. ['A' => true, 'B' => false, 'C' => true, ...]
     *
     * @param array $records from your request
     * @param string $titleField as structure from your record
     * @param array $alphabet array with letters as key and boolean values as value
     * @return array
     */
    public static function getNavigationHeader(
        array $records,
        string $titleField,
        array $alphabet = self::GERMAN_ALPHABET
    ): array
    {
        foreach ($records as $record) {
            $alphabet[self::getFirstLetterOfRecordTitle($record[$titleField])] = true;
        }
        return $alphabet;
    }

    /**
     * Get a list of passed (maybe unsorted) records, sorted by their first
     * letter. Will return an array like
     * ['A' => [], 'B' => [], ...]
     * An array key will only exist if their is at least one record inside of it!
     *
     * @param array $records from your request
     * @param string $titleField as structure from your record
     * @return array
     */
    public static function getSortedRecordList(
        array $records,
        string $titleField
    ): array
    {
        $sortedRecords = [];
        foreach ($records as $record) {
            $sortedRecords[self::getFirstLetterOfRecordTitle($record[$titleField])][] = $record;
        }
        ksort($sortedRecords);
        return $sortedRecords;
    }

    /**
     * Returns the first letter of a record title as upper char
     *
     * @param string $recordTitle
     * @return string
     */
    protected static function getFirstLetterOfRecordTitle(string $recordTitle): string
    {
        return strtoupper(mb_substr(trim($recordTitle, self::TRIM_CHAR_LIST), 0, 1));
    }
}
