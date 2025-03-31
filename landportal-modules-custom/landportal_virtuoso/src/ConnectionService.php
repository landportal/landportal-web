<?php

namespace Drupal\landportal_virtuoso;

use Drupal\Core\Site\Settings;
use EasyRdf\RdfNamespace;
use EasyRdf\Sparql\Client;

/**
 * A connector service for virtuoso.
 *
 * @package Landportal
 */
class ConnectionService implements ConnectionServiceInterface {

  /**
   * Client.
   *
   * @var \EasyRdf\Sparql\Client
   */
  protected $client;

  /**
   * Constructs a new ConnectionService object.
   *
   * @param \Drupal\Core\Site\Settings $settings
   *   Drupal settings.
   */
  public function __construct(Settings $settings) {
    try {
      $this->client = new Client($settings->get('external_virtuoso_host', ''));
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getResults($namepaces, $query) {
    $items = [];

    try {
      foreach ($namepaces as $key => $value) {
        RdfNamespace::set($key, $value);
      }
      $results = $this->client->query($query);

      foreach ($results as $result) {
        $item = [];
        foreach ($result as $field => $value) {
          $item[$field] = $value;
        }
        $items[] = $item;
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $items;
  }

}
