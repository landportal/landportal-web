<?php

namespace Drupal\landportal_country_narrative\Plugin\ExtraField\Display;

use Drupal\Component\Transliteration\TransliterationInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Map image for country heros.
 *
 * @todo This is for country narratives for now, could be extended to other content types.
 *
 * @ExtraFieldDisplay(
 *   id = "hero_map_image",
 *   label = @Translation("Map image for countries"),
 *   bundles = {
 *     "node.country_narrative",
 *   }
 * )
 */
class HeroMapImage extends ExtraFieldDisplayBase implements ContainerFactoryPluginInterface {

  use StringTranslationTrait;

  /**
   * The transliteration helper.
   *
   * @var \Drupal\Component\Transliteration\TransliterationInterface
   */
  protected $transliteration;

  /**
   * Constructs a HeroMapImage object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Component\Transliteration\TransliterationInterface $transliteration
   *   The transliteration helper.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TransliterationInterface $transliteration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->transliteration = $transliteration;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition,
      $container->get('transliteration')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function view(ContentEntityInterface $entity) {
    $langcode = 'en';
    /** @var \Drupal\taxonomy\TermInterface $country */
    $country = $entity->get('geographical_focus')->entity;
    // Ensure getting the English translated entity label.
    $translated_title = str_replace(' ', '-', $country->getTranslation($langcode)->label());
    $translated_title = $this->transliteration->transliterate($translated_title, $langcode, '-');

    // The file format is country-name.svg, with a fallback to Country-Name.png.
    $image_path = 'public://country-maps/' . strtolower($translated_title) . '.svg';
    if (!file_exists($image_path)) {
      $image_path = "public://country-maps/$translated_title.png";
    }

    if (!file_exists($image_path)) {
      $image_path = "public://country-maps/default.svg";
    }

    $image_src = Url::fromUri(\Drupal::service('file_url_generator')->generateAbsoluteString($image_path))->toString();
    $image_alt = $this->t('Map of @country', ['@country' => $translated_title]);

    return [
      '#markup' => "<img src='$image_src' alt='$image_alt'/>",
    ];
  }

}
