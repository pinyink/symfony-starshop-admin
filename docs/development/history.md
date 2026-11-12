# Project History & Changelog — StarShop

## Changelog & Development History

### [2026-11-12 (Later - 2)] — Dynamic Landing Page Variables via Settings Module
- **Landing Page Settings Integration:** Added comprehensive landing page configuration keys (`landing_eyebrow`, `landing_hero_title`, `landing_stats_population`, `landing_stats_districts`, `landing_stats_villages`, `landing_stats_employees`, `landing_address`, `landing_work_hours`, `landing_footer_desc`, `social_facebook`, `social_instagram`, `social_youtube`, `social_twitter`, `social_whatsapp`) to `SeedSettingsCommand`.
- **Dynamic Partials:** Updated landing partials (`_header`, `_hero`, `_sections`, `_footer`) to consume dynamic values from `SettingService` via Twig `setting()` extension instead of hardcoded strings.

### [2026-11-12 (Later)] — Landing Page Hero Carousel Slider & Conditional Logic
- **Hero Carousel Slider:** Implemented Bootstrap 5 carousel in `templates/landing/_hero.html.twig`.
- **Conditional Banner Logic:** Configured the landing hero to render default government template hero when banner records are empty, and dynamic banner slides when banner records exist.
- **Banner Module Update:** Added `description` field to `Banner` entity, form, database migration, and datatable.
- **Modular Government Landing Page:** Created modular landing page partials (`_header`, `_hero`, `_sections`, `_footer`) mirroring `public/goverment/index.html` with dynamic connection to setting and banner modules, plus dynamic login/dashboard header buttons.
- **UserController & RBAC:** Integrated `UserController` with PhpRbac / `olivier127/rbac-bundle` and `AdminVoter`.

### [2026-11-11] — Banner Module
- **Banner Module:** Added `Banner` entity, repository, migration, `BannerController`, and `BannerType` to manage promotional banners with image upload, sort order, and active/inactive status.
- **Seeder & RBAC:** Added `app:seed-banners-permission` command and integrated permission checks (`/banners/read`, `/banners/add`, `/banners/edit`, `/banners/delete`) with `olivier127/rbac-bundle`.
- **Sidebar:** Added Banners menu item in the dashboard sidebar.

### [2026-11-11] — General Settings Module & Permission Integration
- **General Settings Module:** Added `Setting` entity, repository, migration, `SettingService`, and `SettingController` to manage app-wide key-value configurations grouped by category with file upload support.
- **Seeder Commands:** Created `app:seed-settings` and `app:seed-settings-permission` console commands for bootstrapping default settings and RBAC permission nodes.
- **Sidebar & RBAC Integration:** Added Settings menu item in the dashboard sidebar protected by `is_granted('settings')`.

### [2026-11-11] — RBAC & Permission Enhancements
- **Added `AdminVoter` (`src/Security/AdminVoter.php`):** Automatically grants full access to users with `ROLE_ADMIN` across all menus and permissions.
- **User Controller & Form Integration:** Updated `UserController` to automatically assign `ROLE_ADMIN` to Symfony security roles when an admin RBAC role is selected during user creation or editing.
- **Sub-Menu Permission Management:** Added UI controls and actions for editing and deleting sub-menus under permissions.

## Migration History
Schema evolution is tracked via Doctrine Migrations (`migrations/`), covering:
- Initial user, role, permission schema setup.
- Product and categories tables with spatial and relational extensions.
- Continuous iterative schema refinements.
