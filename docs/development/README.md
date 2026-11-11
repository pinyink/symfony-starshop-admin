# Development Documentation — StarShop

Welcome to the StarShop development documentation. This guide outlines local development practices, testing standards, and architecture guidelines.

## Quick Links
- [Current State](current-state.md)
- [Project History & Migrations](history.md)
- [Architecture Overview](../architecture.md)
- [Modules Documentation](modules/README.md)
- [Project Instructions](../../AGENTS.md)

## Getting Started
1. Clone repository and install dependencies (`composer install`).
2. Configure `.env` database connection.
3. Run migrations: `php bin/console doctrine:migrations:migrate`.
4. Run test suite: `php bin/phpunit`.
