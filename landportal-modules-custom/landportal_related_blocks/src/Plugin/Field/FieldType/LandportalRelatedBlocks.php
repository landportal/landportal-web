<?php
/**
 * @file
 * Contains \Drupal\LandportalRelatedBlocks\Plugin\Field\FieldType\LandportalRelatedBlocks.
 */

namespace Drupal\landportal_related_blocks\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;


/**
 * Plugin implementation of the 'LandportalRelatedBlocks' field type.
 *
 * @FieldType(
 *   id = "LandportalRelatedBlocks",
 *   label = @Translation("Landportal Related Blocks"),
 *   description = @Translation("Landportal Related Blocks"),
 *   category = @Translation("Custom"),
 *   default_widget = "LandportalRelatedBlocksDefaultWidget",
 *   default_formatter = "LandportalRelatedBlocksDefaultFormatter"
 * )
 */
class LandportalRelatedBlocks extends FieldItemBase {

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
    $properties['related_blocks'] = DataDefinition::create('string')
      ->setLabel(t('Related Blocks'));
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
    $columns['related_blocks'] = [
      'type' => 'varchar',
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
  
  // public function isEmpty() {
  //   if($this->related_blocks !== NULL) {
  //     return FALSE;
  //   }
  //   return TRUE;
  // }
  
  /*This code is used when your field is unlimited type. then apply this code*/
 
  public function isEmpty() {
    $related_blocks = $this->get('related_blocks')->getValue();
    return empty($related_blocks);
  }
  

} // class

