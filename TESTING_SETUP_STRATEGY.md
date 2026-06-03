# Testing Repository Setup Strategy

## 🏗️ Current Repository Structure Analysis

### Existing Structure
```
joomlatools-console/
├── bin/                    # CLI executable
├── src/                    # Source code
│   └── Joomlatools/
│       └── Console/
│           ├── Command/    # Console commands
│           ├── Joomla/     # Joomla utilities
│           └── Symlinkers/ # Project symlinks
├── vendor/                 # Composer dependencies
├── .github/               # GitHub Actions
│   └── workflows/
├── composer.json
├── composer.lock
└── README.md
```

### Key Characteristics
- **PSR-0 Autoloading**: Uses `psr-0` with namespace prefix `Joomlatools\`
- **CLI Application**: Symfony Console based
- **Joomla Integration**: Direct Joomla file manipulation
- **External Dependencies**: Git, MySQL, file system operations

---

## 🧪 Proposed Testing Structure

### Directory Structure
```
joomlatools-console/
├── bin/
├── src/
├── tests/                    # NEW: Root test directory
│   ├── Unit/                # NEW: Unit tests
│   │   ├── Command/         # Unit tests for commands
│   │   ├── Joomla/          # Unit tests for Joomla utilities
│   │   └── Symlinkers/      # Unit tests for symlinks
│   ├── Integration/         # NEW: Integration tests
│   │   ├── Site/           # Site management integration tests
│   │   ├── Database/        # Database operation integration tests
│   │   └── Extension/      # Extension management integration tests
│   ├── Fixtures/            # NEW: Test fixtures
│   │   ├── Joomla/         # Joomla version-specific fixtures
│   │   │   ├── 3.10/       # Joomla 3.10 samples
│   │   │   ├── 4.4/        # Joomla 4.4 samples
│   │   │   ├── 5.4/        # Joomla 5.4 samples
│   │   │   └── 6.1/        # Joomla 6.1 samples
│   │   ├── Config/         # Configuration samples
│   │   └── SQL/            # SQL schema samples
│   ├── Mocks/               # NEW: Mock data and factories
│   │   ├── Http/           # HTTP response mocks
│   │   ├── Filesystem/     # File system mocks
│   │   └── Database/       # Database mocks
│   ├── bootstrap.php       # NEW: Test bootstrap file
│   └── TestCase.php        # NEW: Base test class
├── phpunit.xml             # NEW: PHPUnit configuration
├── .phpunit.cache/          # NEW: PHPUnit cache (gitignored)
├── .phpunit.result.cache    # NEW: PHPUnit result cache (gitignored)
└── .gitignore               # UPDATE: Add test artifacts
```

---

## 📦 Test Dependencies

### CI/Docker-Only Testing Approach
**No test dependencies in composer.json** - PHPUnit and testing tools are installed dynamically in CI/Docker environments only.

### CI/Docker Dependencies
Testing dependencies are installed in CI/Docker environments via:
- **GitHub Actions**: Installed during workflow execution
- **Docker**: Installed in test container images

### Dependencies Installed in CI/Docker
```yaml
# GitHub Actions example
- name: Install PHPUnit
  run: composer require --dev phpunit/phpunit:^9.5 phpunit/phpunit:^10.0

