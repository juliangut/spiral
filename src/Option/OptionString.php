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
class OptionString extends DefaultOption
{
    /**
     * Create string cURL option.
     *
     * @param int $option
     */
    public function __construct($option)
    {
        parent::__construct($option);

        $this->value = '';
    }

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
