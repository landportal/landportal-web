<?php

namespace Drupal\download_dataset\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'download_dataset' field type.
 *
 * @FieldType(
 *   id = "download_dataset_type",
 *   label = @Translation("Download Dataset"),
 *   module = "download_dataset",
 *   description = @Translation("Download Dataset for download data."),
 *   default_widget = "DownloadDatasetDefaultWidget",
 *   default_formatter = "DownloadDatasetDefaultFormatter"
 * )
 */
class DownloadDataset extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $download_dataset) {
    $columns = [];
    $columns['download_dataset'] = [
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
 * {@inheritdoc}
 */
public static function propertyDefinitions(FieldStorageDefinitionInterface $download_dataset) {
  $properties = [];
  $properties['download_dataset'] = DataDefinition::create('string')
    ->setLabel(t('Download Dataset'));

  return $properties;
}


  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
     if($this->download_dataset !== NULL) {
      return FALSE;
    }
    return True;
  }

}
