<?php

namespace Drupal\issue_blog_posts\Plugin\Block;

use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'Issue blog posts' block.
 *
 * @Block(
 *   id = "issue_blog_posts_block",
 *   admin_label = @Translation("Issue blog posts block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class IssueBlogPostsBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_blogs';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
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
      [
        'bs_promote' => 'desc',
        'ds_field_pubdate' => 'desc',
      ],
      1
    );

    if (count($items)) {
      //$items[0]['tm_body$value'][0] = "hello";
      if(isset($items[0]['tm_body$value'][0])){
        $temp_body = $items[0]['tm_body$value'][0];
        $temp_body = str_replace("https://indonesiaatmelbourne.unimelb.edu.au/does-indonesia-really-need-a-land-bank/", "", $temp_body);
        $temp_body = str_replace("Original source:", "", $temp_body);        
        $items[0]['tm_body$value'][0] = $temp_body;
        //print $temp_body;
      }
      //print "<pre>";
      //print_r($items[0]['tm_body$value'][0]);
      //print "</pre>";
      $build = [
        '#theme' => 'issue_blog_posts_block',
        '#items' => $items,
        '#more_url' => $this->getLanguagePrefix() . 'issues/' . $this->taxonomyTerm . '/blog_post',
        '#more_label' => $this->t('Browse all'),
        '#region_name' => Term::load($temp)->label(),
      ];
    }

    return $build;
  }

}
