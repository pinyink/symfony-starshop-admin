# Project History & Changelog — StarShop

## Changelog & Development History

### [2026-11-11] — RBAC & Permission Enhancements
- **Added `AdminVoter` (`src/Security/AdminVoter.php`):** Automatically grants full access to users with `ROLE_ADMIN` across all menus and permissions.
- **User Controller & Form Integration:** Updated `UserController` to automatically assign `ROLE_ADMIN` to Symfony security roles when an admin RBAC role is selected during user creation or editing.
- **Sub-Menu Permission Management:** Added UI controls and actions for editing and deleting sub-menus under permissions.

## Migration History
Schema evolution is tracked via Doctrine Migrations (`migrations/`), covering:
- Initial user, role, permission schema setup.
- Product and categories tables with spatial and relational extensions.
- Continuous iterative schema refinements.
