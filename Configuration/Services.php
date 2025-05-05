<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3\CMS\Backend;

use JWeiland\ServiceBw2\Indexer\Indexer;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    try {
        if ($containerBuilder->get(\ApacheSolrForTypo3\Solr\IndexQueue\Indexer::class) !== null) {
            $definition = new Definition();
            $definition->setPublic(true);
            $definition->setAutowired(true);
            $definition->setAutoconfigured(true);

            $containerBuilder->setDefinition(Indexer::class, $definition);
            $containerBuilder->setDefinition(SolrIndexService::class, $definition);
        }
    } catch (\Exception $exception) {
        // Service not found. Do not touch configuration
    }
};
