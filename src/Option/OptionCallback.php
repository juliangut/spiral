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
 * Special callback cURL option wrapper.
 */
class OptionCallback extends Option
{
    /**
     * Callback to handle value set.
     *
     * @var callable
     */
    protected $callback;

    /**
     * {@inheritdoc}
     */
    protected $value = '';

    /**
     * Set value handler callback.
     *
     * @param callable $callback
     */
    public function setCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Jgut\Spiral\Exception\OptionException
     */
    public function setValue($value)
    {
        if (!is_callable($this->callback)) {
            throw new OptionException('No callback defined');
        }

        $callback = $this->callback;
        $this->value = $callback($value);
    }
}
