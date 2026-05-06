<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Routing\Aspect;

use JWeiland\ServiceBw2\Controller\ControllerTypeEnum;
use JWeiland\ServiceBw2\Domain\Repository\RepositoryFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;
use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Mapper to map an ID of service_bw API to title.
 *
 * routeEnhancers:
 *   ServiceBwPlugin:
 *     type: Extbase
 *     extension: ServiceBw2
 *     plugin: ServiceBw
 *     routes:
 *       -
 *         routePath: '/service-bw/lebenslagen/show/{lebenslage}'
 *         _controller: 'Lebenslagen::show'
 *         _arguments:
 *           lebenslage: id
 *       -
 *         routePath: '/service-bw/leistungen/show/{leistung}'
 *         _controller: 'Leistungen::show'
 *         _arguments:
 *           leistung: id
 *       -
 *         routePath: '/service-bw/organisationseinheiten/show/{organisationseinheit}'
 *         _controller: 'Organisationseinheiten::show'
 *         _arguments:
 *           organisationseinheit: id
 *     requirements:
 *       organisationseinheit: '^\d+-[a-zA-Z0-9\-]+$'
 *     defaultController: 'Organisationseinheiten::list'
 *     aspects:
 *       lebenslage:
 *         type: ServiceBwTitleMapper
 *         controllerType: lebenslagen
 *       leistung:
 *         type: ServiceBwTitleMapper
 *         controllerType: leistungen
 *       organisationseinheit:
 *         type: ServiceBwTitleMapper
 *         controllerType: organisationseinheiten
 */
class ServiceBwTitleMapper implements StaticMappableAspectInterface
{
    protected array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function generate(string $value): ?string
    {
        $entityId = (int)$value;

        try {
            $repository = $this->getRepositoryFactory()->getRepository(
                ControllerTypeEnum::from((string)($this->settings['controllerType'] ?? '')),
            );

            $records = $repository->findAll();
        } catch (\Exception|\Throwable) {
            return null;
        }

        $titlesById = array_column($records, 'name', 'id');
        $title = $titlesById[$entityId] ?? '';
        if ($title === '') {
            return null;
        }

        // SlugHelper->sanitize will not replace / to -, so do it here
        $title = str_replace('/', '-', $title);

        // make it url friendly
        return sprintf(
            '%d-%s',
            (int)$value,
            $this->getSlugHelper()->sanitize($title),
        );
    }

    public function resolve(string $value): ?string
    {
        if (preg_match('/(?<id>\d+)-.*/', $value, $matches)) {
            $id = $matches['id'];
        } else {
            throw new InvalidPathException('Could not find id by path for service_bw2 RouteEnhancer!', 1525782342);
        }

        return $id;
    }

    protected function getSlugHelper(): SlugHelper
    {
        // We don't have any table- or fieldname. We only have a JSON result,
        // so keep table- and fieldname empty
        return GeneralUtility::makeInstance(
            SlugHelper::class,
            '',
            '',
            [
                'fallbackCharacter' => '-',
                'prependSlash' => false,
            ],
        );
    }

    protected function getRepositoryFactory(): RepositoryFactory
    {
        return GeneralUtility::makeInstance(RepositoryFactory::class);
    }
}
