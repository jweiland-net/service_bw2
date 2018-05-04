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
use DmitryDulepov\Realurl\Encoder\UrlEncoder;
use DmitryDulepov\Realurl\Utility;
use JWeiland\ServiceBw2\Domain\Repository\AbstractRepository;
use JWeiland\ServiceBw2\Domain\Repository\LebenslagenRepository;
use JWeiland\ServiceBw2\Domain\Repository\LeistungenRepository;
use JWeiland\ServiceBw2\Domain\Repository\OrganisationseinheitenRepository;
use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
     * @var UrlEncoder
     */
    protected $urlEncoder;

    /**
     * @var UrlDecoder
     */
    protected $urlDecoder;

    /**
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
            $this->urlDecoder = $encodeDecoderBase;
            $this->utility = $this->getUtilityFromDecoder();
            $id = $this->decodeTitleToId((string)$parameters['value']);
            return $id !== -1 ? (string)$id : (string)$parameters['value'];
        } else {
            $this->urlEncoder = $encodeDecoderBase;
            $this->utility = $this->urlEncoder->getUtility();
            return $this->encodeIdToTitle((int)$parameters['value'], $parameters['pathParts'][0]);
        }
    }

    /**
     * Returns the utility class from url decoder
     *
     * @return Utility
     * @throws \ReflectionException
     */
    protected function getUtilityFromDecoder(): Utility
    {
        $urlDecoderReflection = new \ReflectionClass($this->urlDecoder);
        $reflectionProperty = $urlDecoderReflection->getProperty('utility');
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($this->urlDecoder);
    }

    /**
     * Get repository for requested controller
     *
     * @param string $controller
     * @return AbstractRepository
     * @throws \Exception if controller name is invalid
     */
    protected function getRepositoryForController(string $controller): AbstractRepository
    {
        switch($controller) {
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
                    'Could not find repository for selected controller "' . $controller . '"!',
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
     * @param string $controller
     * @return string
     */
    protected function encodeIdToTitle(int $id, string $controller): string
    {
        $repository = $this->getRepositoryForController($controller);
        switch($controller) {
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
     * @return int id or -1 if no id was found!
     */
    protected function decodeTitleToId(string $title): int
    {
        $controller = $this->getControllerAliasFromUrlDecoder();
        $repository = $this->getRepositoryForController($controller);
        switch ($controller) {
            case 'lebenslagen':
                /** @var LebenslagenRepository $repository */
                $id = $this->findIdRecursively($title, 'name', $repository->getAll());
                break;
            case 'leistungen':
                /** @var LeistungenRepository $repository */
                $id = $this->findIdRecursively($title, 'displayName', $repository->getAll());
                break;
            case 'organisationseinheiten':
                /** @var OrganisationseinheitenRepository $repository */
                $id = $this->findIdRecursively($title, 'name', $repository->getAll());
                break;
            default:
                $id = -1;
                break;
        }
        return $id;
    }

    /**
     * Find id of a Service BW item recursively
     *
     * @param string $search name of the item e.g. Stadtverwaltung
     * @param string $key where the name is stored
     * @param array $items e.g. from $repository->getAll() may includes _children items
     * @return int the id or -1 if no id was found!
     */
    protected function findIdRecursively(string $search, string $key, array $items): int
    {
        foreach ($items as $item) {
            if (array_key_exists($key, $item) && $this->utility->convertToSafeString($item[$key]) === $search) {
                return (int)$item['id'];
            }
            // loop recursively if current item has children
            if (array_key_exists('_children', $item)) {
                $id = $this->findIdRecursively($search, $key, $item['_children']);
                if ($id !== -1) {
                    return $id;
                }
            }
        }
        return -1;
    }

    /**
     * Extracts the controller alias from url decoders original path
     *
     * @return string
     * @throws InvalidPathException
     * @throws \ReflectionException
     */
    protected function getControllerAliasFromUrlDecoder(): string
    {
        $urlDecoderReflection = new \ReflectionClass($this->urlDecoder);
        $reflectionProperty = $urlDecoderReflection->getProperty('originalPath');
        $reflectionProperty->setAccessible(true);
        $originalPath = $reflectionProperty->getValue($this->urlDecoder);
        preg_match('/\/service\-bw\/([a-zA-Z0-9\-]+)\//', $originalPath, $matches);
        if (\count($matches) === 2) {
            return $matches[1];
        }
        throw new InvalidPathException('Could not decode given path for service_bw2 RealURL mapping!', 1524033384);
    }

}
