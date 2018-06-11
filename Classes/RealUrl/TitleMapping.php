<?php
declare(strict_types=1);
namespace JWeiland\ServiceBw2\RealUrl;

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

use DmitryDulepov\Realurl\Decoder\UrlDecoder;
use DmitryDulepov\Realurl\EncodeDecoderBase;
use DmitryDulepov\Realurl\Utility;
use JWeiland\ServiceBw2\Domain\Repository\AbstractRepository;
use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use JWeiland\ServiceBw2\Domain\Repository\SearchRepository;
use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class TitleMapping
 */
class TitleMapping
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Utility from RealURL
     *
     * @var Utility
     */
    protected $utility;

    /**
     * TitleMapping constructor.
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * Main method for Real URL userFunc.
     * Returns a url friendly title or id.
     *
     * @param array $parameters
     * @param EncodeDecoderBase $encodeDecoderBase
     * @return string
     */
    public function main(array $parameters, EncodeDecoderBase $encodeDecoderBase): string
    {
        if ($parameters['decodeAlias']) {
            $this->utility = $this->getUtilityFromDecoder($encodeDecoderBase);
            $id = $this->decodeTitleToId((string)$parameters['value'], $encodeDecoderBase);
            $result = $id !== -1 ? (string)$id : (string)$parameters['value'];
        } else {
            $this->utility = $encodeDecoderBase->getUtility();
            $result = $this->encodeIdToTitle((int)$parameters['value'], $parameters['pathParts'][0]);
        }
        return $result;
    }

    /**
     * Returns the utility class from url decoder
     *
     * @param UrlDecoder $urlDecoder
     * @return Utility
     */
    protected function getUtilityFromDecoder(UrlDecoder $urlDecoder): Utility
    {
        return ObjectAccess::getProperty($urlDecoder, 'utility', true);
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
                break;
        }
        return $repository;
    }

    /**
     * Returns the title as url friendly string or $id if no title could be found
     *
     * @param int $id
     * @param string $controllerType
     * @return string
     */
    protected function encodeIdToTitle(int $id, string $controllerType): string
    {
        $repository = $this->getRepositoryForController($controllerType);
        switch ($controllerType) {
            case 'lebenslagen':
                $title = $repository->getById($id)['displayName'];
                break;
            case 'leistungen':
                $title = $repository->getLiveById($id)['title'];
                break;
            case 'organisationseinheiten':
                $title = $repository->getById($id)['name'];
                break;
            default:
                $title = '';
                break;
        }
        // make it url friendly
        return $this->utility->convertToSafeString($title ?: (string)$id);
    }

    /**
     * Decode title to id
     *
     * @param string $title
     * @param UrlDecoder $urlDecoder
     * @return int id
     * @throws InvalidPathException
     */
    protected function decodeTitleToId(string $title, UrlDecoder $urlDecoder): int
    {
        $searchRepository = $this->objectManager->get(SearchRepository::class);
        $controller = $this->getControllerAliasFromUrlDecoder($urlDecoder);
        $filterAlias = [
            'organisationseinheiten' => 'organisationseinheit',
            'lebenslagen' => 'lebenslage',
            'leistungen' => 'leistung'
        ];
        $result = $searchRepository->search($title, $filterAlias[$controller]);
        if (isset($result['primaryResult']['hits'])) {
            $record = array_shift($result['primaryResult']['hits']);
            $id = (int)$record['id'];
        } else {
            throw new InvalidPathException('Could not find id by path for service_bw2 RealURL mapping!', 1525782342);
        }
        return $id;
    }

    /**
     * Extracts the controller alias from url decoders original path
     *
     * @param UrlDecoder $urlDecoder
     * @return string
     * @throws \InvalidArgumentException
     * @throws InvalidPathException
     */
    protected function getControllerAliasFromUrlDecoder(UrlDecoder $urlDecoder): string
    {
        $originalPath = ObjectAccess::getProperty($urlDecoder, 'originalPath', true);
        preg_match('@/service\-bw/(?<controller>[[:alnum:]\-]+)/@', $originalPath, $matches);
        if (array_key_exists('controller', $matches)) {
            return $matches['controller'];
        }
        throw new InvalidPathException('Could not decode given path for service_bw2 RealURL mapping!', 1524033384);
    }
}