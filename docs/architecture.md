# Project Architecture Documentation

## PHP / Symfony Version
- **PHP Version:** `>=8.2`
- **Symfony Framework:** `7.3.*` (Full-Stack Meta-Package Components)

## Dependencies
- **Core & Database:** 
  - `symfony/framework-bundle`, `symfony/runtime`, `symfony/dotenv`, `symfony/yaml`
  - `doctrine/doctrine-bundle` (^2.16.2), `doctrine/orm` (^3.5.2), `doctrine/dbal` (^3.10.2), `doctrine/doctrine-migrations-bundle` (^3.4.2)
  - `beberlei/doctrineextensions` (^1.5), `longitude-one/doctrine-spatial` (^5.0)
- **Security & Auth:**
  - `symfony/security-bundle` (7.3.*), `symfony/security-csrf` (7.3.*)
  - `olivier127/rbac-bundle` (*)
- **Forms, Validation & Serialization:**
  - `symfony/form`, `symfony/validator`, `symfony/property-access`, `symfony/property-info`, `symfony/serializer`, `symfony/intl`
- **UI & Templates:**
  - `symfony/twig-bundle`, `twig/twig`, `twig/extra-bundle`
  - `symfony/asset`, `symfony/asset-mapper`, `symfony/stimulus-bundle`, `symfony/ux-turbo`
  - `omines/datatables-bundle` (^0.10.3)
- **Utilities & Communications:**
  - `symfony/mailer`, `symfony/mime`, `symfony/notifier`, `symfony/http-client`, `symfony/string`, `symfony/console`, `symfony/monolog-bundle`
- **Development & Testing:**
  - `phpunit/phpunit` (^11.5.42), `symfony/maker-bundle`, `symfony/web-profiler-bundle`, `symfony/browser-kit`, `symfony/css-selector`

## Architecture Pattern
- **Pattern:** MVC (Model-View-Controller) following Symfony standard bundle-less directory structure (`src/Controller`, `src/Entity`, `src/Repository`, `src/Form`, `src/Services`).
- **Services & Dependency Injection:** Fully autowired and autoconfigured services utilizing constructor injection.

## Directory Structure
```
starshop/
├── config/             # Symfony configuration (bundles, packages, routes)
├── public/             # Public web root (index.php, uploads, assets)
├── src/
│   ├── Controller/     # Request handlers (AbstractController extenders)
│   ├── DataFixtures/   # Doctrine data fixtures (if any)
│   ├── Entity/         # Doctrine ORM database entities
│   ├── Form/           # Symfony Form types & DataTransformers
│   ├── Repository/     # Doctrine entity repositories
│   ├── Services/       # Application business services (e.g., FileUploader, Datatable)
│   ├── Twig/           # Custom Twig extensions and filters
│   └── Kernel.php      # Application kernel
├── templates/          # Twig templates organized by controller/entity names
├── tests/              # PHPUnit test suites
└── var/                # Generated caches, logs, sessions
```

## Request Flow
1. **Entry Point:** HTTP requests hit `public/index.php`, booting `App\Kernel`.
2. **Routing:** Symfony Router matches the request URL to controller actions via attributes (e.g., `#[Route('/...')]`).
3. **Controller Execution:** The controller handles request logic, interacts with Doctrine Repositories/EntityManager or custom services (`FileUploader`, `Datatable`), and validates input.
4. **Response:** Controllers return a `Response` object (rendered Twig template via `$this->render()` or redirects).

## Database / Entities
- **ORM:** Doctrine ORM 3.x mapped via PHP attributes.
- **Key Entities:**
  - `User`: Application users with security authentication integration.
  - `Role`: Role management entities.
  - `Permission`: Granular permission definitions.
  - `Product`: Catalog products.
  - `Categories`: Product category classification.
- **Extensions:** Doctrine Spatial and custom extensions via `beberlei/doctrineextensions`.

## Authentication & RBAC
- **Authentication:** Configured via Symfony Security Bundle (`SecurityController`).
- **RBAC:** Managed using `olivier127/rbac-bundle` alongside `Role` and `Permission` entities to handle role-based access control.

## Forms & Validation
- **Forms:** Strongly typed form classes (`UserType`, `ProductType`, `CategoriesType`, `ProfilType`, `ResetPasswordType`, etc.) extending `AbstractType`.
- **Custom Transformers:** Data transformers like `RupiahTransformer` and `PointToArrayTransformer` for data format conversions.
- **Validation:** Symfony Validator constraints applied via annotations/attributes on entities and form mapping.

## Twig & UI
- **Engine:** Twig with `twig/extra-bundle`.
- **Assets:** AssetMapper (`symfony/asset-mapper`), Stimulus (`symfony/stimulus-bundle`), and Turbo (`symfony/ux-turbo`).
- **Extensions:** Custom Twig extensions (`AppExtension`) for formatting or helper logic.

## Testing
- **Framework:** PHPUnit (`phpunit/phpunit`) with browser kit and CSS selector integration components.
- **Bootstrap:** `tests/bootstrap.php`.

## File Uploads
- **Service:** Dedicated `FileUploader` service located in `src/Services/FileUploader.php` handling file storage and validation logic.

## Conventions
- **PSR Standards:** Strict adherence to PSR-4 autoloading and PSR coding standards.
- **Attributes:** Extensive use of PHP 8+ attributes for Routing, Doctrine ORM mapping, and Security constraints.
- **Type Safety:** Strict scalar and return type declarations across all PHP classes.
