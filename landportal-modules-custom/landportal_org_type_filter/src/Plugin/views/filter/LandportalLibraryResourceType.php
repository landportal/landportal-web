<?php

namespace Drupal\landportal_org_type_filter\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Database\Query\Condition;

/**
 * Provides a custom filter for organization type.
 *
 * @ViewsFilter("custom_views_resource_type")
 */
class LandportalLibraryResourceType extends InOperator {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    //\Drupal::logger('check init function')->notice('init function is calling');
    parent::init($view, $display, $options);
    $this->valueTitle = t('Library Resource Type');
    $this->definition['options callback'] = array($this, 'generateOptions');
  }

    /**
    * {@inheritdoc}
    */
    public function query() {
    $values_drop = $this->value;

    //\Drupal::logger('value Drop')->notice(serialize($values_drop));

    if (!empty($values_drop)) {
        $contextual_value = $this->view->args[0];
        $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'landlibrary_resource') 
            ->condition('field_geographical_focus', $contextual_value);
        $library_nids = $query->execute();
        $library_nodes = [];

        // Load the nodes corresponding to the IDs in $library_nids
        $library_nodes_entities = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($library_nids);

        foreach ($library_nodes_entities as $library_node) {
            $field_values = $library_node->get('field_doc_type')->getValue();
            foreach ($field_values as $field_value) {
                // Check if the field value matches any of the values provided in $values_drop
                if (in_array($field_value['target_id'], $values_drop)) {
                    // If it does, add the node ID to the result array
                    $library_nodes[] = $library_node->id();
                    break; // Break out of the inner loop as we've found a match
                }
            }
        }

        //\Drupal::logger('library_nodes')->notice(serialize($library_nodes));

        if (!empty($library_nodes)) {
            $this->query->addWhere('AND', 'node_field_data.nid', $library_nodes, 'IN');
        }
    }

    else {
      parent::query();
      //\Drupal::logger('check query asle')->notice('parent query');
    }
    }

    public function generateOptions() {
        $options = [];

        // Load organization node IDs from CSV based on selected taxonomy term IDs.
        $organization_nids = $this->loadOrganizationNidsFromCsv();

        // Load the referenced terms from the nodes.
        $terms = [];
        foreach ($organization_nids as $taxonomy_term_tid) {
            // Get the license term ID from the CSV
            $license_term_id = $taxonomy_term_tid;
            // Load the license term entity
            $term = Term::load($license_term_id);
            if ($term !== NULL) {
                // Get the name of the license term
                $term_name = $term->getName();
                // Use term ID as array key and name with count as value
                if (!isset($terms[$license_term_id])) {
                    $terms[$license_term_id] = 1;
                } else {
                    $terms[$license_term_id]++;
                }
            }
        }

        // Format options with count.
        foreach ($terms as $term_id => $count) {
            $term = Term::load($term_id);
            if ($term !== NULL) {
                $term_name = $term->getName();
                $options[$term_id] = $term_name . ' - (' . $count . ')';
            }
        }

        return $options;
    }

    /**
     * Load organization node IDs from CSV based on selected taxonomy term IDs.
     */
    protected function loadOrganizationNidsFromCsv() {
        $csv_file_path = 'public://csv/organizationtype/Resourcetype.csv';
        $organization_nids = [];

        if (file_exists($csv_file_path)) {
            $file_handle = fopen($csv_file_path, 'r');
            if ($file_handle !== FALSE) {
                // Skip the header row
                fgetcsv($file_handle);
                while (($data = fgetcsv($file_handle)) !== FALSE) {
                    $organization_nid = trim($data[0]); // Assuming node ID is in the first column
                    $taxonomy_term_tid = trim($data[1]); // Assuming taxonomy term ID is in the second column
                    if (!empty($organization_nid) && is_numeric($organization_nid)) {
                        $organization_nids[] = $taxonomy_term_tid;
                    }
                }
                fclose($file_handle);
            }
        }

        return $organization_nids;
    }

}

