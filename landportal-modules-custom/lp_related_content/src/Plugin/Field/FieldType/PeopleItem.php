<?php

namespace Drupal\lp_related_content\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a field type to store Related content.
 *
 * @FieldType(
 *     id="lp_related_content_people",
 *     label=@Translation("Related content"),
 *     module="lp_related_content",
 *     description=@Translation("Autocomplete field to search for Related content through swapi.dev."),
 *     default_widget="lp_related_content_people_default",
 *     default_formatter="lp_related_content_people_default"
 * )
 */
class PeopleItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return empty($this->swid) || empty($this->fullname);
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];

    $properties['swid'] = DataDefinition::create('integer')
      ->setLabel(t('Related content Identifier')->__toString());

    $properties['fullname'] = DataDefinition::create('string')
      ->setLabel(t('Fullname')->__toString());

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      // Columns contains the values that the field will store.
      'columns' => [
        'swid' => [
          'type' => 'int',
          'size' => 'big',
          'not null' => FALSE,
        ],
        'fullname' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ],
      ],
    ];
  }

}
