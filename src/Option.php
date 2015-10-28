<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral;

/**
 * Option wrapper interface.
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
     * Get option value.
     *
     * @return mixed
     */
    public function getValue();
}
