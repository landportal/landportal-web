<?php

namespace Drupal\landportal_related_blocks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;

/**
 * Class LandportalRelatedBlocksController
 * @package Drupal\landportal_related_blocks\Controller
 */
class LandportalRelatedBlocksController extends ControllerBase {

  /**
   * @return JsonResponse
   */
  public function blockAutocomplete(Request $request) {
    
    $results = [];
    $input = $request->query->get('q');
    if (!$input) {
      return new JsonResponse($results);
    }
    $input = Xss::filter($input);

    // Get all views.
    $views = Views::getEnabledViews();
    
    foreach ($views as $view_id => $view) {
      $label = $view->get('label');

      // Get all displays of the current view.
      $displays = $view->get('display');
      
      foreach ($displays as $display_id => $display) {
        // Customize this part based on your needs.
        $results[] = [
          'value' => $view_id . ':' . $display_id,
          'label' => $label . ' - ' . $display['display_title'],
        ];
      }
    }

    return new JsonResponse($results);
  }

}
