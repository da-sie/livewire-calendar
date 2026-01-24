# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [4.1.0] - 2025-01-24

### Added
- Livewire 4 support with full backward compatibility
- PHP 8.4 support
- Continued Laravel 12 support

### Compatibility
- **PHP**: 7.4 - 8.4
- **Laravel**: 6 - 12
- **Livewire**: 2, 3, 4

## [3.2.0] - 2024-01-09

### Added
- Laravel 12 support

### Compatibility
- **PHP**: 7.4 - 8.3
- **Laravel**: 6 - 12
- **Livewire**: 2, 3

## [3.1.3] - 2023-11-08

### Fixed
- Fixed timezone handling issue ([#35](https://github.com/omnia-digital/livewire-calendar/issues/35))

### Compatibility
- **PHP**: 7.4 - 8.3
- **Laravel**: 6 - 11
- **Livewire**: 2, 3

## [3.1.2] - 2023-10-15

### Added
- Support for nested components in README documentation

### Changed
- Community contribution to improve component flexibility

### Compatibility
- **PHP**: 7.4 - 8.3
- **Laravel**: 6 - 11
- **Livewire**: 2, 3

## [3.1.1] - 2023-09-20

### Fixed
- Fixed bug where number of weeks and days was counting incorrectly

### Changed
- Updated README documentation

### Compatibility
- **PHP**: 7.4 - 8.3
- **Laravel**: 6 - 11
- **Livewire**: 2, 3

## [3.1.0] - 2023-08-15

### Added
- Laravel 11 support

### Compatibility
- **PHP**: 7.4 - 8.3
- **Laravel**: 6 - 11
- **Livewire**: 2, 3

## [3.0.3] - 2023-06-12

### Added
- Livewire Calendar service container binding

### Compatibility
- **PHP**: 7.4 - 8.2
- **Laravel**: 6 - 10
- **Livewire**: 2, 3

## [3.0.2] - 2023-05-18

### Fixed
- Fixed id and getId() errors depending on Livewire version
- Improved version detection for Livewire 2 vs 3

### Compatibility
- **PHP**: 7.4 - 8.2
- **Laravel**: 6 - 10
- **Livewire**: 2, 3

## [3.0.1] - 2023-05-10

### Fixed
- Fixed id and getId() errors depending on Livewire version

### Compatibility
- **PHP**: 7.4 - 8.2
- **Laravel**: 6 - 10
- **Livewire**: 2, 3

## [3.0.0] - 2023-04-20

### Added
- Livewire 3 support with full backward compatibility for Livewire 2
- Automatic version detection between Livewire 2 and 3

### Compatibility
- **PHP**: 7.4 - 8.2
- **Laravel**: 6 - 10
- **Livewire**: 2, 3

## [2.2.2] - 2023-02-15

### Added
- Laravel 10 support
- PHP 8.1 and 8.2 support

### Compatibility
- **PHP**: 7.4 - 8.2
- **Laravel**: 6 - 10
- **Livewire**: 2

## [2.2.1] - 2022-11-10

### Changed
- Updated package namespace to Omnia
- Updated README documentation
- Updated package ownership and branding

### Compatibility
- **PHP**: 7.4 - 8.0
- **Laravel**: 6 - 9
- **Livewire**: 2

## [2.2.0] - 2022-10-05

### Added
- Laravel 9 support

### Compatibility
- **PHP**: 7.4 - 8.0
- **Laravel**: 6 - 9
- **Livewire**: 2

## [2.1.0] - 2021-01-25

### Added
- PHP 8 support

### Compatibility
- **PHP**: 7.4 - 8.0
- **Laravel**: 6 - 8
- **Livewire**: 2

## [2.0.0] - 2020-10-13

### Added
- Laravel 8 support
- Livewire v2 support
- On/off flag for Day click event
- On/off flag for Event click event
- On/off flag for Drag and Drop event
- Ability to automatically poll component with `pollMillis` and `pollAction` parameters
- Comprehensive test suite

### Compatibility
- **PHP**: 7.4 - 8.0
- **Laravel**: 6 - 8
- **Livewire**: 2

## [1.0.0] - 2020-05-30

### Added
- Initial release
- Calendar component for Laravel Livewire
- Month and week view support
- Event handling
- Day click events
- Drag and drop functionality

### Compatibility
- **PHP**: 7.2 - 7.4
- **Laravel**: 6 - 7
- **Livewire**: 1

[Unreleased]: https://github.com/omnia-digital/livewire-calendar/compare/4.1.0...HEAD
[4.1.0]: https://github.com/omnia-digital/livewire-calendar/compare/3.2.0...4.1.0
[3.2.0]: https://github.com/omnia-digital/livewire-calendar/compare/3.1.3...3.2.0
[3.1.3]: https://github.com/omnia-digital/livewire-calendar/compare/3.1.2...3.1.3
[3.1.2]: https://github.com/omnia-digital/livewire-calendar/compare/3.1.1...3.1.2
[3.1.1]: https://github.com/omnia-digital/livewire-calendar/compare/3.1.0...3.1.1
[3.1.0]: https://github.com/omnia-digital/livewire-calendar/compare/3.0.3...3.1.0
[3.0.3]: https://github.com/omnia-digital/livewire-calendar/compare/3.0.2...3.0.3
[3.0.2]: https://github.com/omnia-digital/livewire-calendar/compare/3.0.1...3.0.2
[3.0.1]: https://github.com/omnia-digital/livewire-calendar/compare/3.0.0...3.0.1
[3.0.0]: https://github.com/omnia-digital/livewire-calendar/compare/2.2.2...3.0.0
[2.2.2]: https://github.com/omnia-digital/livewire-calendar/compare/2.2.1...2.2.2
[2.2.1]: https://github.com/omnia-digital/livewire-calendar/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/omnia-digital/livewire-calendar/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/omnia-digital/livewire-calendar/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/omnia-digital/livewire-calendar/compare/1.0.0...2.0.0
[1.0.0]: https://github.com/omnia-digital/livewire-calendar/releases/tag/1.0.0
