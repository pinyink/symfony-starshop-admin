

---
description: Analyze an existing Symfony project and generate AGENTS.md and development documentation
---

# Analyze Project

Analyze the existing Symfony project and generate project-specific instructions and development documentation.

This command MUST generate or update the following files:

- `AGENTS.md`
- `docs/architecture.md`
- `docs/roadmap.md`
- `docs/development/README.md`
- `docs/development/current-state.md`
- `docs/development/history.md`
- `docs/development/modules/README.md`
- `docs/development/modules/*.md` for detected significant modules

The primary output of this command is a documented understanding of the existing project.

This command is strictly for analysis and documentation.

It MUST NOT implement features, fix bugs, refactor application code, or modify application behavior.

The actual source code, configuration, dependencies, database schema, and existing documentation are the source of truth.


## Generate AGENTS.md (Mandatory)

Generating `AGENTS.md` is a mandatory output of this command.

Do not skip this step.

Create `AGENTS.md` at the repository root.

The file must contain project-specific development instructions derived from the actual project analysis.

Include:

1. Project overview and technology stack.
2. Actual directory structure and architecture.
3. Coding conventions found in the project.
4. Controller, service, repository, entity, and form conventions.
5. Authentication and authorization conventions.
6. Database and migration guidelines.
7. Twig and frontend conventions.
8. Validation and security requirements.
9. Testing framework and verified test commands.
10. Development documentation maintenance rules.
11. Development Context Protocol.

If `AGENTS.md` already exists:

- Read it before making changes.
- Preserve relevant existing instructions.
- Update outdated or incomplete sections.
- Do not replace user-defined instructions without justification.

Do not generate generic Symfony instructions that contradict the actual project.

If some information cannot be verified, explicitly mark it as unknown or requiring verification.

After generation, verify that `AGENTS.md` exists at the repository root and contains the required sections.


## Restrictions

This command is strictly an analysis and documentation-generation command.

### Allowed

- Inspect source code and configuration.
- Inspect Git status and diffs.
- Inspect existing documentation.
- Run non-destructive inspection commands.
- Run safe validation or test commands when necessary and appropriate.
- Create or update `AGENTS.md`.
- Create or update documentation under `docs/`.

### Forbidden

- Do not implement features.
- Do not fix identified defects.
- Do not refactor application code.
- Do not modify controllers, entities, services, repositories, forms, templates, or other application code.
- Do not change application behavior.
- Do not modify database schema or migrations.
- Do not install, remove, or update dependencies.
- Do not modify environment configuration or secrets.
- Do not delete existing user documentation.
- Do not commit, merge, or push Git changes.

Identified defects must be documented as findings or recommendations.

They must not be fixed by this command.

If an issue requires implementation, report it as a future task for a separate development command.


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

## Final Output Verification

Before finishing, verify the existence of:

- [ ] `AGENTS.md`
- [ ] `docs/architecture.md`
- [ ] `docs/roadmap.md`
- [ ] `docs/development/README.md`
- [ ] `docs/development/current-state.md`
- [ ] `docs/development/history.md`
- [ ] `docs/development/modules/README.md`

Also verify:

- [ ] Module documentation exists for detected significant modules.
- [ ] `AGENTS.md` contains project-specific instructions.
- [ ] `AGENTS.md` contains the Development Context Protocol.
- [ ] Identified defects are documented, not fixed.
- [ ] No application source code was modified.

If any mandatory file cannot be generated, report it explicitly.

Do not report successful completion while mandatory outputs are missing.
