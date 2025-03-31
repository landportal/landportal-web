<?php

namespace Drupal\landportal_org_filter\Plugin\views\filter;

use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\views\ViewExecutable;

/**
 * Provides a custom filter for a specific view.
 *
 * @ViewsFilter("custom_views_org_types")
 */
class CountOrgFilter extends InOperator {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t('Show Node Ref Title');
    $this->definition['options callback'] = array($this, 'generateOptions');
  }

  /**
   * Override the query so that no filtering takes place if the user doesn't
   * select any options.
   */
    public function query() {
      $values_drop = $this->value;
    $nids = getNidsBasedOnYourLogicTerms($values_drop);

    if (!empty($nids)) {
      // Use the selected NIDs to filter the view results.
      $this->query->addWhere('AND', 'node_field_data.nid', $nids, 'IN');
    }
    else {
      // No options selected, do not filter the results.
      parent::query();
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
 *
 * @return array
 */
public function generateOptions() {
  $options = optionsForGenerateOptions();
  return $options;
}


}
