# Integration Tests

This directory contains integration tests for the Joomlatools Console.

## Purpose
Integration tests test how different components work together, including interactions with external services like databases and file systems.

## Organization
```
tests/Integration/
├── Site/        # Site management integration tests
├── Database/    # Database operation integration tests
└── Extension/   # Extension management integration tests
```

## Guidelines
- Integration tests can use real databases and file systems
- Tests should clean up after themselves (use tearDown())
- Use SQLite in-memory databases for speed when possible
- Tests can be slower than unit tests (aim for < 10 seconds per test)
- Test complete workflows, not just individual functions