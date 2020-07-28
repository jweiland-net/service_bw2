<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Request;

use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\PostProcessor\JsonPostProcessor;
use JWeiland\ServiceBw2\PostProcessor\PostProcessorInterface;
use JWeiland\ServiceBw2\PostProcessor\RenameArrayKeyPostProcessor;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class AbstractRequest
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * @var ExtConf
     */
    protected $extConf;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * POST, GET or PUT
     *
     * @var string
     */
    protected $method = 'GET';

    /**
     * Path
     *
     * @var string
     */
    protected $path = '';

    /**
     * Contains all allowed parameters with configuration for type, default and required:
     * [
     *   'benutzername' => [
     *     'dataType' => 'string',
     *     'default' => 'de',
     *     'required' => true
     *   ]
     * ]
     *
     * @var array
     */
    protected $allowedParameters = [];

    /**
     * Parameters which will be sent to Service BW API
     * You can add a parameter with addParameter()
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Body
     *
     * @var string
     */
    protected $body = '';

    /**
     * Accept
     *
     * Over 80% of all API calls response with JSON
     * So this should be a good default for all requests.
     * Override it in your own Request if needed.
     *
     * @var string
     */
    protected $accept = 'application/json';

    /**
     * If you don't need the default post processors or if you want to defined them on your own
     * you can completely remove all default post processors, if you set this value to true
     *
     * @var bool
     */
    protected $clearDefaultPostProcessorClassNames = false;

    /**
     * Post processor class names
     * Define some post processors to post process the Service BW response
     *
     * @var array
     */
    protected $defaultPostProcessorClassNames = [
        0 => JsonPostProcessor::class,
        1 => RenameArrayKeyPostProcessor::class
    ];

    /**
     * Add request related PostProcessors
     *
     * @var array
     */
    protected $additionalPostProcessorClassNames = [];

    /**
     * Cache tags to be used with cache frontend
     * @see \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend::isValidTag()
     *
     * @var array
     */
    protected $cacheTags = [];

    /**
     * @param ExtConf $extConf
     */
    public function injectExtConf(ExtConf $extConf): void
    {
        $this->extConf = $extConf;
    }

    /**
     * @param ObjectManager $objectManager
     */
    public function injectObjectManager(ObjectManager $objectManager): void
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['method', 'path', 'body', 'accept', 'parameters', 'allowedParameters'];
    }

    /**
     * Returns the mandant
     * ToDo: Currently only one mandant can be configured. Maybe we will allow multiple mandants with a configuration record or pageTSconfig
     *
     * @return string
     */
    public function getMandant(): string
    {
        return $this->extConf->getMandant();
    }

    /**
     * Returns the method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Sets the method
     *
     * @param string $method
     */
    public function setMethod($method): void
    {
        $method = trim(strtoupper((string)$method));
        $allowedMethods = ['GET', 'POST', 'PUT'];
        if (in_array($method, $allowedMethods, true)) {
            $this->method = $method;
        }
    }

    /**
     * Returns the path
     *
     * @return string $path
     */
    public function getPath(): string
    {
        return '/' . ltrim(trim($this->path), '/');
    }

    /**
     * Sets the path
     *
     * @param string $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * Returns the parameters
     *
     * @return array
     */
    public function getParameters(): array
    {
        // add parameters with default values, if not set already
        foreach ($this->allowedParameters as $parameter => $configuration) {
            if (array_key_exists($parameter, $this->parameters)) {
                continue;
            }
            if (isset($configuration['default'])) {
                $this->addParameter($parameter, $configuration['default']);
            }
        }
        return $this->parameters;
    }

    /**
     * Sets the parameters
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = [];
        foreach ($parameters as $parameter => $value) {
            $this->addParameter($parameter, $value);
        }
    }

    /**
     * Add parameter
     *
     * @param string $parameter
     * @param string|int $value
     */
    public function addParameter($parameter, $value): void
    {
        // cast value as defined
        if (array_key_exists($parameter, $this->allowedParameters)) {
            if (isset($this->allowedParameters[$parameter]['dataType'])) {
                switch ($this->allowedParameters[$parameter]['dataType']) {
                    case 'int':
                    case 'integer':
                        $this->parameters[$parameter] = (int)$value;
                        break;
                    case 'bool':
                    case 'boolean':
                        $this->parameters[$parameter] = (bool)$value;
                        break;
                    case 'string':
                    default:
                        $this->parameters[$parameter] = (string)$value;
                        break;
                }
            } else {
                $this->parameters[$parameter] = (string)$value;
            }
        }

        // check, if value was configured as placeholder in URI
        $search = '{' . $parameter . '}';
        if (strpos($this->path, $search) !== false) {
            // if parameter was found, replace it and remove from parameters list
            $this->path = str_replace($search, $value, $this->path);
            $this->removeParameter($parameter);
        }
    }

    /**
     * Remove parameter
     *
     * @param string $parameter
     */
    public function removeParameter($parameter): void
    {
        unset($this->parameters[$parameter]);
    }

    /**
     * Get body parameter for POST data
     *
     * @return string
     */
    public function getBody(): string
    {
        $body = '';
        $parameters = $this->getParameters();
        if (isset($parameters['body'])) {
            $body = $parameters['body'];
        }
        if (!empty($this->body)) {
            $body = $this->body;
        }
        return $body;
    }

    /**
     * Set body for POST requests
     *
     * @param string $body
     */
    public function setBody($body): void
    {
        $this->body = trim($body);
    }

    /**
     * get URI for request
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->extConf->getBaseUrl() . $this->getPath();
    }

    /**
     * Returns the accept
     *
     * @return string
     */
    public function getAccept(): string
    {
        return $this->accept;
    }

    /**
     * Sets the accept
     *
     * @param string $accept
     */
    public function setAccept($accept): void
    {
        $this->accept = (string)trim($accept);
    }

    /**
     * Returns the postProcessors
     *
     * @return PostProcessorInterface[]
     */
    public function getPostProcessors(): array
    {
        $postProcessors = [];

        // clear all default post processors, if request needs its own configuration
        if ($this->clearDefaultPostProcessorClassNames) {
            $this->defaultPostProcessorClassNames = [];
        }

        // create default post processors
        foreach ($this->defaultPostProcessorClassNames as $className) {
            /** @var PostProcessorInterface $postProcessor */
            $postProcessor = $this->objectManager->get($className);
            if ($postProcessor instanceof PostProcessorInterface) {
                $postProcessors[] = $postProcessor;
            }
        }

        // create request related post processors
        foreach ($this->additionalPostProcessorClassNames as $className) {
            /** @var PostProcessorInterface $postProcessor */
            $postProcessor = $this->objectManager->get($className);
            if ($postProcessor instanceof PostProcessorInterface) {
                $postProcessors[] = $postProcessor;
            }
        }
        return $postProcessors;
    }

    /**
     * Checks, if this request is valid
     *
     * @return bool
     */
    public function isValidRequest(): bool
    {
        $isValid = true;

        $path = trim($this->path);
        if (empty($path)) {
            $isValid = false;
        }

        $username = trim($this->extConf->getUsername());
        if (empty($username)) {
            $isValid = false;
        }

        $password = trim($this->extConf->getPassword());
        if (empty($password)) {
            $isValid = false;
        }

        $mandant = trim($this->extConf->getMandant());
        if (empty($mandant)) {
            $isValid = false;
        }

        $baseUrl = trim($this->extConf->getBaseUrl());
        if (empty($baseUrl)) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Returns CacheTags
     *
     * @return array
     */
    public function getCacheTags(): array
    {
        return $this->cacheTags;
    }

    /**
     * Sets CacheTags
     * @see \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend::isValidTag()
     *
     * @param array $cacheTags
     */
    public function setCacheTags(array $cacheTags): void
    {
        $this->cacheTags = $cacheTags;
    }

    /**
     * Adds a cache tag
     * @see \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend::isValidTag()
     *
     * @param string $cacheTag
     */
    public function addCacheTag(string $cacheTag): void
    {
        $this->cacheTags[] = $cacheTag;
    }

    /**
     * Removes a cache tag
     *
     * @param string $cacheTag
     * @return bool true on success otherwise false
     */
    public function removeCacheTag(string $cacheTag): bool
    {
        if (in_array($cacheTag, $this->cacheTags, true)) {
            unset($this->cacheTags[array_search($cacheTag, $this->cacheTags, true)]);
            return true;
        }
        return false;
    }
}
