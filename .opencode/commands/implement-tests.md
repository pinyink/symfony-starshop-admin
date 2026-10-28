
---
description: Implement, run, and verify automated tests for an existing Symfony application
---

# Implement Tests

Implement automated tests for an existing Symfony application based on the user's request, an approved test plan, or clearly identified testing gaps.

Use `project-analyzer` to understand the relevant application behavior and `test-engineer` to design, implement, execute, and evaluate tests.

The objective is to improve meaningful test coverage without unnecessarily changing application behavior or unrelated code.

## Workflow

### 1. Understand the Scope

Read `AGENTS.md` and relevant documentation under `docs/`, if present.

Determine whether the task concerns:

- An existing test plan
- A specific feature or module
- Missing test coverage
- Tests for a bug or regression
- A broader testing gap across the project

If an approved test plan exists, use it as the starting point. Otherwise, inspect the relevant implementation and establish a focused test scope.

### 2. Inspect the Existing Implementation

Use `project-analyzer` to inspect the relevant routes, controllers, services, entities, repositories, forms, validation, authorization, templates, and existing tests.

Identify:

- Expected behavior and important business rules
- Relevant dependencies and side effects
- Existing test patterns and reusable helpers
- Authorization and validation boundaries
- Database relationships and persistence requirements
- Potential regression areas

Follow the actual project architecture. Do not assume a specific authentication mechanism, RBAC bundle, or testing configuration.

### 3. Inspect the Testing Environment

Use `test-engineer` to verify:

- Installed testing tools and versions
- Test directory and naming conventions
- Test database configuration
- Fixture and database-reset strategy
- Available test commands
- Required environment variables and external services

Confirm that any database used by tests is isolated and safe before running destructive test setup or database-reset commands.

Do not install dependencies or change the test infrastructure without a justified need and appropriate authorization.

### 4. Select and Implement Tests

Ask `test-engineer` to implement the relevant tests.

Prioritize:

1. Core expected behavior
2. Input validation and invalid requests
3. Authentication and authorization
4. Database persistence and relationships
5. Important boundary and failure conditions
6. Security-sensitive behavior
7. Regression scenarios for known defects

Choose the simplest suitable test level:

- Unit tests for isolated business logic
- Integration tests for service and persistence behavior
- Functional tests for HTTP requests, routing, forms, and access control
- End-to-end tests only when supported by the project or required by the task

Follow existing Symfony and PHPUnit conventions where applicable, including `KernelTestCase`, `WebTestCase`, fixtures, and project-specific helpers.

For each test:

- Assert observable behavior and meaningful outcomes.
- Use deterministic, isolated test data.
- Verify both successful and rejected operations where relevant.
- Avoid excessive mocking that hides integration problems.
- Avoid duplicating existing coverage without a clear reason.
- Do not modify production code merely to make test setup easier.

### 5. Execute the Tests

Run tests in this order where appropriate:

1. Newly created or modified tests
2. Related test classes
3. The relevant test suite
4. The full test suite
5. Relevant static analysis or code-quality checks

Inspect `composer.json`, PHPUnit configuration, and project documentation to determine the correct commands.

Record the actual commands and results. Do not assume a test command exists or claim that tests passed without executing it.

### 6. Diagnose Failures

For every relevant failure:

1. Inspect the error message and stack trace.
2. Reproduce the failure when possible.
3. Determine whether it originates from application code, test code, configuration, the environment, or a pre-existing defect.
4. Trace the root cause.
5. Decide whether the failure can be resolved within the authorized scope.

This command primarily implements tests. If a failure reveals an application defect:

- Report the evidence and likely root cause.
- Apply a minimal application fix only if the task authorizes fixing defects.
- Otherwise, report the issue separately and recommend `/fix`.

Do not weaken valid assertions, skip tests, or change expected results merely to obtain a passing suite.

### 7. Verify Regressions

After implementation or authorized fixes:

- Rerun the affected tests.
- Run related tests.
- Run the full suite when practical.
- Verify that new tests do not depend on execution order or shared mutable state.
- Check that existing behavior remains intact.

If the full suite cannot run, state what was tested and why broader verification was unavailable.

### 8. Update Testing Documentation

When appropriate, update the existing testing documentation with:

- New test coverage
- Test commands
- Required environment setup
- Known limitations or skipped tests
- Important testing conventions

Avoid duplicating the test plan or rewriting documentation unrelated to the changes.

## Symfony-Specific Considerations

When relevant, test:

- Route methods and HTTP responses
- Form submission and validation
- CSRF protection
- Authentication, authorization, and voters
- Doctrine persistence and relationships
- File upload validation and failure handling
- Redirects, flash messages, and error responses
- Business rules and security-sensitive state transitions

Use only tools and patterns supported by the actual project.

## Scope and Safety

- Do not implement unrelated features.
- Do not change application behavior unless fixing defects is explicitly authorized.
- Do not weaken or remove existing security controls.
- Do not run migrations, reset databases, or alter persistent data without verifying the target environment and authorization.
- Do not use production credentials or production data for testing.
- Do not install or remove packages without appropriate authorization.
- Do not commit, merge, push, or rewrite Git history.
- Do not claim coverage percentages or test results that were not verified.

## Completion Criteria

The task is complete when:

- Relevant tests have been implemented.
- Tests follow existing project conventions.
- Newly created or modified tests have been executed when possible.
- Failures and blockers have been classified accurately.
- Related regression tests have been run when practical.
- Remaining application defects and coverage gaps are documented.
- Relevant testing documentation has been updated when appropriate.

A task is not fully verified merely because test files were created.