# Docker example
RUN composer require --dev phpunit/phpunit:^9.5 phpunit/phpunit:^10.0
```

### Dependency Justification
- **PHPUnit 9.5+**: Testing framework (supports PHP 7.3-8.4)
  - Use PHPUnit 9.5 for PHP 7.3-8.0
  - Use PHPUnit 10.0+ for PHP 8.1+ (modern PHP features)
- **Mockery 1.5+**: Mocking framework for external dependencies (CI/Docker only)
- **Faker 1.23+**: Test data generation (CI/Docker only)
- **PHPStan 1.10+**: Static analysis (CI only)
- **PHP_CodeSniffer 3.7+**: Code style checking (CI only)
- **Psalm 5.0+**: Type checking (CI only)

### Benefits of CI/Docker-Only Testing
- ✅ Clean local development environment
- ✅ No test dependency conflicts in production
- ✅ Consistent test environments across all developers
- ✅ Isolated test execution (no local file system pollution)
- ✅ Easy to reproduce test failures in identical environments
- ✅ Simplified onboarding for new developers

---

## ⚙️ Configuration Files

### phpunit.xml
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit 
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.5/phpunit.xsd"
    bootstrap="tests/bootstrap.php"
    colors="true"
    verbose="true"
    failOnRisky="true"
    failOnWarning="true"
    convertErrorsToExceptions="true"
    convertNoticesToExceptions="true"
    convertWarningsToExceptions="true"
    stopOnFailure="false"
    executionOrder="depends,defects"
    beStrictAboutOutputDuringTests="true"
    beStrictAboutTodoAnnotatedTests="true"
>
    <testsuites>
        <testsuite name="Unit Tests">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Integration Tests">
            <directory>tests/Integration</directory>
        </testsuite>
    </testsuites>

    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">src</directory>
        </include>
        <report>
            <html outputDirectory="tests/coverage/html"/>
            <text outputFile="php://stdout" showUncoveredFiles="false"/>
        </report>
    </coverage>

    <php>
        <ini name="display_errors" value="1"/>
        <ini name="error_reporting" value="-1"/>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
    </php>
</phpunit>
```

### phpstan.neon
```neon
parameters:
    level: 5
    paths:
        - src
    ignoreErrors:
        - '#Dynamic call to static method#'
    bootstrapFiles:
        - vendor/autoload.php
```

### psalm.xml
```xml
<?xml version="1.0"?>
<psalm
    errorLevel="5"
    resolveFromConfigFile="true"
    findUnusedBaselineEntry="true"
    findUnusedCode="false"
>
    <projectFiles>
        <directory name="src" />
        <ignoreFiles>
            <directory name="vendor" />
        </ignoreFiles>
    </projectFiles>
</psalm>
```

### phpcs.xml
```xml
<?xml version="1.0"?>
<ruleset name="Joomlatools">
    <description>Joomlatools Console Coding Standard</description>
    
    <rule ref="PSR12"/>
    
    <file>src</file>
    <file>tests</file>
    
    <exclude-pattern>*/vendor/*</exclude-pattern>
    <exclude-pattern>*/.phpunit.cache/*</exclude-pattern>
</ruleset>
```

---

## 🗄️ Test Data Management Strategy

### Fixture Organization
- **Version-Specific Fixtures**: Each Joomla version gets its own directory
- **Configuration Samples**: Real configuration.php and .env files
- **SQL Samples**: Database schema samples for each version
- **Mock Data**: Pre-canned responses for external services

### Fixture Structure
```
tests/Fixtures/Joomla/
├── 3.10/
│   ├── version.php                 # Sample JVersion class
│   ├── configuration.php.sample    # Sample configuration
│   └── sql/
│       ├── joomla.sql              # Base schema
│       └── sample_data.sql        # Sample data
├── 4.4/
│   ├── Version.php                # Sample Version class
│   ├── .env.sample                # Sample environment file
│   └── sql/
│       ├── base.sql
│       └── extensions.sql
├── 5.4/
│   └── (similar structure)
└── 6.1/
    └── (similar structure)
```

### Mock Data Strategy
- **HTTP Mocks**: Pre-recorded GitHub API responses
- **File System Mocks**: Virtual file system for testing without real files
- **Database Mocks**: SQLite in-memory databases for fast testing
- **Command Mocks**: Mock shell commands for testing CLI interactions

---

## 🐳 Docker Integration Strategy

