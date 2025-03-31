<?php

namespace Drupal\landportal_country_narrative\Plugin\ExtraField\Display;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;

/**
 * Learn more link.
 *
 * @todo This is for country narratives for now, could be extended to other content types.
 *
 * @ExtraFieldDisplay(
 *   id = "read_more",
 *   label = @Translation("Read more"),
 *   bundles = {
 *     "node.country_narrative",
 *   }
 * )
 */
class ReadMore extends ExtraFieldDisplayBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function view(ContentEntityInterface $entity) {
    // Force current language for links, important if displaying content without
    // language.
    $current_language = \Drupal::languageManager()->getCurrentLanguage();
    return [
      '#type' => 'link',
      '#title' => $this->t('Read the country profile'),
      '#url' => $entity->toUrl('canonical', ['language' => $current_language]),
    ];
  }

}
