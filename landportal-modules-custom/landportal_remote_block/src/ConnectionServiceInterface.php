<?php

namespace Drupal\landportal_remote_block;

/**
 * Interface for ConnectionService.
 */
interface ConnectionServiceInterface {

  /**
   * Returns an array of results based on a SOLR query.
   *
   * @param string $index
   *   Name of the index in the SOLR origin.
   * @param array $conditions
   *   Array of conditions.
   * @param array $fields
   *   Array of fields to retrieve, empty for all.
   * @param array $sorts
   *   Array of sorts, can be 'asc' or 'desc'. Empty for score.
   * @param int $num_rows
   *   Number of results.
   *
   * @return array
   *   Array of result items.
   */
  public function getResults($index, array $conditions, array $fields = [], array $sorts = [], $num_rows = 1);

}
