<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

/**
 * cURL CURLINFO_HEADER_OUT option.
 */
class HeaderOut extends OptionBoolean
{
    /**
     * cURL option.
     *
     * @var int
     */
    protected $option = CURLINFO_HEADER_OUT;
}
