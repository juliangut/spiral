<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 *
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral;

/**
 * Transport wrapper interface.
 */
interface Transport
{
    /**
     * Accepted HTTP methods.
     */
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_PATCH   = 'PATCH';

    /**
     * Perform a cURL request.
     *
     * @param string $method
     * @param string $uri
     * @param array  $headers
     * @param array  $vars
     * @param array  $flags
     *
     * @return string
     */
    public function request($method, $uri, array $headers = [], array $vars = [], array $flags = []);

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
