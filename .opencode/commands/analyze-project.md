
---
description: Analyze an existing Symfony project and document its actual architecture, conventions, and technical risks
---

# Analyze Project

Analyze the current Symfony project to establish an evidence-based understanding of its architecture, implementation patterns, dependencies, and development conventions.

Use the `project-analyzer` agent for repository analysis.

This command is for investigation and documentation, not implementation. Do not modify application code.

## Workflow

### 1. Inspect Project Instructions

Read `AGENTS.md` and relevant instructions or documentation under `docs/`, if present.

Follow existing repository instructions. Do not assume these files exist.

### 2. Analyze the Existing Project

Delegate the analysis to `project-analyzer`.

Ask the agent to inspect the actual repository and identify:

- PHP and Symfony versions and installed dependencies
- Application structure and architectural patterns
- Routes, controllers, services, entities, and repositories
- Doctrine configuration, database relationships, and migrations
- Authentication, authorization, roles, voters, and permission checks
- Forms, validation, Twig templates, and frontend conventions
- Testing configuration, existing tests, and available test commands
- File uploads, external integrations, and other relevant application-specific concerns
- Existing coding conventions and reusable implementation patterns
- Confirmed defects, potential risks, and unresolved technical questions

Keep the investigation focused on the actual repository. Do not infer missing functionality or configuration without evidence.

### 3. Review the Findings

Review the agent's report for:

- Evidence supporting important conclusions
- Relevant file paths and implementation details
- Differences between existing conventions and assumptions
- Confirmed issues versus potential risks
- Missing information that could affect future implementation

Do not report commands, tests, or runtime behavior as verified unless they were actually executed.

### 4. Document the Architecture

Create or update `docs/architecture.md` with the verified findings.

Document, where applicable:

- Project overview and technology stack
- Directory structure and component responsibilities
- Request and application execution flow
- Database and entity relationships
- Authentication and authorization architecture
- Frontend and template conventions
- Testing approach and available commands
- Important dependencies and integrations
- Existing limitations and architectural decisions

Preserve accurate existing documentation. Update only outdated or incomplete sections rather than replacing useful information unnecessarily.

Do not document secrets, credentials, or sensitive environment values.

If the project has a different documentation structure, follow its existing conventions rather than creating duplicate documents.

### 5. Report Results

Provide a concise summary containing:

1. Project overview
2. Architecture and conventions discovered
3. Important files inspected
4. Confirmed findings and potential risks
5. Documentation created or updated
6. Unresolved questions and limitations
7. Recommended next steps

## Scope and Safety

- Analyze the existing project; do not scaffold a new Symfony application.
- Do not implement features or fix identified defects.
- Do not install, remove, or update dependencies.
- Do not run migrations or destructive database commands.
- Do not modify environment configuration.
- Do not commit, merge, push, or rewrite Git history.
- Do not create unrelated documentation.
- Preserve the existing architecture and development conventions.

## Completion Criteria

The command is complete when:

- The existing project has been analyzed using repository evidence.
- Its main architecture and conventions are documented.
- Important findings and uncertainties are clearly distinguished.
- `docs/architecture.md` is created or updated when appropriate.
- No application code or configuration has been changed.
