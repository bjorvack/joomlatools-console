# Test Mocks

This directory contains mock data and factories for testing.

## Purpose
Mocks simulate external dependencies (HTTP calls, file system operations, database queries) to ensure tests are isolated and deterministic.

## Directory Structure

### Http Mocks
Pre-recorded HTTP responses for external API calls:
- GitHub API responses
- Joomla download responses
- Version check endpoints

### Filesystem Mocks
Virtual file system structures for testing file operations:
- Directory structures
- File contents
- Permission scenarios

### Database Mocks
Mock database data and query results:
- Sample result sets
- Mock database connections
- Query builders

## Guidelines
- Mocks should be realistic but simple
- Document what each mock simulates
- Keep mocks up to date with actual API responses
- Use factories for generating dynamic mock data