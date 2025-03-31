<?php

/**
 * @file
 * Definition of Drupal\lp_project_status\Plugin\views\filter\ProjectType.
 */

namespace Drupal\lp_project_status\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;

/**
 * Filters by given list of node title options.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("lp_project_status_type_of_project")
 */
class ProjectType extends InOperator {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Project status');
    $this->definition['options callback'] = array($this, 'generateOptions');
  }

  /**
   * Override the query so that no filtering takes place if the user doesn't
   * select any options.
   */
  public function query() {
    if (!empty($this->value)) {
      if(is_array($this->value)){
        if(count($this->value) > 0){
          $this->ensureMyTable();

          /** @var \Drupal\views\Plugin\views\query\Sql $query */
          $query = $this->query;

          $definition = [
            'table' => 'node__field_project_duration',
            'field' => 'entity_id',
            'left_table' => 'node_field_data',
            'left_field' => 'nid',
          ];

          $join = \Drupal::service('plugin.manager.views.join')
            ->createInstance('standard', $definition);
          $query->addRelationship('node__field_project_duration', $join, 'node_field_data');
    
          $table = "node__field_project_duration";
          $now_date = date("Y-m-d", date('U')) . "T" . date("h:i:s", date('U'));

          if($this->value[0] == "Active"){
            $query->addWhere($this->options['group'], $table . '.field_project_duration_value', $now_date, '<=');
            $query->addWhere($this->options['group'], $table . '.field_project_duration_end_value', $now_date, '>=');
          }
          if($this->value[0] == "Planned"){
            $query->addWhere($this->options['group'], $table . '.field_project_duration_value', $now_date, '>');
            //$query->addWhere($this->options['group'], $table . '.field_project_duration_end_value', $now_date, '>=');
          }          
          if($this->value[0] == "Completed"){
            $query->addWhere($this->options['group'], $table . '.field_project_duration_value', $now_date, '<=');
            $query->addWhere($this->options['group'], $table . '.field_project_duration_end_value', $now_date, '<');
          }          
        } 
      }
    }
  }

  /**
   * Skip validation if no options have been chosen so we can use it as a
   * non-filter.
   */
  public function validate() {
    if (!empty($this->value)) {
      parent::validate();
    }
  }

  /**
   * Helper function that generates the options.
   * @return array
   */
  public function generateOptions() {
    // Array keys are used to compare with the table field values.
    return array(
      'Active' => 'Active',
      'Planned' => 'Planned',
      'Completed' => 'Completed',
    );
  }

}