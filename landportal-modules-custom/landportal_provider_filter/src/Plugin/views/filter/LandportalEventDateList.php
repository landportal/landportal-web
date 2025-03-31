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
 * Provides a custom filter for event dates.
 *
 * @ViewsFilter("custom_views_event_date")
 */
class LandportalEventDateList extends InOperator {

    /**
     * {@inheritdoc}
     */
    public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL){
        parent::init($view, $display, $options);
        $this->valueTitle = t('Event Date');
        $this->definition['options callback'] = array($this, 'generateOptions');
    }

    /**
     * Override the query to filter by event date.
     */
    public function query(){
        $values_drop = $this->value;
        if (!empty($values_drop)) {
            $node_ids = [];
            foreach ($values_drop as $value) {
                $node_query = \Drupal::entityQuery('node');
                $node_query->condition('type', 'event');
                $node_query->condition('status', 1);
                $node_query->condition('field_date', $value, 'CONTAINS');
                $node_ids = array_merge($node_ids, $node_query->execute());
            }

            // Check if node_ids is not empty and execute the query
            if (!empty($node_ids)) {
                $this->query->addWhere('AND', 'node_field_data.nid', $node_ids, 'IN');
            } else {
                // If no node IDs are found for selected options, add a condition to return an empty result set
                $this->query->addWhere('AND', 'node_field_data.nid', NULL, 'IN');
            }
        } else {
            parent::query();
        }
    }

    /**
     * Generate options for the filter based on a range of dates.
     */
    public function generateOptions() {
        $options = [];

        // Get current timestamp
        $current_timestamp = time();

        // Calculate date range
        $start_date = strtotime('-7 years', $current_timestamp);
        $end_date = strtotime('+1 year', $current_timestamp);

        // Generate date options within the date range
        while ($start_date <= $end_date) {
            $date_option = date('Y', $start_date);
            // Add date option to options array
            $options[$date_option] = $date_option;
            // Move to next day
            $start_date = strtotime('+1 day', $start_date);
        }

        // Remove options that are 6 years ago
        $six_years_ago = strtotime('-7 years', $current_timestamp);
        $six_years_ago_date = date('Y', $six_years_ago);
        unset($options[$six_years_ago_date]);

        // Add option for one year ahead
        $one_year_ahead = strtotime('+1 year', $current_timestamp);
        $one_year_ahead_date = date('Y', $one_year_ahead);
        $options[$one_year_ahead_date] = $one_year_ahead_date;

        return $options;
    }

}
