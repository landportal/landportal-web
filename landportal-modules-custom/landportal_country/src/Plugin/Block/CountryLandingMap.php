<?php

namespace Drupal\landportal_country\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Map' block for the country landing page.
 *
 * @Block(
 *   id = "country_landing_map",
 *   admin_label = @Translation("Country landing page map block"),
 *   category = @Translation("Country Landing Page")
 * )
 */
class CountryLandingMap extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs an CountryLandingMap object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $div = 'block-landbook-view-coda-lbvc-map-country-wrapper';
    $build = [
      'label' => [
        '#markup' => $this->t('Take a look at the map and pick the country portfolio you are looking for'),
        '#prefix' => '<h2 class="h2">',
        '#suffix' => '</h2>',
      ],
      'map' => [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'id' => $div,
        ],
        '#attached' => [
          'library' => [
            'landportal_country/landing_page_map',
          ],
          'drupalSettings' => [
            'target' => $div,
            'data' => $this->getCountries(),
          ],
        ],
        '#value' => $this->t('Loading map...'),
      ],
    ];
    return $build;
  }

  /**
   * Returns an array of data keyed by ISO3.
   *
   * Key is ISO3
   * Value is:
   *  - 0: country.
   *  - 1: with narrative.
   *  - 2: promoted.
   *
   * @return array
   *   Array of countries.
   */
  protected function getCountries() {
    $countries = [];

    /** @var \Drupal\taxonomy\TermStorageInterface $term_storage */
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    /** @var \Drupal\node\NodeStorageInterface $node_storage */
    $node_storage = $this->entityTypeManager->getStorage('node');

    // Language for URLs, needed if building content with mixed languages.
    $current_language = \Drupal::languageManager()->getCurrentLanguage();

    $query = $term_storage->getQuery();
    $query->condition('vid', 'regions');
    $query->condition('field_iso3', NULL, 'IS NOT NULL');
    $tids = $query->accessCheck(TRUE)->execute();
    $terms = $term_storage->loadMultiple($tids);
    foreach ($terms as $term) {
      $iso3 = $term->get('field_iso3')->value;
      $countries[$iso3] = [
        'id' => $iso3,
        'value' => 0,
        'url' => $term->toUrl('canonical', ['language' => $current_language])->toString(),
      ];
    }

    $query = $node_storage->getQuery();
    $query->condition('status', NodeInterface::PUBLISHED);
    $query->condition('type', 'country_narrative');
    $query->condition('geographical_focus', $tids, 'IN');
    $nids = $query->accessCheck(TRUE)->execute();
    /** @var \Drupal\node\NodeInterface[] $narratives */
    $narratives = $node_storage->loadMultiple($nids);

    foreach ($narratives as $narrative) {
      $iso3 = $narrative->get('geographical_focus')->entity->get('field_iso3')->value;
      $countries[$iso3]['id'] = $iso3;
      $countries[$iso3]['value'] = $narrative->isPromoted() ? 2 : 1;
    }

    return array_values($countries);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route', 'languages:language_interface']);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(
      parent::getCacheTags(),
      ['node_list:country_narrative', 'taxonomy_term_list:regions']
    );
  }

}
