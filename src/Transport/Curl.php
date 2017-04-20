<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Transport;

use Jgut\Spiral\Exception\TransportException;
use Jgut\Spiral\Option\OptionInterface;

/**
 * cURL transport handler.
 */
class Curl extends AbstractTransport
{
    /**
     * cURL error map.
     *
     * @var array
     */
    protected static $errorCategoryMap = [
        CURLE_FTP_WEIRD_SERVER_REPLY      => 'FTP',
        CURLE_FTP_ACCESS_DENIED           => 'FTP',
        CURLE_FTP_USER_PASSWORD_INCORRECT => 'FTP',
        CURLE_FTP_WEIRD_PASS_REPLY        => 'FTP',
        CURLE_FTP_WEIRD_USER_REPLY        => 'FTP',
        CURLE_FTP_WEIRD_PASV_REPLY        => 'FTP',
        CURLE_FTP_WEIRD_227_FORMAT        => 'FTP',
        CURLE_FTP_CANT_GET_HOST           => 'FTP',
        CURLE_FTP_CANT_RECONNECT          => 'FTP',
        CURLE_FTP_COULDNT_SET_BINARY      => 'FTP',
        CURLE_FTP_COULDNT_RETR_FILE       => 'FTP',
        CURLE_FTP_WRITE_ERROR             => 'FTP',
        CURLE_FTP_QUOTE_ERROR             => 'FTP',
        CURLE_FTP_COULDNT_STOR_FILE       => 'FTP',
        CURLE_FTP_COULDNT_SET_ASCII       => 'FTP',
        CURLE_FTP_PORT_FAILED             => 'FTP',
        CURLE_FTP_COULDNT_USE_REST        => 'FTP',
        CURLE_FTP_COULDNT_GET_SIZE        => 'FTP',
        CURLE_FTP_BAD_DOWNLOAD_RESUME     => 'FTP',
        CURLE_FTP_SSL_FAILED              => 'FTP',
        CURLE_SSL_CONNECT_ERROR           => 'SSL',
        CURLE_SSL_PEER_CERTIFICATE        => 'SSL',
        CURLE_SSL_ENGINE_NOTFOUND         => 'SSL',
        CURLE_SSL_ENGINE_SETFAILED        => 'SSL',
        CURLE_SSL_CERTPROBLEM             => 'SSL',
        CURLE_SSL_CIPHER                  => 'SSL',
        CURLE_SSL_CACERT                  => 'SSL',
        CURLE_LDAP_CANNOT_BIND            => 'LDAP',
        CURLE_LDAP_SEARCH_FAILED          => 'LDAP',
        CURLE_LDAP_INVALID_URL            => 'LDAP',
        CURLE_COULDNT_RESOLVE_PROXY       => 'proxy',
    ];

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
        CURLOPT_SSLVERSION        => 0,
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
     * @throws TransportException
     */
    public function request($method, $uri, array $headers = [], array $vars = [], array $flags = [])
    {
        $this->close();
        $this->handler = curl_init();

        $method = strtoupper($method);
        $this->setMethod($this->handler, $method);

        $this->forgeOptions($this->handler, $this->options);
        $this->forgeHeaders($this->handler, $headers);

        $flags = array_merge(['post_multipart' => false], $flags);

        if (count($vars)) {
            $parameters = $vars;

            if (in_array(
                $method,
                [
                    TransportInterface::METHOD_OPTIONS,
                    TransportInterface::METHOD_HEAD,
                    TransportInterface::METHOD_GET,
                    TransportInterface::METHOD_PUT,
                    TransportInterface::METHOD_DELETE,
                ],
                true
            )) {
                $parameters = null;
                $uri .= ((strpos($uri, '?') !== false) ? '&' : '?') . http_build_query($vars, '', '&');
            } elseif ($method !== TransportInterface::METHOD_POST || $flags['post_multipart'] !== true) {
                $parameters = http_build_query($vars, '', '&');
            }

            if ($parameters !== null) {
                curl_setopt($this->handler, CURLOPT_POSTFIELDS, $parameters);
            }
        }
        curl_setopt($this->handler, CURLOPT_URL, $uri);

        $response = $this->execute($this->handler);

        if (curl_errno($this->handler) !== CURLE_OK) {
            $errCode = curl_errno($this->handler);

            $exception = new TransportException(
                curl_error($this->handler),
                $errCode,
                array_key_exists($errCode, static::$errorCategoryMap) ? static::$errorCategoryMap [$errCode] : ''
            );

            $this->close();

            throw $exception;
        }

        return $response;
    }

    /**
     * Isolate curl execution.
     *
     * @param resource $handler
     *
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
            case TransportInterface::METHOD_HEAD:
                curl_setopt($handler, CURLOPT_NOBODY, true);
                break;

            case TransportInterface::METHOD_GET:
                curl_setopt($handler, CURLOPT_HTTPGET, true);
                break;

            case TransportInterface::METHOD_POST:
                curl_setopt($handler, CURLOPT_POST, true);
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, TransportInterface::METHOD_POST);
                break;

            default:
                curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $method);
        }
    }

    /**
     * Set cURL options on handler.
     *
     * @param resource          $handler
     * @param OptionInterface[] $options
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
            return;
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

            $this->handler = null;
        }
    }
}
