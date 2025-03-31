<?php

namespace Drupal\view_coda_extra\Controller;

use Drupal\Core\Entity\Element\EntityAutocomplete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;

/**
 * Class JsonApiIndicatorController
 * @package Drupal\view_coda_extra\Controller
 */
class JsonApiIndicatorController
{

  /**
   * @return JsonResponse
   */
  public function handleAutocomplete(Request $request)
  {
    
    $results = [];
    $input = $request->query->get('q');
    if (!$input) {
      return new JsonResponse($results);
    }
    $input = Xss::filter($input);
    $query = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'indicators')
      ->condition('name', $input, 'CONTAINS')
      ->groupBy('tid')
      // ->sort('created', 'DESC')
      ->range(0, 10);
    $tids = $query->accessCheck(TRUE)->execute();
    
    //$nodes = $ids ? \Drupal\node\Entity\Node::loadMultiple($ids) : [];
    $terms = $tids ? \Drupal\taxonomy\Entity\Term::loadMultiple($tids) : [];
    foreach ($terms as $term) {
      $results[] = [
        'value' => EntityAutocomplete::getEntityLabels([$term]),
        'label' => $term->getName().' ('.$term->id().')',
      ];
    }
    return new JsonResponse($results);
  }

}
