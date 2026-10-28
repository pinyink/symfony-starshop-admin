
---
description: Diagnose, fix, test, and verify bugs in an existing Symfony application
---

# Fix

Diagnose and fix a reported bug or verified defect in the current Symfony project.

The goal is to resolve the root cause with the smallest appropriate change, preserve existing behavior, and verify the fix through relevant tests.

## Workflow

### 1. Understand the Problem

Read `AGENTS.md` and relevant project documentation, if present.

Identify:

- Reported symptoms and expected behavior
- Steps to reproduce the problem
- Relevant error messages, logs, and stack traces
- Recent changes that may be related
- Constraints and acceptance criteria

If information is incomplete, investigate the code and provide a reasonable provisional diagnosis where possible. Ask for clarification only when missing information materially prevents safe progress.

Do not assume the reported symptom identifies the root cause.

### 2. Inspect the Existing Implementation

Use `project-analyzer` to investigate the affected feature and its dependencies.

Inspect relevant:

- Routes and controllers
- Services and business logic
- Entities, repositories, and database relationships
- Forms, validation, and request handling
- Authentication and authorization
- Twig templates and frontend interactions
- Configuration and external integrations
- Existing tests and related implementation patterns

Trace the execution path from the triggering input or request to the failing behavior.

Follow the existing project architecture and conventions. Do not redesign unrelated components.

### 3. Establish the Root Cause

Determine whether the issue originates from:

- Application logic
- Incorrect assumptions or business rules
- Validation or authorization
- Database queries or relationships
- Configuration or dependency compatibility
- Template or frontend behavior
- External services
- Test setup or environment problems

Reproduce the issue when practical.

Use code evidence, logs, stack traces, and test results to support the diagnosis.

Distinguish confirmed causes from hypotheses. Do not make speculative changes merely because they appear plausible.

### 4. Plan the Fix

Before modifying code, determine:

- The confirmed or most likely root cause
- The smallest change that addresses it
- Files and components affected
- Potential regressions
- Required regression tests
- Any security or data-integrity implications

For security-sensitive defects, consult `security-auditor` for focused analysis when appropriate.

If the proposed fix requires a broad architectural change, data migration, dependency replacement, or potentially destructive operation, explain the impact and obtain appropriate authorization before proceeding.

### 5. Implement the Fix

Apply the smallest maintainable correction that resolves the underlying problem.

Requirements:

- Preserve established project conventions.
- Reuse existing services, validation rules, and authorization mechanisms where appropriate.
- Keep business logic in the project's established layers.
- Avoid unnecessary refactoring and unrelated changes.
- Preserve backward compatibility unless the task requires a behavior change.
- Do not remove security controls to make the feature work.
- Do not weaken validation or tests to hide the problem.
- Do not introduce new dependencies without a justified need and appropriate authorization.

Do not silently change API contracts, database schemas, permissions, or business rules beyond the requested scope.

### 6. Add or Update Regression Tests

Use `test-engineer` to identify or implement regression tests for the defect.

Tests should demonstrate:

- The original failure or incorrect behavior
- The expected behavior after the fix
- Relevant invalid inputs or failure paths
- Authorization and security boundaries, where applicable
- Important adjacent behavior that could regress

Prefer a test that fails before the fix and passes afterward when this can be demonstrated safely.

If the task does not authorize test implementation, recommend the required tests and explain the remaining verification gap.

### 7. Run Verification

Run the narrowest relevant tests first.

When appropriate, continue with:

1. Tests for the affected component
2. Related integration or functional tests
3. The broader test suite
4. Relevant static analysis or code-quality checks

Determine actual commands from `composer.json`, PHPUnit configuration, and project documentation.

Inspect command results rather than relying only on exit codes or summaries.

If a test fails, determine whether it is related to the fix, pre-existing, or caused by the environment.

Do not claim success when verification is incomplete.

### 8. Review the Changes

Review the resulting diff and verify:

- The root cause has been addressed.
- The fix matches the intended behavior.
- No unrelated changes were introduced.
- Authorization and validation remain intact.
- Database consistency is preserved.
- Error handling remains appropriate.
- Tests cover the defect.
- No secrets or sensitive data were exposed.
- Relevant documentation is updated when necessary.

Use `security-auditor` for focused security verification if the change introduces or affects a security-sensitive path.

### 9. Document the Outcome

Update documentation only when the fix changes relevant behavior, configuration, architecture, security decisions, or operational procedures.

Do not create documentation for every trivial code change.

## Symfony-Specific Considerations

When relevant, verify:

- Route definitions and HTTP methods
- Dependency injection and service configuration
- Form handling and validation
- Authentication, voters, and access-control rules
- Doctrine queries, relationships, and transaction behavior
- Twig rendering and escaping
- CSRF protection
- File upload and download behavior
- Redirects, flash messages, and HTTP response codes
- Exception handling and logging

Inspect the actual project before applying Symfony-specific assumptions.

## Scope and Safety

- Fix the reported problem and directly related defects only.
- Do not rewrite working components without a clear reason.
- Do not run migrations, reset databases, or modify persistent data without verifying the target environment and obtaining appropriate authorization.
- Do not change production configuration or access production systems without explicit authorization.
- Do not expose credentials or sensitive production data.
- Do not install or remove dependencies without appropriate authorization.
- Do not commit, merge, push, or rewrite Git history.
- Preserve unrelated user changes in the working tree.
- If the fix cannot be safely completed, report the blocker and the next required action.

## Completion Criteria

The task is complete when:

- The root cause is identified or remaining uncertainty is clearly documented.
- A focused fix has been implemented when authorized.
- Regression tests have been added or their absence explained.
- Relevant tests and checks have been executed where possible.
- The resulting changes have been reviewed for unintended effects.
- Remaining failures, limitations, and risks are reported accurately.
