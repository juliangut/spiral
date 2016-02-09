<?php
/**
 * Spiral: PSR7 aware cURL client (https://github.com/juliangut/spiral)
 *
 * @link https://github.com/juliangut/spiral for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/spiral/master/LICENSE
 */

namespace Jgut\Spiral\Option;

use Jgut\Spiral\Exception\OptionException;
use Jgut\Spiral\Option;

/**
 * cURL option wrappers factory.
 */
abstract class OptionFactory
{
    /**
     * Accepted cURL option aliases.
     *
     * @var array
     */
    private static $optionAliasMap = [
        'http-header'        => CURLOPT_HTTPHEADER,

        'crlf'               => CURLOPT_CRLF,
        'header-out'         => CURLINFO_HEADER_OUT,
        'return-transfer'    => CURLOPT_RETURNTRANSFER,
        'verbose'            => CURLOPT_VERBOSE,
        'user-agent'         => CURLOPT_USERAGENT,
        'ssl-version'        => CURLOPT_SSLVERSION,
        'cookie-file'        => CURLOPT_COOKIEFILE,
        'cookie-jar'         => CURLOPT_COOKIEJAR,
        'referer'            => CURLOPT_REFERER,
        'auto-referer'       => CURLOPT_AUTOREFERER,
        'file-time'          => CURLOPT_FILETIME,
        'user-password'      => CURLOPT_USERPWD,
        'http-version'       => CURLOPT_HTTP_VERSION,
        'port'               => CURLOPT_PORT,
        'encoding'           => CURLOPT_ENCODING,
        'buffer-size'        => CURLOPT_BUFFERSIZE,
        'post-redir'         => CURLOPT_POSTREDIR,
        'stderr'             => CURLOPT_STDERR,
        'netrc'              => CURLOPT_NETRC,

        'header'             => CURLOPT_HEADER,
        'include'            => CURLOPT_HEADER,

        'connect-timeout'    => CURLOPT_CONNECTTIMEOUT,
        'connection-timeout' => CURLOPT_CONNECTTIMEOUT,

        'timeout'            => CURLOPT_TIMEOUT,
        'max-time'           => CURLOPT_TIMEOUT,

        'ssl_verify_host'    => CURLOPT_SSL_VERIFYHOST,
        'ssl_verify_peer'    => CURLOPT_SSL_VERIFYPEER,
        'insecure'           => CURLOPT_SSL_VERIFYPEER,

        'follow-location'    => CURLOPT_FOLLOWLOCATION,
        'follow-redirects'   => CURLOPT_FOLLOWLOCATION,
        'location'           => CURLOPT_FOLLOWLOCATION,

        'max-redirs'         => CURLOPT_MAXREDIRS,
        'max-redirects'      => CURLOPT_MAXREDIRS,

        'cookie'             => CURLOPT_COOKIE,
        'cookies'            => CURLOPT_COOKIE,

        'http-auth'          => CURLOPT_HTTPAUTH,
        'auth'               => CURLOPT_HTTPAUTH,

        'unrestricted-auth'  => CURLOPT_UNRESTRICTED_AUTH,
        'location-trusted'   => CURLOPT_UNRESTRICTED_AUTH,

        'forbid-reuse'       => CURLOPT_FORBID_REUSE,
        'fresh-connect'      => CURLOPT_FRESH_CONNECT,
    ];

