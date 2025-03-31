<?php

namespace Drupal\Tests\cache_purge\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the functionality of the Cache Purge module.
 *
 * @group cache_purge
 */
class CachePurgeTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['cache_purge'];

  /**
   * Tests cache purge functionality.
   */
  public function testCachePurge() {
    // Set a small cache size limit.
    $this->config('cache_purge.settings')
      ->set('size_limit', 1)
      ->save();

    // Generate cache entries to exceed the limit.
    $cacheBackend = \Drupal::cache();
    for ($i = 0; $i < 1000; $i++) {
      $cacheBackend->set("test_key_$i", str_repeat('a', 1024), time() + 3600);
    }

    // Run cron.
    $this->cronRun();

    // Check if cache tables were truncated.
    $database = \Drupal::database();
    $tables = $database->query("SHOW TABLES LIKE 'cache%'")->fetchCol();
    
    foreach ($tables as $table) {
      $count = $database->query("SELECT COUNT(*) FROM {" . $table . "}")->fetchField();
      $this->assertLessThanOrEqual(100, $count, "Cache table $table should have been truncated.");
    }

    // Check logs for truncation events.
    $logs = \Drupal::database()->select('watchdog', 'w')
      ->fields('w', ['message', 'variables'])
      ->condition('type', 'cache_purge')
      ->execute()
      ->fetchAll();

    $this->assertNotEmpty($logs, 'Truncation events should be logged.');

    foreach ($logs as $log) {
      $this->assertStringContainsString('has been truncated', $log->message, 'Log message should indicate truncation.');
    }
  }
}
