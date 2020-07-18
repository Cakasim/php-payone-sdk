SDK for PAYONE Payment Integration
==================================

[![CI Status](https://github.com/Cakasim/php-payone-sdk/workflows/CI/badge.svg?branch=master)](https://github.com/Cakasim/php-payone-sdk/actions)
[![Build Status](https://travis-ci.org/Cakasim/php-payone-sdk.svg?branch=master)](https://travis-ci.org/Cakasim/php-payone-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Cakasim/php-payone-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Cakasim/php-payone-sdk/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Cakasim/php-payone-sdk/badge.svg?branch=master)](https://coveralls.io/github/Cakasim/php-payone-sdk?branch=master)
[![LICENSE](https://img.shields.io/github/license/Cakasim/php-payone-sdk.svg)](LICENSE)
[![Total Downloads](https://poser.pugx.org/cakasim/payone-sdk/downloads)](https://packagist.org/packages/cakasim/payone-sdk)

Introduction
------------

The SDK for PAYONE Payment Integration helps you to integrate payment into your app. The SDK makes use of the
[PAYONE Server API](https://docs.payone.com/display/public/PLATFORM/Channel+Server+API) which is quite feature rich
but has aged and direct use may be uncomfortable.

### Features

 - Modern interface and robust concepts to make using this SDK a pleasure
 - Flexible design and extendable structure for tailored solutions
 - Simple yet powerful use of the [PAYONE Server API](https://docs.payone.com/display/public/PLATFORM/Channel+Server+API)
 - Automatic processing of [PAYONE Notifications](https://docs.payone.com/pages/releaseview.action?pageId=1213962)
 - Secure redirect URL generation with state data payload for redirect payments

### Requirements

 - At least **PHP 7.1**
 - [Composer Dependency Manager](https://getcomposer.org)

*Although the SDK uses Composer, it does not have extensive dependencies on other packages.
Currently, the only dependencies are on PSR interfaces.*

License
-------

The SDK for PAYONE Payment Integration is open-sourced software licensed under the [MIT license](LICENSE).

Installing the SDK
------------------

Just run `composer require cakasim/payone-sdk` to install the SDK via composer.

Core Concepts
-------------

The SDK is based on several core principles that should make it easy to use and flexible to integrate.

 - **Sensible Defaults.** A useful and functional default configuration allows easy bootstrapping of the SDK without
   extensive initialization of dependencies.
 - **Inversion of Control.** A simple IoC implementation using constructor parameter injection allows a central
   modification of relevant components.
 - **Services.** Service classes make individual components of the SDK and their features accessible.

### PSR Interfaces

The use of PSR interfaces allows a high degree of adaptability to an existing system
that already provides PSR compatible components.

The SDK makes use of the following PSR interfaces:

 - [PSR-3, Logger Interface](https://www.php-fig.org/psr/psr-3/)
 - [PSR-11, Container Interface](https://www.php-fig.org/psr/psr-11/)
 - [PSR-7, HTTP Message Interfaces](https://www.php-fig.org/psr/psr-7/)
 - [PSR-17, HTTP Factories](https://www.php-fig.org/psr/psr-17/)
 - [PSR-18, HTTP Client](https://www.php-fig.org/psr/psr-18/)

Using the SDK
-------------

*This section explains how to use the SDK. Various code examples are given.
For reasons of clarity, general code components have been omitted
(e.g. use statements).*

### Construct the SDK

To use the SDK, the main class `Sdk` must be instantiated. There are three different
ways to do that.

#### Variant 1 – Using All Defaults

This variant is the easiest to get started. The SDK will be constructed using all
defaults which requires you to install some default PSR implementation packages:
 
 - `cakasim/payone-sdk-http-message` (PSR-7, PSR-17)
 - `cakasim/payone-sdk-stream-client` (PSR-18)
 - `cakasim/payone-sdk-silent-logger` (PSR-3)

Now you are able to construct the SDK with just a single line of code:

```php
$sdk = new Sdk();
```

#### Variant 2 – Replacing Defaults by Providing Other Bindings

In order to change defaults you may override the default container bindings
with your own or even third-party implementations. This enables a deep integration
of the SDK into any existing environment which already provides PSR implementations.

```php
// Create the SDK container builder which lets you provide custom bindings
$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->getContainer();

// Use the methods of $container to override default bindings ...

// For example, provide your own PSR-18 HTTP client implementation
$container->bind(\Psr\Http\Client\ClientInterface::class, MyPsr18Client::class);

// Or use an already instantiated PSR-3 logger, maybe provided by you
// or any PSR-3 compatible third-party package like monolog/monolog.
// Assume $logger is any PSR-3 compatible logger instance.
$container->bindInstance(\Psr\Log\LoggerInterface::class, $logger);

// Finally, construct the SDK and provide your customized container
$sdk = new Sdk($containerBuilder->buildContainer());
```

#### Variant 3 – Deeply Integrate with Existing IoC Environments

Often you will have a scenario with an already existing PSR-11 compatible IoC container
which provides constructor parameter DI. In such cases you may replace the SDK container
completely with the existing one.

You will have to provide all necessary bindings by yourself. Have a look at the
`src/ContainerBuilder.php` source to get an idea of the required bindings.

Once you have configured the existing container you may use it to instantiate the SDK:

```php
// Assume $existingContainer is an already configured container
$sdk = new Sdk($existingContainer);
```

*For all following examples we assume that the variable `$sdk`
contains the `Sdk` instance.*

### Configure the SDK

The SDK requires various configuration parameters to be set. These parameters
must be provided via the `Config` class. Some parameters have sane defaults and
others need to be set. Have a look at the table below which lists all parameters.

| Parameter                               | Type           | Default                             | Description                                                                   |
|-----------------------------------------|----------------|-------------------------------------|-------------------------------------------------------------------------------|
| `api.endpoint`                          | **`string`**   | `https://api.pay1.de/post-gateway/` | PAYONE Server API endpoint                                                    |
| `api.merchant_id`                       | **`string`**   | _required_                          | Merchant ID of your PAYONE account                                            | 
| `api.portal_id`                         | **`string`**   | _required_                          | Portal ID which can be configured in your PAYONE account                      |
| `api.sub_account_id`                    | **`string`**   | _required_                          | ID of your configured sub-account                                             |
| `api.mode`                              | **`string`**   | `test`                              | API mode, choose between test or live                                         |
| `api.key`                               | **`string`**   | _required_                          | PAYONE Server API endpoint                                                    |
| `api.key_hash_type`                     | **`string`**   | `sha384`                            | API key hashing method                                                        |
| `api.integrator_name`                   | **`string`**   | _required_                          | Name of your app or company                                                   |
| `api.integrator_version`                | **`string`**   | _required_                          | Version of your app                                                           |
| `notification.sender_address_whitelist` | **`string[]`** | _Valid sender IP list_              | List of valid sender IPs                                                      |
| `redirect.url`                          | **`string`**   | _required_                          | Redirect URL template, use `$token` as placeholder for the actual token value |
| `redirect.token_lifetime`               | **`int`**      | `3600`                              | Redirect token lifetime (in seconds)                                          |
| `redirect.token_encryption_key`         | **`string`**   | _required_                          | Encryption key for redirect tokens                                            |
| `redirect.token_encryption_method`      | **`string`**   | `aes-256-ctr`                       | Encryption method for redirect tokens                                         |
| `redirect.token_signing_key`            | **`string`**   | _required_                          | Signing key for redirect tokens                                               |
| `redirect.token_signing_algo`           | **`string`**   | `sha256`                            | Signing algorithm for redirect tokens                                         |

The following example shows how to set all required parameters.

```php
$config = $sdk->getConfig();

// Your API credentials
$config->set('api.merchant_id', 'your_merchant_id');
$config->set('api.portal_id', 'your_portal_id');
$config->set('api.sub_account_id', 'your_sub_account_id');
$config->set('api.key', 'your_api_key');

// General API config options
$config->set('api.integrator_name', 'YourProjectName');
$config->set('api.integrator_version', '1.0.0');

// The redirect URL template, $token will be replaced by the actual token value.
$config->set('redirect.url', 'https://example.com/redirect/$token');

// Redirect token security settings
$config->set('redirect.token_encryption_key', 'your_secret_encryption_key');
$config->set('redirect.token_signing_key', 'your_secret_signing_key');
```

### Sending API Requests

The following example shows how to send a simple API request that pre-authorizes
a debit payment. There is no need to set global API parameters (e.g your API credentials)
because the SDK uses the config to set them before sending the actual request.

```php
// Create your request / response objects
$response = new \Cakasim\Payone\Sdk\Api\Message\Response();
$request = new \Cakasim\Payone\Sdk\Api\Message\Payment\AuthorizationRequest([
    // Perform a pre-authorization which reserves the amount,
    // a follow-up request will be necessary to actually capture the amount
    'request' => 'preauthorization',

    // Set the type of payment to debit payment
    // https://docs.payone.com/display/public/PLATFORM/clearingtype+-+definition
    'clearingtype' => 'elv',

    // Set the IBAN for the debit payment
    // Here you may generate a valid test IBAN:
    // http://randomiban.com
    'iban' => 'DE91500105176688925818',
]);

// Set the transaction currency
// https://docs.payone.com/display/public/PLATFORM/currency+-+definition
$request->setCurrency('EUR');

// Set amount to 15049 fractional monetary units of transaction currency,
// for currency EUR this represents 150,49 €
$request->setAmount(15049);

// Set your transaction reference which identifies the transaction
// in your system (e.g. the order number within an online shop)
// https://docs.payone.com/display/public/PLATFORM/reference+-+definition
$request->setReference('1A2B3C4D5E');

// Send the request to PAYONE
$sdk->getApiService()->sendRequest($request, $response);

// Do something with the response
echo serialize($response);
```

### Handling Notifications From PAYONE

With the SDK, PAYONE notifications can be easily processed.
The SDK takes important steps in the verification and
mapping of notifications.

```php
// Register notification handlers
$sdk->getNotificationService()->registerHandler(new class() implements HandlerInterface {
    public function handleNotification(ContextInterface $context): void
    {
        $message = $context->getMessage();

        if ($message instanceof TransactionStatusInterface) {
            // handle the TX status notification
            echo "Received TX action {$message->getAction()}";
        }
    }
});

// Get the server request factory to create a request from the current environment
/** @var ServerRequestFactoryInterface $requestFactory */
$requestFactory = $sdk->getContainer()->get(ServerRequestFactoryInterface::class);
$request = $requestFactory->createServerRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER);

// Process the server request
$sdk->getNotificationService()->processRequest($request);
```

### The Redirect Service

For certain payment methods the customer will be redirected to a third party service (PayPal, Sofortüberweisung, etc.)
in order to authenticate and authorize the payment.

The SDK can set the `successurl`, `errorurl` and `backurl` API request parameters for you to specify the
target URL a customer will be redirected to after he finishes the third party service process. Furthermore, the SDK
appends a token to the target URL.

The token holds metadata (e.g. creation timestamp) as well as custom data you may set. The token itself will be
encrypted and signed which ensures protection of the payload data against unauthorized access and modification.
Additionally, the SDK verifies the age of the token and ensures it does not exceed the configured max age.

#### Apply Redirect Parameters to an API Request

The example below shows how to apply redirect parameters to a (pre-)authorization API request. The API response
has a status of `REDIRECT` and a `redirecturl` parameter which must be used to redirect the customer.
In some scenarios a redirect is not always necessary, the example covers this as well.

```php
use Cakasim\Payone\Sdk\Api\Message\Parameter\BackUrlAwareInterface;
use Cakasim\Payone\Sdk\Api\Message\Parameter\ErrorUrlAwareInterface;
use Cakasim\Payone\Sdk\Api\Message\Parameter\SuccessUrlAwareInterface;
use Cakasim\Payone\Sdk\Api\Message\Payment\AuthorizationRequest;

// Use an inline class to customize the API request.
// The inline class is tagged with the interfaces to support redirect URL parameters.
// This is how the SDK knows which redirect URL parameters are supported and should
// be added to the request by passing it to applyRedirectParameters() later on.
$request = new class() extends AuthorizationRequest
    implements SuccessUrlAwareInterface, ErrorUrlAwareInterface, BackUrlAwareInterface {

    public function __construct()
    {
        parent::__construct([
            // Perform an authorization of a credit card payment,
            // this means the card will be charged immediately.
            'request'      => 'authorization',
            'clearingtype' => 'cc',

            // This identifies the credit card of your customer and is valid within your account scope only.
            // You will obtain this value during a credit card check which should be done in the context
            // of the PAYONE Client API (e.g. in the browser of the customer). This is good for you because
            // you are not getting in touch with the actual credit card data of your customer.
            // https://docs.payone.com/pages/releaseview.action?pageId=1214583
            'pseudocardpan' => '...',
        ]);

        // Set other mandatory parameters
        $this->setCurrency('EUR');
        $this->setAmount(1499);
        $this->setReference('9Z8Y7X6W5V');
    }
};

// Use an inline class to customize the API response
$response = new class() extends \Cakasim\Payone\Sdk\Api\Message\Response {
    public function getRedirectUrl(): ?string
    {
        // Return a valid redirect URL or null
        return $this->getStatus() === 'REDIRECT'
            ? $this->getParameter('redirecturl')
            : null;
    }
};

// Add redirect parameters to the request
$sdk->getRedirectService()->applyRedirectParameters($request, [
    // Provide any custom payload data and encode it securely within the token value
    'order_id' => '9Z8Y7X6W5V',
    // ...
]);

// Send API request to PAYONE
$sdk->getApiService()->sendRequest($request, $response);

// Get the redirect URL. The value can be null which (in this particular case)
// indicates that no redirect is necessary because not every credit card payment
// authorization requires a redirect.
$redirectUrl = $response->getRedirectUrl();

// Check if we have to redirect the customer
if ($redirectUrl !== null) {
    // At this point the customer must be redirected to $redirectUrl.
    // Basically send a status code of 302 and a Location header with the
    // value of $redirectUrl.
    http_response_code(302);
    header("Location: {$redirectUrl}");
    exit;
}

// At this point no redirect was done, just process the payment
// ...
```

#### Process a Redirect Token

If a customer returns to your app you first need to read the redirect token. The SDK does not make any assumptions
how this should be done. Basically using a simple query parameter is a sane and totally valid approach.

Have a look at the example below which shows you how to process the token and access the token payload data.

```php
use Cakasim\Payone\Sdk\Redirect\Context\ContextInterface;
use Cakasim\Payone\Sdk\Redirect\Handler\HandlerInterface;

// Get the token from the request URL
$token = '...';

$handler = new class() implements HandlerInterface {
    public function handleRedirect(ContextInterface $context): void
    {
        // Get the decoded token from the redirect context
        $token = $context->getToken();

        // Read your custom token payload data
        $orderId = $token->get('order_id');

        // Proceed with your business logic ...
    }
};

$sdk->getRedirectService()->registerHandler($handler);
$sdk->getRedirectService()->processRedirect($token);
```
