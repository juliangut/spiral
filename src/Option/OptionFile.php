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
 * File cURL option wrapper.
 */
class OptionFile extends Option
{
    /**
     * {@inheritdoc}
     */
    protected $value = '';

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
        if (!is_file($value) || !is_readable($value)) {
            throw new OptionException(sprintf('"%s" is not a readable file', $value));
        }

        $this->value = $value;
    }
}
