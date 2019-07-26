<?php declare(strict_types=1);
namespace JWeiland\ServiceBw2\Utility;

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

/**
 * Class AlphabeticalIndexUtility
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
     * The $sortedLetterList is an array with alphabetic letters
     * from passed alphabet (german by default).
     * Entries are (bool) true if $records contains a record that begins with that letter
     * e.g. ['A' => true, 'B' => false, 'C' => true, ...]
     *
     * The $sortedRecordList is a list of passed (maybe unsorted) records, sorted by their first
     * letter.
     * e.g. ['A' => [], 'B' => [], ...]
     * An array key will only exist if their is at least one record inside of it!
     *
     * @param array $records from your request
     * @param string $titleField as structure from your record
     * @param array $sortedLetterList reference for letter list (navigation part)
     * @param array $sortedRecordList reference for record list (list part)
     * @param array $alphabet array with letters as key and boolean value as value (default letters)
     */
    public static function createAlphabeticalIndex(
        array $records,
        string $titleField,
        array &$sortedLetterList,
        array &$sortedRecordList,
        array $alphabet = self::GERMAN_ALPHABET
    )
    {
        $sortedLetterList = $alphabet;
        foreach ($records as $record) {
            $sortedLetterList[self::getFirstLetterOfRecordTitle($record[$titleField])] = true;
            $sortedRecordList[self::getFirstLetterOfRecordTitle($record[$titleField])][] = $record;
        }
        ksort($sortedRecordList);
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
