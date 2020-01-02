SDK for PAYONE Payment Integration
==================================

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

Within your composer-based project, you can reference the SDK repository to install it as a
regular composer package. Modify your `composer.json` as follows:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Cakasim/php-payone-sdk.git"
        }
    ]
}
```

Just run `composer require cakasim/payone-sdk@dev-develop` to install the SDK via composer.

Core Concepts
-------------

The SDK is based on several core principles that should make it easy to use and flexible to integrate.

### Sensible Defaults

A useful and functional default configuration allows easy bootstrapping of the SDK without
extensive initialization of dependencies.

### Inversion of Control

A simple IoC implementation using constructor parameter injection allows a central
modification of relevant components.

### Services

Service classes make individual components of the SDK and their features accessible.

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

To use the SDK, the main class `Sdk` must be instantiated.

```php
// Variant 1, construct the Sdk with all defaults
$sdk = new Sdk();

// - or -

// Variant 2, construct the Sdk with a customized IoC container
$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->getContainer();

// Apply your custom bindings ...
$container->bind(RequestFactoryInterface::class, YourPsr17RequestFactory::class);
$container->bind(ResponseFactoryInterface::class, YourPsr17ResponseFactory::class);

$sdk = new Sdk($containerBuilder->buildContainer());
```

*For the following examples we assume that the variable `$sdk`
contains the `Sdk` instance.*

### Configure the SDK

The SDK requires some configuration parameters. These parameters
are provided by a Config class. The following example shows how
the configuration can be done.

```php
/** @var ConfigInterface $config */
$config = $sdk->getContainer()->get(ConfigInterface::class);

// Your API credentials
$config->set('api.merchant_id', 'your_merchant_id');
$config->set('api.portal_id', 'your_portal_id');
$config->set('api.key_hash', 'hash_of_your_api_key');

// General API config options
$config->set('api.mode', 'test');
$config->set('api.endpoint', 'https://api.pay1.de/post-gateway/');
$config->set('api.integrator_name', 'YourProjectName');
$config->set('api.integrator_version', '1.0.0');

// Set the IP address ranges of trusted notification senders.
$config->set('notification.sender_address_whitelist', [
    '185.60.20.0/24',
    '213.178.72.196',
    '213.178.72.197',
    '217.70.200.0/24',
]);
```

### Sending API Requests

The following example shows how to send a simple API request
to perform a credit card check.

```php
// Create your request / response objects
$response = new \Cakasim\Payone\Sdk\Api\Message\Response();
$request = new \Cakasim\Payone\Sdk\Api\Message\Request([
    'request' => 'creditcardcheck',
    'storecarddata' => 'yes',
    'cardpan' => '4111111111111111',
    'cardtype' => 'V',
    'cardexpiredate' => '2212',
    'cardcvc2' => '123',
]);

// Send the request to PAYONE
$sdk->getApiService()->getClient()->sendRequest($request, $response);

// Do something with the response
echo serialize($response);
```

### Handling Notifications from PAYONE

With the SDk, PAYONE notifications can be easily processed.
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
