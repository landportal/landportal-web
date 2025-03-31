<?php

namespace Drupal\library_resources\Plugin\Block;

use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'Library resources' block.
 *
 * @Block(
 *   id = "library_resources_block",
 *   admin_label = @Translation("Region library resources block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class LibraryResourcesBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_libraries';

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
        'ds_field_doc_creation_date' => 'desc',
      ],
      1
    );

    if (count($items)) {
      $build = [
        '#theme' => 'library_resources_block',
        '#items' => $items,
        '#more_url' => $this->getLanguagePrefix() . 'library/browse/regions/' . $this->taxonomyTerm,
        '#more_label' => $this->t('Browse all'),
        '#region_name' => Term::load($this->taxonomyTerm)->label(),
      ];
    }

    return $build;
  }

}
