<?php
/**
 * @file
 * Contains \Drupal\ContentSelection\Plugin\Field\FieldType\ContentSelection.
 */

namespace Drupal\landportal_content_selection\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;

/**
 * Plugin implementation of the 'ContentSelection' field type.
 *
 * @FieldType(
 *   id = "ContentSelection",
 *   label = @Translation("Content Selection"),
 *   description = @Translation("Content Selection."),
 *   category = @Translation("Custom"),
 *   default_widget = "ContentSelectionDefaultWidget",
 *   default_formatter = "ContentSelectionDefaultFormatter"
 * )
 */
class ContentSelection extends FieldItemBase {

  /**
   * Field type properties definition.
   * 
   * Inside this method we defines all the fields (properties) that our 
   * custom field type will have.
   * 
   * Here there is a list of allowed property types: https://goo.gl/sIBBgO
   */
  public static function propertyDefinitions(StorageDefinition $storage) {

    $properties = [];

    $properties['label'] = DataDefinition::create('string')
      ->setLabel(t('Label'));

    $properties['link'] = DataDefinition::create('string')
      ->setLabel(t('Link'));

    return $properties;
  }

  /**
   * Field type schema definition.
   * 
   * Inside this method we defines the database schema used to store data for 
   * our field type.
   * 
   * Here there is a list of allowed column types: https://goo.gl/YY3G7s
   */
  public static function schema(StorageDefinition $storage) {

    $columns = [];
    $columns['label'] = [
      'type' => 'char',
      'length' => 255,
    ];
     $columns['link'] = [
      'type' => 'char',
      'length' => 255,
    ];

    return [
      'columns' => $columns,
      'indexes' => [],
    ];
  }

  /**
   * Define when the field type is empty. 
   * 
   * This method is important and used internally by Drupal. Take a moment
   * to define when the field fype must be considered empty.
   */
  public function isEmpty() {

    $link = $this->get('link')->getValue();
    $label = $this->get('label')->getValue();
    return empty($link) && empty($label);

    /*
    if($this->link !== NULL) {
      return FALSE;
    }
    elseif($this->label !== NULL){
      return FALSE;
    }

    return TRUE;
    */
  }
  
} // class