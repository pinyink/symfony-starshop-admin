# Banner Module Documentation

## Overview
The Banner module allows administrators to manage promotional banners displayed on the platform. Banners support title, image upload, sorting order, and active/inactive status.

## Components
- **Entity:** `src/Entity/Banner.php`
- **Repository:** `src/Repository/BannerRepository.php`
- **Controller:** `src/Controller/BannerController.php` (handles CRUD operations and DataTables Ajax endpoints)
- **Form:** `src/Form/BannerType.php`
- **Migration:** `migrations/Version20261111072841.php`
- **Seeder Command:** `src/Command/SeedBannersPermissionCommand.php`
- **Templates:** `templates/banner/` (index, show, new, edit, _form, button)

## RBAC Permissions
Protected under the following permission routes:
- `/banners/read`
- `/banners/add`
- `/banners/edit`
- `/banners/delete`
