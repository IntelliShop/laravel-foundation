# laravel-foundation

Integrates multi-tenant, localization, permissions and more laravel extensions to work out of the box.

To be more specific, following extensions are bundled:

- laravel/framework (5.6)
- laravel/passport
- mcamara/laravel-localization
- hyn/multi-tenant
- spatie/laravel-permission

# Features

- separated tenancy and tenant databases (incl. tenant-scope migrations)
- host-level language and timezone settings
- automatic locale detection and injection into URL
- passport and permissions extensions configured to work in tenant scope

# Installation notes

The extension is being published, hence installation instructions are not yet provided.

- add `intellishop/laravel-foundation` package as a dependency into your application
- run `composer update` and `php artisan jwt:secret`
- configure databases (in .env file) similar to this:
```
DB_CONNECTION=system
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tenancy
DB_USERNAME=<username>
DB_PASSWORD=<password>

TENANCY_HOST=127.0.0.1
TENANCY_PORT=3306
TENANCY_DATABASE=tenancy
TENANCY_USERNAME=<username>
TENANCY_PASSWORD=<password>
LIMIT_UUID_LENGTH_32=true
```
- run `php artisan migrate` to create tenancy tables
- register a new tenant with e.g. German time and language applied by default
  - `insert into websites (uuid) values ('master')`, assuming the inserted row id is 1
  - `insert into hostnames (fqdn, website_id, locale, timezone) values ('localhost', 1, 'de', 'CET')`
- run `php artisan tenancy:recreate` in order to create tenants databases
  - might fail on MySQL/MariaDB due to permissions issues
  - if so, check `\Hyn\Tenancy\Generators\Webserver\Database\Drivers\MariaDB::created` for queries and execute them manually
- run `php artisan tenancy:migrate` to migrate tenants databases
- run `php artisan tenancy:passport:install` to install passport
