# Joomla 3.10 Test Fixtures

This directory contains sample files for testing Joomla 3.10 compatibility.

## Files

- `version_sample.php` - Sample JVersion class structure for Joomla 3.10

## Usage

These fixtures are used by unit tests to verify:
- Version detection logic for Joomla 3.x
- Property-based version format (RELEASE, DEV_LEVEL)
- Canonical version string generation

## Joomla 3.10 Characteristics

- Uses properties `RELEASE` and `DEV_LEVEL` for version information
- Version file location: `/libraries/cms/version/version.php`
- Class name: `JVersion`
- Version format: `3.10.12`