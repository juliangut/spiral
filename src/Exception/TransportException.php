<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Exception;

/**
 * Transport exception.
 */
class TransportException extends \RuntimeException
{
    /**
     * @var string
     */
    protected $category;

    /**
     * TransportException constructor.
     *
     * @param string $message
     * @param int    $code
     * @param string $category
     */
    public function __construct($message, $code = 0, $category = '')
    {
        parent::__construct($message, $code);
        $this->category = trim($category);
    }

    /**
     * Retrieve category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
}
