<?php

namespace Drupal\projects\Plugin\Block;

use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'Projects' block.
 *
 * @Block(
 *   id = "region_projects_block",
 *   admin_label = @Translation("Region projects block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class ProjectsBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_projects';

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
      ['ds_created' => 'desc'],
      6
    );

    if (count($items)) {
      $build = [
        '#theme' => 'projects_block',
        '#items' => $items,
        //'#more_url' => $this->getLanguagePrefix() . 'countries/' . $this->taxonomyTerm . '/project',
        '#more_url' => $this->getLanguagePrefix() . 'search-in-projects?key=&f[0]=by_geographical_focus:' . $this->taxonomyTerm,
        '#more_label' => $this->t('Browse all'),
        '#region_name' => Term::load($this->taxonomyTerm)->label(),
      ];
    }

    return $build;
  }

}
