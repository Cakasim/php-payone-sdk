Changelog
=========

NEXT
----

### Changes

 - **[BREAKING]** Removed `Cakasim\Payone\Sdk\Api\Service::getClient()`
 - Added new proxy method `Cakasim\Payone\Sdk\Api\Service::sendRequest()`

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
