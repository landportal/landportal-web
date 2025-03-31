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

/**
 * Provides a custom filter for organization type.
 *
 * @ViewsFilter("custom_views_org_type")
 */
class LandportalOrgType extends InOperator {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Organization Type');
    $this->definition['options callback'] = array($this, 'generateOptions');
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $values_drop = $this->value;

    if (!empty($values_drop)) {
      $organization_nids = $this->loadOrganizationNidsFromCsv(); // organization org_type taxonomy org type
      \Drupal::logger('other filter')->notice(serialize($organization_nids));
      if (!empty($organization_nids)) {
        $this->query->addWhere('AND', 'node_field_data.nid', $organization_nids, 'IN');
      }
    }
    else {
      parent::query();
    }
  }

  /**
   * Load organization node IDs from CSV based on selected taxonomy term IDs.
   */
  protected function loadOrganizationNidsFromCsv() {
    $csv_file_path = 'public://csv/organizationtype/organizationThemes.csv';
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
            // Check if the organization node uses any of the selected taxonomy term IDs
            if (in_array($taxonomy_term_tid, $this->value)) {
              $organization_nids[] = $organization_nid;
            }
          }
        }
        fclose($file_handle);
      }
    }
    return $organization_nids;
  }


  /**
   * {@inheritdoc}
   */
  public function generateOptions() {
    $options = [];

    // Get the theme tid from the contextual filter.
    $theme_tid = $this->view->args[0]; // Assuming the contextual filter is the first argument.

    $query = \Drupal::entityQuery('node')
    ->condition('status', 1)
    ->condition('type', 'organization'); // Assuming your content type machine name is 'organization'.
  
  // Create an OR condition group.
  $group = $query->orConditionGroup()
    ->condition('field_related_themes', $theme_tid)
    ->condition('field_related_topics', $theme_tid);
  
  // Add the OR condition group to the query.
  $query->condition($group);
  
  $nids = $query->execute();

    // Load organization type terms associated with the nodes.
    if (!empty($nids)) {
      $nodes = Node::loadMultiple($nids);
      foreach ($nodes as $node) {
        $organization_type_terms = $node->get('field_organization_type')->referencedEntities();
        foreach ($organization_type_terms as $term) {
          $term_id = $term->id();
          $term_name = $term->getName();

          // Increment the count for the organization type.
          if (!isset($options[$term_id])) {
            $options[$term_id] = [
              'name' => $term_name,
              'count' => 1,
            ];
          } else {
            $options[$term_id]['count']++;
          }
        }
      }
    }

    // Format options with count.
    $formatted_options = [];
    foreach ($options as $term_id => $option_data) {
      $formatted_options[$term_id] = $option_data['name'] . ' (' . $option_data['count'] . ')';
    }

    return $formatted_options;
  }

}

