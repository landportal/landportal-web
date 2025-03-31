<?php
/**
 * @file
 * Contains \Drupal\LandportalRelatedBlocksDefaultWidget\Plugin\Field\FieldType\LandportalRelatedBlocksDefaultWidget.
 */
namespace Drupal\landportal_related_blocks\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'LandportalRelatedBlocksDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "LandportalRelatedBlocksDefaultWidget",
 *   label = @Translation("Landportal Related Blocks"),
 *   field_types = {
 *     "LandportalRelatedBlocks"
 *   }
 * )
 */
class LandportalRelatedBlocksDefaultWidget extends WidgetBase {

  /**
   * Define the form for the field type.
   * 
   * Inside this method we can define the form used to edit the field type.
   * 
   * Here there is a list of allowed element types: https://goo.gl/XVd4tA
   */
  public function formElement(FieldItemListInterface $items,$delta, Array $element, Array &$form, FormStateInterface $formState) {
    
    $element['related_blocks'] = [
      '#title' => $this->t('Related Blocks'),
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'landportal_related_blocks.viewblocks_autocomplete',
      '#default_value' => isset($items[$delta]->related_blocks) ? $items[$delta]->related_blocks : '',
      '#maxlength' => 255,
    ];

    return $element;
  }
} // class