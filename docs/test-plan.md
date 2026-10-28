# Starshop Test Plan

Comprehensive test plan for Starshop Symfony application.

## 1. Introduction
This test plan defines strategy, scope, modules, priorities, and execution procedures for testing the Starshop Symfony application using PHPUnit 11.

## 2. Testing Framework & Stack
- **Framework:** PHPUnit 11
- **Testing Extensions:** Symfony PHPUnit Bridge, Doctrine Test Bundle (if applicable)
- **Environment:** Dedicated test database via `.env.test` (`DATABASE_URL=sqlite:///%kernel.project_dir%/var/data_test.sqlite`)

## 3. Core Modules & Scope
1. **Products Module:** Catalog listing, detail view, creation, updating, deletion, inventory/stock validation.
2. **Categories Module:** Hierarchy, slug generation, product association.
3. **User/Auth Module:** Registration, login, password hashing, session management, password reset.
4. **RBAC (Role-Based Access Control):** Role assignment (`ROLE_USER`, `ROLE_ADMIN`), security voter checks, route access restrictions.
5. **Profiling Module:** User profile management, address book, order history association.

## 4. Prioritized Test Scenarios
- **P1 (Critical / Blockers):**
  - User authentication (successful login, invalid credentials, password hashing).
  - Product CRUD and listing (admin operations and public visibility).
  - RBAC enforcement (guest vs regular user vs admin access control).
- **P2 (Important / Core Flows):**
  - Category hierarchy and product filtering by category.
  - User registration validation and unique email enforcement.
  - Profile updates and data integrity.
- **P3 (Edge Cases / Enhancements):**
  - Pagination limits and sorting on product listings.
  - Edge cases in slug generation for products and categories.
  - Optional profiling metrics and telemetry.

## 5. Test Setup & Configuration
- Configure `.env.test` for isolated testing.
- Database reset and migration execution before test suites run via PHPUnit bootstrap or extension.
- Fixture loading strategy using Doctrine fixtures or factories for repeatable state.

## 6. Coverage Gaps & Recommendations
- **Current Gap:** Lack of integration tests for complex controller interactions.
- **Recommendation:** Implement WebTestCase-based functional tests covering HTTP endpoints and security firewalls.

## 7. Execution Commands
Run test suite:
```bash
vendor/bin/phpunit
```
Run specific test file:
```bash
vendor/bin/phpunit tests/Controller/ProductControllerTest.php
```
