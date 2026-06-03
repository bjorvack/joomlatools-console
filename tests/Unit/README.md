# Unit Tests

This directory contains unit tests for the Joomlatools Console.

## Purpose
Unit tests test individual functions and methods in isolation, without external dependencies like databases or file systems.

## Organization
Tests should mirror the source code structure:
```
tests/Unit/
├── Command/      # Tests for console commands
├── Joomla/       # Tests for Joomla utilities
└── Symlinkers/   # Tests for project symlinks
```

## Guidelines
- Use mocks for external dependencies (HTTP calls, file system, database)
- Each test should be independent and isolated
- Tests should be fast (aim for < 1 second per test)
- Test both success and failure cases
- Use descriptive test names that explain what is being tested