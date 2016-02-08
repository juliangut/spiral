<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

/**
 * cURL CURLOPT_SSLVERSION option.
 */
class SslVersion extends OptionRegex
{
    /**
     * cURL option.
     *
     * @var int
     */
    protected $option = CURLOPT_SSLVERSION;

    /**
     * @inheritdoc
     */
    protected $regex = '/^[0-6]$/';

    /**
     * @inheritdoc
     */
    protected $message = '"%s" is not valid SSL version';
}