    /**
     * cURL accepted options and class mapper.
     *
     * @see http://php.net/manual/en/function.curl-setopt.php
     * @see http://www.whatsmyip.org/lib/php-curl-option-guide/
     *
     * @var array
     */
    private static $optionClassMap = [
        // Boolean
        // Convert Unix newlines to CRLF newlines on transfers
        CURLOPT_CRLF              => 'Crlf',
        // Include request header in the output
        CURLINFO_HEADER_OUT       => 'HeaderOut',
        // Include response header in the output
        CURLOPT_HEADER            => 'Header',
        // Return the transfer as a string instead of outputting
        CURLOPT_RETURNTRANSFER    => 'ReturnTransfer',
        // Output verbose information
        CURLOPT_VERBOSE           => 'Verbose',
        // False to stop cURL from verifying the peer's certificate
        CURLOPT_SSL_VERIFYPEER    => 'SslVerifyPeer',
        // Follow any Location headers sent by server
        CURLOPT_FOLLOWLOCATION    => 'FollowLocation',
        // Automatically set the http referer header when following a redirect
        CURLOPT_AUTOREFERER       => 'AutoReferer',
        // Keep sending the username and password when following locations
        CURLOPT_UNRESTRICTED_AUTH => 'UnrestrictedAuth',
        // Get HTTP header for modification date of file
        CURLOPT_FILETIME          => 'FileTime',
        // Automatically close connection after processing
        CURLOPT_FORBID_REUSE      => 'ForbidReuse',
        // Force to use new connection instead of cached
        CURLOPT_FRESH_CONNECT     => 'FreshConnect',
        // Scan ~/.netrc file for user credentials
        CURLOPT_NETRC             => 'Netrc',

        /*
        HTTP authentication method(s) to use:
            CURLAUTH_BASIC, CURLAUTH_DIGEST, CURLAUTH_GSSNEGOTIATE, CURLAUTH_NTLM, CURLAUTH_ANY, CURLAUTH_ANYSAFE
        Currently only CURLAUTH_BASIC is available and implemented as boolean
        */
        CURLOPT_HTTPAUTH          => 'HttpAuth',

        // Integer
        // Number of seconds to wait while trying to connect. 0 to wait indefinitely
        CURLOPT_CONNECTTIMEOUT    => 'ConnectTimeout',
        // Maximum number of seconds to allow cURL functions to execute
        CURLOPT_TIMEOUT           => 'Timeout',
        // The maximum amount of HTTP redirections to follow
        CURLOPT_MAXREDIRS         => 'MaxRedirs',
        // Which SSL version (2 or 3) to use
        CURLOPT_SSLVERSION        => 'SslVersion',
        // Alternative port number to connect to
        CURLOPT_PORT              => 'Port',
        // Size of the buffer for each read
        CURLOPT_BUFFERSIZE        => 'BufferSize',
        // Bit mask to maintain redirection type
        CURLOPT_POSTREDIR         => 'PostRedir',

        // String
        // Which HTTP version to use. "1.0" for CURL_HTTP_VERSION_1_0 or "1.1" for CURL_HTTP_VERSION_1_1
        CURLOPT_HTTP_VERSION      => 'HttpVersion',
        // HTTP header definition
        CURLOPT_HTTPHEADER        => 'HttpHeader',
        // Contents of the "User-Agent: " header to be used in a HTTP request
        CURLOPT_USERAGENT         => 'UserAgent',
        // Contents of the "Referer: " header to be used in a HTTP request
        CURLOPT_REFERER           => 'Referer',
        // Username and password formatted as "[username]:[password]" to use for the connection
        CURLOPT_USERPWD           => 'UserPwd',
        // Contents of the "Cookie: " header to be used in the HTTP request. Can be an array
        CURLOPT_COOKIE            => 'Cookie',
        // Name of the file containing the cookie data
        CURLOPT_COOKIEFILE        => 'CookieFile',
        // Name of a file to save all internal cookies to when the handle is closed
        CURLOPT_COOKIEJAR         => 'CookieJar',
        // Contents of the "Accept-Encoding: " header. This enables decoding of the response
        CURLOPT_ENCODING          => 'Encoding',
        // Verify existence of a common name in peer certificate, and matches hostname
        CURLOPT_SSL_VERIFYHOST    => 'SslVerifyHost',
        // Alternative location to output errors
        CURLOPT_STDERR            => 'StdErr',
    ];

    /**
     * Create cURL option.
     *
     * @param int $option
     * @param mixed $value
     *
     * @return \Jgut\Spiral\Option
     *
     * @throws \Jgut\Spiral\Exception\OptionException
     */
    public static function create($option, $value)
    {
        if (!array_key_exists($option, self::$optionClassMap)) {
            throw new OptionException(sprintf('"%s" is not valid supported option', $option));
        }

        $optionClass = '\Jgut\Spiral\Option\\' . self::$optionClassMap[$option];

        return new $optionClass($value);
    }

    /**
     * Get mapped option.
     *
     * @param int|string $option
     *
     * @return int
     *
     * @throws \Jgut\Spiral\Exception\OptionException
     */
    public static function getOptionKey($option)
    {
        if (is_string($option)) {
            $option = preg_replace('/[ _]+/', '-', strtolower(trim($option)));
            if (array_key_exists($option, self::$optionAliasMap)) {
                $option = self::$optionAliasMap[strtolower($option)];
            }
        }

        if (!array_key_exists($option, self::$optionClassMap)) {
            throw new OptionException(sprintf('"%s" is not valid supported option', $option));
        }

        return $option;
    }
}
