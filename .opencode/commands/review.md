
---
description: Review Symfony code changes for correctness, maintainability, security, and regressions
---

# Code Review

Review code changes in the current Symfony project to identify correctness issues, regressions, security risks, maintainability problems, and deviations from project requirements or conventions.

The default behavior is read-only. Inspect and report findings without modifying application code.

## Workflow

### 1. Understand the Review Scope

Read `AGENTS.md` and relevant documentation under `docs/`, if present.

Determine the review scope from the user's request:

- Recent code changes
- A specific file, class, feature, or module
- Changes related to a bug fix
- A newly implemented feature
- An existing pull request or branch comparison, when the necessary Git context is available
- A broader review of the current project

If the user does not specify a scope, review the current uncommitted changes first.

Inspect Git status and diffs before reviewing. Include staged, unstaged, and relevant untracked files when appropriate.

Do not assume that all current working-tree changes belong to the task being reviewed. Preserve and distinguish unrelated user changes.

If no relevant changes are found, explain the limitation and ask for a target or offer a focused review of specified files.

### 2. Understand the Intended Behavior

Establish what the changes are intended to accomplish.

Use relevant sources of truth, such as:

- The user's requirements and acceptance criteria
- Existing project documentation
- Approved implementation or test plans
- Related issues or task descriptions
- Existing application behavior
- Established project conventions

Identify important requirements that the changes may violate.

Do not invent requirements or treat personal style preferences as defects.

If requirements are ambiguous, state the assumption and assess the changes against the best-supported interpretation.

### 3. Inspect the Implementation

Use `project-analyzer` when additional architectural context is needed.

Inspect relevant:

- Routes and controllers
- Services and business logic
- Entities and Doctrine relationships
- Repositories and query construction
- Forms, validation, and request handling
- Authentication, authorization, and voters
- Twig templates and frontend interactions
- Configuration and dependency injection
- Database migrations and schema changes
- File uploads and external integrations
- Existing tests and test fixtures

Trace the affected execution paths rather than reviewing changed lines in isolation.

Inspect surrounding code when needed to understand behavior, dependencies, and possible regressions.

Do not assume every Symfony project uses the same packages, directory structure, authentication system, or frontend stack.

### 4. Review Correctness and Business Logic

Check for:

- Incorrect conditions, calculations, or state transitions
- Missing validation or incorrect validation rules
- Incorrect handling of null values and missing resources
- Broken database relationships or persistence behavior
- Inconsistent transaction boundaries
- Incorrect redirects, HTTP methods, or response codes
- Duplicate operations and unexpected side effects
- Race conditions where relevant
- Broken pagination, filtering, sorting, or search behavior
- Inconsistent behavior across related features
- Edge cases that could cause runtime errors or data loss

Prioritize defects that can cause incorrect results, failed requests, inconsistent data, or broken user workflows.

Do not report purely hypothetical issues without a plausible execution path.

### 5. Review Symfony Conventions and Maintainability

Check whether the changes follow the actual project's established patterns.

Review, where relevant:

- Dependency injection and service configuration
- Controller responsibilities and business logic placement
- Entity mapping and relationship management
- Repository query patterns
- Form handling and validation
- Route naming and HTTP method conventions
- Twig template organization and escaping
- Exception handling and logging
- Configuration management
- Naming consistency and code duplication
- Unnecessary complexity or unrelated refactoring
- Compatibility with the project's installed PHP and Symfony versions

Prefer minimal, maintainable changes that fit the existing architecture.

Do not require a redesign merely because another architecture or coding style is preferred.

### 6. Review Security

Inspect security-sensitive changes, including:

- Authentication and authorization
- RBAC permissions, voters, and resource ownership
- IDOR and privilege escalation
- Input validation and mass assignment
- SQL injection and unsafe query construction
- XSS and unsafe template rendering
- CSRF protection
- File upload and download restrictions
- Open redirects and SSRF where relevant
- Sensitive data exposure
- Secret handling and logging
- State-changing HTTP methods
- Business-rule enforcement on the server

Use `security-auditor` for deeper analysis when the changes introduce or affect significant security boundaries.

Do not assume that hidden interface elements provide authorization. Verify server-side enforcement.

Report security findings with concrete evidence and realistic impact.

### 7. Review Tests and Verification

Inspect tests associated with the changes.

Determine whether they cover:

