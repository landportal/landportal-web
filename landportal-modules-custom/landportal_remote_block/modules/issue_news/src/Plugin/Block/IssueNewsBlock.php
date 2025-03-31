<?php

namespace Drupal\issue_news\Plugin\Block;

use Drupal\taxonomy\Entity\Term;
use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;

/**
 * Provides a 'Issue news' block.
 *
 * @Block(
 *   id = "region_issue_news_block",
 *   admin_label = @Translation("Region issue news block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class IssueNewsBlock extends RemoteBlockBase {

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
    $temp = $this->taxonomyTerm;
    $issue_maping_arr = [];
    $issue_maping_arr[0]['d9'] = "8917";
    $issue_maping_arr[0]['d7'] = "7344";
    $issue_maping_arr[1]['d9'] = "8918";
    $issue_maping_arr[1]['d7'] = "8472";
    $issue_maping_arr[2]['d9'] = "8919";
    $issue_maping_arr[2]['d7'] = "7347";
    $issue_maping_arr[3]['d9'] = "8920";
    $issue_maping_arr[3]['d7'] = "8643";
    $issue_maping_arr[4]['d9'] = "8921";
    $issue_maping_arr[4]['d7'] = "8641";
    $issue_maping_arr[5]['d9'] = "8922";
    $issue_maping_arr[5]['d7'] = "7348";
    $issue_maping_arr[6]['d9'] = "8923";
    $issue_maping_arr[6]['d7'] = "7345";
    $issue_maping_arr[7]['d9'] = "8924";
    $issue_maping_arr[7]['d7'] = "7343";
    $issue_maping_arr[8]['d9'] = "8925";
    $issue_maping_arr[8]['d7'] = "7349";
    $issue_maping_arr[9]['d9'] = "8926";
    $issue_maping_arr[9]['d7'] = "7352";
    $issue_maping_arr[10]['d9'] = "8927";
    $issue_maping_arr[10]['d7'] = "7346";
    $issue_maping_arr[11]['d9'] = "8928";
    $issue_maping_arr[11]['d7'] = "7351";

    foreach ($issue_maping_arr as $key => $issue_index) {
      if(isset($issue_index['d9'])){
        $issue_term_id_d9 = $issue_index['d9'];
        if(isset($issue_term_id_d9)){
          if($issue_term_id_d9 == $this->taxonomyTerm){
            if(isset($issue_index['d7'])){
              $this->taxonomyTerm = $issue_index['d7'];
            } 
          }
        }
      }
    }

    $items = $this->connectionService->getResults($this->indexName,
      [
        'is_status' => 1,
        'im_field_related_themes' => $this->taxonomyTerm,
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      3
    );

    if (count($items)) {
      $build = [
        '#theme' => 'issue_news_block',
        '#items' => $items,
        '#more_url' => $this->getLanguagePrefix() . 'issues/' . $this->taxonomyTerm . '/news',
        '#more_label' => $this->t('Browse all'),
        '#region_name' => Term::load($temp)->label(),
      ];
    }
    else {
      // Show the More about title even if there are no news items.
      $build = [
        '#markup' => $this->t('More on this issue'),
        '#prefix' => '<h2 class="h2 h2--news-block-empty">',
        '#suffix' => '</h2>',
      ];
    }

    return $build;
  }

}
