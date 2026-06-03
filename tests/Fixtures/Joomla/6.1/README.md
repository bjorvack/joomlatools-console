# Joomla 6.1 Test Fixtures

This directory contains sample files for testing Joomla 6.1 compatibility.

## Files

- `version_sample.php` - Sample Version class structure for Joomla 6.1

## Usage

These fixtures are used by unit tests to verify:
- Version detection logic for Joomla 6.x
- Constant-based version format (MAJOR_VERSION, MINOR_VERSION, PATCH_VERSION)
- Canonical version string generation
- Namespace support (`\Joomla\CMS\Version`)

## Joomla 6.1 Characteristics

- Uses constants `MAJOR_VERSION`, `MINOR_VERSION`, `PATCH_VERSION` for version information
- Version file location: `/libraries/src/Version.php`
- Class name: `Joomla\CMS\Version`
- Namespace: `Joomla\CMS`
- Version format: `6.1.0`
- Requires PHP 8.3+