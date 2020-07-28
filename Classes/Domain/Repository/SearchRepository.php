<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Domain\Repository;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\Request\Search;

/**
 * Class SearchRepository
 */
class SearchRepository extends AbstractRepository
{
    /**
     * @var ExtConf
     */
    protected $extConf;

    /**
     * @param ExtConf $extConf
     */
    public function injectExtConf(ExtConf $extConf): void
    {
        $this->extConf = $extConf;
    }

    /**
     * Search for something
     *
     * @param string $query e.g. Personalentwicklung
     * @param string $filter e.g. organisationseinheit
     * @param string $sortBy e.g. relevance
     * @param string $lang e.g. de
     * @return array
     */
    public function search(string $query, string $filter, string $sortBy = 'relevance', string $lang = 'de'): array
    {
        $request = $this->objectManager->get(Search::class);
        $request->addParameter('q', $query);
        $request->addParameter('f', $filter);
        $request->addParameter('s', $sortBy);
        $request->addParameter('lang', $lang);
        $request->addParameter('position', $this->extConf->getRegionIds());
        return $this->serviceBwClient->processRequest($request);
    }
}
