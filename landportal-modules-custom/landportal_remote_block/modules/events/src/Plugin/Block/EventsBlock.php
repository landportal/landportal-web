<?php

namespace Drupal\events\Plugin\Block;

use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'Events' block.
 *
 * @Block(
 *   id = "events_block",
 *   admin_label = @Translation("Region events block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class EventsBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_events';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $items = $this->connectionService->getResults($this->indexName,
      [
        'is_status' => 1,
        'im_field_geographical_focus' => $this->taxonomyTerm,
      ],
      [],
      [
        'bs_promote' => 'desc',
        'ds_field_date$value' => 'desc',
      ],
      1
    );

    if (count($items)) {
      $build = [
        '#theme' => 'events_block',
        '#items' => $items,
        '#more_url' => $this->getLanguagePrefix() . 'countries/' . $this->taxonomyTerm . '/event',
        '#more_label' => $this->t('Browse all'),
        '#region_name' => Term::load($this->taxonomyTerm)->label(),
      ];
    }

    return $build;
  }

}