### Docker Compose for Testing
```yaml
version: '3.8'
services:
  php71:
    image: php:7.1-cli
    volumes:
      - .:/app
    working_dir: /app
  
  php72:
    image: php:7.2-cli
    volumes:
      - .:/app
    working_dir: /app
  
  # ... other PHP versions
  
  mysql57:
    image: mysql:5.7
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: test
  
  mysql80:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: test
```

### Test Matrix Strategy
- Use existing GitHub Actions matrix for CI
- Use Docker Compose for local development testing
- Support both approaches for flexibility

---

## 🚀 CI/CD Integration

### Enhanced GitHub Actions Workflow
```yaml
test:
  strategy:
    matrix:
      php-version: ['7.3', '7.4', '8.0', '8.1', '8.2', '8.3', '8.4']
      test-suite: ['unit', 'integration']
      # Conditional PHPUnit versions
      phpunit-version: ['9.5', '10.0']
    exclude:
      # PHPUnit 10.0 requires PHP 8.1+
      - php-version: '7.3'
        phpunit-version: '10.0'
      - php-version: '7.4'
        phpunit-version: '10.0'
      - php-version: '8.0'
        phpunit-version: '10.0'
  
  steps:
    - name: Install PHPUnit ${{ matrix.phpunit-version }}
      run: composer require --dev phpunit/phpunit:${{ matrix.phpunit-version }}
    
    - name: Run tests
      run: |
        if [ "${{ matrix.test-suite }}" = "unit" ]; then
          vendor/bin/phpunit --testsuite "Unit Tests"
        else
          vendor/bin/phpunit --testsuite "Integration Tests"
        fi
```

### Test Matrix Details
- **Total combinations**: 7 PHP versions × 4 Joomla versions = 28
- **Excluded combinations**: 8 (Joomla 5.4/6.1 PHP requirements)
- **Actual test jobs**: 20
- **PHP versions**: 7.3, 7.4, 8.0, 8.1, 8.2, 8.3, 8.4
- **PHPUnit versions**: 9.5 for PHP 7.3-8.0, 10.0 for PHP 8.1+

### Test Stages
1. **Static Analysis**: PHPStan, Psalm, PHPCS
2. **Unit Tests**: Fast, isolated tests
3. **Integration Tests**: Component interaction tests
4. **E2E Tests**: Full workflow tests (selected combinations)

---

## 🔒 Isolation Strategy

### Test Isolation Techniques
- **Database**: SQLite in-memory for unit tests, MySQL/PostgreSQL for integration
- **File System**: Virtual file system using vfsstream or temporary directories
- **HTTP Requests**: Mocked HTTP clients using Guzzle Mock Handler
- **Shell Commands**: Mocked exec() calls using function mocking
- **Environment**: Test-specific environment variables

### Cleanup Strategy
- `setUp()`: Create fresh test environment
- `tearDown()`: Clean up after each test
- Temporary directories: Automatic cleanup using PHP's temp functions
- Database transactions: Rollback after each test

---

## 📊 Performance Considerations

### Test Performance Optimization
- **Parallel Execution**: Run test suites in parallel where possible
- **Selective Testing**: Run only affected tests based on code changes
- **Test Caching**: Cache test results when code hasn't changed
- **Fast Failures**: Configure PHPUnit to stop on first failure in CI
- **Coverage Thresholds**: Only run full coverage on main branch

### Database Testing Performance
- **SQLite for Unit Tests**: In-memory SQLite for fast unit tests
- **Connection Pooling**: Reuse database connections across tests
- **Minimal Data**: Use minimal test data sets
- **Transaction Rollback**: Rollback instead of drop/create

---

## 🎯 Implementation Priority

### Phase 0: Setup (Immediate)
1. Create test directory structure
2. Create phpunit.xml configuration (for CI/Docker usage)
3. Create test bootstrap file
4. Create base TestCase class
5. Update .gitignore for test artifacts
6. Create Dockerfile for testing (optional)
7. Update GitHub Actions workflow for testing

### Phase 1: Unit Tests (Foundation)
1. Create unit tests for version detection
2. Create unit tests for configuration generation
3. Create unit tests for command validation

