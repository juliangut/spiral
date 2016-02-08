<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

/**
 * cURL CURLOPT_COOKIEFILE option.
 */
class CookieFile extends OptionFile
{
    /**
     * cURL option.
     *
     * @var int
     */
    protected $option = CURLOPT_COOKIEFILE;
}
