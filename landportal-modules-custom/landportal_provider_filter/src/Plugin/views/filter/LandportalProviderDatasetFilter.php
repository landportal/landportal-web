<?php

namespace Drupal\landportal_provider_filter\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;
use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a custom filter for a specific view.
 *
 * @ViewsFilter("custom_views_field_dataset_page")
 */
class LandportalProviderDatasetFilter extends InOperator {

    /**
     * {@inheritdoc}
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL)
    {
        parent::init($view, $display, $options);
        $this->valueTitle = t('Show Node Ref Dataset Title');
        $this->definition['options callback'] = array($this, 'generateOptions');
    }

    /**
     * Override the query so that no filtering takes place if the user doesn't
     * select any options.
     */
    public function query(){
        $values_drop = $this->value;

        //\Drupal::logger('First')->notice(serialize($values_drop));

        if (!empty($values_drop)) {
            $getdatasetProvidersCSV = getOrganizationNidsFromCSV("public://csv/datasetProviderFilter/datasetProviders.csv");

            $datasets_tids = [];
            foreach ($getdatasetProvidersCSV as $row) {
                $columns = str_getcsv($row);
                $datasets_tid = isset($columns[0]) ? $columns[0] : null;
                if ($datasets_tid !== null && in_array($columns[1], $values_drop)) {
                    $datasets_tids[] = $datasets_tid;
                }
            }
            //\Drupal::logger('l_p_f-datasets_tids')->notice(serialize($datasets_tids));

            $this->query->addWhere('AND', 'taxonomy_term_field_data.tid', $datasets_tids, 'IN');
        } else {
            parent::query();
        }
    }


    /**
     * Skip validation if no options have been chosen so we can use it as a
     * non-filter.
     */
    public function validate()
    {
        if (!empty($this->value)) {
            parent::validate();
        }
    }


    /**
     * Helper function that generates the options.
     *
     * @return array
     */
    public function generateOptions()
    {
        $options = [];

        // $tids = $query->execute();

        $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
        $query->fields('ttfd', ['tid', 'langcode']); // Include the fields you need.
        $query->innerJoin('taxonomy_term__field_type_of_data', 'ttofd', 'ttfd.tid = ttofd.entity_id AND ttofd.deleted = :deleted', [':deleted' => 0]);
        //$query->condition('ttofd.field_type_of_data_target_id', 8880);
        $query->condition('ttfd.vid', 'datasets');

        // Execute the query and fetch the results.
        $results = $query->execute()->fetchAllAssoc('tid');

        //\Drupal::logger('l_p_f-org_nid-tids')->notice(serialize($results));
        foreach ($results as $result) {
            $tid = $result->tid;
            $term = Term::load($tid);

            $organization_nid = $term->get('field_orgref')->target_id;
            if (empty($organization_nid) || !is_numeric($organization_nid)) {
                // \Drupal::logger('l_p_f-org_nid-empty')->notice(serialize($organization_nid));
                continue;
            }
            $organization_term = Node::load($organization_nid);
            // \Drupal::logger('l_p_f-org_nid-test')->notice(serialize($term));
            $organization_title = $organization_term->title->value;
            $organization_field_acronym = $organization_term->field_acronym->value;
            // \Drupal::logger('l_p_f-org_nid-load')->notice(serialize($organization_field_acronym));


            // code for org ref count 

            $getdatasetProvidersCSV = getOrganizationNidsFromCSV("public://csv/datasetProviderFilter/datasetProviders.csv");


            $datasets_tids = [];

            foreach ($getdatasetProvidersCSV as $row) {

                $columns = str_getcsv($row);
                $datasets_tid = isset($columns[0]) ? $columns[0] : null;

                if ($datasets_tid !== null && $organization_nid == $columns[1]) {
                    // \Drupal::logger('l_p_f-test-logger')->notice("test logger");
                    // Add organization nid to the array.
                    $datasets_tids[] = $datasets_tid;
                }
            }
            if ($organization_field_acronym === null) {
                $options[$organization_nid] = $organization_title . ' - (' . count($datasets_tids) . ')';
            } else {
                $options[$organization_nid] = $organization_title . ' - ' . $organization_field_acronym . ' - (' . count($datasets_tids) . ')';
            }

            // $options[$tid] = $term->getName();

        }
        asort($options);
        return $options;
    }
}
