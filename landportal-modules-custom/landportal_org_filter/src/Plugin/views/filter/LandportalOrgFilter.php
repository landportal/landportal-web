<?php

namespace Drupal\landportal_org_filter\Plugin\views\filter;


use Drupal\Core\Database\Query\SelectInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\filter\InOperator;


/**
 * Provides a custom filter for a specific view.
 *
 * @ViewsFilter("custom_views_org_titles")
 */
class LandportalOrgFilter extends InOperator {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->valueTitle = t("Select 'YES' to apply this filter." );
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $nids = readNidsFromCsvFile();

    if (!empty($nids)) {
      // Use the selected NIDs to filter the view results.
      $this->query->addWhere('AND', 'node_field_data.nid', $nids, 'IN');
    }
    else {
      // No options selected, do not filter the results.
      parent::query();
    }
  }





}
