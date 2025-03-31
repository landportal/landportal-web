<?php

namespace Drupal\download_dataset\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldFilteredMarkup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'DownloadDatasetDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "DownloadDatasetDefaultFormatter",
 *   label = @Translation("Download Dataset"),
 *   field_types = {
 *     "download_dataset_type"
 *   }
 * )
 */
class DownloadDatasetDefaultFormatter extends FormatterBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {

      $parentEntity = $items->getParent()->getEntity();
      $field_download_dataset = $parentEntity->get('field_download_dataset')->download_dataset;
            
      // Format the field value for display
      // $elements[$delta] = [
      //   '#markup' => "Selected value: (" . $field_download_dataset . " )",
      // ];
    }

    if($field_download_dataset == 'yes'){ // if check, Are dataset Download?

    $download_coda =  '<div id="block-landbook-view-coda-lbvc-download-cat"><div id="block-landbook-view-coda-lbvc-download-cat-wrapper"><p>Download this Dataset data.</p></div></div>';

      $elements[0]['dataset_download'] = [
        '#type' => 'item',
        '#markup' => $download_coda,
            '#attached' => [
              'library' => ['landbook_view_coda/lbvis.dl'],
              'library' => ['view_coda_extra/my_library'],
            //   'drupalSettings' => [
            //     'my_library' => [
            //       // 'indicator_download_id' => $indicator_sparql_field_id_for_map,
            //     ],
            //   'library' => ['view_coda_extra/lp-related-content-edit'],
            // ],            
          ]
         ];
       }// End if check, Are indicator Download?


    return $elements;
  }

}
