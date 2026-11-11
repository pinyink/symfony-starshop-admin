# Current State — StarShop

## System Status
- **Framework Version:** Symfony 7.3.*
- **PHP Version:** >= 8.2
- **Database:** Doctrine ORM 3.5 with Migrations fully synchronized.
- **Modules Active:** Product, User, Categories, Role, Permission, Profil, Security, General Settings, Banner.
- **Security & Authorization Updates:** Added `AdminVoter` to grant full access for `ROLE_ADMIN`, sub-menu edit/delete capabilities, and `settings` permission integration.
- **Testing:** PHPUnit configured and passing for form data transformers.

## Known Limitations & Technical Debt
- Controller and integration test suites need expansion.
- `security.yaml` access control rules require hardening.
