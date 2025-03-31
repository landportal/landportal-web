<?php

namespace Drupal\landportal_country_narrative\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\text\Plugin\Field\FieldType\TextItemBase;

/**
 * Defines the 'additional_information' field type.
 *
 * @FieldType(
 *   id = "additional_information",
 *   label = @Translation("Additional information"),
 *   category = @Translation("Landportal"),
 *   default_widget = "additional_information",
 *   default_formatter = "additional_information_default"
 * )
 */
class AdditionalInformationItem extends TextItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {

    $title = $this->get('title')->getValue();
    $description = $this->get('description')->getValue();
    return empty($title) && empty($description);
  }
  /*
  public function isEmpty() {
    if ($this->title !== NULL) {
      return FALSE;
    }
    elseif ($this->description !== NULL) {
      return FALSE;
    }
    elseif ($this->format !== NULL) {
      return FALSE;
    }
    return TRUE;
  }
  */

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['title'] = DataDefinition::create('string')
      ->setLabel(t('Title'));
    $properties['description'] = DataDefinition::create('string')
      ->setLabel(t('Description'));
    $properties['format'] = DataDefinition::create('filter_format')
      ->setLabel(t('Text format'));
    $properties['processed'] = DataDefinition::create('string')
      ->setLabel(t('Processed text'))
      ->setDescription(t('The text with the text format applied.'))
      ->setComputed(TRUE)
      ->setClass('\Drupal\text\TextProcessed')
      ->setSetting('text source', 'description')
      ->setInternal(FALSE);

    $properties['value'] = $properties['processed'];
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $columns = [
      'title' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'description' => [
        'type' => 'text',
        'size' => 'big',
      ],
      'format' => [
        'type' => 'varchar_ascii',
        'length' => 255,
      ],
    ];
    $schema = [
      'columns' => $columns,
      'indexes' => [
        'format' => ['format'],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $random = new Random();
    $values['title'] = $random->word(mt_rand(1, 255));
    $values['description'] = $random->paragraphs(5);
    $values['format'] = filter_fallback_format();

    return $values;
  }

}
