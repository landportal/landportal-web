<?php

namespace Drupal\landportal_country_narrative\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;

/**
 * Provides a breadcrumb builder for issue narrative pages.
 *
 * 'Issues' > [Issue] > [Issue Narrative]
 */
class IssueNarrativeBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  use StringTranslationTrait;

  /**
   * Apply to this node type.
   */
  const NODE_TYPE = 'issues';

  /**
   * Main node id (/issues)
   */
  const ISSUES_LANDING_PAGE_NID = 42574;

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $node = $this->getNode($route_match);
    return $route_match->getRouteName() == 'entity.node.canonical' && $node instanceof NodeInterface && $node->bundle() == self::NODE_TYPE;
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();

    // Add in main 'Issues' node page.
    $breadcrumb->addLink(Link::createFromRoute($this->t('Issues'), 'entity.node.canonical', ['node' => self::ISSUES_LANDING_PAGE_NID]));

    if ($node = $this->getNode($route_match)) {
      /** @var \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem $item */
      /** @var \Drupal\taxonomy\TermInterface $issue */
      if ($node->hasField('thematic_narrative') && !$node->thematic_narrative->isEmpty() &&
          ($item = $node->thematic_narrative->first()) && ($issue = $item->entity)) {
        $breadcrumb->addCacheableDependency($issue);
        $breadcrumb->addLink(Link::createFromRoute($issue->getName(), 'entity.taxonomy_term.canonical', ['taxonomy_term' => $issue->id()]));

      }
      $breadcrumb->addCacheableDependency($node);
      $breadcrumb->addLink(Link::createFromRoute($node->label(), '<none>'));
    }

    // This breadcrumb builder is based on a route parameter, and hence it
    // depends on the 'route' cache context.
    $breadcrumb->addCacheContexts(['url.path.parent', 'route']);

    return $breadcrumb;
  }

  /**
   * Returns the current node from the route.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   *
   * @return \Drupal\Core\Entity\EntityInterface|false
   *   Return the node from the route.
   */
  protected function getNode(RouteMatchInterface $route_match) {
    if ($node = $route_match->getParameter('node')) {
      return $node;
    }

    return FALSE;
  }

}
