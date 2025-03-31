<?php

namespace Drupal\landportal_alias_import\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\path_alias\Entity\PathAlias;
use Drupal\taxonomy\Entity\Term;

class TaxonomyController extends ControllerBase {

  /**
   * Import aliases from the CSV file and assign them to a specific language.
   *
   * @param string $tax_file_uri
   *   The URI of the CSV file.
   * @param string $selected_language
   *   The language code for the aliases (e.g., 'en' for English, 'fr' for French).
   */

  // show all data from csv file
  public function importAliasesTax($tax_file_uri, $tax_selected_language) {
    $tax_csv_data = file_get_contents($tax_file_uri);
    //\Drupal::logger('csv_data')->notice(serialize($csv_data));


    // Split file contents into lines and trim any empty lines.
    $remove_empty_line = array_filter(array_map('trim', explode("\n", $tax_csv_data)));
    

    $csv_file_header = str_getcsv(array_shift($remove_empty_line));
    $term_id_index = array_search('TID', $csv_file_header);
    $alias_index = array_search('Alias', $csv_file_header);

    if ($term_id_index === FALSE && $alias_index === FALSE) {
      \Drupal::logger('term-alias')->error('Required columns not found in CSV file.');
      return;
    }

    foreach ($remove_empty_line as $line) {
      if (empty($line)) {
        continue;
      }

      $fields = str_getcsv($line);
      if (isset($fields[$term_id_index]) && isset($fields[$alias_index])) {
        $term_id = $fields[$term_id_index];
        $alias = $fields[$alias_index];

        if (!empty($term_id) && !empty($alias)) {
          $taxonomy = Term::load($term_id);

          if ($taxonomy) {
            $langcode = $tax_selected_language;

            // get origional alias of the node for the selected language.
            // like -->> /basic-page-10
            $path_alias_manager = \Drupal::service('path_alias.manager');
            $term_origional_alias = $path_alias_manager->getAliasByPath('/taxonomy/term/' . $term_id, $langcode);
            

            // Ensure we only add the new alias if it doesn't already exist for the selected language.
            // like -->>  empty 0
            $alias_storage = \Drupal::entityTypeManager()->getStorage('path_alias');
            $existing_aliases = $alias_storage->loadByProperties([
              'alias' => $alias,
              'langcode' => $langcode,
            ]);

            if (empty($existing_aliases)) {
              PathAlias::create([
                'path' => '/taxonomy/term/' . $term_id,
                'alias' => $alias,
                'langcode' => $langcode,
              ])->save();

              \Drupal::logger('New Alias')->notice('Alias created for Term ID ' . $term_id . ' in ' . $langcode . ': ' . $alias);
            }
            else {
              \Drupal::logger('Already Exist')->notice('Alias already exists for Term ID ' . $term_id . ' in ' . $langcode . ': ' . $alias);
            }

            // Add again origional alias on the node if it was changed
            // new alias -->> /basic-page-10-3
            $current_alias_by_path = $path_alias_manager->getAliasByPath('/taxonomy/term/' . $term_id, $langcode);

            if ($term_origional_alias !== $current_alias_by_path) {
              PathAlias::create([
                'path' => '/taxonomy/term/' . $term_id,
                'alias' => $term_origional_alias,
                'langcode' => $langcode,
              ])->save();


              $term_all_aliases = $alias_storage->loadByProperties([
                'path' => '/taxonomy/term/' . $term_id,
                'langcode' => $langcode,
              ]);



              // find the current primary alias and delete it and then stop execution
              foreach ($term_all_aliases as $alias_record) {
                if ($alias_record->get('alias')->value === $term_origional_alias) {
                  $alias_storage->delete([$alias_record]);
                  break;
                }
              }
              
              \Drupal::logger('Origional-Alias')->notice('Restored primary alias for Term ID ' . $term_id . ' in ' . $langcode . ': ' . $term_origional_alias);
            }

          } else {
            \Drupal::logger('Term Not Found')->warning('Term ID ' . $term_id . ' not found. Skipping alias creation.');
          }
        } else {
          \Drupal::logger('term-alias')->warning('Skipping line with missing Term ID or Alias: ' . $line);
        }
      } else {
        \Drupal::logger('term-alias')->warning('Skipping malformed line: ' . $line);
      }
    }
  }
}
