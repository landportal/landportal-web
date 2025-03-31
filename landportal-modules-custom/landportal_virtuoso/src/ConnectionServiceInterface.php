<?php

namespace Drupal\landportal_virtuoso;

/**
 * Interface for ConnectionService.
 */
interface ConnectionServiceInterface {

  /**
   * Returns an array of results based on a Virtuoso query.
   *
   * @param array $namespaces
   *   Namespaces used in the Virtuoso query.
   * @param string $query
   *   Virtuoso query.
   *
   * @return array
   *   Array of result items.
   */
  public function getResults(array $namespaces, $query);

}
