<?php

namespace Drupal\landportal_common\Plugin\Block;

use Drupal\Component\Transliteration\TransliterationInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Common class for blocks that load the node/term from context.
 */
abstract class LandportalCountryNodeBlockBase extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The CurrentRouteMatch.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * Constructs an BlockquoteNarrative object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *   The current route match service.
   * @param \Drupal\Component\Transliteration\TransliterationInterface $transliteration
   *   The transliteration helper.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, CurrentRouteMatch $current_route_match, TransliterationInterface $transliteration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentRouteMatch = $current_route_match;
    $this->transliteration = $transliteration;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_route_match'),
      $container->get('transliteration')
    );
  }

  /**
   * Returns the narrative node from the country.
   *
   * @todo Move this to the bundle class.
   *
   * @param \Drupal\taxonomy\TermInterface $country
   *   Country term.
   *
   * @return \Drupal\node\NodeInterface
   *   Country Narrative.
   */
  protected function getNarrativeFromCountry(TermInterface $country) {
    /** @var \Drupal\node\NodeStorageInterface $node_storage */
    $node_storage = $this->entityTypeManager->getStorage('node');
    $node = $node_storage->loadByProperties([
      'status' => NodeInterface::PUBLISHED,
      'type' => 'country_narrative',
      'geographical_focus' => $country->id(),
    ]);
    /** @var \Drupal\node\NodeInterface $node */
    $node = reset($node);
    return $node;
  }

  /**
   * Gets the Node from context/route.
   *
   * @return bool|\Drupal\node\NodeInterface|mixed
   *   The relevant node or FALSE if not found.
   */
  protected function getNode() {
    /** @var \Drupal\taxonomy\TermInterface $term */
    if ($term = $this->currentRouteMatch->getParameter('taxonomy_term')) {
      $node = $this->getNarrativeFromCountry($term);
    }
    else {
      $node = $this->currentRouteMatch->getParameter('node');
    }

    return $node ?? FALSE;
  }

  /**
   * Gets the Country name from context/route.
   *
   * @return string
   *   The Country taxonomy term name.
   */
  protected function getCountryName() {
    /** @var \Drupal\taxonomy\TermInterface $term */
    if ($term = $this->currentRouteMatch->getParameter('taxonomy_term')) {
      $name = $term->label();
    }

    return $name ?? FALSE;
  }

  /**
   * Gets the Country map from context/route.
   *
   * The map is in multiple formats. The file format is country-name.svg, with
   * a fallback to Country-Name.png.
   *
   * @return string
   *   The Country map file name.
   */
  protected function getCountryMapFile() {
    /** @var \Drupal\taxonomy\TermInterface $term */
    if ($term = $this->currentRouteMatch->getParameter('taxonomy_term')) {
      // The file name is derived from the default translation (English).
      $langcode = 'en';
      $name = str_replace(' ', '-', $term->getTranslation($langcode)->label());
      $name = $this->transliteration->transliterate($name, $langcode, '-');
      // Does the svg map exist? Filename format is lowercase country-name.svg.
      $image_path = 'public://country-maps/' . strtolower($name) . '.svg';
      if (!file_exists($image_path)) {
        $image_path = "public://country-maps/$name.png";
        if (!file_exists($image_path)) {
          $image_path = "public://country-maps/default.svg";
        }
      }
    }

    return $image_path ?? FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $cache_tags = parent::getCacheTags();
    /** @var \Drupal\taxonomy\TermInterface $term */
    if ($term = $this->currentRouteMatch->getParameter('taxonomy_term')) {
      $cache_tags = Cache::mergeTags($cache_tags, $term->getCacheTags());
      $node = $this->getNarrativeFromCountry($term);
    }
    else {
      $node = $this->currentRouteMatch->getParameter('node');
    }
    if ($node) {
      $cache_tags = Cache::mergeTags($cache_tags, $node->getCacheTags());
    }

    return $cache_tags;
  }

}
