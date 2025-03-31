<?php

namespace Drupal\landportal_country\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Jump Menu' block for countries.
 *
 * @Block(
 *   id = "country_jump_menu",
 *   admin_label = @Translation("Country jump menu block"),
 *   category = @Translation("Country Landing Page")
 * )
 */
class CountryJumpMenu extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructs an CountryJumpMenu object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, LanguageManagerInterface $language_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->languageManager = $language_manager;
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
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $langcode = $this->languageManager->getCurrentLanguage()->getId();

    /** @var \Drupal\taxonomy\TermStorageInterface $term_storage */
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    /** @var \Drupal\node\NodeStorageInterface $node_storage */
    $node_storage = $this->entityTypeManager->getStorage('node');

    $areas = [
      'Africa',
      'Americas',
      'Asia',
      'Europe',
      'Oceania',
    ];
    foreach ($areas as $area) {
      $terms = $term_storage->loadByProperties([
        'name' => $area,
        'vid' => 'regions',
      ]);
      /** @var \Drupal\taxonomy\TermInterface $term */
      $term = reset($terms);
      if ($children = $term_storage->loadTree('regions', $term->id())) {
        $query = $node_storage->getQuery();
        $query->condition('status', NodeInterface::PUBLISHED);
        $query->condition('type', 'country_narrative');
        $query->condition('geographical_focus', array_column($children, 'tid'), 'IN');
        $nids = $query->accessCheck(TRUE)->execute();
        /** @var \Drupal\node\NodeInterface[] $narratives */
        $narratives = $node_storage->loadMultiple($nids);
        $options = ['' => $this->t('- Choose -')];
        foreach ($narratives as $narrative) {
          /** @var \Drupal\taxonomy\TermInterface $country */
          $country = $narrative->get('geographical_focus')->entity;
          if ($country->hasTranslation($langcode)) {
            $country = $country->getTranslation($langcode);
          }
          $options[$country->toUrl()->toString()] = $country->label();
        }
        asort($options);

        $build[$area] = [
          '#title' => $term->label(),
          '#type' => 'select',
          '#options' => $options,
          '#empty_option' => $this->t('- Choose -'),
          '#empty_value' => '',
          '#default_value' => '',
          '#required' => TRUE,
          '#attributes' => [
            'class' => ['landportal-country-jumpmenu'],
          ],
        ];
      }
    }

    $build['#attached']['library'][] = 'landportal_country/jump_menu';
    return $build;
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
    return Cache::mergeTags(
      parent::getCacheTags(),
      ['node_list:country_narrative', 'taxonomy_term_list:regions'],
    );
  }

}
