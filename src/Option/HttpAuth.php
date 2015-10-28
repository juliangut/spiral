<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Option;

class HttpAuth implements Option
{
    use OptionAware;

    /**
     * cURL option.
     *
     * @var int
     */
    protected $option = CURLOPT_HTTPAUTH;

    /**
     * Set option value.
     *
     * @param bool $value
     */
    protected function setValue($value)
    {
        $this->value = $value === false ? false : CURLAUTH_BASIC;
    }
}
