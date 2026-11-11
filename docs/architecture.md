# Architecture Documentation — StarShop

## 1. Overview & Technology Stack
StarShop is a Symfony 7.3 web application designed for e-commerce management, featuring role-based access control (RBAC), spatial/geospatial capabilities, server-side data tables, and modern frontend asset mapping.

- **Backend Framework:** Symfony 7.3.* (PHP >= 8.2)
- **Database / ORM:** Doctrine ORM 3.5, Doctrine DBAL 3.10, Doctrine Migrations
- **Extensions:** `longitude-one/doctrine-spatial`, `beberlei/doctrineextensions`
- **Security & Authorization:** Symfony Security Bundle, `olivier127/rbac-bundle`
- **Frontend & UI:** Twig, AssetMapper, Stimulus, Turbo, `omines/datatables-bundle`
- **Testing:** PHPUnit 11

## 2. Directory Structure & Component Responsibilities
- `src/Controller/`: Request handlers managing endpoints for products, users, categories, roles, permissions, profiling, and authentication.
- `src/Entity/`: Doctrine ORM entity definitions (`User`, `Role`, `Permission`, `Product`, `Categories`).
- `src/Repository/`: Data access repositories with custom query builder methods.
- `src/Form/`: Form types and custom data transformers (`RupiahTransformer`, `PointToArrayTransformer`).
- `src/Service/`: Reusable business logic services (e.g., `FileUploader`, `Datatable`).
- `src/Twig/`: Custom Twig extensions (`AppExtension`).
- `templates/`: Modular Twig view templates.
- `migrations/`: Database schema migration scripts.
- `tests/`: Automated unit tests.

## 3. Request & Application Execution Flow
1. **Routing:** Incoming HTTP requests are intercepted by Symfony's front controller and mapped via `#[Route]` attributes on controllers.
2. **Security & Authorization:** The Security Bundle authenticates requests, while controller attributes (`#[IsGranted]`) and RBAC bundle policies enforce permissions.
3. **Controllers & Services:** Controllers handle request lifecycle, invoke services (like `FileUploader`), and interact with Doctrine repositories.
4. **Persistence:** Doctrine ORM manages database transactions and entity mapping.
5. **Response:** Data is rendered via Twig templates, JSON responses, or datatable handlers.

## 4. Authentication & Authorization Architecture
- **Authentication:** Form-based login (`app_login`) with password hashing via `PasswordAuthenticatedUserInterface` and session management.
- **RBAC Authorization:** Managed through `olivier127/rbac-bundle`, associating users with roles and permissions for granular access control.

## 5. Frontend & UI Architecture
- **Asset Management:** Symfony AssetMapper provides native asset compilation without Node.js toolchains.
- **Interactivity:** Stimulus and Turbo deliver dynamic frontend interactions.
- **Tabular Data:** `omines/datatables-bundle` handles server-side processing for complex data grids.

## 6. Testing Strategy
- Unit tests cover form data transformers (`tests/Form/DataTransformer/`).
- Executed via PHPUnit: `php bin/phpunit`.
