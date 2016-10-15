<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Exception\OptionException;

/**
 * Regex cURL option wrapper.
 */
class OptionRegex extends Option
{
    /**
     * Regex to check.
     *
     * @var string
     */
    protected $regex = '/^$/';

    /**
     * Error message.
     *
     * @var string
     */
    protected $message = '"%s" is not a valid value';

    /**
     * {@inheritdoc}
     */
    protected $value = '';

    /**
     * Set regex expression.
     *
     * @param $regex
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Set fail message.
     *
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $value
     *
     * @throws \Jgut\Spiral\Exception\OptionException
     */
    public function setValue($value)
    {
        $value = trim($value);

        if (!preg_match($this->regex, $value)) {
            throw new OptionException(sprintf($this->message, $value));
        }

        $this->value = $value;
    }
}
