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
use JWeiland\ServiceBw2\Configuration\ExtConf;
use JWeiland\ServiceBw2\PostProcessor\JsonPostProcessor;
use JWeiland\ServiceBw2\PostProcessor\PostProcessorInterface;
use JWeiland\ServiceBw2\PostProcessor\RenameArrayKeyPostProcessor;
use JWeiland\ServiceBw2\PostProcessor\TranslatePostProcessor;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class AbstractRequest
 *
 * @package JWeiland\ServiceBw2\Request
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
     * inject extConf
     *
     * @param ExtConf $extConf
     *
     * @return void
     */
    public function injectExtConf(ExtConf $extConf)
    {
        $this->extConf = $extConf;
    }

    /**
     * inject ObjectManager
     *
     * @param ObjectManager $objectManager
     *
     * @return void
     */
    public function injectObjectManager(ObjectManager $objectManager)
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
     *
     * @ToDo: Currently only one mandant can be configured. Maybe we will allow multiple mandants with a configuration record or pageTSconfig
     *
     * @return string $mandant
     */
    public function getMandant()
    {
        return $this->extConf->getMandant();
    }

    /**
     * Returns the method
     *
     * @return string $method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the method
     *
     * @param string $method
     *
     * @return void
     */
    public function setMethod($method)
    {
        $method = trim(strtoupper((string)$method));
        $allowedMethods = array('GET', 'POST', 'PUT');
        if (in_array($method, $allowedMethods)) {
            $this->method = $method;
        }
    }

    /**
     * Returns the path
     *
     * @return string $path
     */
    public function getPath()
    {
        return '/' . ltrim(trim($this->path), '/');
    }

    /**
     * Sets the path
     *
     * @param string $path
     *
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Returns the parameters
     *
     * @return array $parameters
     */
    public function getParameters()
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
     *
     * @return void
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = array();
        foreach ($parameters as $parameter => $value) {
            $this->addParameter($parameter, $value);
        }
    }

    /**
     * Add parameter
     *
     * @param string $parameter
     * @param string|int $value
     *
     * @return void
     */
    public function addParameter($parameter, $value)
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
     *
     * @return void
     */
    public function removeParameter($parameter)
    {
        unset($this->parameters[$parameter]);
    }

    /**
     * Get body parameter for POST data
     *
     * @return string
     */
    public function getBody()
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
    public function setBody($body)
    {
        $this->body = trim($body);
    }

    /**
     * get URI for request
     *
     * @return string
     */
    public function getUri()
    {
        return $this->extConf->getBaseUrl() . $this->getPath();
    }

    /**
     * Returns the accept
     *
     * @return string $accept
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Sets the accept
     *
     * @param string $accept
     *
     * @return void
     */
    public function setAccept($accept)
    {
        $this->accept = (string)trim($accept);
    }

    /**
     * Returns the postProcessors
     *
     * @return PostProcessorInterface[]
     */
    public function getPostProcessors()
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
    public function isValidRequest()
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
}
