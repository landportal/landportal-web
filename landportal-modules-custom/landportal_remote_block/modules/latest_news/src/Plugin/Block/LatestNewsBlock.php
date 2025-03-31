<?php

namespace Drupal\latest_news\Plugin\Block;

use Drupal\taxonomy\Entity\Term;
use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;

/**
 * Provides a 'Latest news' block.
 *
 * @Block(
 *   id = "region_latest_news_block",
 *   admin_label = @Translation("Region latest news block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class LatestNewsBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_news';

  /**
   * {@inheritdoc}
   */
  public function build() {
    if (!$this->taxonomyTerm) {
      return [];
    }

    $items = $this->connectionService->getResults($this->indexName,
      [
        'is_status' => 1,
        'im_field_geographical_focus' => $this->taxonomyTerm,
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      3
    );

    if (count($items)) {
      $build = [
        '#theme' => 'latest_news_block',
        '#items' => $items,
        '#more_url' => $this->getLanguagePrefix() . 'countries/' . $this->taxonomyTerm . '/news',
        '#more_label' => $this->t('Browse all'),
        '#region_name' => Term::load($this->taxonomyTerm)->label(),
      ];
    }
    else {
      // Show the More about title even if there are no news items.
      $build = [
        '#markup' => $this->t('More on this country'),
        '#prefix' => '<h2 class="h2 h2--news-block-empty">',
        '#suffix' => '</h2>',
      ];
    }

    return $build;
  }

}
