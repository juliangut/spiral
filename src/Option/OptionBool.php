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
 * Boolean cURL option wrapper.
 */
class OptionBool extends Option
{
    /**
     * {@inheritdoc}
     */
    protected $value = false;

    /**
     * {@inheritdoc}
     *
     * @param bool $value
     */
    public function setValue($value)
    {
        $this->value = $value === true;
    }
}