### Phase 2: Integration Tests (Advanced)
1. Set up integration test framework
2. Create database integration tests
3. Create file system integration tests

### Phase 3: CI/CD Enhancement (Polish)
1. Add static analysis to CI
2. Optimize test execution with conditional PHPUnit versions
3. Add coverage reporting

**Note**: All test dependencies are installed in CI/Docker environments only, not in composer.json

---

## 🔍 PHP 7.3+ Decision Rationale

### Why PHP 7.3+ Instead of 7.1+?

**PHPUnit Compatibility Challenge**:
- No single PHPUnit version supports PHP 7.1-8.4
- PHPUnit 7.x: PHP 7.1-7.3 (EOL 2020)
- PHPUnit 8.x: PHP 7.2+ (EOL 2023)
- PHPUnit 9.x: PHP 7.3+ (EOL 2024)
- PHPUnit 10.x: PHP 8.1+ (current)

**Benefits of PHP 7.3+**:
1. **Unified Testing**: PHPUnit 9.x works for PHP 7.3-8.0, PHPUnit 10.x for 8.1+
2. **Simplified CI/CD**: No complex conditional PHPUnit version management needed
3. **Modern PHP Features**: Access to modern PHP features in production code
4. **Security**: PHP 7.1-7.2 are EOL and unsupported
5. **Performance**: Newer PHP versions have significant performance improvements
6. **Ecosystem**: Better library and framework support

**Impact Analysis**:
- **Lost Support**: PHP 7.1 (EOL Dec 2018), PHP 7.2 (EOL Nov 2019)
- **Retained Support**: PHP 7.3-8.4 (actively maintained)
- **Joomla Coverage**: All Joomla versions still supported (3.10, 4.4, 5.4, 6.1)
- **Symfony Compatibility**: Symfony 4.4 already requires PHP 7.2.5+

**Conclusion**: The trade-off is favorable - we lose two ancient PHP versions but gain significantly simplified testing infrastructure and better overall maintainability. Additionally, CI/Docker-only testing keeps the local development environment clean and ensures consistent test environments.

---

## 📝 Key Decisions Made

### PHP Version Support
- **Minimum PHP**: 7.3+ (raised from 7.1+ to enable unified PHPUnit 9.x+)
- **Rationale**: PHP 7.1-7.2 are EOL (2018-2019), Symfony 4.4 requires PHP 7.2.5+
- **PHPUnit Strategy**: 
  - PHP 7.3-8.0: PHPUnit 9.5.x (stable, well-tested)
  - PHP 8.1-8.4: PHPUnit 10.0+ (modern PHP features, better performance)

### Autoloading Strategy
- **Keep PSR-0**: Maintain compatibility with existing structure
- **Test Autoloading**: Separate test autoloading in bootstrap.php
- **Namespace Consistency**: Tests mirror source namespace structure

### PHP Version Support
- **PHPUnit Version**: Use PHPUnit 9.5+ for PHP 7.1-8.4 support
- **Static Analysis**: Configure for PHP 7.1 minimum
- **CI Matrix**: Test all supported PHP versions

### Database Strategy
- **Unit Tests**: SQLite in-memory for speed
- **Integration Tests**: Real MySQL/PostgreSQL for accuracy
- **CI Testing**: Matrix testing with different database versions

### Mocking Strategy
- **External Services**: Mock all external HTTP requests
- **File System**: Use virtual file system where possible
- **Shell Commands**: Mock exec() calls for predictable testing

---

## 🚦 Next Steps

1. **Review this strategy** with the team
2. **Approve the structure** and dependencies
3. **Begin Phase 0 setup** (TASK-1.1 through TASK-1.3)
4. **Iterate** based on actual implementation experience

This strategy provides a solid foundation for implementing a robust testing framework that handles the complex PHP/Joomla version compatibility matrix while maintaining code quality and developer productivity.