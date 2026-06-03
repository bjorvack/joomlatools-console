# Test Fixtures

This directory contains test data and sample files used by tests.

## Purpose
Fixtures provide consistent test data across different test runs, ensuring tests are deterministic and reproducible.

## Directory Structure

### Joomla Fixtures
Each Joomla version has its own subdirectory with version-specific samples:
- `3.10/` - Joomla 3.10 version files and configurations
- `4.4/` - Joomla 4.4 version files and configurations
- `5.4/` - Joomla 5.4 version files and configurations
- `6.1/` - Joomla 6.1 version files and configurations

### Config Fixtures
Sample configuration files for different scenarios:
- Database configurations
- Site configurations
- Environment configurations

### SQL Fixtures
SQL schema and sample data for database testing:
- Base schemas for different Joomla versions
- Sample data for testing
- Migration scripts

## Guidelines
- Keep fixtures minimal and focused
- Document the purpose of each fixture file
- Use realistic but anonymized data
- Update fixtures when Joomla versions change