
---
description: Audit an existing Symfony application's security and report evidence-based findings
---

# Security Audit

Perform a defensive security audit of the current Symfony project.

Use the `security-auditor` agent to inspect the relevant code, configuration, and security boundaries. Verify findings where safely possible and provide actionable remediation recommendations.

The default behavior is read-only. Do not fix vulnerabilities or modify application files unless explicitly requested.

## Workflow

### 1. Establish Scope

Read `AGENTS.md` and relevant security documentation under `docs/`, if present.

Determine whether the request covers:

- The entire application
- A specific module or feature
- A recent code change
- A particular suspected vulnerability
- Security controls such as authentication, RBAC, file uploads, or input handling

If the user does not specify a scope, perform a broad application security review based on the available repository and time constraints.

State the audit scope and any exclusions. Do not claim comprehensive coverage when only selected components were inspected.

### 2. Inspect the Application

Delegate the security analysis to `security-auditor`.

Ask the agent to inspect the actual implementation, including relevant:

- Routes, controllers, and access-control configuration
- Authentication, authorization, voters, and permission checks
- Entities, repositories, and database queries
- Forms, validation, and request handling
- Twig templates and HTML rendering
- File upload and download workflows
- Session, CSRF, and redirect handling
- External integrations and HTTP requests
- Environment configuration and secret handling
- Error handling, logging, and dependencies
- Existing security tests

Follow data flows from untrusted input to sensitive operations and outputs.

Do not assume that a particular security bundle, authentication mechanism, or directory structure exists without verifying it.

### 3. Identify and Verify Findings

For each suspected issue:

1. Locate the relevant code or configuration.
2. Trace the input, trust boundary, and sensitive operation.
3. Determine whether the expected security control is absent or ineffective.
4. Assess realistic prerequisites and potential impact.
5. Verify the finding through code analysis, existing tests, or safe focused testing when possible.
6. Distinguish confirmed vulnerabilities from potential risks and hardening recommendations.

Do not conduct intrusive tests against production systems, unrelated services, or unauthorized targets.

Do not run destructive commands, modify production data, or retrieve real secrets.

### 4. Assess Severity

Classify findings by realistic exploitability and impact:

- **Critical:** Catastrophic compromise, broad system takeover, or large-scale exposure of highly sensitive data.
- **High:** Serious unauthorized access, privilege escalation, or exposure of important sensitive data.
- **Medium:** Meaningful but constrained security impact.
- **Low:** Limited practical impact or a narrow weakness.
- **Informational:** Hardening recommendations without a demonstrated vulnerability.

Consider required privileges, attack complexity, affected assets, and realistic consequences.

Do not inflate severity or treat every missing hardening measure as a vulnerability.

### 5. Prioritize Remediation

For each finding, recommend:

- The underlying security problem
- The affected code and execution path
- The smallest appropriate remediation
- Required regression tests
- Any relevant operational or deployment considerations

Group findings with a shared root cause where appropriate, while identifying all affected locations.

Do not recommend architectural changes, package installation, or storage redesign without a justified security need.

### 6. Review Existing Security Tests

Inspect available tests for relevant coverage, including:

- Unauthorized and unauthenticated access
- RBAC and resource ownership
- Invalid input and validation failures
- CSRF protection
- XSS and output encoding
- File upload restrictions
- Sensitive business operations
- Regression cases for confirmed findings

Run relevant existing tests only when the environment is safe and the commands are appropriate.

Do not claim a vulnerability is fixed merely because a test passes. This command audits the current state; remediation requires a separate authorized workflow.

### 7. Document Results

If requested, create or update the project's security documentation.

Preserve existing documentation and avoid duplicating detailed findings across multiple files.

Do not include credentials, tokens, secret values, or sensitive production data in reports.

## Finding Format

For each finding, report:

- **ID and title**
- **Severity**
- **Status:** Confirmed, Potential, or Not Reproduced
- **Location:** File, class, method, route, or configuration
- **Evidence:** Relevant implementation behavior
- **Attack prerequisites:** Required access or conditions
- **Attack path:** How the issue could occur
- **Impact:** What an attacker could achieve
- **Recommendation:** Focused remediation
- **Verification:** Evidence and tests actually performed

Include only details relevant to the finding. If evidence is insufficient, explain what remains to be verified.

## Final Report

Provide the following sections:

### 1. Audit Summary
- Scope and components inspected
- Overall observations and important limitations

### 2. Findings by Severity
- Critical
- High
- Medium
- Low
- Informational

Omit empty categories or explicitly state that no findings were identified within the inspected scope.

### 3. Existing Security Controls
Summarize relevant controls that were verified in the inspected code. Do not infer application-wide security from a few protected routes.

### 4. Test and Verification Results
List commands executed, actual results, and findings that could not be reproduced or verified.

### 5. Recommended Actions
Prioritize the most important remediations and required regression tests.

### 6. Remaining Risks
Document unresolved issues, assumptions, and uninspected areas.

## Scope and Safety

- Perform defensive analysis of the current repository and authorized test environment.
- Do not modify application code by default.
- Do not install or remove dependencies.
- Do not run migrations, reset databases, or perform destructive operations.
- Do not expose secrets or sensitive data.
- Do not test unauthorized external targets.
- Do not commit, merge, push, or rewrite Git history.
- Do not claim that the application is secure based on a limited audit.
- Do not invent findings, exploitability, or verification results.

## Completion Criteria

The audit is complete when:

- The scope and relevant attack surface are identified.
- Findings are supported by repository evidence.
- Confirmed vulnerabilities are distinguished from potential risks.
- Severity and remediation priorities are justified.
- Existing security tests are reviewed where relevant.
- Verification limitations and remaining risks are documented.
- No unauthorized changes or destructive actions were performed.
