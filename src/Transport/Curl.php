<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Transport;

use Jgut\Spiral\Transport;
use Jgut\Spiral\Option;
use Jgut\Spiral\Exception\CurlException;
use Jgut\Spiral\Exception\CurlOptionException;
use Jgut\Spiral\Option\OptionFactory;
use Jgut\Spiral\Option\HttpHeader;

class Curl implements Transport
{
    use TransportAware;

    /**
     * Creation's default options.
     *
     * @var array
     */
    protected static $defaultOptions = [
        CURLOPT_VERBOSE           => false,
        CURLOPT_HTTP_VERSION      => 1.1,
        CURLOPT_USERAGENT         => 'Jgut\Spiral\Transport\Curl',
        CURLOPT_CONNECTTIMEOUT    => 60,
        CURLOPT_TIMEOUT           => 60,
        CURLOPT_CRLF              => false,
        CURLOPT_SSLVERSION        => 3,
        CURLOPT_AUTOREFERER       => true,
        CURLOPT_FOLLOWLOCATION    => true,
        CURLOPT_MAXREDIRS         => 10,
        CURLOPT_UNRESTRICTED_AUTH => false,
        CURLOPT_RETURNTRANSFER    => true,
        CURLOPT_HEADER            => true,
    ];

    /**
     * cURL resource handler.
     *
     * @var resource
     */
    private $handler;

    /**
     * cURL options.
     *
     * @var array
     */
    private $options = [];

    /**
     * Create cURL transport manager.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Create cURL transport manager with default options.
     *
     * @return self
     */
    public static function createFromDefaults()
    {
        return new static(static::$defaultOptions);
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
     * Set cURL option.
     *
     * @param int|string|\Jgut\Spiral\Option $option
     * @param mixed $value
     */
    public function setOption($option, $value = '')
    {
        if (!$option instanceof Option) {
            $option = OptionFactory::getOptionKey($option);

            $option = OptionFactory::create($option, $value);
        }

        $this->removeOption($option->getOption());
        $this->options[] = $option;
    }

    /**
     * Check if an option has been added.
     *
     * @param int|string $option
     * @param mixed $value
     * @return bool
     */
    public function hasOption($option, $value = null)
    {
        try {
            $option = OptionFactory::getOptionKey($option);
        } catch (CurlOptionException $exception) {
            return false;
        }

        foreach ($this->options as $transportOption) {
            if ($transportOption->getOption() === $option) {
                return $value === null ?: $transportOption->getValue() === $value;
            }
        }

        return false;
    }

    /**
     * Remove cURL option.
     *
     * @param int|string $option
     */
    public function removeOption($option)
    {
        try {
            $option = OptionFactory::getOptionKey($option);
        } catch (CurlOptionException $exception) {
            return;
        }

        for ($i = 0, $length = count($this->options); $i < $length; $i++) {
            if ($this->options[$i]->getOption() === $option) {
                unset($this->options[$i]);
            }
        }

        $this->options = array_values($this->options);
    }

    /**
     * Retrieve added cURL options.
     *
     * @return Jgut\Spiral\Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     * @throws \Jgut\Exception\CurlException
     */
    public function request($method, $uri, array $headers = [], array $vars = [], array $flags = [])
    {
        $this->close();

        $method = strtoupper($method);
        if (!defined('\Jgut\Spiral\Transport::METHOD_' . $method)) {
            throw new CurlException(sprintf('"%s" is not an accepted HTTP method', $method));
        }
        $method = constant('\Jgut\Spiral\Transport::METHOD_' . $method);

        $this->handler = curl_init();

        $this->setMethod($method);
        $this->forgeOptions($this->options);
        $this->forgeHeaders($headers);

        $flags = array_merge(['post_multipart' => false], $flags);

        if (count($vars)) {
            if (in_array(
                $method,
                [
                    Transport::METHOD_OPTIONS,
                    Transport::METHOD_HEAD,
                    Transport::METHOD_GET,
                    Transport::METHOD_PUT,
                    Transport::METHOD_DELETE,
                ]
            )) {
                $uri .= (stripos($uri, '?') !== false) ? '&' : '?';
                $uri .= http_build_query($vars, '', '&');
                $vars = null;
            } elseif ($method !== Transport::METHOD_POST || $flags['post_multipart'] !== true) {
                $vars = http_build_query($vars, '', '&');
            }

            if ($vars !== null) {
                curl_setopt($this->handler, CURLOPT_POSTFIELDS, $vars);
            }
        }
        curl_setopt($this->handler, CURLOPT_URL, $uri);

        $response = $this->execute($this->handler);

        if (curl_errno($this->handler) !== 0) {
            $error = curl_error($this->handler);
            $errorNumber = curl_errno($this->handler);

            $this->close();

            throw new CurlException($error, $errorNumber);
        }

        return $response;
    }

    /**
     * Isolate curl execution.
     *
     * @param resource $handler
     * @return string
     */
    protected function execute($handler)
    {
        return curl_exec($handler);
    }

    /**
     * Set HTTP method on handler.
     *
     * @param string $method
     */
    private function setMethod($method)
    {
        switch ($method) {
            case Transport::METHOD_HEAD:
                curl_setopt($this->handler, CURLOPT_NOBODY, true);
                break;

            case Transport::METHOD_GET:
                curl_setopt($this->handler, CURLOPT_HTTPGET, true);
                break;

            case Transport::METHOD_POST:
                curl_setopt($this->handler, CURLOPT_POST, true);
                curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, Transport::METHOD_POST);
                break;

            default:
                curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, $method);
        }
    }

    /**
     * Set cURL options on handler.
     *
     * @param Jgut\Spiral\Option[] $options
     */
    private function forgeOptions(array $options)
    {
        foreach ($options as $option) {
            curl_setopt($this->handler, $option->getOption(), $option->getValue());
        }

        if ($this->hasOption(CURLOPT_USERPWD) && !$this->hasOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC)) {
            curl_setopt($this->handler, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }
    }

    /**
     * Set HTTP headers on handler.
     */
    private function forgeHeaders(array $headers)
    {
        $headerList = [];

        foreach ($headers as $header => $value) {
            $headerList[] = sprintf('%s: %s', $header, is_array($value) ? implode(', ', $value) : (string) $value);
        }

        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $headerList);
    }

    /**
     * {@inheritdoc}
     */
    public function responseInfo($option = null)
    {
        if (!is_resource($this->handler)) {
            return null;
        }

        if ($option !== null) {
            return curl_getinfo($this->handler, $option);
        }

        return curl_getinfo($this->handler);
    }

    /**
     * Always free resources on destruction.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (is_resource($this->handler)) {
            curl_close($this->handler);
        }
    }
}
