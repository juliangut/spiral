<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

class Cookie extends OptionString
{
    /**
     * cURL option.
     *
     * @var int
     */
    protected $option = CURLOPT_COOKIE;

    /**
     * Set option value.
     *
     * @param string|array $value
     */
    protected function setValue($value)
    {
        if (is_array($value)) {
            $value = http_build_query($value, '', '; ');
        }

        parent::setValue($value);
    }
}
