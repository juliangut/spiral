<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Transport;

use Jgut\Spiral\Exception\TransportException;
use Jgut\Spiral\Option;
use Jgut\Spiral\Transport;

/**
 * cURL transport handler.
 */
class Curl implements Transport
{
    use TransportAware;

    /**
     * Default options.
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
        CURLOPT_SSL_VERIFYPEER    => true,
        CURLOPT_SSL_VERIFYHOST    => 2,
        CURLOPT_AUTOREFERER       => true,
        CURLOPT_FOLLOWLOCATION    => true,
        CURLOPT_MAXREDIRS         => 10,
        CURLOPT_UNRESTRICTED_AUTH => false,
        CURLOPT_RETURNTRANSFER    => true,
        CURLOPT_HEADER            => true,
        CURLOPT_FORBID_REUSE      => true,
        CURLOPT_FRESH_CONNECT     => true,
    ];

    /**
     * cURL resource handler.
     *
     * @var resource
     */
    private $handler;

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
     * {@inheritdoc}
     *
     * @throws \Jgut\Spiral\Exception\TransportException
     */
    public function request($method, $uri, array $headers = [], array $vars = [], array $flags = [])
    {
        $method = strtoupper($method);
        if (!defined('\Jgut\Spiral\Transport::METHOD_' . $method)) {
            throw new TransportException(sprintf('"%s" is not an accepted HTTP method', $method));
        }
        $method = constant('\Jgut\Spiral\Transport::METHOD_' . $method);

        $this->close();

        $this->handler = curl_init();

        $this->setMethod($this->handler, $method);
        $this->forgeOptions($this->handler, $this->options);
        $this->forgeHeaders($this->handler, $headers);

        $flags = array_merge(['post_multipart' => false], $flags);

        if (count($vars)) {
            $parameters = $vars;

            if (in_array(
                $method,
                [
                    Transport::METHOD_OPTIONS,
                    Transport::METHOD_HEAD,
                    Transport::METHOD_GET,
                    Transport::METHOD_PUT,
                    Transport::METHOD_DELETE,
                ],
                true
            )) {
                $parameters = null;
                $uri .= ((strpos($uri, '?') !== false) ? '&' : '?') . http_build_query($vars, '', '&');
            } elseif ($method !== Transport::METHOD_POST || $flags['post_multipart'] !== true) {
                $parameters = http_build_query($vars, '', '&');
            }

            if ($parameters !== null) {
                curl_setopt($this->handler, CURLOPT_POSTFIELDS, $vars);
            }
        }
        curl_setopt($this->handler, CURLOPT_URL, $uri);

        $response = $this->execute($this->handler);

        if (curl_errno($this->handler) !== 0) {
            $error = curl_error($this->handler);
            $errorNumber = curl_errno($this->handler);

            $this->close();

            throw new TransportException($error, $errorNumber);
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
     * @param resource $handler
     * @param string   $method
     */
    protected function setMethod($handler, $method)
    {
        switch ($method) {
            case Transport::METHOD_HEAD:
                curl_setopt($handler, CURLOPT_NOBODY, true);
                break;

            case Transport::METHOD_GET:
                curl_setopt($handler, CURLOPT_HTTPGET, true);
                break;

            case Transport::METHOD_POST:
                curl_setopt($handler, CURLOPT_POST, true);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, Transport::METHOD_POST);
                break;

            default:
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $method);
        }
    }

    /**
     * Set cURL options on handler.
     *
     * @param resource              $handler
     * @param \Jgut\Spiral\Option[] $options
     */
    protected function forgeOptions($handler, array $options)
    {
        foreach ($options as $option) {
            curl_setopt($handler, $option->getOption(), $option->getValue());
        }

        if ($this->hasOption(CURLOPT_USERPWD) && !$this->hasOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC)) {
            curl_setopt($handler, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }
    }

    /**
     * Set HTTP headers on handler.
     *
     * @param resource $handler
     * @param array    $headers
     */
    protected function forgeHeaders($handler, array $headers)
    {
        $headerList = [];

        foreach ($headers as $header => $value) {
            $headerList[] = sprintf('%s: %s', $header, is_array($value) ? implode(', ', $value) : (string) $value);
        }

        curl_setopt($handler, CURLOPT_HTTPHEADER, $headerList);
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
