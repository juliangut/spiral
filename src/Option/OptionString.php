<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral\Option;

/**
 * String cURL option wrapper.
 */
class OptionString extends Option
{
    /**
     * {@inheritdoc}
     */
    protected $value = '';

    /**
     * {@inheritdoc}
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = trim($value);
    }
}
