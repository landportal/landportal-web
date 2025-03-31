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
 * @ViewsFilter("custom_views_field_dataset")
 */
class LandportalProviderFilter extends InOperator {

    /**
     * {@inheritdoc}
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL)
    {
        parent::init($view, $display, $options);
        $this->valueTitle = t('Show Node Ref Title');
        $this->definition['options callback'] = array($this, 'generateOptions');
    }

    /**
     * Override the query so that no filtering takes place if the user doesn't
     * select any options.
     */
    public function query()
    {
        $values_drop = $this->value;
        // $nids = getNidsBasedOnYourLogicTerms($values_drop);
        // \Drupal::logger('l_p_f-values')->notice(serialize($values_drop));

        if (!empty($values_drop)) {
            $getdatasetProvidersCSV = getOrganizationNidsFromCSV("public://csv/datasetProviderFilter/datasetProviders.csv");
            $getIndicatorsListWithDatasetCSV = getOrganizationNidsFromCSV("public://csv/datasetProviderFilter/indicatorsListWithDataset.csv");
            // \Drupal::logger('l_p_f-getNids')->notice(serialize($getIndicatorsListWithDatasetCSV));


            $datasets_tids = [];
            $organization_nids = [];
            foreach ($getdatasetProvidersCSV as $row) {
                // Split the row into columns.
                $columns = str_getcsv($row);

                // Get the organization nid from the first column.
                $datasets_tid = isset($columns[0]) ? $columns[0] : null;

                // Get the organization nid from the second column.
                // $organization_nid = $columns[1];

                if ($datasets_tid !== null && in_array($columns[1], $values_drop)) {
                    // \Drupal::logger('l_p_f-test-logger')->notice("test logger");
                    // Add organization nid to the array.
                    $datasets_tids[] = $datasets_tid;

                    // Add organization nid to the array.
                    // $organization_nids[] = $organization_nid;
                }
            }
            // \Drupal::logger('l_p_f-datasets_tids')->notice(serialize($datasets_tids));
            // \Drupal::logger('l_p_f-organization_nids')->notice(serialize($organization_nids));


            foreach ($getIndicatorsListWithDatasetCSV as $row) {
                // Split the row into columns.
                $columns = str_getcsv($row);

                $indicator_tid = isset($columns[0]) ? $columns[0] : null;
                $datasetTerm_tid = isset($columns[1]) ? $columns[1] : null;

                if ($indicator_tid !== null && $datasetTerm_tid !== null) {
                    if (in_array($datasetTerm_tid, $datasets_tids)) {
                        $listOfIndicators[] = $indicator_tid;
                    }
                }
            }
             //\Drupal::logger('l_p_f-listOfIndicators')->notice(serialize($listOfIndicators));

            $this->query->addWhere('AND', 'taxonomy_term_field_data.tid', $listOfIndicators, 'IN');
        } else {
            // No options selected, do not filter the results.
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
        $query->condition('ttofd.field_type_of_data_target_id', 8880);
        $query->condition('ttfd.vid', 'datasets');

        // Execute the query and fetch the results.
        $results = $query->execute()->fetchAllAssoc('tid');

        // \Drupal::logger('l_p_f-org_nid-tids')->notice(serialize($tids));
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
            // \Drupal::logger('l_p_f-13300')->notice(serialize(count($datasets_tids)));



            // if ($organization_field_acronym == '') {
            // \Drupal::logger('l_p_f-acronym')->notice(serialize($organization_field_acronym));
            // }
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
