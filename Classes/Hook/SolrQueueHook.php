<?php
namespace JWeiland\ServiceBw2\Hook;

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
 * Class SolrQueueHook
 *
 * @package JWeiland\ServiceBw2\Hook
 */
class SolrQueueHook
{
    /**
     * Ignore database and just pass uid through
     *
     * @param array $params
     * @return void
     */
    public function addRecordsAfterFetching(&$params)
    {
        if ($params && $params['table'] === 'servicebw2_organisationsEinheiten') {
            foreach ($params['uids'] as $uid) {
                $params['tableRecords']['servicebw2_organisationsEinheiten'][$uid] = [
                    'uid' => $uid
                ];
            }
        }
    }
}
