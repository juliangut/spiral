<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Transport;

use Jgut\Spiral\Transport;

trait TransportAware
{
    /**
     * Perform OPTIONS cURL request.
     *
     * @param string $uri
     * @param array $headers
     * @param array $vars
     * @return string
     */
    public function options($uri, array $headers = [], array $vars = [])
    {
        return $this->request(Transport::METHOD_OPTIONS, $uri, $headers, $vars);
    }

    /**
     * Perform HEAD cURL request.
     *
     * @param string $uri
     * @param array $headers
     * @param array $vars
     * @return string
     */
    public function head($uri, array $headers = [], array $vars = [])
    {
        return $this->request(Transport::METHOD_HEAD, $uri, $headers, $vars);
    }

    /**
     * Perform GET cURL request.
     *
     * @param string $uri
     * @param array $headers
     * @param array $vars
     * @return string
     */
    public function get($uri, array $headers = [], array $vars = [])
    {
        return $this->request(Transport::METHOD_GET, $uri, $headers, $vars);
    }

    /**
     * Perform POST cURL request.
     *
     * @param string $uri
     * @param array $headers
     * @param array $vars
     * @return string
     */
    public function post($uri, array $headers = [], array $vars = [], array $flags = [])
    {
        return $this->request(Transport::METHOD_POST, $uri, $headers, $vars, $flags);
    }

    /**
     * Perform PUT cURL request.
     *
     * @param string $uri
     * @param array $headers
     * @param array $vars
     * @return string
     */
    public function put($uri, array $headers = [], array $vars = [])
    {
        return $this->request(Transport::METHOD_PUT, $uri, $headers, $vars);
    }

    /**
     * Perform DELETE cURL request.
     *
     * @param string $uri
     * @param array $headers
     * @param array $vars
     * @return string
     */
    public function delete($uri, array $headers = [], array $vars = [])
    {
        return $this->request(Transport::METHOD_DELETE, $uri, $headers, $vars);
    }

    /**
     * Perform PATCH cURL request.
     *
     * @param string $uri
     * @param array $headers
     * @param array $vars
     * @return string
     */
    public function patch($uri, array $headers = [], array $vars = [])
    {
        return $this->request(Transport::METHOD_PATCH, $uri, $headers, $vars);
    }
}
