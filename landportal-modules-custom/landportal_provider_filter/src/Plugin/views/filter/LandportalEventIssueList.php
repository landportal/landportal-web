<?php

namespace Drupal\landportal_provider_filter\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Database\Query\Condition;
use Drupal\Core\Language\LanguageInterface;


/**
 * Provides a custom filter for theme indicator counts.
 *
 * @ViewsFilter("custom_views_event_issue")
 */
class LandportalEventIssueList extends InOperator {

    /**
     * {@inheritdoc}
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL){
        //\Drupal::logger('issueCsv-init-check')->notice('Init method called.');
        parent::init($view, $display, $options);
        $this->valueTitle = t('Event Issue Count');
        $this->definition['options callback'] = array($this, 'generateOptions');

    }

    /**
     * Override the query to filter by theme indicator count.
     */
    // Inside the query method
    public function query()
    {
        $values_drop = $this->value;

        // Log the values_drop to see if it's populated
        //\Drupal::logger('issueCsv-values_drop')->notice('Values drop: ' . print_r($values_drop, true));
        if (!empty($values_drop)) {
            $theme_tids = [];
            foreach ($values_drop as $value) {
                // \Drupal::logger('issueCsv-selected_theme abc')->notice('Selected theme name: ' . serialize($value));
                // \Drupal::logger('issueCsv-loop')->notice('Entering loop');
                $node_query = \Drupal::entityQuery('node');
                $node_query->condition('type', "event");
                $node_query->condition('field_related_themes', $value);
                $theme_tids = $node_query->execute();

                //\Drupal::logger('issueCsv-selected_theme theme_tids')->notice('Selected theme name: ' . serialize(count($theme_tids)));

            }

            // Check if theme_tids is not empty and execute the query
            if (!empty($theme_tids)) {
              //  \Drupal::logger('issueCsv-query1asd')->notice('hi');
                $this->query->addWhere('AND', 'node_field_data.nid', $theme_tids, 'IN');
            }
        } else {
            parent::query();
        }
    }


    public function generateOptions() {
        $options = [];

        // Load organization node IDs from CSV based on selected taxonomy term IDs.
        $organization_nids = $this->loadOrganizationNidsFromCsv();
        // Initialize an array to store all node IDs associated with each term.
        $term_node_ids = [];

        // Query the database for the nodes using each term.
        foreach ($organization_nids as $taxonomy_term_tid) {
            // Query for nodes using the term.
            $query = \Drupal::entityQuery('node')
                ->condition('type', 'event')
                ->condition('status', 1)
                ->condition('field_related_themes', $taxonomy_term_tid)
                ->condition('langcode', [
                    \Drupal::languageManager()->getCurrentLanguage()->getId(),
                    LanguageInterface::LANGCODE_NOT_SPECIFIED,
                ], 'IN');
            $node_ids = $query->execute();
            // Store the node IDs in the array with the term ID as the key.
            $term_node_ids[$taxonomy_term_tid] = $node_ids;
        }

        // Count the number of unique nodes for each term.
        foreach ($term_node_ids as $term_id => $node_ids) {
            $unique_node_ids = array_unique($node_ids);
            $count = count($unique_node_ids);
            // Retrieve the term name.
            if($count > 0){
                $term = Term::load($term_id);
                if ($term !== NULL) {
                    $term_name = $term->getName();
                    $options[$term_id] = $term_name . ' - (' . $count . ')';
                }
            }
        }
        asort($options);
        return $options;
    }

    protected function loadOrganizationNidsFromCsv() {
        $csv_file_path = 'public://csv/datasetProviderFilter/eventThemes.csv';
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
        //\Drupal::logger('organization_nids')->notice(serialize($organization_nids));
        return $organization_nids;
    }  

}
