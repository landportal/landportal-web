<?php

namespace Drupal\landportal_content_selection\Feeds\Target;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\feeds\FieldTargetDefinition;
use Drupal\feeds\Plugin\Type\Target\FieldTargetBase;

/**
 * Defines a field mapper for Country Field.
 *
 * @FeedsTarget(
 *   id = "LandportalContentSelection",
 *   field_types = {"ContentSelection"}
 * )
 */
class LandportalContentSelection extends FieldTargetBase {

  /**
   * {@inheritdoc}
   */
  protected static function prepareTarget(FieldDefinitionInterface $field_definition) {
    $definition = FieldTargetDefinition::createFromFieldDefinition($field_definition)
      ->addProperty('label')
      ->addProperty('link');

    return $definition;
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareValue($delta, array &$values) {
    $value = mb_strtoupper(trim($values['label']));
    $values['label'] = $value;
    $link = mb_strtoupper(trim($values['link']));
    $values['link'] = $link;
  }

}
