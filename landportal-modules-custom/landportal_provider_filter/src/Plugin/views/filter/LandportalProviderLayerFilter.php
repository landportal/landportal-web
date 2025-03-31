<?php

namespace Drupal\landportal_provider_filter\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a custom filter for the "layer" taxonomy.
 *
 * @ViewsFilter("custom_views_field_layer_page")
 */
class LandportalProviderLayerFilter extends InOperator {

    /**
     * {@inheritdoc}
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL)
    {
        parent::init($view, $display, $options);
        $this->valueTitle = t('Show Node Ref Layer Title');
        $this->definition['options callback'] = array($this, 'generateOptions');
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $values_drop = $this->value;

        if (!empty($values_drop)) {
            $getLayerProvidersCSV = getOrganizationNidsFromCSV("public://csv/datasetProviderFilter/layerProviders.csv");
            $layer_tids = [];
            foreach ($getLayerProvidersCSV as $row) {
                $columns = str_getcsv($row);
                $layer_tid = isset($columns[0]) ? $columns[0] : null;
                if ($layer_tid !== null && in_array($columns[1], $values_drop)) {
                    $layer_tids[] = $layer_tid;
                }
            }

            //\Drupal::logger('Layer Main 1')->notice(serialize($layer_tids));

            if (!empty($layer_tids)) {
                $this->query->addWhere('AND', 'taxonomy_term_field_data.tid', $layer_tids, 'IN');
            }
        } else {
            parent::query();
        }
    }


    /**
     * {@inheritdoc}
     */

    public function generateOptions(){
        $options = [];

        $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
        $query->fields('ttfd', ['tid', 'langcode']);
        $query->condition('ttfd.vid', 'layer');

        $results = $query->execute()->fetchAllAssoc('tid');

        foreach ($results as $result) {
            $tid = $result->tid;
            $term = Term::load($tid);

            // Get related dataset term IDs from field_dataset
            $dataset_tids = $term->get('field_dataset')->getValue();
            $organization_nids = [];

            foreach ($dataset_tids as $item) {
                $dataset_tid = $item['target_id'];
                $dataset_term = Term::load($dataset_tid);

                $organization_nid = $dataset_term->get('field_orgref')->target_id;
                if (empty($organization_nid) || !is_numeric($organization_nid)) {
                    continue;
                }

                $organization_nids[] = $organization_nid;
            }

            if (!empty($organization_nids)) {
                $organization_nid = reset($organization_nids); // Get the first organization nid
                $organization_term = Node::load($organization_nid);
                $organization_title = $organization_term->title->value;
                $organization_field_acronym = $organization_term->field_acronym->value;

                $getLayerProvidersCSV = getOrganizationNidsFromCSV("public://csv/datasetProviderFilter/layerProviders.csv");
                $layerIds = [];
                foreach ($getLayerProvidersCSV as $row) {
                    $columns = str_getcsv($row);
                    $datasets_tid = isset($columns[0]) ? $columns[0] : null;

                    if ($datasets_tid !== null && $organization_nid == $columns[1]) {
                        $layerIds[] = $datasets_tid;
                    }
                }

                $term_layer = Term::loadMultiple($layerIds);
                $dataset_in_layers = [];

                foreach ($term_layer as $term) {
                    $dataset_values = $term->get('field_dataset')->getValue();
                    foreach ($dataset_values as $value) {
                        $dataset_in_layers[] = $value['target_id'];
                    }
                }

                // Remove duplicates and empty values
                $dataset_in_layers = count(array_unique(array_filter($dataset_in_layers)));


                if ($organization_field_acronym === null) {
                    $options[$organization_nid] = $organization_title . ' - (' . $dataset_in_layers . ')';
                } else {
                    $options[$organization_nid] = $organization_title . ' - ' . $organization_field_acronym . ' - (' . $dataset_in_layers . ')';
                }
            }
        }

        asort($options);
        return $options;
    }

}
