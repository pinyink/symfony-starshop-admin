
---
description: Plan, implement, test, and integrate a new module into an existing Symfony project
---

# Add Module

Add a new module or feature to the existing Symfony application while preserving its architecture, conventions, security boundaries, and existing behavior.

The goal is to deliver a working, maintainable, and tested module that integrates with the current project.

Do not scaffold a new Symfony application or rebuild unrelated components.

## Workflow

### 1. Understand the Requirements

Read `AGENTS.md` and relevant documentation under `docs/`, if present.

Identify the requested module's:

- Purpose and business requirements
- Main entities and data fields
- Relationships with existing entities
- Required user workflows
- Routes and HTTP methods
- Forms and validation rules
- Authentication and authorization requirements
- Expected interface and navigation
- Search, filtering, sorting, and pagination requirements
- File uploads or external integrations, if applicable
- Acceptance criteria

If important requirements are missing, inspect the existing application and establish reasonable assumptions where possible.

Ask concise clarification questions when an unresolved decision materially affects the data model, permissions, business rules, or scope.

Do not block useful progress over minor details that can be decided consistently with existing project conventions.

### 2. Analyze the Existing Project

Use `project-analyzer` to inspect the current architecture and relevant implementation patterns.

Inspect, where applicable:

- `composer.json` and installed dependencies
- Routes and controllers
- Entities and Doctrine relationships
- Repositories and query patterns
- Services and dependency injection
- Forms and validation
- Authentication, authorization, roles, and voters
- Twig layouts, templates, and reusable components
- Navigation and menu configuration
- Database migrations
- Existing tests and fixtures
- Similar modules that can serve as implementation references

Identify the files and components that must change or be created.

Do not assume the project uses a particular RBAC bundle, frontend stack, CRUD pattern, or directory structure. Verify the actual implementation first.

Prefer reusing existing services, base classes, templates, components, and utilities when they are appropriate.

### 3. Prepare an Implementation Plan

Before making changes, establish a concise plan covering:

1. Module scope and acceptance criteria
2. Data model and relationships
3. Routes and application flow
4. Controllers, services, repositories, and forms
5. Validation and business rules
6. Authentication and authorization
7. Twig templates and navigation
8. Database migration requirements
9. Tests and regression risks
10. Files to create or modify

Keep the implementation proportional to the module's complexity.

For a small module, use a lightweight plan and proceed without unnecessary ceremony.

For a module involving significant schema changes, complex business rules, sensitive data, or broad architectural impact, provide the plan for review before proceeding.

Do not implement unrelated features or expand the scope without justification.

### 4. Design the Data Model

When the module requires persistence:

- Inspect existing entities and naming conventions.
- Define only the fields required by the approved requirements.
- Choose appropriate Doctrine types, nullability, defaults, and constraints.
- Define relationships and their owning and inverse sides correctly.
- Consider deletion behavior and referential integrity.
- Add database indexes or unique constraints where justified.
- Avoid storing redundant data without a clear reason.
- Protect sensitive and privileged fields from unauthorized modification.
- Keep entity methods and business logic consistent with existing project conventions.

Use Doctrine ORM when it is already the project's persistence strategy. Do not introduce a different persistence layer without a justified requirement.

Do not edit an existing database schema directly or run migrations against production.

### 5. Implement the Application Layer

Create or modify the necessary components according to the project's established architecture.

#### Entities and Repositories

- Implement entity mappings and relationships.
- Use repositories for queries that belong there.
- Use parameterized Doctrine queries for user-controlled values.
- Validate sorting fields and other dynamic query components against an explicit allowlist.
- Avoid unnecessary custom repositories or abstractions.

#### Controllers and Routes

- Follow existing route naming and HTTP method conventions.
- Keep controllers focused on request handling and response generation.
- Delegate complex or reusable business logic to services when appropriate.
- Handle missing resources and invalid requests consistently.
- Use appropriate HTTP responses, redirects, and flash messages.
- Avoid duplicating existing functionality.

#### Forms and Validation

- Reuse Symfony Forms and Validator when they match the project's existing approach.
- Validate required fields, lengths, formats, ranges, and business rules.
- Validate on the server regardless of frontend validation.
- Handle invalid submissions without persisting invalid data.
- Apply CSRF protection where appropriate for browser-based forms.
- Prevent users from modifying protected fields such as ownership, roles, or privileged status without authorization.

#### Services and Business Rules

- Keep business rules in the project's established application layer.
- Reuse existing services where appropriate.
- Handle expected failures explicitly.
- Preserve data integrity across related operations.
- Use transactions when multiple database operations must succeed or fail together.
- Avoid introducing abstractions that do not solve a real problem.

### 6. Implement Authorization and Security

Determine who may view, create, read, update, delete, publish, or otherwise manage the module.

Follow the project's actual authorization strategy, such as access-control configuration, `IsGranted`, voters, or an existing RBAC system.

Requirements:

- Enforce permissions on the server.
- Protect every relevant route and sensitive operation.
- Check resource ownership or tenant boundaries when applicable.
- Do not rely on hidden buttons or menu items as access control.
- Validate all user-controlled input.
- Escape untrusted output using the appropriate Twig rendering context.
- Do not render untrusted HTML without appropriate sanitization.
- Use CSRF protection for applicable state-changing browser requests.
- Validate uploaded files and generate safe filenames when uploads are required.
- Avoid exposing secrets, internal errors, or sensitive entity fields.
- Use safe database query construction.

Do not invent new role names or permission conventions without checking the existing application.

If authorization requirements are unclear and materially affect security, clarify them before exposing the functionality.

