<?php

namespace Drupal\lp_project_status\Plugin\Block;

//Connection Service
use Drupal\Core\Site\Settings;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;
//Project Organization
use Drupal\node\Entity\Node;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'country_project_map' Block.
 *
 * @Block(
 *   id = "country_project_map",
 *   admin_label = @Translation("Country Project Map"),
 *   category = @Translation("Landportal project Map"),
 * )
 */

class CountryProjectMap extends BlockBase {

/**
   * {@inheritdoc}
   */
   public function build(){   
      \Drupal::logger('dddd')->notice("my field a ");
///Connecting solr
    try {
      $adapter = new Curl();
      $eventDispatcher = new EventDispatcher();
      $config = [
        'endpoint' => [
          'localhost' => [
            'host' => '95.217.35.83',
            'port' => '8983',
            'path' => '/',
            'core' => 'landportal9',
          ],
        ],
      ];
      \Drupal::logger('dddd')->notice("my field b ");
      $this->client = new Client($adapter, $eventDispatcher, $config);
    }
    catch (\Exception $e) {
      \Drupal::logger('dddd')->notice("my field c ");
      watchdog_exception('facets', $e);
    }   



//Get organizations record from solr d7 on the basis of node ids string
    $index = 'local_projects';
    $conditions = [];  
    $fields = []; 
    $sorts = []; 
    $num_rows = 2000;
    $items = [];

    try {
      $query = $this->client->createSelect();

      // Add the base conditions (index and language), then add custom ones.
      $query->createFilterQuery('index')->setQuery('index_id:' . $index);

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
      //\Drupal::logger('facetsnewsa')->notice(serialize($resultsa));
      
      foreach ($resultsa as $document) {
        //\Drupal::logger('facetsnewsa_itema')->notice("iqbal is testing");
        $item = [];
        foreach ($document as $field => $value) {
          $item[$field] = $value;
        }
        $items[] = $item;
      }
      //\Drupal::logger('facetsnewsa_items')->notice(serialize($items));
    }
    catch (\Exception $e) {
      //watchdog_exception('facetsnew', $e);
    }
    $res = '';
      foreach ($items as $key => $one_item) {
         $iso3 = $one_item['sm_field_iso3'];
         $countryName = $one_item['sm_name'];
         $regionId = $one_item['itm_project_regions'];
         \Drupal::logger('dddd')->notice("my field d " . serialize($iso3) . " -- " . serialize($countryName) . " -- " . serialize($regionId));

      }


        return [
      '#markup' => $this->t('Hello Blockas'),
    ];
 }
}
?>
