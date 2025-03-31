<?php

namespace Drupal\landportal_indicators\Plugin\Block;

use Drupal\landportal_common\Plugin\Block\LandportalCountryNodeBlockBase;
use Drupal\taxonomy\TermInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Indicators' block.
 *
 * @Block(
 *   id = "country_indicators",
 *   admin_label = @Translation("Country indicators block"),
 *   category = @Translation("Landportal Indicators")
 * )
 */
class CountryIndicatorsBlock extends LandportalCountryNodeBlockBase {

  /**
   * The client service.
   *
   * @var \Drupal\landportal_virtuoso\ConnectionService
   */
  protected $connectionService;

  /**
   * The Socio economic indicator service.
   *
   * @var \Drupal\landportal_virtuoso\IndicatorsService
   */
  protected $indicatorsService;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->connectionService = $container->get('landportal_virtuoso.connection');
    $instance->indicatorsService = $container->get('landportal_virtuoso.indicators');
    $instance->languageManager = $container->get('language_manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    if ($iso3 = $this->getIso3()) {
      $prefix = $this->getLanguagePrefix();
      $build = [
        '#theme' => 'country_indicators_block',
        '#socio_economic_title' => $this->t('Socio-economic indicators'),
        '#socio_economic_more_label' => $this->t('browse all'),
        '#socio_economic_more_url' => $prefix . '/book/indicators',
        '#land_title' => $this->t('Land-related indicators'),
        '#land_more_label' => $this->t('browse all'),
        '#land_more_url' => $prefix . '/book/indicators',
      ] + $this->buildRegionIndicators($iso3);
    }

    return $build;
  }

  /**
   * Build indicator values for specific region.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array with raw indicator values, ready for theming.
   */
  protected function buildRegionIndicators($iso3) {
    $build = [
      '#land_area' => $this->indicatorsService->getLandArea($iso3),
      '#total_population' => $this->indicatorsService->getTotalPopulation($iso3),
      '#gini_index' => $this->indicatorsService->getGiniIndex($iso3),
      '#gdp_capita' => $this->indicatorsService->getGdpCapita($iso3),
      '#urban_population' => $this->indicatorsService->getUrbanPopulation($iso3),
      '#agricultural_land' => $this->indicatorsService->getAgriculturalLand($iso3),
      '#perceived_tenure_security' => $this->indicatorsService->getPerceivedTenureSecurity($iso3),
      '#primary_forests' => $this->indicatorsService->getPrimaryForests($iso3),
      '#women_owning_land' => $this->indicatorsService->getWomenOwningLand($iso3),
      '#area_in_deals' => $this->indicatorsService->getAreaInDeals($iso3),
    ];

    // Build links with proper language prefix.
    // @todo The tricky part is we don't get language alias, but Prefix + English alias
    $prefix = $this->getLanguagePrefix();
    foreach ($build as &$values) {
      if (!empty($values['taxonomy_term'])) {
        $values['url'] = $prefix . $values['taxonomy_term']->toUrl()->toString();
      }
    }

    return $build;
  }

  /**
   * Retrieves the ISO3 code for countries and narratives.
   *
   * @todo Move this to the bundle class.
   *
   * @return bool|string
   *   The iso3 for the country if found, FALSE otherwise.
   */
  protected function getIso3() {
    /** @var \Drupal\taxonomy\TermInterface $term */
    if ($node = $this->getNode()) {
      if ($node->hasField('geographical_focus') && !$node->get('geographical_focus')->isEmpty()) {
        $term = $node->get('geographical_focus')->entity;
      }
    }
    else {
      $term = $this->currentRouteMatch->getParameter('taxonomy_term');
    }

    if (!empty($term) && $term instanceof TermInterface) {
      return $term->get('field_iso3')->value;
    }

    return FALSE;
  }

  /**
   * Gets the language prefix to render the more links.
   *
   * @return string
   *   Prefix language for the links without trailing slash.
   */
  protected function getLanguagePrefix() {
    $langcode = $this->languageManager->getCurrentLanguage()->getId();
    if ($langcode === $this->languageManager->getDefaultLanguage()->getId()) {
      return '';
    }
    elseif ($langcode === 'pt-pt') {
      return "/pt";
    }
    else {
      return "/$langcode";
    }
  }

}
