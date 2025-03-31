<?php
/**
 * @file
 * Contains \Drupal\Indicator\Plugin\Field\FieldType\Indicator.
 */

namespace Drupal\lp_issue_indicator\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;

/**
 * Plugin implementation of the 'Indicator' field type.
 *
 * @FieldType(
 *   id = "Indicator",
 *   label = @Translation("Indicator"),
 *   description = @Translation("Stores an Indicator."),
 *   category = @Translation("Custom"),
 *   default_widget = "IndicatorDefaultWidget",
 *   default_formatter = "IndicatorDefaultFormatter"
 * )
 */
class Indicator extends FieldItemBase {

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
    $properties['hover'] = DataDefinition::create('string')
      ->setLabel(t('Hover'));
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
    $columns['hover'] = [
      'type' => 'text',
      'size' => 'big',
      'not null' => FALSE,
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

    // $isEmpty = empty($this->get('label')->getValue());
    // $isEmpty = empty($this->get('hover')->getValue());
    // $isEmpty = empty($this->get('digits')->getValue());
    // $isEmpty = empty($this->get('link')->getValue());

    // $va = $this->hover;
    // print_r($va);

    if($this->link !== NULL) {
      return FALSE;
    }
    elseif($this->label !== NULL){
      return FALSE;
    }
    elseif($this->hover !== NULL){
      return FALSE;
    }

    return TRUE;
  }

} // class