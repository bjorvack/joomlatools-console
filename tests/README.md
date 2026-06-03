# Test Directory Structure

This directory contains all tests for the Joomlatools Console project.

## Directory Organization

```
tests/
├── Unit/              # Unit tests (isolated, fast)
├── Integration/       # Integration tests (component interactions)
├── Fixtures/          # Test data and sample files
│   ├── Joomla/       # Joomla version-specific fixtures
│   │   ├── 3.10/    # Joomla 3.10 samples
│   │   ├── 4.4/     # Joomla 4.4 samples
│   │   ├── 5.4/     # Joomla 5.4 samples
│   │   └── 6.1/     # Joomla 6.1 samples
│   ├── Config/      # Configuration samples
│   └── SQL/         # SQL schema samples
├── Mocks/           # Mock data and factories
│   ├── Http/        # HTTP response mocks
│   ├── Filesystem/  # File system mocks
│   └── Database/    # Database mocks
├── bootstrap.php    # Test bootstrap file
└── TestCase.php     # Base test class
```

## Test Types

### Unit Tests
- Test individual functions and methods in isolation
- Use mocks for external dependencies
- Fast execution, no external services required
- Located in `tests/Unit/`

### Integration Tests
- Test component interactions
- May use real databases or file systems
- Slower execution, but more realistic
- Located in `tests/Integration/`

## Running Tests

### Run All Tests
```bash
vendor/bin/phpunit
```

### Run Unit Tests Only
```bash
vendor/bin/phpunit --testsuite "Unit Tests"
```

### Run Integration Tests Only
```bash
vendor/bin/phpunit --testsuite "Integration Tests"
```

### Run Specific Test File
```bash
vendor/bin/phpunit tests/Unit/Joomla/UtilTest.php
```

### Run with Coverage
```bash
vendor/bin/phpunit --coverage-html tests/coverage/html
```

## CI/CD Testing

Tests are run automatically in CI/CD environments:
- GitHub Actions: See `.github/workflows/phpunit-tests.yml`
- Docker: See `Dockerfile.test`

PHPUnit is installed dynamically in CI/CD environments based on PHP version.