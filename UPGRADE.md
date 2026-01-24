# Upgrade Guide

This guide will help you upgrade between major versions of Livewire Calendar.

## Overview

Livewire Calendar maintains backward compatibility across Livewire 2, 3, and 4. The package uses automatic version detection, so in most cases, upgrading is as simple as updating your composer dependencies.

## Upgrading to 4.x from 3.x

Version 4.1.0 adds Livewire 4 support with **full backward compatibility** for Livewire 2 and 3.

### What's New
- Livewire 4 support
- PHP 8.4 support
- Continued support for Laravel 6-12

### No Breaking Changes
There are **no breaking changes** when upgrading from 3.x to 4.x. The package automatically detects your Livewire version and adapts accordingly.

### Update Instructions

1. Update your composer.json:

```bash
composer require omnia-digital/livewire-calendar:^4.1
```

2. Clear your application cache:

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

3. Test your calendar components to ensure they work as expected.

That's it! No code changes required.

## Upgrading to 3.x from 2.x

Version 3.0.0 added Livewire 3 support with **full backward compatibility** for Livewire 2.

### What's New
- Livewire 3 support
- Automatic version detection between Livewire 2 and 3
- Improved component lifecycle handling

### No Breaking Changes
There are **no breaking changes** when upgrading from 2.x to 3.x. The package uses feature detection to work with both Livewire 2 and Livewire 3.

### Update Instructions

1. Update your composer.json:

```bash
composer require omnia-digital/livewire-calendar:^3.0
```

2. Clear your application cache:

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

3. Test your calendar components.

### Known Issues (Fixed in 3.0.1+)

Early versions (3.0.0) had some issues with `id` vs `getId()` method detection. If you experience errors related to component IDs, upgrade to 3.0.1 or later:

```bash
composer require omnia-digital/livewire-calendar:^3.0.1
```

## Upgrading to 2.x from 1.x

Version 2.0.0 was a major upgrade that added Laravel 8 and Livewire 2 support.

### What's New
- Laravel 8 support
- Livewire v2 support
- New event interaction controls (day-click-enabled, event-click-enabled, drag-and-drop-enabled)
- Automatic polling support with `pollMillis` and `pollAction`
- Comprehensive test suite

### Breaking Changes

1. **Livewire v2 Required**: Livewire v1 is no longer supported. You must upgrade to Livewire v2 first.

2. **PHP 7.4+ Required**: Minimum PHP version increased from 7.2 to 7.4.

### Update Instructions

1. First, upgrade your application to Livewire v2 by following the [official Livewire upgrade guide](https://laravel-livewire.com/docs/2.x/upgrading).

2. Ensure you're running PHP 7.4 or higher.

3. Update your composer.json:

```bash
composer require omnia-digital/livewire-calendar:^2.0
```

4. Clear your application cache:

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

5. If you published the package views, you may need to republish them:

```bash
php artisan vendor:publish --tag=livewire-calendar --force
```

6. Test all your calendar components thoroughly.

## Version Selection Guide

Not sure which version to use? Here's a quick guide:

| Your Stack | Recommended Version |
|-----------|---------------------|
| Livewire 4, Laravel 12, PHP 8.4 | `^4.1` |
| Livewire 3, Laravel 11, PHP 8.3 | `^4.1` or `^3.2` |
| Livewire 2, Laravel 10, PHP 8.2 | `^4.1`, `^3.2`, or `^2.2` |
| Livewire 2, Laravel 9, PHP 8.1 | `^3.2` or `^2.2` |
| Livewire 2, Laravel 8, PHP 8.0 | `^2.2` or `^2.1` |
| Livewire 1, Laravel 7, PHP 7.4 | `^1.0` (no longer maintained) |

**Recommendation**: Always use the latest version (`^4.1`) when possible, as it provides the widest compatibility and receives active updates.

## Troubleshooting

### Component ID Errors

If you see errors like "Property [id] not found on component", ensure you're on version 3.0.1 or later. This issue was fixed in early 3.x releases.

### Version Detection Issues

The package automatically detects your Livewire version. If you experience issues:

1. Clear all caches:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan config:clear
   composer dump-autoload
   ```

2. Verify your Livewire installation:
   ```bash
   composer show livewire/livewire
   ```

3. Ensure you're using compatible versions per the compatibility matrix in [README.md](README.md).

### Drag and Drop Not Working

Ensure you've included the calendar scripts after Livewire scripts:

```blade
@livewireScripts
@livewireCalendarScripts
```

## Getting Help

If you encounter issues during upgrade:

1. Check the [CHANGELOG.md](CHANGELOG.md) for detailed version changes
2. Review the [compatibility matrix](README.md#compatibility) in README.md
3. Search [existing GitHub issues](https://github.com/omnia-digital/livewire-calendar/issues)
4. Create a [new issue](https://github.com/omnia-digital/livewire-calendar/issues/new) with:
   - Your PHP version
   - Your Laravel version
   - Your Livewire version
   - Your livewire-calendar version
   - Complete error message and stack trace

## Future Versions

The package maintainers are committed to backward compatibility. Future major versions will:

1. Provide at least 12 months of bug fix support for previous major versions
2. Include comprehensive upgrade guides
3. Minimize breaking changes where possible
4. Use feature detection to support multiple framework versions simultaneously

Stay updated by watching the [repository](https://github.com/omnia-digital/livewire-calendar) and checking the [CHANGELOG.md](CHANGELOG.md) regularly.
