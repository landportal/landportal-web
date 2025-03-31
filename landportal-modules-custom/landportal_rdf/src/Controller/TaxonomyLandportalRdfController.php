<?php
namespace Drupal\landportal_rdf\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use SimpleXMLElement;

use Drupal\Core\Link;
use Drupal\Core\Url;
use DOMDocument;
use DOMAttr;
use DateTime;


/**
 * Class TaxonomyLandportalRdfController
 * @package Drupal\landportal_rdf\Controller
 */

class TaxonomyLandportalRdfController extends ControllerBase{

  /**
   * Returns JSON data from landportal_rdf.json.
   */

   public function getJsonData() {
    // Get the absolute path to your module's root directory
    $modulePath = \Drupal::service('extension.path.resolver')->getPath('module', 'landportal_rdf');
    // Construct the path to your JSON file
    $jsonFilePath = $modulePath . '/landportal_rdf.json';

    // Check if the JSON file exists
    if (file_exists($jsonFilePath)) {
      // Example code to read and decode JSON from the file
      $jsonData = file_get_contents($jsonFilePath);
      $decodedData = json_decode($jsonData, true);
      return $decodedData;
    }
  }

  public function myPagetest() {

    // to get Node id from crrunt Path URL
    $current_path = \Drupal::service('path.current')->getPath();
    $current_path_arr = explode('/', $current_path);
    $current_path_nid = $current_path_arr['2'];

    // Load Taxonomy Term by Node id
    $taxonomy = \Drupal\taxonomy\Entity\Term::load($current_path_nid);

    // Title
    $title_val = $taxonomy->name->value;
    $title_val = htmlspecialchars($title_val);

    // UUID
    $uuid = $taxonomy->uuid->value;

    // vid of of Taxonomy Term
    $vid = $taxonomy->vid->target_id;

    //call json fucntion getJsonData()
    $vocbulary = $this->getJsonData();
    $vocubalry_id =  $vocbulary[$vid];

    // get URL alias of node
    $aliasManager = \Drupal::service('path_alias.manager');
    $alias = $aliasManager->getAliasByPath('/taxonomy/term/'.$current_path_nid);

    // Get Website Host with https 
    $host = \Drupal::request()->getSchemeAndHttpHost();

    if($vid == 'datasets'){
       include_once 'taxonomy/datasets.php';
    }

    elseif($vid == 'indicators'){
      include_once 'taxonomy/indicators.php';    
    }

    elseif($vid == 'regions'){
      include_once 'taxonomy/regions.php';   
    }
    
    elseif($vid == 'concepts'){
      include_once 'taxonomy/concepts.php';   
    }

    elseif($vid == 'theme'){
      include_once 'taxonomy/themes.php';
    }

    elseif($vid == 'licences'){
      include_once 'taxonomy/licences.php';
    }

    elseif($vid == 'debate_status'){
      include_once 'taxonomy/debate_status.php';
    }

    elseif($vid == 'organization_relation'){
      include_once 'taxonomy/organization_relation.php';
    }

    elseif($vid == 'organization_types'){
      include_once 'taxonomy/organization_types.php';
    }

    elseif($vid == 'campaigns'){
      include_once 'taxonomy/campaigns.php';
    }

    elseif($vid == 'currency'){
      include_once 'taxonomy/currency.php';
    }

    elseif($vid == 'events_types'){
      include_once 'taxonomy/events_types.php';
    }

    elseif($vid == 'observations'){
      include_once 'taxonomy/observations.php';
    }

    elseif($vid == 'landlibrary_resource_type'){
      include_once 'taxonomy/landlibrary_resource_type.php';
    }

    elseif($vid == 'layer'){
      include_once 'taxonomy/layer.php';
    }

    elseif($vid == 'sdgs_tiers'){
      include_once 'taxonomy/sdgs_tiers.php';
    }

    elseif($vid == 'types_of_data'){
      include_once 'taxonomy/types_of_data.php';
    }

    elseif($vid == 'languages'){
      include_once 'taxonomy/languages.php';
    }

    elseif($vid == 'sdgs'){
      include_once 'taxonomy/sdgs.php';
    }

    elseif($vid == 'slide_types'){
      include_once 'taxonomy/slide_types.php';
    }

    exit();
  
  }
 
}


?>