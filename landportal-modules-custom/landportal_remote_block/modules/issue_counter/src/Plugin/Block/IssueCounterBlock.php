<?php

namespace Drupal\issue_counter\Plugin\Block;

use Drupal\taxonomy\Entity\Term;
use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;
use Drupal\Core\Language\LanguageInterface;

/**
 * Provides a 'Issue counter' block.
 *
 * @Block(
 *   id = "region_issue_counter_block",
 *   admin_label = @Translation("Region issue counter block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class IssueCounterBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $newsIndexName = 'remote_news';
  protected $projectIndexName = 'remote_projects';
  protected $blogIndexName = 'remote_blogs';
  protected $libraryIndexName = 'remote_libraries'; 
  protected $layerIndexName = 'remote_layers';   
  protected $indicatorIndexName = 'remote_indicators';  

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
    /*
    $username = '';
    $password = '';
     
    $context = stream_context_create(array(
        'http' => array(
            'header'  => "Authorization: Basic " . base64_encode("$username:$password")
        )
    ));

    $all_issue = new \DOMDocument();
    $url = 'http://iqbal7.landportal.org/collect-counter/';

    $file = file_get_contents($url.$this->taxonomyTerm, false, $context);

    if(isset($file)){
    $all_issue->loadHTML($file, LIBXML_NOERROR);
    }
    */
    $blogs = '';
    $projects = '';
    $news = '';
    $layers = '';
    $indicators = '';
    $library = '';
    $newsItems = $this->connectionService->getCountResults($this->newsIndexName,
      [
        'is_status' => 1,
        'im_field_related_themes' => $this->taxonomyTerm,
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      1
    );

    $projectItems = $this->connectionService->getCountResults($this->projectIndexName,
      [
        'is_status' => 1,
        'im_field_related_themes' => $this->taxonomyTerm,
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      1
    );    

    $blogItems = $this->connectionService->getCountResults($this->blogIndexName,
      [
        'is_status' => 1,
        'im_field_related_themes' => $this->taxonomyTerm,
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      1
    );   

    $libraryItems = $this->connectionService->getCountResults($this->libraryIndexName,
      [
        'is_status' => 1,
        'im_field_related_themes' => $this->taxonomyTerm,
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      1
    );  

    $layerItems = $this->connectionService->getCountResults($this->layerIndexName,
      [
        'is_status' => 1,
        'im_field_related_themes' => $this->taxonomyTerm,
      ],
      [],
      [],
      1
    ); 

    $indicatorItems = $this->connectionService->getCountResults($this->indicatorIndexName,
      [
        'is_status' => 1,
        'im_field_related_themes' => $this->taxonomyTerm,
      ],
      [],
      [],
      1
    );      

    $newsCounter = "0";
    if (count($newsItems)) {
      $newsCounter = $newsItems[0];
    }

    $projectsCounter = "0";
    if (count($projectItems)) {
      $projectsCounter = $projectItems[0];
    }

    $blogsCounter = "0";
    if (count($blogItems)) {
      $blogsCounter = $blogItems[0];
    }   

    $libraryCounter = "0";
    if (count($libraryItems)) {
      $libraryCounter = $libraryItems[0];
    } 
     
    $layerCounter = "0";
    if (count($layerItems)) {
      $layerCounter = $layerItems[0];
    }    

    $indicatorCounter = "0";
    if (count($indicatorItems)) {
      $indicatorCounter = $indicatorItems[0];
    }           
    /*
    $blogs = $all_issue->getElementsByTagName('h6')->item(0)->nodeValue;

    $projects = $all_issue->getElementsByTagName('h6')->item(1)->nodeValue; 

    $news = $all_issue->getElementsByTagName('h6')->item(2)->nodeValue;

    $layers = $all_issue->getElementsByTagName('h6')->item(3)->nodeValue;

    $indicators = $all_issue->getElementsByTagName('h6')->item(4)->nodeValue;

    $library = $all_issue->getElementsByTagName('h6')->item(5)->nodeValue;   
      */ 
      $issue_name = "";
      /*
      //$langcode = $this->languageManager->getCurrentLanguage()->getId();
      $langcode =  \Drupal::languageManager()->getCurrentLanguage()->getId();
      $langcode = $langcode == 'pt-pt' ? 'pt' : $langcode;  
      if(null !== Term::load($temp)->getTranslation($langcode)){  
        //$issue_name = Term::load($temp)->getTranslation($langcode)->label();
      }
      else{
        $issue_name = Term::load($temp)->label();
      }
      */

    $curr_langcode = \Drupal::service('language_manager')->getCurrentLanguage(LanguageInterface::TYPE_CONTENT)->getId();
    // Retrieve term.
    $taxonomy_term = Term::load($temp);
    // Retrieve the translated taxonomy term in specified language
    // ($curr_langcode) with fallback to default language if translation not
    // exists.
    $taxonomy_term_trans = \Drupal::service('entity.repository')->getTranslationFromContext($taxonomy_term, $curr_langcode);
    // Get the value of the field "myfield".
    $myfield_translated = $taxonomy_term_trans->name->value;      

    $build = [
      '#theme' => 'issue_counter_block',
      '#news_counter' => $newsCounter,
      '#projects_counter' => $projectsCounter,
      '#blogs_counter' => $blogsCounter,
      '#library_counter' => $libraryCounter,
      '#indicators_counter' => $indicatorCounter,
      '#layers_counter' => $layerCounter,
      '#issue_name' => $this->t('Resources for') . " " . $myfield_translated,
    ];      

    return $build;
  }

}