- The intended behavior
- Invalid inputs and failure cases
- Relevant authorization boundaries
- Important edge cases
- Regression scenarios
- Database persistence and relationships where applicable

Use `test-engineer` when deeper test analysis or verification is required.

Run relevant tests and quality checks only when appropriate for the current environment.

Inspect the project's Composer scripts, PHPUnit configuration, and documentation before selecting commands.

Report only commands actually executed and results actually observed.

Do not reset databases, run destructive migrations, or modify persistent data without verifying the target environment and obtaining appropriate authorization.

A passing test suite does not automatically prove that the implementation is correct or secure.

### 8. Evaluate Change Scope and Compatibility

Check for:

- Unrelated modifications
- Accidental deletion or replacement of existing behavior
- Unnecessary dependency changes
- Incompatible method signatures or public interfaces
- Database schema changes without appropriate migration handling
- Configuration changes that affect other environments
- Missing documentation for significant behavior changes
- Generated files or artifacts that should not be included
- Potential backward-compatibility issues

Preserve unrelated user work. Do not revert changes merely because they are outside the expected scope; identify them and clarify ownership when necessary.

### 9. Validate Findings

Before reporting a finding:

1. Identify the relevant code or configuration.
2. Understand the surrounding execution path.
3. Confirm the issue is introduced by the reviewed changes or is directly relevant to the requested review.
4. Determine realistic conditions under which it can occur.
5. Assess its actual impact.
6. Verify it through tests or other safe methods when possible.

Distinguish newly introduced defects from pre-existing issues.

If a pre-existing issue materially affects the changed feature, report it separately.

Do not invent line numbers, test results, runtime behavior, or security impact.

### 10. Classify Findings

Use these priority levels:

- **P1 — Critical:** A severe defect or exploitable security issue requiring immediate attention.
- **P2 — High:** A significant correctness, security, data-integrity, or regression issue.
- **P3 — Medium:** A meaningful defect with a narrower impact or a condition-dependent failure.
- **P4 — Low:** A minor defect or maintainability issue with limited practical impact.
- **Info:** An observation, optional improvement, or non-blocking recommendation.

Prioritize actual impact and likelihood, not the number of findings.

Do not classify style preferences or speculative concerns as high-priority defects.

### 11. Produce the Review Report

For each actionable finding, use this format:

#### [ID] Short finding title

- **Priority:** P1 / P2 / P3 / P4
- **Category:** Correctness / Security / Regression / Performance / Maintainability / Testing
- **Location:** File path and line number or relevant method
- **Evidence:** The specific code behavior or condition
- **Impact:** What can go wrong
- **Recommendation:** The smallest appropriate correction

Include a location that helps the developer find the issue quickly.

If a finding cannot be verified, label it as a potential issue and explain the missing evidence.

Do not report a finding solely to suggest an alternative implementation when the existing implementation is valid.

## Final Report

### 1. Review Summary

Summarize the scope, inspected changes, and overall assessment.

### 2. Findings

List actionable findings in priority order, highest first.

If no actionable findings are identified, state that explicitly and mention any important limitations.

### 3. Positive Observations

Mention relevant design decisions or safeguards that are well implemented, when useful.

Do not add generic praise merely to fill this section.

### 4. Verification Results

List:

- Tests and commands actually executed
- Their actual results
- Checks not executed and the reason
- Environment limitations

### 5. Remaining Risks

Identify unresolved concerns, missing coverage, and assumptions that materially affect confidence in the review.

### 6. Recommended Next Steps

Provide the smallest useful follow-up actions.

If fixes are required, recommend `/fix` with the specific findings to address.

## Scope and Safety

- Perform a review by default; do not modify code.
- Do not implement fixes unless explicitly authorized.
- Do not install or remove dependencies.
- Do not run migrations, reset databases, or perform destructive operations without authorization and environment verification.
- Do not expose secrets or sensitive data.
- Do not overwrite or revert unrelated user changes.
- Do not commit, merge, push, or rewrite Git history.
- Do not claim that tests passed unless they were actually executed.
- Do not claim that a project is defect-free or secure based on a limited review.

## Completion Criteria

The review is complete when:

- The review scope and intended behavior are understood.
- Relevant changes and surrounding code have been inspected.
- Findings are supported by evidence and prioritized by impact.
- Newly introduced issues are distinguished from pre-existing problems.
- Relevant tests and verification results are reported accurately.
- Remaining risks and limitations are explicit.
- No unauthorized code changes were made.
