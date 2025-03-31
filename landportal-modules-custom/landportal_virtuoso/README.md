# Landportal - Virtuoso

## Installation

- Configure in settings.php the external Virtuoso connection data:
```php
  /**
   * Landportal external virtuoso connection data.
   */
  $settings['external_virtuoso_host'] = '';
```

- Make sure all project dependencies are installed:
  ```bash
  composer install
  ```

- Enable `landportal_virtuoso` module:
  ```bash
  drush en landportal_virtuoso --yes
  ```

- Finally, clean the Drupal caches:
  ```bash
  drush cr
  ```
