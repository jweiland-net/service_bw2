<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Routing\Aspect;

use JWeiland\ServiceBw2\Domain\Repository\AbstractRepository;
use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;
use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/*
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
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(array $settings)
    {
        $this->settings = $settings;

        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * {@inheritdoc}
     */
    public function generate(string $value): ?string
    {
        $controllerType = (string)$this->settings['controllerType'];
        $repository = $this->getRepositoryForController($controllerType);
        switch ($controllerType) {
            case 'lebenslagen':
                $title = $repository->getById((int)$value)['displayName'];
                break;
            case 'leistungen':
                $title = $repository->getLiveById((int)$value)['title'];
                break;
            case 'organisationseinheiten':
                $title = $repository->getById((int)$value)['name'];
                break;
            default:
                $title = '';
                break;
        }

        // SlugHelper->sanitize will not replace / to -, so do it here
        $title = str_replace('/', '-', $title);

        // make it url friendly
        return sprintf(
            '%d-%s',
            (int)$value,
            $this->getSlugHelper()->sanitize($title)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(string $value): ?string
    {
        if (preg_match('/(?<id>\d+)-.*/', $value, $matches)) {
            $id = $matches['id'];
        } else {
            throw new InvalidPathException('Could not find id by path for service_bw2 RouteEnhancer!', 1525782342);
        }
        return (string)$id;
    }

    /**
     * Get repository for requested controller type
     *
     * @param string $controllerType
     * @return AbstractRepository
     * @throws \Exception if controller name is invalid
     */
    protected function getRepositoryForController(string $controllerType): AbstractRepository
    {
        switch ($controllerType) {
            case 'lebenslagen':
                $repository = $this->objectManager->get(LebenslagenRepository::class);
                break;
            case 'leistungen':
                $repository = $this->objectManager->get(LeistungenRepository::class);
                break;
            case 'organisationseinheiten':
                $repository = $this->objectManager->get(OrganisationseinheitenRepository::class);
                break;
            default:
                throw new \InvalidArgumentException(
                    'Could not find repository for selected controller type "' . $controllerType . '"!',
                    1523960421
                );
        }
        return $repository;
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
            ]
        );
    }
}
