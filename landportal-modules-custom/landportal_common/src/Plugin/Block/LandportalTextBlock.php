<?php

namespace Drupal\landportal_common\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'LandportalTextBlock' block.
 *
 * @Block(
 *  id = "landportal_text_block",
 *  admin_label = @Translation("Text Block"),
 *  provider = "landportal_common",
 *  category = "Landportal",
 * )
 */
class LandportalTextBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'text' => NULL,
      'wrapper_class' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['text'] = [
      '#type' => 'text_format',
      '#title' => 'Body',
      '#default_value' => $this->configuration['text']['value'],
      '#format' => $this->configuration['text']['format'],
      '#required' => TRUE,
    ];
    $form['wrapper_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Wrapper classes'),
      '#default_value' => $this->configuration['wrapper_class'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['text'] = $form_state->getValue('text');
    $this->configuration['wrapper_class'] = $form_state->getValue('wrapper_class');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#type' => 'processed_text',
      '#text' => $this->configuration['text']['value'],
      '#format' => $this->configuration['text']['format'],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(
      parent::getCacheContexts(),
      [
        'languages:language_interface',
      ]
    );
  }

}
