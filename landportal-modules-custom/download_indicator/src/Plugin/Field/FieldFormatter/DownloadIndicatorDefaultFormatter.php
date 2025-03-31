<?php

namespace Drupal\download_indicator\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldFilteredMarkup;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'DownloadIndicatorDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "DownloadIndicatorDefaultFormatter",
 *   label = @Translation("Download Indicator"),
 *   field_types = {
 *     "download_indicator_type"
 *   }
 * )
 */
class DownloadIndicatorDefaultFormatter extends FormatterBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {

      $parentEntity = $items->getParent()->getEntity();
      $field_indicator = $parentEntity->get('field_download_indicator')->download_indicator;

      // Format the field value for display
      // $elements[$delta] = [
      //   '#markup' => "Selected value: (" . $field_indicator . " )",
      // ];
    }

    if($field_indicator == 'yes'){

    $download_coda =  '<div id="block-landbook-view-coda-lbvc-download-ind"><div id="block-landbook-view-coda-lbvc-download-ind-wrapper"><p>Download this Indicator data.</p></div></div>';

      $elements[0]['indicator_download'] = [
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
       }


    return $elements;
  }

}
