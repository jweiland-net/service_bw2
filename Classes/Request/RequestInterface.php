<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request;

use JWeiland\ServiceBw2\PostProcessor\PostProcessorInterface;

/**
 * Interface RequestInterface
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
    public function getMandant(): string;

    /**
     * Returns the method
     *
     * @return string $method
     */
    public function getMethod(): string;

    /**
     * Returns the uri
     *
     * @return string $method
     */
    public function getUri(): string;

    /**
     * Returns the parameters
     *
     * @return array $parameters
     */
    public function getParameters(): array;

    /**
     * Returns the body
     *
     * @return string $body
     */
    public function getBody(): string;

    /**
     * Returns the accept
     *
     * @return string $accept
     */
    public function getAccept(): string;

    /**
     * Returns the postProcessors
     *
     * @return PostProcessorInterface[]
     */
    public function getPostProcessors(): array;

    /**
     * Checks, if this request is valid
     *
     * @return bool
     */
    public function isValidRequest(): bool;

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
    public function setCacheTags(array $cacheTags): void;

    /**
     * Adds a cache tag
     * @see \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend::isValidTag()
     *
     * @param string $cacheTag
     */
    public function addCacheTag(string $cacheTag): void;

    /**
     * Removes a cache tag
     *
     * @param string $cacheTag
     * @return bool true on success otherwise false
     */
    public function removeCacheTag(string $cacheTag): bool;
}
