# Landportal - Remote blocks

## Installation

- Configure in settings.php the external Solr connection data:
```php
  /**
   * Landportal external solr connection data.
   */
  $settings['external_solr_host'] = '';
  $settings['external_solr_port'] = '';
  $settings['external_solr_path'] = '';
  $settings['external_solr_core'] = '';
```

- Make sure all project dependencies are installed:
  ```bash
  composer install
  ```

- Enable `legacy_blocks` module:
  ```bash
  drush en landportal_remote_block --yes
  ```

- Finally, clean the Drupal caches:
  ```bash
  drush cr
  ```
