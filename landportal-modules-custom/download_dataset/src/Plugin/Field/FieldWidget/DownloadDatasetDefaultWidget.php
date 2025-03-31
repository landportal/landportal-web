<?php

namespace Drupal\download_dataset\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'DownloadDatasetDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "DownloadDatasetDefaultWidget",
 *   label = @Translation("Download Dataset"),
 *   field_types = {
 *     "download_dataset_type"
 *   }
 * )
 */
class DownloadDatasetDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, Array $element, Array &$form, FormStateInterface $form_state) {
    $element['download_dataset'] = [
      '#type' => 'select',
      '#title' => $this->t('Download Dataset'),
      '#options' => [
        'yes' => $this->t('Yes'),
        'no' => $this->t('No'),
      ],
      '#default_value' => isset($items[$delta]->download_dataset) ? $items[$delta]->download_dataset : 'yes',
    ];

    return $element;
  }

}
