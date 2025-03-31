<?php

namespace Drupal\blog_posts\Plugin\Block;

use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'Blog posts' block.
 *
 * @Block(
 *   id = "blog_posts_block",
 *   admin_label = @Translation("Region blog posts block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class BlogPostsBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_blogs';

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
        'ds_field_pubdate' => 'desc',
      ],
      1
    );

    if (count($items)) {
      $build = [
        '#theme' => 'blog_posts_block',
        '#items' => $items,
        '#more_url' => $this->getLanguagePrefix() . 'countries/' . $this->taxonomyTerm . '/blog_post',
        '#more_label' => $this->t('Browse all'),
        '#region_name' => Term::load($this->taxonomyTerm)->label(),
      ];
    }

    return $build;
  }

}
