<?php

namespace Drupal\lp_swiper_cycle\Plugin\LpSwiperType;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\lp_swiper\LpSwiperTypeBase;
use Drupal\Core\Link;

/**
 * Provides a swiper type based on jquery cycle.
 *
 * @LpSwiperType(
 *   id = "lp_swiper_cycle",
 *   label = @Translation("Swiper"),
 *   accepts = {
 *     "goToSlide",
 *     "nextSlide",
 *     "pause",
 *     "play",
 *     "previousSlide"
 *   },
 *   calls = {
 *     "transitionBegin",
 *     "transitionEnd",
 *     "goToSlide",
 *     "pause",
 *     "play",
 *     "nextSlide",
 *     "previousSlide"
 *   }
 * )
 */
class Cycle extends LpSwiperTypeBase {

  /**
   * {@inheritdoc}
   */
  
  public function defaultConfiguration() {
    return [
      'contains' => [
        // Transition.
        'effect' => ['default' => 'fade'],
        'transition_advanced' => ['default' => 0],
        'timeout' => ['default' => 5000],
        'speed' => ['default' => 700],
        'delay' => ['default' => 0],
        'items_per_slide_cycle_swipper' => [4 => 4],
        'items_per_slide_mobile_cycle_swipper' => [2 => 2],
        'sync' => ['default' => 1],
        'random' => ['default' => 0],
        // Action.
        'pause' => ['default' => 1],
        'pause_on_click' => ['default' => 0],
        'action_advanced' => ['default' => 0],
        'start_paused' => ['default' => 0],
        'remember_slide' => ['default' => 0],
        'remember_slide_days' => ['default' => 1],
        'pause_in_middle' => ['default' => 0],
        'pause_when_hidden' => ['default' => 0],
        'pause_when_hidden_type' => ['default' => 'full'],
        'amount_allowed_visible' => ['default' => ''],
        'nowrap' => ['default' => 0],
        'fixed_height' => ['default' => 1],
        'items_per_slide' => ['default' => 1],
        'items_per_slide_first' => ['default' => FALSE],
        'items_per_slide_first_number' => ['default' => 1],
        'wait_for_image_load' => ['default' => 1],
        'wait_for_image_load_timeout' => ['default' => 3000],

        // Internet Explorer Tweaks.
        'cleartype' => ['default' => 'true'],
        'cleartypenobg' => ['default' => 'false'],

        // Advanced.
        'advanced_options' => ['default' => '{}'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $cycle = \Drupal::service('library.discovery')->getLibraryByName('lp_swiper_cycle', 'jquery_cycle');
    if (!isset($cycle['js'][0]['data']) || !file_exists($cycle['js'][0]['data'])) {
      $form['lp_swiper_cycle']['no_cycle_js'] = [
        '#markup' => '<div style="color: red">' . $this->t('You need to install the @url plugin. If you have installed it check file paths. See the readme for more details.',
          [
            '@url' => Link::fromTextAndUrl('jQuery Cycle', Url::FromUri('http://malsup.com/jquery/cycle', [
              'attributes' => ['target' => '_blank'],
            ]))->toString(),
          ]) . '</div>',
      ];
    }


    $effects = [
      'none' => 'none',
      'blindX' => 'blindX',
      'blindY' => 'blindY',
      'blindZ' => 'blindZ',
      'cover' => 'cover',
      'curtainX' => 'curtainX',
      'curtainY' => 'curtainY',
      'fade' => 'fade',
      'fadeZoom' => 'fadeZoom',
      'growX' => 'growX',
      'growY' => 'growY',
      'scrollUp' => 'scrollUp',
      'scrollDown' => 'scrollDown',
      'scrollLeft' => 'scrollLeft',
      'scrollRight' => 'scrollRight',
      'scrollHorz' => 'scrollHorz',
      'scrollVert' => 'scrollVert',
      'shuffle' => 'shuffle',
      'slideX' => 'slideX',
      'slideY' => 'slideY',
      'toss' => 'toss',
      'turnUp' => 'turnUp',
      'turnDown' => 'turnDown',
      'turnLeft' => 'turnLeft',
      'turnRight' => 'turnRight',
      'uncover' => 'uncover',
      'wipe' => 'wipe',
      'zoom' => 'zoom',
    ];

    $slide_desktop_val = [
      '1' => '1',
      '2' => '2',
      '3' => '3',
      '4' => '4',
      '5' => '5',
      '6' => '6',
      '7' => '7',
      '8' => '8',
      '9' => '9',
      '10' => '10',
      '11' => '11',
      '12' => '12',
      '13' => '13',
    ];

    $slide_mobile_val = [
      '1' => '1',
      '2' => '2',
      '3' => '3',
      '4' => '4',
      '5' => '5',
      '6' => '6',
      '7' => '7',
      '8' => '8',
      '9' => '9',
      '10' => '10',
      '11' => '11',
      '12' => '12',
      '13' => '13',
    ];








    // Need to wrap this so it indents correctly.
    $form['lp_swiper_cycle']['action_advanced_wrapper'] = [
      '#markup' => '<div class="vs-dependent">',
    ];




    $form['lp_swiper_cycle']['#attached']['library'][] = 'lp_swiper_cycle/formoptions';


    $form['lp_swiper_cycle']['items_per_slide_cycle_swipper'] = [
      '#type' => 'select',
      '#title' => $this->t('Slides in Desktop'),
      '#options' => $slide_desktop_val,
      '#default_value' => $this->getConfiguration()['items_per_slide_cycle_swipper'],
      '#description' => $this->t('The transition effect that will be used to change between images. Not all options below may be relevant depending on the effect. <a href="http://jquery.malsup.com/cycle/browser.html" target="_black">Follow this link to see examples of each effect.</a>'),
    ];

    $form['lp_swiper_cycle']['items_per_slide_mobile_cycle_swipper'] = [
      '#type' => 'select',
      '#title' => $this->t('Slides in Mobile'),
      '#options' => $slide_mobile_val,
      '#default_value' => $this->getConfiguration()['items_per_slide_mobile_cycle_swipper'],
      '#description' => $this->t('The transition effect that will be used to change between images. Not all options below may be relevant depending on the effect. <a href="http://jquery.malsup.com/cycle/browser.html" target="_black">Follow this link to see examples of each effect.</a>'),
    ];





    return $form;
  }


}
