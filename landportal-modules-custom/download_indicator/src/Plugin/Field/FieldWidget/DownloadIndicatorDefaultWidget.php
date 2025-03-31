<?php

namespace Drupal\download_indicator\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'DownloadIndicatorDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "DownloadIndicatorDefaultWidget",
 *   label = @Translation("Download Indicator"),
 *   field_types = {
 *     "download_indicator_type"
 *   }
 * )
 */
class DownloadIndicatorDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, Array $element, Array &$form, FormStateInterface $form_state) {
    $element['download_indicator'] = [
      '#type' => 'select',
      '#title' => $this->t('Download Indicator'),
      '#options' => [
        'yes' => $this->t('Yes'),
        'no' => $this->t('No'),
      ],
      '#default_value' => isset($items[$delta]->download_indicator) ? $items[$delta]->download_indicator : 'yes',
    ];

    return $element;
  }

}
