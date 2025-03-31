<?php
/**
 * @file
 * Contains \Drupal\Indicator\Plugin\Field\FieldType\IndicatorDefaultFormatter.
 */

namespace Drupal\lp_issue_indicator\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal;

/**
 * Plugin implementation of the 'IndicatorDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "IndicatorDefaultFormatter",
 *   label = @Translation("Indicator"),
 *   field_types = {
 *     "Indicator"
 *   }
 * )
 */
class IndicatorDefaultFormatter extends FormatterBase {

  /**
   * Define how the field type is showed.
   * 
   * Inside this method we can customize how the field is displayed inside 
   * pages.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      if ($item->label) {
        $elements[$delta]['label'] = [
          '#type' => 'item',
        ];
      }
      if ($item->link) {
        $elements[$delta]['link'] = [
          '#type' => 'item',
        ];
      }
      if ($item->hover !== NULL) {
        if($item->link == ""){
          $item->link = "#";
        }
        $elements[$delta]['hover'] = [
          '#type' => 'item',
          '#prefix' => '<div class="indicator"><a class="indicator__label" href="'.$item->link.'"><span class="tooltip" data-text="'.$item->hover.'">'.$item->label.'</span></a>',
          '#suffix' => '</div>',
        ]; 
      }
    }
    return $elements;
  }
  
} // class