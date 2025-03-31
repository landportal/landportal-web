<?php
namespace Drupal\lp_related_content\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Unicode;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class PeopleAutocompleteController extends ControllerBase {

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request, $type, $regional) {
    $results = [];
    $content_type_label = "";
    // Get the typed string from the URL, if it exists.
    if ($input = $request->query->get('q')) {
      $typed_string = Tags::explode($input);

                    
      $str_typed_string = $typed_string[0];

      //$str_typed_string = urlencode($str_typed_string);

 

      /**********************Solr code Start***************************/

      ///Connecting solr

      try {
        $adapter = new Curl();
        $eventDispatcher = new EventDispatcher();
        $config = [
          'endpoint' => [
            'localhost' => [
              'host' => 'localhost',
              'port' => '8983',
              'path' => '/',
              'core' => 'countries',
            ],
          ],
        ];
        $client = new Client($adapter, $eventDispatcher, $config);
      }
      catch (\Exception $e) {
        watchdog_exception('facets', $e);
      }  

      //Get organizations record from solr d7 on the basis of node ids string
      //$index = 'remote_news';
      $conditions = [];  
      $fields = []; 
      $sorts = ['is_nid' => 'desc']; 
      $num_rows = 1000;
      $items = [];

      try {
        $query = $client->createSelect();

        
        if($type == "news"){
          $query->createFilterQuery('index')->setQuery('index_id:remote_news');
          $content_type_label = "News";
        }
        else if($type == "blog_post"){
          $query->createFilterQuery('index')->setQuery('index_id:remote_blogs'); 
          $content_type_label = "Blog post";
        }
        else if($type == "landlibrary_resource"){
          $query->createFilterQuery('index')->setQuery('index_id:remote_libraries');
          $content_type_label = "LandLibrary Resource";
        }
        else if($type == "event"){
          $query->createFilterQuery('index')->setQuery('index_id:remote_events');
          $content_type_label = "Event";
        }
        else if($type == "organization"){
          $query->createFilterQuery('index')->setQuery('index_id:remote_organizations');
          $content_type_label = "Organization";
        }  
         
        // Add the base conditions (index and language), then add custom ones.


        foreach ($conditions as $name => $value) {
          $query->createFilterQuery($name)->setQuery($name . ':' . $value);
        }
        if(is_numeric($regional)){
        $query->createFilterQuery('im_field_geographical_focus')->setQuery("im_field_geographical_focus:" . $regional);
        }

        $query->setFields(array('im_field_geographical_focus','item_id','ss_title_field',"ds_field_pubdate","is_nid","ss_url"));
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

        $resultsa = $client->select($query);        
        foreach ($resultsa as $document) {
            if(isset($document["ss_title_field"]) && isset($document["is_nid"])){
              $node_title = strtolower($document["ss_title_field"]);
              if (str_contains($node_title, strtolower($str_typed_string))) {
              //if((stripos($document["ss_title_field"],$str_typed_string) === 0) || (stripos($document["ss_title_field"],$str_typed_string) >= 1)) {  
                //\Drupal::logger('title_value')->notice($document["ss_title_field"] . " -- " . $document["is_nid"] . " -- " . $str_typed_string . " --- " . $document["ss_url"]);
                $results[] = array("value" => '<a href=\"' . $document["ss_url"] . '">'.$document["ss_title_field"].'</a>' ,"label" => $content_type_label . "(" . $document["is_nid"] . ")" . $document["ss_title_field"]);
              }              
            }
        }
      }
      catch (\Exception $e) {
        //watchdog_exception('facetsnew', $e);
      }
      //////////Solr code end here 
     
    }

    return new JsonResponse($results);
  }

}
