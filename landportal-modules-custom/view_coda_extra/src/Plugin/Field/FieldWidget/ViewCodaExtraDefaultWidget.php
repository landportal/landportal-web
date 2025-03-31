<?php
/**
 * @file
 * Contains \Drupal\ViewCodaExtra\Plugin\Field\FieldType\ViewCodaExtraDefaultWidget.
 */
namespace Drupal\view_coda_extra\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ViewCodaExtraDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "ViewCodaExtraDefaultWidget",
 *   label = @Translation("View Coda Extra"),
 *   field_types = {
 *     "ViewCodaExtra"
 *   }
 * )
 */
class ViewCodaExtraDefaultWidget extends WidgetBase {

  /**
   * Define the form for the field type.
   * 
   * Inside this method we can define the form used to edit the field type.
   * 
   * Here there is a list of allowed element types: https://goo.gl/XVd4tA
   */
  public function formElement(FieldItemListInterface $items,$delta, Array $element, Array &$form, FormStateInterface $formState) {

    $element['indicator_title'] = [
      '#title' => $this->t('Title'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->indicator_title) ? $items[$delta]->indicator_title : null,
    ];    
    $element['indicator_description'] = [
      '#title' => $this->t('Description'),
      '#type' => 'text_format',
      // '#format' => 'rich_text',
      // '#rows' => 7,
      '#default_value' => isset($items[$delta]->indicator_description) ? $items[$delta]->indicator_description : null,
    ];
/*     
    $element['indicator'] = [
      '#title' => $this->t('Indicators'),
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'view_coda_extra.indicator_autocomplete',
      '#default_value' => isset($items[$delta]->indicator) ? $items[$delta]->indicator : null,
    ];
*/

    $element['indicator'] = [
      '#title' => $this->t('Indicators'),
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'view_coda_extra.indicator_autocomplete',
      '#default_value' => isset($items[$delta]->indicator) ? $items[$delta]->indicator : '',
      '#maxlength' => 16383,
    ];

    $element['visualization'] = [
      '#title' => $this->t('Visualization'),
      '#type' => 'select',
      '#options' => [
        'map' => $this->t('Map projection'),
        'htmlTable' => $this->t('An HTML table'),
        'ranking' => $this->t('Ranking list'),
      ],
      '#default_value' => isset($items[$delta]->visualization) ? $items[$delta]->visualization : 'map',
    ];


    $element['textarea'] = [
      '#title' => $this->t('Text'),
      '#type' => 'textarea',
      '#weight' => 1005,
      '#default_value' => isset($items[$delta]->textarea) ? $items[$delta]->textarea : null,
    ];
    return $element;
  }

    /**
     * {@inheritdoc}
     */
    public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
      foreach ($values as $key => $value) {
        if (isset($value['indicator_description'])) {
          $values[$key]['indicator_description'] = $value['indicator_description']['value'];
        }
        if (isset($value['visualization'])) {
          $values[$key]['visualization'] = $value['visualization'];
        }
      }
      return $values;
    }



} // class