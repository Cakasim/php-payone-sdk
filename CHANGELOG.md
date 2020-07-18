Changelog
=========

0.2.0
----
**_Development Release / 2020-07-18_**

_Please use this release with caution. Versions prior to 1.0.0
are considered development releases._

### Changes

 - **[BREAKING]** Removed `Cakasim\Payone\Sdk\Api\Service::getClient()`
 - Added new proxy method `Cakasim\Payone\Sdk\Api\Service::sendRequest()`
 - **[BREAKING]** Removed `getUriFactory()`, `getStreamFactory()`,
   `getRequestFactory()`, `getResponseFactory()` and `getClient()` from
   `Cakasim\Payone\Sdk\Http\Service` class
 - Added new method `Cakasim\Payone\Sdk\Http\Service::createServerRequest()`
   that creates a PSR-7 server request from the current environment.
 - The `Cakasim\Payone\Sdk\Config\Config` class now comes with a default
   configuration that is automatically applied. The following parameters
   have default values: `api.endpoint`, `api.key_hash_type`, `api.mode`,
   `notification.sender_address_whitelist`, `redirect.token_lifetime`,
   `redirect.token_encryption_method` and `redirect.token_signing_algo`
   [[#22](https://github.com/Cakasim/php-payone-sdk/issues/22)]
 - **[BREAKING]** Moved PSR-7 HTTP message and PSR-17 HTTP factory components
   into separate package which is now available as `cakasim/payone-sdk-http-message`
 - **[BREAKING]** Moved PSR-18 HTTP client component into separate package
   which is now available as `cakasim/payone-sdk-stream-client`
 - **[BREAKING]** Moved PSR-3 logging component into separate package
   which is now available as `cakasim/payone-sdk-silent-logger`
 - **[BREAKING]** Removed log component and service
 - Use URL-safe Base64 encoding instead of Base62 for redirect tokens which
   fixes a strange IV length problem and drops the `ext-gmp` requirement
 - The `Cakasim\Payone\Sdk\Sdk` class now has a `getConfig()` method
   for accessing the config more conveniently
 - Implemented logging for the redirect and notification services
   [[#14](https://github.com/Cakasim/php-payone-sdk/issues/14)]
 - Updated README in general and added a new section about the redirect service

0.1.0
-----
**_Development Release / 2020-02-05_**

_This is the initial release of the project. Please use this release with caution.
Versions prior to 1.0.0 are considered development releases._

### Features

 - **Core Features**
   - Handling of PAYONE Server API requests and responses
   - Processing of PAYONE notifications
   - Generation and processing of secure redirect URLs
 - **Other Features**
   - Modern and robust implementation which is designed to be as much flexible as possible
   - Development with a focus on quality measures like testing, code quality and coding style
   - Secure and predictable handling of errors and exceptions
   - Comes with a PSR-11 implementation that provides constructor dependency injection
   - Depends only on PSR packages to avoid conflicts
   - Provides a PSR-7, PSR-17 and PSR-18 implementation
