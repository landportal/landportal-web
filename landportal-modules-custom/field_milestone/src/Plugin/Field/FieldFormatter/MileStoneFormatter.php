<?php
/**
 * @file
 * Contains \Drupal\field_milestone\Plugin\Field\FieldType\Milestone.
 */

namespace Drupal\field_milestone\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal;

/**
 * Plugin implementation of the 'MileStone' formatter.
 *
 * @FieldFormatter(
 *   id = "MilestoneFormatter",
 *   label = @Translation("MileStone"),
 *   field_types = {
 *     "MileStone"
 *   }
 * )
 */
class MileStoneFormatter extends FormatterBase
{

  /**
   * Define how the field type is showed.
   *
   * Inside this method we can customize how the field is displayed inside
   * pages.
   */
    public function viewElements(FieldItemListInterface $items, $langcode)
    {
        $elements = [];
        foreach ($items as $delta => $item) {
            $date_range = [];
            $date_range[0] = '';
            $date_range[1] = '';
            $date_range[2] = '';
            if ($item->title) {
                $elements[$delta]['title'] = [
                '#type' => 'item',
                '#markup'=> '<h4 class="key-date-title">'.$item->title.'</h4>',
                ];
            }
            if ($item->start_date) {
                // Format the date without the time portion.
                $date_range[0] = date('j F Y', strtotime($item->start_date));
            }            
            if ($item->end_date) {
                // Format the date without the time portion.
                $date_range[1] = 'to';
                $date_range[2] = date('j F Y', strtotime($item->end_date));
            }
            if($date_range[0] == $date_range[2]){
                $elements[$delta]['date'] = [
                    '#type' => 'item',
                    '#markup'=> '<div class="key-date-range"><span>'.$date_range[0].'</span></div>',
                ];
            }
            elseif($date_range[0] == ''){
                $elements[$delta]['date'] = [
                    '#type' => 'item',
                    '#markup'=> '<div class="key-date-range"><span>'.$date_range[2].'</span></div>',
                ];
            }
            else{
                $elements[$delta]['date'] = [
                    '#type' => 'item',
                    '#markup'=> '<div class="key-date-range"><span>'.$date_range[0].' '.$date_range[1].' '.$date_range[2].'</span></div>',
                ];
            }
            if ($item->description) {
                $elements[$delta]['description'] = [
                '#type' => 'item',
                '#markup'=> '<div class="key-date-description">'.$item->description.'</div>',
                ];
            }
        }
        return $elements;
    }
} // class
