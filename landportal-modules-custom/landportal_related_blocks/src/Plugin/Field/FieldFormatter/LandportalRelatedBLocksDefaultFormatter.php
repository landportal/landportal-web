<?php

namespace Drupal\landportal_related_blocks\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\views\Views;

/**
 * Plugin implementation of the 'LandportalRelatedBlocksDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "LandportalRelatedBlocksDefaultFormatter",
 *   label = @Translation("LandportalRelatedBlocks"),
 *   field_types = {
 *     "LandportalRelatedBlocks"
 *   }
 * )
 */
class LandportalRelatedBLocksDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      if ($item->related_blocks) {

        // Load the view by ID (machine name).
        $view_id = $item->related_blocks;
        $view_arr = explode(",", $view_id);

        foreach($view_arr as $view_id_single){
          $view_id_exp = explode(":", $view_id_single);

          $view_name = $view_id_exp[0];
          $view_display = $view_id_exp[1];

        // Load the view by machine name.
        $view = Views::getView($view_name);
      
         $display_ids = [$view_display];
         // \Drupal::logger('Fida1')->notice(serialize($display_ids));
          // Loop through all displays.
        
          foreach ($display_ids as $display_id) {
            // Set the display for execution.
            $view->setDisplay($display_id);

            // Execute the view and get the render array.
            $view->preExecute();
            $view->execute();
            //$output = $view->render();
            $render_array = $view->buildRenderable($display_id);

            // Add the rendered view to the elements.
            $elements[$delta]['related_blocks'][$display_id] = $render_array;
            //$elements[] = $output;

          }
          
        }
      }
    }

    return $elements;
  }

}
