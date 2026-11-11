# Project History & Changelog — StarShop

## Changelog & Development History

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
