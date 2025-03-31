<?php

namespace Drupal\download_indicator\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'download_indicator' field type.
 *
 * @FieldType(
 *   id = "download_indicator_type",
 *   label = @Translation("Download Indicator"),
 *   module = "download_indicator",
 *   description = @Translation("Download Indicator for download data."),
 *   default_widget = "DownloadIndicatorDefaultWidget",
 *   default_formatter = "DownloadIndicatorDefaultFormatter"
 * )
 */
class DownloadIndicator extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $download_indicator) {
    $columns = [];
    $columns['download_indicator'] = [
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
public static function propertyDefinitions(FieldStorageDefinitionInterface $download_indicator) {
  $properties = [];
  $properties['download_indicator'] = DataDefinition::create('string')
    ->setLabel(t('Download Indicator'));

  return $properties;
}


  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
     if($this->download_indicator !== NULL) {
      return FALSE;
    }
    return True;
  }

}
