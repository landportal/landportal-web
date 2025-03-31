<?php

namespace Drupal\landportal_alias_import\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\path_alias\Entity\PathAlias;
use Drupal\node\Entity\Node;

class AliasPageController extends ControllerBase {

  /**
   * Import aliases from the CSV file and assign them to a specific language.
   *
   * @param string $file_uri
   *   The URI of the CSV file.
   * @param string $selected_language
   *   The language code for the aliases (e.g., 'en' for English, 'fr' for French).
   */
  public function importAliases($file_uri, $selected_language) {
    $csv_data = file_get_contents($file_uri); 

    if ($csv_data === FALSE) {
      \Drupal::logger('node-alias')->error('Failed to read file contents.');
      return;
    }

    /*
      * in this code, it will splite csv data into lines
      * 
      */
    $remove_empty_line = array_filter(array_map('trim', explode("\n", $csv_data)));
    

     /*
      * In this if condition, it will check that the csv file is empty then it will generate the error log. 
      */
    if (empty($remove_empty_line)) {
      \Drupal::logger('node-alias')->error('CSV file is empty or could not be read.');
      return;
    }

    /*
     * in this code, it will search about the required column in csv file like 'NID' and 'Alias'
     */
    $csv_file_header = str_getcsv(array_shift($remove_empty_line));
    $node_id_index = array_search('NID', $csv_file_header);
    $alias_index = array_search('Alias', $csv_file_header);

    /*
      * This code checks if the required columns 'NID' and 'Alias' is not exist in the CSV file. 
      * an error log is generated.
      * 
      */
    if ($node_id_index === FALSE || $alias_index === FALSE) {
      \Drupal::logger('node-alias')->error('Required columns not found in CSV file.');
      return;
    }

    /*
      * In this if condition, it will check that the csv file has any empty line. 
      * if any, then it will skip the empty line and move on the new line. 
      */
    foreach ($remove_empty_line as $line) { 
      if (empty($line)) {
        continue;
      }

      /*
      * The str_getcsv() function splits the CSV line into an array of values (NID, Alias).  
      * And if statement checks if both the NID and Alias fields are set, then assigns them to variables
      */
      $fields = str_getcsv($line);
      if (isset($fields[$node_id_index]) && isset($fields[$alias_index])) {
        $node_id = $fields[$node_id_index];
        $alias = $fields[$alias_index];

        /*
        * In this 'if' condition, it will check $node_id and $alias are not empty 
        * then it will load $node_id
        */
        if (!empty($node_id) && !empty($alias)) {
          $node = Node::load($node_id);

           /*
            * In this if condition, it will check that it is a node, if it is, 
            * then it will get the language code like (en, fr, pt, es, nl, und).
            */
          if ($node) {
            $langcode = $selected_language;


            /*
            * get origional alias of the node for the selected language. 
            * like (en, fr, pt, es, nl, und).
            * eg) original alias is -->> /Shahi-Testing-Campaign
            */
            $path_alias_manager = \Drupal::service('path_alias.manager');
            $node_origional_alias = $path_alias_manager->getAliasByPath('/node/' . $node_id, $langcode);

            /*
            * This code checks if the new alias already exists for the node and selected language.  
            * eg) new alias is -->> /Shahi-Testing-Campaign-Test-1
            */
            $alias_storage = \Drupal::entityTypeManager()->getStorage('path_alias');
            $existing_aliases = $alias_storage->loadByProperties([
              'alias' => $alias,
              'langcode' => $langcode, 
            ]);

            /*
            * in this if condition, it will check that the $existing_aliases is empty  
            * if empty, then it will add the new alias
            */
            if (empty($existing_aliases)) {
              PathAlias::create([
                'path' => '/node/' . $node_id,
                'alias' => $alias,
                'langcode' => $langcode,
              ])->save();

              \Drupal::logger('New Alias')->notice('Alias created for Node ID ' . $node_id . ' in ' . $langcode . ': ' . $alias);
            }
            else {
              \Drupal::logger('Already Exist')->notice('Alias already exists for Node ID ' . $node_id . ' in ' . $langcode . ': ' . $alias);
            }

            /*
            * in this if condition, it will check that the $node_origional_alias and $current_alias_by_path
            * is not equal, then the original alias is restored.
            */
            $current_alias_by_path = $path_alias_manager->getAliasByPath('/node/' . $node_id, $langcode);
            if ($node_origional_alias !== $current_alias_by_path) {
              PathAlias::create([
                'path' => '/node/' . $node_id,
                'alias' => $node_origional_alias,
                'langcode' => $langcode,
              ])->save();

              /*
              * here in this code it will get the all aliases of node and their langcode 
              * and then store in the $node_all_aliases variable
              */
              $node_all_aliases = $alias_storage->loadByProperties([
                'path' => '/node/' . $node_id,
                'langcode' => $langcode,
              ]);


              /*
              * here in this code, it will run forach loop for all aliases of the node and find the primary aliases 
              * and if any, then delete recent one from the aliase record.
              */
              foreach ($node_all_aliases as $alias_record) {
                if ($alias_record->get('alias')->value === $node_origional_alias) {
                  $alias_storage->delete([$alias_record]);
                  break; 
                }
              }
              
              \Drupal::logger('Origional-Alias')->notice('Restored primary alias for Node ID ' . $node_id . ' in ' . $langcode . ': ' . $node_origional_alias);
            }

          } else {
            \Drupal::logger('NID Not Found')->warning('Node ID ' . $node_id . ' not found. Skipping alias creation.');
          }
        } else {
          \Drupal::logger('node-alias')->warning('Skipping line with missing Node ID or Alias: ' . $line);
        }
      } else {
        \Drupal::logger('node-alias')->warning('Skipping malformed line: ' . $line);
      }
    }
  }
}
