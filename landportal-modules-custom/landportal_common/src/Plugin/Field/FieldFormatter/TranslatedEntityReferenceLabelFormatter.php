<?php

namespace Drupal\landportal_common\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;

/**
 * Plugin implementation of the 'translated entity reference label' formatter.
 *
 * @FieldFormatter(
 *   id = "translated_entity_reference_label",
 *   label = @Translation("Translated Label"),
 *   description = @Translation("Display the label of the referenced entities ensuring current page language."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class TranslatedEntityReferenceLabelFormatter extends EntityReferenceLabelFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $current_langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    return parent::viewElements($items, $current_langcode);
  }

}
