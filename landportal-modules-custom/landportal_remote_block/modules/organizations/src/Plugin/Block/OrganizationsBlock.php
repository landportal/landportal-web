<?php

namespace Drupal\organizations\Plugin\Block;

use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'Organizations' block.
 *
 * @Block(
 *   id = "organizations_block",
 *   admin_label = @Translation("Region organizations block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class OrganizationsBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_organizations';

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
      // @todo Short by tm_title_field asc.
      // SolrException: Can not sort on multivalued field: tm_title_field
      [
        'ss_title_field' => 'asc',
      ],
      12
    );

    if (count($items)) {
      $build = [
        '#theme' => 'organizations_block',
        '#items' => $items,
        '#more_url' => $this->getLanguagePrefix() . 'countries/' . $this->taxonomyTerm . '/organization',
        '#more_label' => $this->t('Browse all'),
        '#region_name' => Term::load($this->taxonomyTerm)->label(),
      ];
    }

    return $build;
  }

}
