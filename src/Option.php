<?php

/*
 * A PSR7 aware cURL client (https://github.com/juliangut/spiral).
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/spiral
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

namespace Jgut\Spiral;

/**
 * cURL option wrapper interface.
 */
interface Option
{
    /**
     * Get cURL option.
     *
     * @return int
     */
    public function getOption();

    /**
     * Set option value.
     *
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * Get cURL option value.
     *
     * @return mixed
     */
    public function getValue();
}
