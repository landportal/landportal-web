<?php
/**
 * @file
 * Contains \Drupal\field_milestone\Plugin\Field\FieldType\MileStone.
 */

namespace Drupal\field_milestone\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;

/**
 * Plugin implementation of the 'Milestone' field type.
 *
 * @FieldType(
 *   id = "Milestone",
 *   label = @Translation("Milestone"),
 *   description = @Translation("Milestone."),
 *   category = @Translation("Custom"),
 *   default_widget = "MilestoneWidget",
 *   default_formatter = "MilestoneFormatter"
 * )
 */
class MileStone extends FieldItemBase
{

  /**
   * Field type properties definition.
   *
   * Inside this method we defines all the fields (properties) that our
   * custom field type will have.
   *
   * Here there is a list of allowed property types: https://goo.gl/sIBBgO
   */
    public static function propertyDefinitions(StorageDefinition $storage)
    {

        $properties = [];

        $properties['title'] = DataDefinition::create('string')
        ->setLabel(t('Title'));
        $properties['description'] = DataDefinition::create('string')
        ->setLabel(t('Description'));
        $properties['start_date'] = DataDefinition::create('string')
        ->setLabel(t('Start Date'));
        $properties['end_date'] = DataDefinition::create('string')
        ->setLabel(t('End Date'));

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
    public static function schema(StorageDefinition $storage)
    {

        $columns = [];
        $columns['title'] = [
            'type' => 'varchar',
            'length' => 255,
        ];
        $columns['description'] = [
            'type' => 'text',
            'size' => 'big',
            'not null' => FALSE,
        ];
        $columns['start_date'] = [
          'type' => 'date',
          'mysql_type' => 'DATE',
          'not null' => FALSE, // Allow NULL values.
        ];
        $columns['end_date'] = [
          'type' => 'date',
          'mysql_type' => 'DATE',
          'not null' => FALSE, // Allow NULL values.
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
    $title = $this->get('title')->getValue();
    $description = $this->get('description')->getValue();
    $start_date = $this->get('start_date')->getValue();
    $end_date = $this->get('end_date')->getValue();

    return empty($title) && empty($description) && empty($start_date) && empty($end_date);
  }

  public function preSave() {
    // Validate and format the date values.
    if (!empty($this->start_date)) {
      $this->start_date = date('Y-m-d', strtotime($this->start_date));
    } else {
      $this->start_date = NULL;
    }

    if (!empty($this->end_date)) {
      $this->end_date = date('Y-m-d', strtotime($this->end_date));
    } else {
      $this->end_date = NULL;
    }
  }

} // class
