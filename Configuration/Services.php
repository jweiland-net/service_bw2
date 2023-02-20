<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend;

use JWeiland\ServiceBw2\Indexer\Indexer;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    try {
        if ($containerBuilder->get(\ApacheSolrForTypo3\Solr\IndexQueue\Indexer::class)) {
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
