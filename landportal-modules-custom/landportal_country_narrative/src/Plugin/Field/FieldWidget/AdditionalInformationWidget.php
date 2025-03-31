<?php

namespace Drupal\landportal_country_narrative\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'additional_information' field widget.
 *
 * @FieldWidget(
 *   id = "additional_information",
 *   label = @Translation("Additional information"),
 *   field_types = {"additional_information"},
 * )
 */
class AdditionalInformationWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'rows' => '5',
      'placeholder' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['rows'] = [
      '#type' => 'number',
      '#title' => $this->t('Rows'),
      '#default_value' => $this->getSetting('rows'),
      '#required' => TRUE,
      '#min' => 1,
    ];
    $element['placeholder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Placeholder'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => $this->t('Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format.'),
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Number of rows: @rows', ['@rows' => $this->getSetting('rows')]);
    $placeholder = $this->getSetting('placeholder');
    if (!empty($placeholder)) {
      $summary[] = $this->t('Placeholder: @placeholder', ['@placeholder' => $placeholder]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => isset($items[$delta]->title) ? $items[$delta]->title : NULL,
    ];

    $element['description'] = [
      '#type' => 'text_format',
      '#default_value' => $items[$delta]->description,
      '#rows' => $this->getSetting('rows'),
      '#format' => $items[$delta]->format,
      '#placeholder' => $this->getSetting('placeholder'),
      '#attributes' => ['class' => ['js-text-full', 'text-full']],
    ];
    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'additional-information-elements';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    return isset($violation->arrayPropertyPath[0]) ? $element[$violation->arrayPropertyPath[0]] : $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if ($value['title'] === '') {
        $values[$delta]['title'] = NULL;
      }
      if ($value['description']['value'] === '') {
        $values[$delta]['description'] = NULL;
        $values[$delta]['format'] = filter_fallback_format();
      }
      else {
        $values[$delta]['description'] = $value['description']['value'];
        $values[$delta]['format'] = $value['description']['format'];
        unset($value['description']);
      }
    }
    return $values;
  }

}
