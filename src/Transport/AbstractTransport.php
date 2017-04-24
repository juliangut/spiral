<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Transport;

use Fig\Http\Message\RequestMethodInterface;
use Jgut\Spiral\Exception\OptionException;
use Jgut\Spiral\Option\OptionFactory;
use Jgut\Spiral\Option\OptionInterface;

/**
 * Common transport trait.
 */
abstract class AbstractTransport implements TransportInterface
{
    /**
     * List of cURL options.
     *
     * @var OptionInterface[]
     */
    protected $options = [];

    /**
     * Retrieve added cURL options.
     *
     * @return OptionInterface[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set cURL options.
     *
     * @param array $options
     *
     * @throws OptionException
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws OptionException
     */
    public function setOption($option, $value = '', $quiet = false)
    {
        if (!$option instanceof OptionInterface) {
            try {
                $option = OptionFactory::build($option, $value);
            } catch (OptionException $exception) {
                if ($quiet !== true) {
                    throw $exception;
                }
            }
        }

        $this->removeOption($option->getOption());
        $this->options[] = $option;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($option, $value = null)
    {
        if ($option instanceof OptionInterface) {
            $option = $option->getOption();
        } else {
            try {
                $option = OptionFactory::getOptionKey($option);
            } catch (OptionException $exception) {
                return false;
            }
        }

        foreach ($this->options as $transportOption) {
            if ($transportOption->getOption() === $option) {
                return $value === null ?: $transportOption->getValue() === $value;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function removeOption($option)
    {
        if ($option instanceof OptionInterface) {
            $option = $option->getOption();
        } else {
            try {
                $option = OptionFactory::getOptionKey($option);
            } catch (OptionException $exception) {
                return;
            }
        }

        $this->options = array_filter(
            $this->options,
            function ($transportOption) use ($option) {
                /* @var OptionInterface $transportOption */
                return !($transportOption->getOption() === $option);
            }
        );
    }

    /**
     * Shorthand for OPTIONS cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param string $requestBody
     *
     * @return string
     */
    public function options($uri, array $headers = [], $requestBody = null)
    {
        return $this->request(RequestMethodInterface::METHOD_OPTIONS, $uri, $headers, $requestBody);
    }

    /**
     * Shorthand for HEAD cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param string $requestBody
     *
     * @return string
     */
    public function head($uri, array $headers = [], $requestBody = null)
    {
        return $this->request(RequestMethodInterface::METHOD_HEAD, $uri, $headers, $requestBody);
    }

    /**
     * Shorthand for GET cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param string $requestBody
     *
     * @return string
     */
    public function get($uri, array $headers = [], $requestBody = null)
    {
        return $this->request(RequestMethodInterface::METHOD_GET, $uri, $headers, $requestBody);
    }

    /**
     * Shorthand for POST cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param string $requestBody
     *
     * @return string
     */
    public function post($uri, array $headers = [], $requestBody = null)
    {
        return $this->request(RequestMethodInterface::METHOD_POST, $uri, $headers, $requestBody);
    }

    /**
     * Shorthand for PUT cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param string $requestBody
     *
     * @return string
     */
    public function put($uri, array $headers = [], $requestBody = null)
    {
        return $this->request(RequestMethodInterface::METHOD_PUT, $uri, $headers, $requestBody);
    }

    /**
     * Shorthand for DELETE cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param string $requestBody
     *
     * @return string
     */
    public function delete($uri, array $headers = [], $requestBody = null)
    {
        return $this->request(RequestMethodInterface::METHOD_DELETE, $uri, $headers, $requestBody);
    }

    /**
     * Shorthand for PATCH cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param string $requestBody
     *
     * @return string
     */
    public function patch($uri, array $headers = [], $requestBody = null)
    {
        return $this->request(RequestMethodInterface::METHOD_PATCH, $uri, $headers, $requestBody);
    }
}
