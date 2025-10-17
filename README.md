
# Symfony RBAC 

Symfony Boilerplate built with bootstrap 5


## Deployment

To deploy this project run

```bash
  DATABASE_URL="mysql://root:password@127.0.0.1:3308/symfony?serverVersion=8.0.32&charset=utf8mb4"
```


## Installation

Install my-project create database

```bash
  php bin/console doctrine:database:create
```

Migrations database

```bash
  php bin/console doctrine:migrations:migrate
```

Install RBAC

```bash
   php bin/console security:rbac:install
```
    
