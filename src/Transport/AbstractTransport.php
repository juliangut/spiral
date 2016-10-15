<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Transport;

use Jgut\Spiral\Exception\OptionException;
use Jgut\Spiral\Option;
use Jgut\Spiral\Option\OptionFactory;
use Jgut\Spiral\Transport as TransportInterface;

/**
 * Common transport trait.
 */
abstract class AbstractTransport implements TransportInterface
{
    /**
     * List of cURL options.
     *
     * @var \Jgut\Spiral\Option[]
     */
    protected $options = [];

    /**
     * Retrieve added cURL options.
     *
     * @return \Jgut\Spiral\Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set cURL options.
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($option, $value = '', $quiet = false)
    {
        if (!$option instanceof Option) {
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
        if ($option instanceof Option) {
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
        if ($option instanceof Option) {
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
                /* @var \Jgut\Spiral\Option $transportOption */
                return !($transportOption->getOption() === $option);
            }
        );
    }

    /**
     * Shorthand for OPTIONS cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param array  $vars
     *
     * @return string
     */
    public function options($uri, array $headers = [], array $vars = [])
    {
        return $this->request(TransportInterface::METHOD_OPTIONS, $uri, $headers, $vars);
    }

    /**
     * Shorthand for HEAD cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param array  $vars
     *
     * @return string
     */
    public function head($uri, array $headers = [], array $vars = [])
    {
        return $this->request(TransportInterface::METHOD_HEAD, $uri, $headers, $vars);
    }

    /**
     * Shorthand for GET cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param array  $vars
     *
     * @return string
     */
    public function get($uri, array $headers = [], array $vars = [])
    {
        return $this->request(TransportInterface::METHOD_GET, $uri, $headers, $vars);
    }

    /**
     * Shorthand for POST cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param array  $vars
     * @param array  $flags
     *
     * @return string
     */
    public function post($uri, array $headers = [], array $vars = [], array $flags = [])
    {
        return $this->request(TransportInterface::METHOD_POST, $uri, $headers, $vars, $flags);
    }

    /**
     * Shorthand for PUT cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param array  $vars
     *
     * @return string
     */
    public function put($uri, array $headers = [], array $vars = [])
    {
        return $this->request(TransportInterface::METHOD_PUT, $uri, $headers, $vars);
    }

    /**
     * Shorthand for DELETE cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param array  $vars
     *
     * @return string
     */
    public function delete($uri, array $headers = [], array $vars = [])
    {
        return $this->request(TransportInterface::METHOD_DELETE, $uri, $headers, $vars);
    }

    /**
     * Shorthand for PATCH cURL request.
     *
     * @param string $uri
     * @param array  $headers
     * @param array  $vars
     *
     * @return string
     */
    public function patch($uri, array $headers = [], array $vars = [])
    {
        return $this->request(TransportInterface::METHOD_PATCH, $uri, $headers, $vars);
    }
}
