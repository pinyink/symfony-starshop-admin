# Current State — StarShop

## System Status
- **Framework Version:** Symfony 7.3.*
- **PHP Version:** >= 8.2
- **Database:** Doctrine ORM 3.5 with Migrations fully synchronized.
- **Modules Active:** Product, User, Categories, Role, Permission, Profil, Security, General Settings, Banner (with description), Modular Government Landing Page with Conditional Hero Carousel Slider and dynamic settings integration for all text, stats, contact info, and social media links.
- **Security & Authorization Updates:** Added `AdminVoter` to grant full access for `ROLE_ADMIN`, integrated `UserController` with RBAC and Symfony security roles, sub-menu edit/delete capabilities, and `settings` permission integration.
- **Testing:** PHPUnit configured and passing for form data transformers.

## Known Limitations & Technical Debt
- Controller and integration test suites need expansion.
- `security.yaml` access control rules require hardening.
