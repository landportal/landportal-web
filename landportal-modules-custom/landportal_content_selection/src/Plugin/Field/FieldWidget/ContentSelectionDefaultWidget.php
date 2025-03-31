<?php
/**
 * @file
 * Contains \Drupal\ContentSelection\Plugin\Field\FieldType\ContentSelectionDefaultWidget.
 */
namespace Drupal\landportal_content_selection\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ContentSelectionDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "ContentSelectionDefaultWidget",
 *   label = @Translation("ContentSelection select"),
 *   field_types = {
 *     "ContentSelection"
 *   }
 * )
 */
class ContentSelectionDefaultWidget extends WidgetBase {

  /**
   * Define the form for the field type.
   * 
   * Inside this method we can define the form used to edit the field type.
   * 
   * Here there is a list of allowed element types: https://goo.gl/XVd4tA
   */
  public function formElement(FieldItemListInterface $items,$delta, Array $element, Array &$form, FormStateInterface $formState) {

    $element['label'] = [
      '#title' => $this->t('Organization'),
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'landportal_content_selection.autocomplete',
      '#default_value' => isset($items[$delta]->label) ? $items[$delta]->label : null,
      // '#element_validate' => [
      //   [static::class, 'validate'],
      // ],  
    ];

    $element['link'] = [
      '#type' => 'textfield',
      '#title' => t('Affiliation'),
      '#autocomplete_route_name' => 'landportal_content_selection.concepts_autocomplete',
      '#default_value' => isset($items[$delta]->link) ? $items[$delta]->link : null,
      // '#element_validate' => [
      //   [static::class, 'validate'],
      // ], 
    ];

    return $element;
  }

} // class