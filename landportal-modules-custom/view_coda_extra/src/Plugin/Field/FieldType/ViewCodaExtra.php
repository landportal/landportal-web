<?php
/**
 * @file
 * Contains \Drupal\ViewCodaExtra\Plugin\Field\FieldType\ViewCodaExtra.
 */

namespace Drupal\view_coda_extra\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;


/**
 * Plugin implementation of the 'ViewCodaExtra' field type.
 *
 * @FieldType(
 *   id = "ViewCodaExtra",
 *   label = @Translation("View Coda Extra"),
 *   description = @Translation("View Coda Extra."),
 *   category = @Translation("Custom"),
 *   default_widget = "ViewCodaExtraDefaultWidget",
 *   default_formatter = "ViewCodaExtraDefaultFormatter"
 * )
 */
class ViewCodaExtra extends FieldItemBase {

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

    $properties['indicator_title'] = DataDefinition::create('string')
      ->setLabel(t('indicator_title'));
    $properties['indicator_description'] = DataDefinition::create('string')
      ->setLabel(t('indicator_description'));      
    $properties['indicator'] = DataDefinition::create('string')
      ->setLabel(t('Indicator'));
    $properties['visualization'] = DataDefinition::create('string')
      ->setLabel(t('Visualization'));      
    $properties['textarea'] = DataDefinition::create('string')
      ->setLabel(t('Textarea'));


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
    $columns['indicator'] = [
      'type' => 'text',
      'size' => 'big',
    ];
    $columns['indicator_title'] = [
      'type' => 'text',
      'size' => 'big',
      'not null' => FALSE,
    ];
    $columns['indicator_description'] = [
      'type' => 'text',
      'size' => 'big',
      'not null' => FALSE,
    ];  
    $columns['visualization'] = [
      'type' => 'text',
      'size' => 'big',
      'not null' => FALSE,
    ];        
    $columns['textarea'] = [
      'type' => 'text',
      'size' => 'big',
      'not null' => FALSE,
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
   * to define when the field type must be considered empty.
   */
  public function isEmpty() {

    $indicator = $this->get('indicator')->getValue();
    $indicator_title = $this->get('indicator_title')->getValue();
    $indicator_description = $this->get('indicator_description')->getValue();
    //$visualization = $this->get('visualization')->getValue();
    $textarea = $this->get('textarea')->getValue();

    return empty($indicator) && empty($indicator_title) && empty($indicator_description) && empty($textarea);
  }

} // class

