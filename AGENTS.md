# StarShop — Symfony 7 Project Instructions

## 1. Project Overview & Technology Stack
- **Framework:** Symfony 7.3.*
- **PHP Version:** PHP >= 8.2
- **Database / ORM:** Doctrine ORM 3.5, Doctrine DBAL 3.10, Doctrine Migrations, with `longitude-one/doctrine-spatial` and `beberlei/doctrineextensions`.
- **Security & RBAC:** Symfony Security Bundle (`security.yaml`), `olivier127/rbac-bundle` for Role-Based Access Control.
- **Frontend & UI:** Symfony AssetMapper, Stimulus Bundle (`symfony/stimulus-bundle`), Turbo (`symfony/ux-turbo`), Twig, and `omines/datatables-bundle` for interactive tables.

## 2. Directory Structure & Architecture
- `src/Controller/`: Request handlers (`MainController`, `ProductController`, `UserController`, `CategoriesController`, `RoleController`, `PermissionController`, `ProfilController`, `SecurityController`).
- `src/Entity/`: Doctrine entities (`User`, `Role`, `Permission`, `Product`, `Categories`).
- `src/Repository/`: Doctrine repositories (`UserRepository`, `ProductRepository`, `CategoriesRepository`).
- `src/Form/`: Symfony form types and data transformers (`RupiahTransformer`, `PointToArrayTransformer`).
- `src/Service/`: Service classes (`FileUploader`, `Datatable`).
- `src/Twig/`: Custom Twig extensions (`AppExtension`).
- `templates/`: Twig views organized by module (`product/`, `user/`, `categories/`, `role/`, `profil/`, `permission/`, `security/`, `partials/`).
- `migrations/`: Doctrine database migration files.
- `tests/`: PHPUnit test suite (`tests/Form/DataTransformer/`).

## 3. Coding Conventions
- **Namespaces & PSR:** Follow PSR-4 standards under the `App\` namespace.
- **Strict Types:** Use `declare(strict_types=1);` in PHP files.
- **Dependency Injection:** Constructor injection for services, repositories, and form factories.
- **Naming:** PascalCase for classes, camelCase for methods/variables, snake_case or kebab-case for database columns and route names.

## 4. Controller, Service, Repository, Entity, and Form Conventions
- **Controllers:** Extend `AbstractController`, leverage route attributes (`#[Route]`), and use dependency injection via method parameters or constructor.
- **Entities:** Annotated with Doctrine attributes (`#[ORM\Entity]`), private/protected properties with public getters/setters, lifecycle callbacks where needed.
- **Repositories:** Extend `ServiceEntityRepository`, encapsulate complex DQL and query builder queries.
- **Forms:** Extend `AbstractType`, use Symfony form builder, implement data transformers (e.g., `RupiahTransformer` for currency values, `PointToArrayTransformer` for spatial coordinates) when custom mapping is required.
- **Services:** Single-responsibility business logic classes (e.g., `FileUploader`).

## 5. Authentication & Authorization
- **Authentication:** Form login (`app_login`), password hashing (`PasswordAuthenticatedUserInterface`), remember-me service.
- **Authorization:** Integrated with `olivier127/rbac-bundle` for role and permission management. Controller actions protected via security attributes (`#[IsGranted]`) or security configuration.

## 6. Database & Migration Guidelines
- **Migrations:** All schema changes must be managed via Doctrine Migrations (`php bin/console doctrine:migrations:migrate`).
- **Spatial / Extensions:** Leverage `longitude-one/doctrine-spatial` for spatial data types and `beberlei/doctrineextensions` for advanced DQL functions.

## 7. Twig & Frontend Conventions
- **Templates:** Base layout in `templates/base.html.twig`, modular templates grouped by domain.
- **Asset Management:** AssetMapper and Stimulus controllers for interactive frontend behavior.
- **Datatables:** Integrated via `omines/datatables-bundle` for server-side processing and rendering of tabular data.

## 8. Validation & Security
- **Validation:** Symfony Validation constraints (`#[Assert\*]`) on entity properties and form types.
- **Security:** CSRF protection enabled on forms, secure password hashing, role-based checks.

## 9. Testing Framework & Verified Test Commands
- **Framework:** PHPUnit 11 (`bin/phpunit`).
- **Test Command:**
  ```bash
  php bin/phpunit
  ```
- **Current Tests:** Unit tests for data transformers under `tests/Form/DataTransformer/`.

## 10. Development Documentation Maintenance Rules
- Update `docs/architecture.md`, `docs/roadmap.md`, and module documentation under `docs/development/modules/` whenever architecture or significant features change.
- Keep `AGENTS.md` aligned with actual project implementation.

## 11. Development Context Protocol
- Always inspect repository evidence before proposing or reviewing changes.
- Respect all defined constraints, coding standards, and separation of concerns.
- Do not bypass security or validation layers.
