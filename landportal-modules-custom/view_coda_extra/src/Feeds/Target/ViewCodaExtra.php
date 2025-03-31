<?php

namespace Drupal\view_coda_extra\Feeds\Target;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\feeds\FieldTargetDefinition;
use Drupal\feeds\Plugin\Type\Target\FieldTargetBase;

/**
 * Defines a field mapper for View Coda Extra.
 *
 * @FeedsTarget(
 *   id = "ViewCodaExtra",
 *   field_types = {"ViewCodaExtra"}
 * )
 */
class ViewCodaExtra extends FieldTargetBase {

  /**
   * {@inheritdoc}
   */
  protected static function prepareTarget(FieldDefinitionInterface $field_definition) {
    $definition = FieldTargetDefinition::createFromFieldDefinition($field_definition)
      ->addProperty('indicator_title')
      ->addProperty('indicator_description')          
      ->addProperty('indicator')
      ->addProperty('textarea')
      ->addProperty('visualization');

    return $definition;
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareValue($delta, array &$values) {

    $indicator_title = trim($values['indicator_title']);
    $values['indicator_title'] = $indicator_title;

    $indicator_description = trim($values['indicator_description']);
    $values['indicator_description'] = $indicator_description;    

    $indicator = trim($values['indicator']);
    $values['indicator'] = $indicator;

    $textarea = trim($values['textarea']);
    $values['textarea'] = $textarea;

    $visualization = trim($values['visualization']);
    $values['visualization'] = $visualization;

    /*
    $indicator = mb_strtoupper(trim($values['indicator']));
    $values['indicator'] = $indicator;

    $year = mb_strtoupper(trim($values['year']));
    $values['year'] = $year;

    $textarea = mb_strtoupper(trim($values['textarea']));
    $values['textarea'] = $textarea;

    $coda = mb_strtoupper(trim($values['coda']));
    $values['coda'] = $coda;
    */

  }

}
