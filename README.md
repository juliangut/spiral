[![PHP version](https://img.shields.io/badge/PHP-%3E%3D5.5-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/vpre/juliangut/spiral.svg?style=flat-square)](https://packagist.org/packages/juliangut/spiral)
[![License](https://img.shields.io/github/license/juliangut/spiral.svg?style=flat-square)](https://github.com//spiral/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/juliangut/spiral.svg?style=flat-square)](https://travis-ci.org/juliangut/spiral)
[![Style Check](https://styleci.io/repos/45148606/shield)](https://styleci.io/repos/45148606)
[![Code Quality](https://img.shields.io/scrutinizer/g/juliangut/spiral.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/spiral)
[![Code Coverage](https://img.shields.io/coveralls/juliangut/spiral.svg?style=flat-square)](https://coveralls.io/github/juliangut/spiral)

[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/spiral.svg?style=flat-square)](https://packagist.org/packages/juliangut/spiral)
[![Monthly Downloads](https://img.shields.io/packagist/dm/juliangut/spiral.svg?style=flat-square)](https://packagist.org/packages/juliangut/spiral)

# PSR7 aware cURL client

PSR7 compatible cURL client.

Use PSR7 Request and Response objects to perform HTTP Requests with cURL.

## Installation

Best way to install is using [Composer](https://getcomposer.org/):

```
composer require juliangut/spiral
```

Then require_once the autoload file:

```php
require_once './vendor/autoload.php';
```

## Usage

```php
use Jgut\Spiral\Client;

// Create Spiral cURL client
$spiralClient = new Client;

// Create \Psr\Http\Message\RequestInterface request and configure it
$request = new PSR7RequestImplementation();

// Perform the request providing an empty \Psr\Http\Message\ResponseInterface response object to be populated
$response = $spiralClient->request($request, new PSR7ResponseImplementation());

// Use response
$headers = $response->getHeaders();
$content = (string) $response->getBody();
```

## Transport

You can create an empty cURL transport object and set options on it later

```php
$transport = new Curl;

$spiralClient = new Client($transport);
```

Or you can create the transport object from sane defaults

```php
$transport = Curl::createFromDefaults();

$spiralClient = new Client($transport);
```

If no transport object is provided to `Jgut\Spiral\Client` constructor method one will be created by `Jgut\Spiral\Transport\Curl::createFromDefaults`

## Options

Transport object accepts cURL options

```php
use Jgut\Spiral\Transport\Curl;
use Jgut\Spiral\Option\ConnectTimeout;

// Create Spiral cURL client
$transport = new Curl;

// Set option explicitly
$transport->setOption(new ConnectTimeout(10));

// Set by cURL constant
$transport->setOption(CURLOPT_CONNECTTIMEOUT, 10);

// Set using an alias
$transport->setOption('connect_timeout', 10);
```

### Reserved options

Some options are reserved and cannot be added to transport object using `setOption` method. Some of them are set by extracting the relevant data from request oobject.

The following is a list of options automatically handled by the transport object

* `CURLOPT_NOBODY`
* `CURLOPT_HTTPGET`
* `CURLOPT_POST`
* `CURLOPT_CUSTOMREQUEST`
* `CURLOPT_URL`
* `CURLOPT_POSTFIELDS`
* `CURLOPT_HTTPHEADER`

### Available options

Option class     | Alias             | cURL constant             | Value type
---------------- | ----------------- | ------------------------- | -----------------------
AutoReferer      | auto_referer      | CURLOPT_AUTOREFERER       | bool
ConnectTimeout   | connect_timeout   | CURLOPT_CONNECTTIMEOUT    | int
Cookie           | cookie            | CURLOPT_COOKIE            | string
CookieFile       | cookie_file       | CURLOPT_COOKIEFILE        | string
CookieJar        | cookie_jar        | CURLOPT_COOKIEJAR         | string
Crlf             | crlf              | CURLOPT_CRLF              | bool
Encoding         | encoding          | CURLOPT_ENCODING          | string
FileTime         | file_time         | CURLOPT_FILETIME          | bool
FollowLocation   | follow_location   | CURLOPT_FOLLOWLOCATION    | bool
Header           | header            | CURLOPT_HEADER            | bool
HeaderOut        | header_out        | CURLINFO_HEADER_OUT       | bool
HttpAuth         | http_auth         | CURLOPT_HTTPAUTH          | bool
HttpVersion      | http_version      | CURLOPT_HTTP_VERSION      | float (1.0 or 1.1)
MaxRedirs        | max_redirs        | CURLOPT_MAXREDIRS         | int
Port             | port              | CURLOPT_PORT              | int
Referer          | referer           | CURLOPT_REFERER           | string
ReturnTransfer   | return_transfer   | CURLOPT_RETURNTRANSFER    | bool
SslVerifyPeer    | ssl_verify_peer   | CURLOPT_SSL_VERIFYPEER    | bool
SslVersion       | ssl_version       | CURLOPT_SSLVERSION        | int
Timeout          | timeout           | CURLOPT_TIMEOUT           | int
UnrestrictedAuth | unrestricted_auth | CURLOPT_UNRESTRICTED_AUTH | bool
UserAgent        | user_agent        | CURLOPT_USERAGENT         | string
UserPwd          | user_password     | CURLOPT_USERPWD           | string (user:password)
Verbose          | verbose           | CURLOPT_VERBOSE           | bool

*Review Jgut\Spiral\Option\OptionFactory for a full list of available aliases*

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/spiral/blob/master/issues). Have a look at existing issues before

See file [CONTRIBUTING.md](https://github.com/juliangut/spiral/blob/master/CONTRIBUTING.md)

## License

### Release under BSD-3-Clause License.

See file [LICENSE](https://github.com/juliangut/spiral/blob/master/LICENSE) included with the source code for a copy of the license terms