### 7. Implement the User Interface

Follow the existing project's frontend stack and design conventions.

When using Twig:

- Reuse the established base layout and reusable components.
- Keep templates consistent with existing modules.
- Implement appropriate listing, detail, create, edit, and delete interfaces as required.
- Display validation errors clearly.
- Preserve existing navigation and layout behavior.
- Add navigation entries only when appropriate and supported by the current navigation system.
- Ensure actions are available only to users who are authorized to perform them.
- Provide sensible empty states and feedback for successful or failed operations.

If the project uses Bootstrap, Tailwind, custom CSS, or another frontend approach, follow the installed and existing implementation rather than introducing a new framework.

Do not redesign unrelated pages.

### 8. Create Database Migrations

When entity changes require schema updates:

1. Inspect the current Doctrine configuration and migration conventions.
2. Generate or create the appropriate migration using the project's installed tools.
3. Review the generated migration carefully.
4. Verify that it matches the intended schema changes.
5. Consider existing data, constraints, indexes, and foreign keys.
6. Document any required operational steps or data backfills.

Do not assume an automatically generated migration is correct.

Do not run migrations against production or an unverified database.

If a migration is potentially destructive, requires data conversion, or may cause downtime, explain the impact and obtain appropriate authorization before proceeding.

### 9. Implement Automated Tests

Use `test-engineer` to identify and implement tests appropriate to the module.

Cover, where relevant:

- Successful create, read, update, and delete operations
- Required fields and invalid input
- Database persistence and entity relationships
- Authentication requirements
- Authorization and permission boundaries
- Resource ownership and inaccessible records
- CSRF protection for applicable forms
- Search, filtering, sorting, and pagination
- File upload validation
- Important business rules and failure paths
- Regression scenarios involving existing modules

Use the project's existing PHPUnit configuration, fixtures, helpers, and test conventions.

Do not introduce a new testing framework without a justified need.

Ensure test database configuration is isolated before executing tests that reset data or modify the schema.

Do not weaken existing tests to make the module pass.

### 10. Run Verification

Run the narrowest relevant checks first, then expand verification when practical.

Inspect the project's configuration to determine which commands are available.

Potential checks include:

- PHP syntax validation
- Symfony container or configuration validation
- Route inspection
- Doctrine schema validation
- Focused PHPUnit tests
- Related test suites
- Full test suite
- Existing static analysis or code-quality tools

Examples of possible commands include:

- `php bin/console lint:container`
- `php bin/console lint:twig templates/`
- `php bin/console lint:yaml config/`
- `php bin/console doctrine:schema:validate`
- `php bin/phpunit`

Run only commands supported by the installed Symfony version, dependencies, and project configuration.

Be careful with environment-dependent commands. Verify the active environment and database target before executing database-related operations.

Report commands that were actually executed and their actual results.

Do not claim that the module is verified merely because files were generated or syntax checks passed.

### 11. Review the Implementation

Use `project-analyzer` to check consistency with the existing architecture where needed.

Use `security-auditor` for focused review when the module introduces significant security-sensitive behavior.

Review the final changes for:

- Requirement completeness
- Correct business logic
- Consistent architecture and naming
- Validation and error handling
- Authorization and data ownership
- Database integrity
- Secure rendering and request handling
- Test coverage
- Accidental changes to unrelated functionality
- Unnecessary dependencies or abstractions
- Missing documentation

Use `/review` for a broader review when appropriate.

If defects are discovered, fix them within the authorized scope and rerun the relevant checks.

### 12. Update Documentation

Update relevant documentation when the new module changes:

- Application architecture
- Data model or relationships
- Routes or public interfaces
- Permissions and access-control rules
- Configuration or environment requirements
- Installation or migration procedures
- Important business rules
- Testing instructions

Preserve existing documentation and update only the sections affected by the new functionality.

Do not create redundant documentation for routine implementation details.

### 13. Report the Result

Provide a concise implementation report containing:

#### Module Overview
- Purpose and implemented functionality
- Important design decisions

#### Files Changed
- Files created
- Files modified
- Purpose of important changes

#### Database Changes
- Entities and relationships
- Migration files created
- Whether migrations were executed and in which verified environment

#### Authorization and Security
- Permissions and access restrictions implemented
- Important security considerations

#### Testing and Verification
- Tests created or updated
- Commands executed
- Actual results
- Failures, skipped checks, or blockers

#### Remaining Work
- Unimplemented requirements
- Known limitations
- Required manual steps
- Recommended next actions

## Scope and Safety

- Extend the existing Symfony application; do not scaffold a new project.
- Follow the actual project architecture and conventions.
- Implement only the requested module and directly necessary supporting changes.
- Do not modify unrelated modules without a clear reason.
- Do not install, remove, or update dependencies without appropriate authorization.
- Do not run destructive migrations or database operations against unverified environments.
- Do not expose secrets or production data.
- Do not weaken existing authorization or validation.
- Do not overwrite or revert unrelated user changes.
- Do not commit, merge, push, or rewrite Git history.
- Do not claim tests or checks passed unless they were actually executed.

## Completion Criteria

The module is complete when:

- The requested behavior and acceptance criteria are addressed.
- Implementation follows the existing architecture.
- Required validation and authorization are enforced server-side.
- Database changes are represented by reviewed migrations where needed.
- Relevant automated tests are implemented and executed when possible.
- Relevant verification checks have been performed.
- The final changes have been reviewed for regressions and security risks.
- Documentation is updated when appropriate.
- Remaining limitations and required manual steps are clearly reported.
