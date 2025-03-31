<?php

namespace Drupal\lp_swiper\Plugin\LpSwiperWidgetType;

use Drupal\Core\Form\FormStateInterface;
use Drupal\lp_swiper\LpSwiperWidgetTypeBase;

/**
 * Provides a control widget type.
 *
 * @LpSwiperWidgetType(
 *   id = "lp_swiper_controls",
 *   label = @Translation("Controls"),
 *   accepts = {"pause" = {"required" = TRUE}, "play" = {"required" = TRUE}},
 *   calls = {"nextSlide", "pause", "play", "previousSlide"}
 * )
 */
class Controls extends LpSwiperWidgetTypeBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'type' => ['default' => 0],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    /* @var \Drupal\Component\Plugin\PluginManagerInterface */
    $widgetManager = \Drupal::service('plugin.manager.lp_swiper.widget');

    $widgets = $widgetManager->getDefinitions($this->getPluginId());

    if (!empty($widgets)) {
      $options = [];
      foreach ($widgets as $widgetId => $widgetInfo) {
        $options[$widgetId] = $widgetInfo['label'];
      }

      // Need to wrap this so it indents correctly.
      $form['lp_swiper_controls_wrapper'] = [
        '#markup' => '<div class="vs-dependent">',
      ];

      // Create the widget type field.
      $form['type'] = [
        '#type' => 'select',
        '#title' => $this->t('Controls Type'),
        '#description' => $this->t('Style of the controls'),
        '#default_value' => $this->getConfiguration()['type'],
        '#options' => $options,
        '#states' => [
          'visible' => [
            ':input[name="' . $this->getConfiguration()['dependency'] . '[enable]"]' => ['checked' => TRUE],
          ],
        ],
      ];

      foreach ($widgets as $widget_id => $widget_info) {
        // Get the current configuration of this widget.
        $configuration = [];
        if (!empty($this->getConfiguration()[$widget_id])) {
          $configuration = $this->getConfiguration()[$widget_id];
        }
        $configuration['dependency'] = $this->getConfiguration()['dependency'];
        $instance = $widgetManager->createInstance($widget_id, $configuration);

        // Get the configuration form of this widget type.
        $form[$widget_id] = isset($form[$widget_id]) ? $form[$widget_id] : [];
        $form[$widget_id] = $instance->buildConfigurationForm($form[$widget_id], $form_state);
      }

      $form['controls_wrapper_close'] = [
        '#markup' => '</div>',
      ];
    }
    else {
      $form['enable_controls'] = [
        '#markup' => 'There are no controls available.',
      ];
    }

    return $form;
  }

}
