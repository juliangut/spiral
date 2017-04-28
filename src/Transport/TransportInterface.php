<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Transport;

/**
 * Transport wrapper interface.
 */
interface TransportInterface
{
    /**
     * Perform a cURL request.
     *
     * @param string $method
     * @param string $uri
     * @param array  $headers
     * @param string|null $requestBody
     *
     * @return string
     */
    public function request($method, $uri, array $headers = [], $requestBody = null);

    /**
     * Retrieve response information.
     *
     * @param int|null $option
     *
     * @return mixed|null
     */
    public function responseInfo($option = null);

    /**
     * Free resources when done with transport.
     */
    public function close();
}
