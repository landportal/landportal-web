<?php

namespace Drupal\landportal_remote_block\Plugin;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;
use Drupal\landportal_remote_block\ConnectionServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a remote base block implementation that remote blocks will extend.
 */
abstract class RemoteBlockBase extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The client service.
   *
   * @var \Drupal\landportal_remote_block\ConnectionService
   */
  protected $connectionService;

  /**
   * Index name.
   *
   * @var string
   */
  protected $indexName;

  /**
   * The current route name.
   *
   * @var string
   */
  protected $routeName;

  /**
   * The current taxonomy term ID.
   *
   * @var int
   */
  protected $taxonomyTerm;

  /**
   * The current content node ID.
   *
   * @var int
   */
  protected $contentNodeId;

  /**
   * The current content node Type.
   *
   * @var string
   */
  protected $contentNodeType;  

  /**
   * The current taxonomy term's vocabulary.
   *
   * @var string
   */
  protected $taxonomyVocabulary;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructs a new RemoteBlockBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *   The current route match service.
   * @param \Drupal\landportal_remote_block\ConnectionServiceInterface $connection_service
   *   The connection service to Solr.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $current_route_match, ConnectionServiceInterface $connection_service, LanguageManagerInterface $language_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->connectionService = $connection_service;
    $this->routeName = $current_route_match->getRouteName();
    $this->languageManager = $language_manager;
    /** @var \Drupal\taxonomy\TermInterface $taxonomy_term */
    if ($taxonomy_term = $current_route_match->getParameter('taxonomy_term')) {
      $this->taxonomyTerm = $taxonomy_term->id();
      $this->taxonomyVocabulary = $taxonomy_term->bundle();
    }
    //$this->contentNodeId = "100906";
    /** @var \Drupal\taxonomy\TermInterface $taxonomy_term */
    
    if ($node_content = $current_route_match->getParameter('node')) {

      //$node_content_id = $node_content->id();
      //\Drupal::logger('my_module')->notice("ProjectC");
      // /\Drupal::logger('my_module')->notice("ProjectD " . $node_content->id());
      if(is_numeric($node_content->id())){
        $node_content_type = $node_content->bundle();
        if($node_content_type == "project") {
          $this->contentNodeId = $node_content->id();
          //\Drupal::logger('my_module')->notice("ProjectK " . $this->contentNodeId);
        }
        // $content_node_id = $node_content->id();
        // if(is_numeric($content_node_id)){
        //   \Drupal::logger('my_module')->notice("ProjectH " . $content_node_id);
        //   $this->contentNodeId = "100906";
        //   \Drupal::logger('my_module')->notice("ProjectK " . $this->contentNodeId);
        // }

      //   \Drupal::logger('my_module')->notice("ProjectE " . $this->contentNodeId);
        
      }

      //$this->taxonomyTerm = $taxonomy_term->id();
      //$this->taxonomyVocabulary = $taxonomy_term->bundle();
    } 
    

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('landportal_remote_block.connection'),
      $container->get('language_manager')
    );
  }

  /**
   * Gets the language prefix to render the more links.
   *
   * @return string
   *   Prefix language for the links.
   */
  protected function getLanguagePrefix() {
    $langcode = $this->languageManager->getCurrentLanguage()->getId();
    if ($langcode === $this->languageManager->getDefaultLanguage()->getId()) {
      return '/';
    }
    elseif ($langcode === 'pt-pt') {
      return "/pt/";
    }
    else {
      return "/$langcode/";
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $block_cache_context = [
      'languages:language_content',
      'url.path',
    ];

    return Cache::mergeContexts(parent::getCacheContexts(), $block_cache_context);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $block_cache_tags = parent::getCacheTags();

    if ($this->taxonomyTerm) {
      $block_cache_tags = Cache::mergeTags(
        $block_cache_tags,
        ['taxonomy_term:' . $this->taxonomyTerm],
      );
    }

    return $block_cache_tags;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 3600;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf(is_numeric($this->contentNodeId) || $this->routeName == 'entity.taxonomy_term.canonical' && ($this->taxonomyVocabulary == 'regions' || $this->taxonomyVocabulary == 'theme' || $this->taxonomyVocabulary == 'programme'));
  }

}
