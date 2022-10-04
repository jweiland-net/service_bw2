<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend;

use JWeiland\ServiceBw2\Indexer\Indexer;
use JWeiland\ServiceBw2\Service\SolrIndexService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return static function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    if (ExtensionManagementUtility::isLoaded('solr')) {
        $definition = new Definition();
        $definition->setPublic(true);
        $definition->setAutowired(true);
        $definition->setAutoconfigured(true);

        $containerBuilder->setDefinition(Indexer::class, $definition);
        $containerBuilder->setDefinition(SolrIndexService::class, $definition);
    }
};
