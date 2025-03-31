<?php

namespace Drupal\landportal_country_narrative\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'additional_information_default' formatter.
 *
 * @FieldFormatter(
 *   id = "additional_information_default",
 *   label = @Translation("Default"),
 *   field_types = {"additional_information"}
 * )
 */
class AdditionalInformationDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    // The ProcessedText element already handles cache context & tag bubbling.
    // @see \Drupal\filter\Element\ProcessedText::preRenderText()
    foreach ($items as $delta => $item) {
      if ($item->description) {
        if ($item->title) {
          $elements[$delta]['title'] = [
            '#type' => 'item',
            '#markup' => $item->title,
            '#prefix' => '<h4 class="primary-underline">',
            '#suffix' => '</h4>',
          ];
        }
        $elements[$delta]['description'] = [
          '#type' => 'processed_text',
          '#text' => $item->description,
          '#format' => $item->format,
          '#langcode' => $item->getLangcode(),
        ];
      }
    }

    return $elements;
  }

}
