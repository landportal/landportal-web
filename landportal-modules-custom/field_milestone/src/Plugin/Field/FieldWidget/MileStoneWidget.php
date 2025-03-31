<?php
/**
 * @file
 * Contains \Drupal\field_milestone\Plugin\Field\FieldType\Milestone.
 */
namespace Drupal\field_milestone\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Date;

/**
 * Plugin implementation of the 'ExperienceFields' widget.
 *
 * @FieldWidget(
 *   id = "MilestoneWidget",
 *   label = @Translation("Key dates"),
 *   field_types = {
 *     "Milestone"
 *   }
 * )
 */
class MileStoneWidget extends WidgetBase{

  /**
   * Define the form for the field type.
   *
   * Inside this method we can define the form used to edit the field type.
   *
   * Here there is a list of allowed element types: https://goo.gl/XVd4tA
   */
    public function formElement(FieldItemListInterface $items, $delta, Array $element, Array &$form, FormStateInterface $formState)
    {

        $element['title'] = [
        '#type' => 'textfield',
        '#title' => t('Title'),
        '#default_value' => isset($items[$delta]->title) ? $items[$delta]->title : null,
        '#empty_value' => '',
        ];
        $element['start_date'] = [
          '#type' => 'date',
          '#title' => $this->t('Date'),
          '#default_value' => isset($items[$delta]->start_date) ? $items[$delta]->start_date : NULL,
          '#required' => FALSE, // Allow this field to be empty.
        ];

        $element['end_date'] = [
          '#type' => 'date',
          '#title' => $this->t('To Date'),
          '#default_value' => isset($items[$delta]->end_date) ? $items[$delta]->end_date : NULL,
          '#required' => FALSE, // Allow this field to be empty.
        ];
        
        $element['description'] = [
        '#type' => 'text_format',
        '#title' => t('Descritpion'),
        '#default_value' => isset($items[$delta]->description) ? $items[$delta]->description : null,
        '#format' => 'full_html', 
        // '#empty_value' => '',
        ];
        return $element;
    }

    /**
     * {@inheritdoc}
     */
    public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
      foreach ($values as $key => $value) {
        if (isset($value['description'])) {
          $values[$key]['description'] = $value['description']['value'];
        }
        // if (isset($value['visualization'])) {
        //   $values[$key]['visualization'] = $value['visualization'];
        // }
      }
      return $values;
    }
} // class
