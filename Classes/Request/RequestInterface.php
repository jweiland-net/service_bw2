<?php
namespace JWeiland\ServiceBw2\Request;

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
use JWeiland\ServiceBw2\PostProcessor\PostProcessorInterface;

/**
 * Interface RequestInterface
 *
 * @package JWeiland\ServiceBw2\Request
 */
interface RequestInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';

    const ACCEPT_PLAIN = 'text/plain';
    const ACCEPT_JSON = 'application/json';
    const ACCEPT_XML = 'application/xml';

    /**
     * Returns the mandant
     *
     * @return string $mandant
     */
    public function getMandant();

    /**
     * Returns the method
     *
     * @return string $method
     */
    public function getMethod();

    /**
     * Returns the uri
     *
     * @return string $method
     */
    public function getUri();

    /**
     * Returns the parameters
     *
     * @return array $parameters
     */
    public function getParameters();

    /**
     * Returns the body
     *
     * @return string $body
     */
    public function getBody();

    /**
     * Returns the accept
     *
     * @return string $accept
     */
    public function getAccept();

    /**
     * Returns the postProcessors
     *
     * @return PostProcessorInterface[]
     */
    public function getPostProcessors();

    /**
     * Checks, if this request is valid
     *
     * @return bool
     */
    public function isValidRequest();

    /**
     * Returns CacheTags
     *
     * @return array
     */
    public function getCacheTags(): array;

    /**
     * Sets CacheTags
     * @see \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend::isValidTag()
     *
     * @param array $cacheTags
     */
    public function setCacheTags(array $cacheTags);

    /**
     * Adds a cache tag
     * @see \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend::isValidTag()
     *
     * @param string $cacheTag
     * @return void
     */
    public function addCacheTag(string $cacheTag);

    /**
     * Removes a cache tag
     *
     * @param string $cacheTag
     * @return bool true on success otherwise false
     */
    public function removeCacheTag(string $cacheTag): bool;
}
