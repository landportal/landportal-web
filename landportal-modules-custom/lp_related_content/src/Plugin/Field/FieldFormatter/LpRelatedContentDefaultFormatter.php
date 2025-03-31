<?php
/**
 * @file
 * Contains \Drupal\lp_related_content\Plugin\Field\FieldType\LpRelatedContentDefaultFormatter.
 */

namespace Drupal\lp_related_content\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal;

/**
 * Plugin implementation of the 'lp_related_content_people_default' formatter.
 *
 * @FieldFormatter(
 *   id = "lp_related_content_people_default",
 *   label = @Translation("Related content"),
 *   field_types = {
 *     "lp_related_content_people"
 *   }
 * )
 */
class LpRelatedContentDefaultFormatter extends FormatterBase {

  /**
   * Define how the field type is showed.
   * 
   * Inside this method we can customize how the field is displayed inside 
   * pages.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      if($item->swid){
        $elements[$delta]['label'] = [
          '#type' => 'item',
          '#prefix' => $item->swid,
        ];
      }

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
          '#prefix' => "<div class='indicator'><a class='indicator__label' href='".$item->link."'><span class='tooltip' data-text='".$item->hover."'>".$item->label."</span></a>",
          '#suffix' => '</div>',
        ]; 
      }
    }
    return $elements;
  }
  
} // class