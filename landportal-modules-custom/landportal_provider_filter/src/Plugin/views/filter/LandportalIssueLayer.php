<?php

namespace Drupal\landportal_provider_filter\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a custom filter for theme indicator counts.
 *
 * @ViewsFilter("custom_views_issue_layer_field_related_themes")
 */
class LandportalIssueLayer extends InOperator {

    /**
     * {@inheritdoc}
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL){
        //\Drupal::logger('issueCsv-init-check')->notice('Init method called.');
        parent::init($view, $display, $options);
        $this->valueTitle = t('Theme Layer Count');
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
                $taxonomy_query = \Drupal::entityQuery('taxonomy_term');
                $taxonomy_query->condition('vid', "layer");
                $taxonomy_query->condition('field_related_themes', $value);
                $theme_tids = $taxonomy_query->execute();

                //\Drupal::logger('issueCsv-selected_theme theme_tids')->notice('Selected theme name: ' . serialize(count($theme_tids)));

            }

            // Check if theme_tids is not empty and execute the query
            if (!empty($theme_tids)) {
              //  \Drupal::logger('issueCsv-query1asd')->notice('hi');
                $this->query->addWhere('AND', 'taxonomy_term_field_data.tid', $theme_tids, 'IN');
            }
        } else {
            parent::query();
        }
    }



    // Inside the generateOptions method
    public function generateOptions()
    {
        $options = [];

        $theme_indicator_counts = $this->loadThemeIndicatorCountsFromCSV();
        
        //\Drupal::logger('issueCsv-generateOptions')->notice('Loaded theme_indicator_counts: ' . serialize($theme_indicator_counts));

        foreach ($theme_indicator_counts as $theme_display => $theme_tid) {
            $options[$theme_tid] = $theme_display;
        }

        return $options;
    }

    // Inside the loadThemeIndicatorCountsFromCSV method
    protected function loadThemeIndicatorCountsFromCSV()
    {
        $csv_file_path = "public://csv/datasetProviderFilter/theme_layer_counts.csv";
        $theme_indicator_counts = [];

        if (file_exists($csv_file_path)) {
            $file_handle = fopen($csv_file_path, 'r');

            if ($file_handle !== FALSE) {
                $firstRow = true;

                while (($data = fgetcsv($file_handle, 0, ',')) !== FALSE) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue;
                    }

                    if (!empty($data[0]) && !empty($data[1])) {
                        $theme_tid = trim($data[0]);
                        $count = trim($data[1]);

                        $term = Term::load($theme_tid);
                        
                        if ($term) {
                            $theme_name = $term->getName();
                            $theme_display = $theme_name . ' - ' . $count;
                            $theme_indicator_counts[$theme_display] = $theme_tid;
                            //\Drupal::logger('issueCsv-loadCSV')->notice('Loaded term: ' . $theme_name . ' with count: ' . $count);
                        }
                    }
                }
                fclose($file_handle);
            }
        }

        return $theme_indicator_counts;
    }


}
