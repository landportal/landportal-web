<?php

namespace Drupal\lp_swiper\Plugin\LpSwiperWidget;

use Drupal\Core\Form\FormStateInterface;
use Drupal\lp_swiper\LpSwiperWidgetBase;

/**
 * Provides a pager using bullets.
 *
 * @LpSwiperWidget(
 *   id = "lp_swiper_pager_bullets",
 *   type = "lp_swiper_pager",
 *   label = @Translation("Bullets"),
 * )
 */
class PagerBullets extends LpSwiperWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'lp_swiper_pager_bullets_hover' => ['default' => 0],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    // Add field to see if they would like to activate slide and pause on pager
    // hover.
    $form['lp_swiper_pager_bullets_hover'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Activate Slide and Pause on Pager Hover'),
      '#default_value' => $this->getConfiguration()['lp_swiper_pager_bullets_hover'],
      '#description' => $this->t('Should the slide be activated and paused when hovering over a pager item.'),
      '#states' => [
        'visible' => [
          ':input[name="' . $this->getConfiguration()['dependency'] . '[enable]"]' => ['checked' => TRUE],
          ':input[name="' . $this->getConfiguration()['dependency'] . '[type]"]' => ['value' => 'lp_swiper_pager_bullets'],
        ],
      ],
    ];

    return $form;
  }

}
