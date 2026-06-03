# PHPUnit Version Requirements

## Version Strategy

This project uses different PHPUnit versions based on the PHP version to ensure maximum compatibility and performance.

## PHPUnit Version Matrix

| PHP Version | PHPUnit Version | Reason |
|-------------|----------------|--------|
| 7.3 - 8.0   | 9.5.x          | Stable, well-tested, supports PHP 7.3+ |
| 8.1 - 8.4   | 10.0+          | Modern PHP features, better performance |

## Installation

### GitHub Actions
PHPUnit is installed dynamically based on the PHP version:

```yaml
- name: Install PHPUnit
  run: |
    if [ "${{ matrix.php-version }}" = "7.3" ] || \
       [ "${{ matrix.php-version }}" = "7.4" ] || \
       [ "${{ matrix.php-version }}" = "8.0" ]; then
      composer require --dev phpunit/phpunit:^9.5
    else
      composer require --dev phpunit/phpunit:^10.0
    fi
```

### Docker
Add to your Dockerfile:

```dockerfile
# Install PHPUnit based on PHP version
RUN if [ $(php -r "echo PHP_VERSION_ID;") -lt 80100 ]; then \
      composer require --dev phpunit/phpunit:^9.5; \
    else \
      composer require --dev phpunit/phpunit:^10.0; \
    fi
```

## Version Compatibility Notes

- **PHPUnit 9.5**: Last version to support PHP 7.3, EOL February 2024
- **PHPUnit 10.0**: Requires PHP 8.1+, supports modern PHP features like union types, attributes
- **PHPUnit 11.0+**: Requires PHP 8.2+, not currently used

## Testing

To verify PHPUnit installation:

```bash
# For PHP 7.3-8.0
vendor/bin/phpunit --version

# For PHP 8.1+
vendor/bin/phpunit --version
```

Expected output should show the appropriate PHPUnit version for your PHP version.