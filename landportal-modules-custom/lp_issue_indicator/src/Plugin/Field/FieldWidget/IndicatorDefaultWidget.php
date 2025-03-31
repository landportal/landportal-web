<?php
/**
 * @file
 * Contains \Drupal\Indicator\Plugin\Field\FieldType\IndicatorDefaultWidget.
 */
namespace Drupal\lp_issue_indicator\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'IndicatorDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "IndicatorDefaultWidget",
 *   label = @Translation("Indicator select"),
 *   field_types = {
 *     "Indicator"
 *   }
 * )
 */
class IndicatorDefaultWidget extends WidgetBase {

  /**
   * Define the form for the field type.
   * 
   * Inside this method we can define the form used to edit the field type.
   * 
   * Here there is a list of allowed element types: https://goo.gl/XVd4tA
   */
  public function formElement(FieldItemListInterface $items,$delta, Array $element, Array &$form, FormStateInterface $formState) {

    $element['label'] = [
      '#type' => 'textfield',
      '#title' => t('Label'),
      '#default_value' => isset($items[$delta]->label) ? $items[$delta]->label : null,
      '#empty_value' => '',
    ];
    $element['hover'] = [
      '#type' => 'textarea',
      '#title' => t('Hover text'),
      '#default_value' => isset($items[$delta]->hover) ? $items[$delta]->hover : null,
      '#description' => t("If you want to add Add double apostrphy \" \" , please add this sign instead « » ."),
      '#empty_value' => '',
    ];
    $element['link'] = [
      '#type' => 'textfield',
      '#title' => t('Url of the indicator'),
      '#default_value' => isset($items[$delta]->link) ? $items[$delta]->link : null,
      '#empty_value' => '',
      '#description' => t('Url is required field. If you will not give its value, the indicator will not show.'), 
    ];


    return $element;
  }

} // class