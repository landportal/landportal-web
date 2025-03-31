# Landportal - Migration

<a name="installation"></a>
## Installation

- Configure in settings.php the D7(migration source) database using 'legacy'
target:
```php
$databases['legacy']['default'] = [
  'database' => "",
  'username' => "",
  'password' => "",
  'host' => "",
  'driver' => "",
  'port' => "",
  'prefix' => "",
];
```

- Make sure all project dependencies are installed:
  ```bash
  composer install
  ```

- Enable `landportal_migration` module:
  ```bash
  drush en landportal_migration --yes
  ```
  This module is ready to install all dependencies automatically and those
  `dependencies` are:
  - migrate
  - migrate_plus
  - migrate_tools

- Finally, clean the Drupal caches:
  ```bash
  drush cr
  ```

<a name="migration"></a>
## Migration

<a name="migration-commands"></a>
### Available commands

- `drush migrate-status` Lists migrations and their status.
- `drush migrate-import` Performs import operations.
- `drush migrate-rollback` Performs rollback operations.
- `drush migrate-stop` Cleanly stops a running operation.
- `drush migrate-reset-status` Sets a migration status to Idle if it's gotten
  stuck.
- `drush migrate-messages` Lists any messages associated with a migration
  import.

### Execute migration

- Run migration:
  ```bash
  drush migrate-import --tag=landportal_migration
  ```

- Rollback migration:
  ```bash
  drush migrate-rollback --tag=landportal_migration
  ```
