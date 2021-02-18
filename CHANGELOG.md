# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Added
* Add PHP 8 support.

### Changed
* Replace `php-http/guzzle6-adapter` with `php-http/guzzle7-adapter` in install instructions for PHP 8 support.

### Removed
* Drop PHP 7.1 support.

## [0.2.2] - 2020-11-02

### Fixed
* Fix several response keys and state options. ([#26]) Props @arthurflooris.

## [0.2.1] - 2020-09-23

### Fixed
* Use correct namespaces for `Payments` and `PaymentInterface`. ([#25]) Props @arthurflooris.

## [0.2.0] - 2020-05-05

### Added
* Add interface abstraction layer for dependency injection. ([#19]) Props @emkookmer.

### Fixed
* Ensure `updated_since` is using ISO 8601 date format. ([#18]) Props @stewsnooze.
* Fix and add pagination method to retrieve total entries count. ([#20]) Props @jkulak.

## [0.1.1] - 2018-03-19

### Changed
* Changed version constrain for php-http/httplug to continue supporting 1.x.

### Fixed
* Fixed fatal error by missing php-http/client-common dependency.

## [0.1.0] - 2018-03-18

### Added
* API v2 support for Client Contacts, Clients, Company, Invoice Messages, Invoice Payments, Invoices, Invoice Item Categories, Estimate Messages, Estimates, Estimate Item Categories, Expenses, Expense Categories, Tasks, Time Entries, Project User Assignments, Project Task Assignments, Projects, Roles, User Project Assignments, and Users.
* `AutoPagingIterator` to be used for list requests with automatic pagination.

[Unreleased]: https://github.com/wearerequired/harvest-api-php-client/compare/0.2.2...HEAD
[0.2.2]: https://github.com/wearerequired/harvest-api-php-client/compare/0.2.1...0.2.2
[0.2.1]: https://github.com/wearerequired/harvest-api-php-client/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/wearerequired/harvest-api-php-client/compare/0.1.1...0.2.0
[0.1.1]: https://github.com/wearerequired/harvest-api-php-client/compare/0.1.0...0.1.1
[0.1.0]: https://github.com/wearerequired/harvest-api-php-client/compare/75bc0967f...0.1.0

[#18]: https://github.com/wearerequired/harvest-api-php-client/pull/18
[#19]: https://github.com/wearerequired/harvest-api-php-client/pull/19
[#20]: https://github.com/wearerequired/harvest-api-php-client/pull/20
[#25]: https://github.com/wearerequired/harvest-api-php-client/pull/25
[#26]: https://github.com/wearerequired/harvest-api-php-client/pull/26
