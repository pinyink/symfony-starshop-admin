
---
description: Analyze a Symfony feature and create a prioritized, evidence-based test plan
---

# Test Plan

Create a practical test plan for an existing Symfony application or a specific feature.

This command plans testing only. Do not implement application changes or write test files unless explicitly requested.

## Workflow

### 1. Understand Project Instructions

Read `AGENTS.md` and relevant documentation under `docs/`, if present.

Follow the project's existing conventions and testing requirements.

### 2. Analyze the Feature

Use `project-analyzer` to inspect the requested feature and its dependencies.

Identify, where applicable:

- Routes, HTTP methods, and expected responses
- Controllers, services, entities, and repositories
- Forms, validation rules, and business logic
- Authentication, authorization, roles, and voters
- Database relationships and persistence behavior
- Twig templates, redirects, and flash messages
- File uploads, external integrations, and other security-sensitive operations
- Existing tests and reusable testing helpers

Use repository evidence to understand current behavior. Distinguish existing behavior from the expected behavior described by the user.

If the request does not specify a particular feature, assess the project's testing coverage and identify the highest-priority gaps.

### 3. Inspect Testing Capabilities

Use `test-engineer` to examine the existing testing setup and determine:

- Installed testing framework and relevant dependencies
- Existing test suites and directory conventions
- Test database and fixture configuration
- Available test commands
- Existing helpers, factories, fixtures, and base test classes
- Environment or external-service requirements

Do not assume a test command or dependency exists without checking the project.

### 4. Design Test Scenarios

Ask `test-engineer` to propose tests covering the relevant behaviors.

Consider:

- Successful operations
- Invalid inputs and validation errors
- Authentication and authorization failures
- Missing or inaccessible resources
- Database persistence and relationships
- Boundary conditions and duplicate submissions
- Business-rule violations
- Error handling and failure paths
- Security controls and regression scenarios

Choose the simplest suitable test level:

- Unit tests for isolated business logic
- Integration tests for service and persistence interactions
- Functional tests for HTTP requests, routing, forms, and access control
- End-to-end tests only when supported by the project or required by the task

Do not create scenarios that are unrelated to the feature or duplicate existing coverage without a reason.

### 5. Prioritize the Plan

Classify scenarios by priority:

- **P1 — Critical:** Essential functionality, significant security boundaries, or high-impact failure risks.
- **P2 — Important:** Core validation, authorization, persistence, and important edge cases.
- **P3 — Additional:** Lower-risk edge cases, secondary behavior, and broader regression coverage.

Adjust priorities to the feature's actual risk. Do not classify every test as critical.

For each scenario, specify:

- Scenario and expected behavior
- Test level
- Preconditions or test data
- Important assertions
- Priority
- Existing test coverage, if known

### 6. Review Gaps and Constraints

Identify:

- Missing test coverage
- Existing tests that may already cover a scenario
- Required fixtures or test data
- Database and environment constraints
- Dependencies that are unavailable
- Behaviors that need clarification

Do not claim that a test passes or fails when it has not been executed.

If requirements are ambiguous, state the assumption and provide a provisional plan where possible rather than blocking the entire task.

### 7. Document the Test Plan

If appropriate, create or update `docs/test-plan.md`.

Keep the plan specific to the requested feature or clearly label it as a project-wide plan.

Preserve useful existing documentation. Avoid duplicating information already maintained in a dedicated testing document; link to or reference that document where appropriate.

## Output Format

### 1. Scope
- Feature or components covered
- Relevant behavior and boundaries
- Assumptions and exclusions

### 2. Existing Test Setup
- Testing framework and relevant dependencies
- Test commands and suites
- Test database and fixture strategy
- Existing coverage relevant to the task

### 3. Test Scenarios

For each scenario, provide:

| ID | Scenario | Expected Result | Test Level | Priority |
|---|---|---|---|---|

Add preconditions and important assertions where needed.

### 4. Coverage Gaps
- Missing tests
- Important edge cases
- Security and regression concerns

### 5. Execution Plan
- Recommended order
- Commands to run, verified against project configuration
- Required environment setup
- Dependencies or blockers

### 6. Documentation
- Files created or updated
- Relevant unresolved questions

## Scope and Safety

- Do not implement application features or fixes.
- Do not create test files unless explicitly requested.
- Do not run migrations, reset databases, or alter application data.
- Do not install dependencies.
- Do not weaken existing tests.
- Do not commit, merge, push, or rewrite Git history.
- Do not invent test results or coverage statistics.

## Completion Criteria

The plan is complete when:

- Relevant application behavior and testing conventions are understood.
- Scenarios cover important success, failure, validation, authorization, and regression paths.
- Priorities reflect realistic risk.
- Test commands and environment requirements are evidence-based.
- Existing coverage and gaps are distinguished.
- The plan is documented when appropriate.
- No application code or test implementation has been changed.
