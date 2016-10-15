<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
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
