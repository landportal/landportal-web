<?php

namespace Drupal\landportal_country\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
//Connection Service
use Drupal\Core\Site\Settings;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;


/**
 * Provides a 'Map' block for the project landing page.
 *
 * @Block(
 *   id = "project_landing_map",
 *   admin_label = @Translation("Project landing page map block"),
 *   category = @Translation("Project Landing Page")
 * )
 */
class ProjectLandingMap extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs an ProjectLandingMap object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
  
  // foreach ($projectsData as $key => $projectData) {
  //   if(isset($projectData['its_nid'])){
  //     $projectId = $projectData['its_nid'];
  //     \Drupal::logger('ProjectId')->notice(serialize($projectId));
  //   }

  //   if(isset($projectData['its_project_regions'])){
  //     $countryID = $projectData['its_project_regions'];
  //     \Drupal::logger('CountriesID')->notice(serialize($countryID));
  //   }
    
  // }


    $build = [];
    $div = 'block-landbook-view-coda-lbvc-map-country-wrapper';
    $build = [
      'label' => [
        '#markup' => $this->t('Search featured projects by country'),
        '#prefix' => '<h2 class="h2">',
        '#suffix' => '</h2>',
      ],
      'map' => [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'id' => $div,
        ],
        '#attached' => [
          'library' => [
            'landportal_country/project_landing_page_map',
          ],
          'drupalSettings' => [
            'target' => $div,
            'data' => $this->getCountries(),
          ],
        ],
        '#value' => $this->t('Loading map...'),
      ],
    ];
    return $build;
  }

  /**
   * Returns an array of data keyed by ISO3.
   *
   * Key is ISO3
   * Value is:
   *  - 0: country.
   *  - 1: with narrative.
   *  - 2: promoted.
   *
   * @return array
   *   Array of countries.
   */
  protected function getCountries() {
    $countries = [];

  ///Connecting solr
    try {
      $adapter = new Curl();
      $eventDispatcher = new EventDispatcher();
      $config = [
        'endpoint' => [
          'localhost' => [
            'host' => 'solr',
            'port' => '8983',
            'path' => '/',
            'core' => 'landportal.org',
          ],
        ],
      ];
      \Drupal::logger('kkk')->notice("map");
      $this->client = new Client($adapter, $eventDispatcher, $config);
    }
    catch (\Exception $e) {
      \Drupal::logger('kkk')->notice("country map");
      watchdog_exception('ProjectMap', $e);
    } 


//Get Project record from solr d7 on the basis of node ids string
    $index = 'local_projects_new';
    $conditions = [];  
    $fields = []; 
    $sorts = []; 
    $num_rows = 10000;
    $projectsData = [];

    try {
      $query = $this->client->createSelect();

      // Add the base conditions (index and language), then add custom ones.
      $query->createFilterQuery('index')->setQuery('index_id:' . $index);

     // $indexes_of_related_content = "index_id:remote_events OR index_id:remote_news OR index_id:remote_blogs";
     //$query->createFilterQuery('index')->setQuery($indexes_of_related_content);

      foreach ($conditions as $name => $value) {
        $query->createFilterQuery($name)->setQuery($name . ':' . $value);
      }

//$orginization_item_ids_str = "item_id:13129 OR item_id:52627";
      //$item_ids = "item_id:5 OR item_id:4";
      //$query->createFilterQuery('item_id')->setQuery($orginization_item_ids_str);

      // Set the projection of fields to retrieve.
      if ($fields) {
        $query->setFields($fields);
      }

      // The sorts are asc/desc.
      foreach ($sorts as $name => $value) {
        $query->addSort($name, $value);
      }

      // Limit the query.
      $query->setRows($num_rows);

      $resultsa = $this->client->select($query);
      //\Drupal::logger('part one')->notice(serialize($resultsa));
      
      foreach ($resultsa as $document) {
        //\Drupal::logger('Part Two')->notice("iqbal is testing");
        $item = [];
        foreach ($document as $field => $value) {
          $item[$field] = $value;
        }
        $projectsData[] = $item;
      }
      //\Drupal::logger('facetsnewsa_items')->notice(serialize($items));
    }
    catch (\Exception $e) {
      watchdog_exception('projectMapTwo', $e);
    }


    /** @var \Drupal\taxonomy\TermStorageInterface $term_storage */
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    /** @var \Drupal\node\NodeStorageInterface $node_storage */
    $node_storage = $this->entityTypeManager->getStorage('node');

    // Language for URLs, needed if building content with mixed languages.
    $current_language = \Drupal::languageManager()->getCurrentLanguage();

    $query = $term_storage->getQuery();
    $query->condition('vid', 'regions');
    $query->condition('field_iso3', NULL, 'IS NOT NULL');
    $tids = $query->accessCheck(TRUE)->execute();
    $terms = $term_storage->loadMultiple($tids);
    $projectsCount = ""; 
    foreach ($terms as $term) {
      $projectsCount = 0;
      //\Drupal::logger('TermId')->notice(serialize($term->id()));
      //if($term->id() == '720'){
        //\Drupal::logger('TermId')->notice(serialize($term->id()));


      foreach ($projectsData as $key => $projectData) {
        //\Drupal::logger('projectData')->notice($projectData['bs_status']);
        /*
        if(isset($projectData['its_nid'])){

          $projectId = $projectData['its_nid'];
          if($projectId == '90937'){
            \Drupal::logger('ProjectId')->notice(serialize($projectData['its_project_regions']));
            \Drupal::logger('ProjectId')->notice(serialize($projectId));
          }
          
        }
        */

        if(isset($projectData['itm_project_regions']) && ($projectData['bs_status'] == "1")){
          $countryID_arr = $projectData['itm_project_regions'];
          // $term->id()
          if(is_array($countryID_arr)){
            foreach($countryID_arr as $countryID){
              if($term->id() == $countryID){
                $projectsCount = $projectsCount + 1;
                //\Drupal::logger('SolrCountryId')->notice(serialize($countryID));
              }
            }
          }
          //\Drupal::logger('PakIdOuter')->notice(serialize($countryID));
        }
        
      //}
      }
      $iso3 = $term->get('field_iso3')->value;
        $lang_url_first_initial = "/";
          if($current_language->getId() == "en"){
            $lang_url_first_initial = "/";
          }
          else if($current_language->getId() == "pt-pt"){
            $lang_url_first_initial = "/pt/";
          }          
          else{
            $lang_url_first_initial = "/" . $current_language->getId() . "/";
          }    
      $countries[$iso3] = [
        'id' => $iso3,
        'value' => $projectsCount,
        //'url' => $term->toUrl('canonical', ['language' => $current_language])->toString() . $term->id(),
        'countryName' => $term->getName(),
        'url' => $lang_url_first_initial . 'search-in-projects?key=&f[0]=by_geographical_focus:' . $term->id(),
        
      ];
    }
/*
    $query = $node_storage->getQuery();
    $query->condition('status', NodeInterface::PUBLISHED);
    $query->condition('type', 'country_narrative');
    $query->condition('geographical_focus', $tids, 'IN');
    $nids = $query->accessCheck(TRUE)->execute();
    /** @var \Drupal\node\NodeInterface[] $narratives 
    $narratives = $node_storage->loadMultiple($nids);

    foreach ($narratives as $narrative) {
      $iso3 = $narrative->get('geographical_focus')->entity->get('field_iso3')->value;
      $countries[$iso3]['id'] = $iso3;
      $countries[$iso3]['value'] = rand(1,10);
    }
*/
    //\Drupal::logger('Part Two12')->notice(serialize($countries));
    return array_values($countries);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route', 'languages:language_interface']);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(
      parent::getCacheTags(),
      ['node_list:country_narrative', 'taxonomy_term_list:regions']
    );
  }

}
