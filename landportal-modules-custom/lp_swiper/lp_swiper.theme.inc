<?php
//use Drupal\Core\Entity\EntityTypeManagerInterface;
/**
 * @file
 * The theme system, which controls the output of views swiper.
 */

/**
 * @defgroup vss_templates Templates
 * @{
 * Swiper and component templates.
 *
 * @see vss_theme
 * @}
 */

/**
 * @defgroup vss_theme Theme Functions
 * @{
 * Theme processing and display generation.
 *
 * Most of the logic behind the generation of the swiper is here.
 *
 * @see vss_templates
 */

use Drupal\Component\Utility\Html;

/**
 * Lp Swiper: swiper.
 */
function _lp_swiper_preprocess_views_view_swiper(&$vars) {
  $options = $vars['view']->style_plugin->options;
  $vars['skin'] = 'default';
  $vars['swiper'] = '';
  $main_frame_module = $options['swiper_type'];

  //\Drupal::logger('Swiper main3')->notice(serialize($vars));

  if (empty($main_frame_module)) {
    // Get all swiper types.
    $typeManager = \Drupal::service('plugin.manager.lp_swiper.swiper_type');
    $types = $typeManager->getDefinitions();

    if ($types) {
      foreach ($types as $id => $definition) {
        $main_frame_module = $id;
        break;
      }
    }
  }

  // Make sure the main swiper settings are defined before building the
  // swiper.
  if (empty($main_frame_module)) {
    \Drupal::messenger()->addMessage(
      t('No main frame module is enabled for views swiper. This is often because another module which Lp Swiper needs is not enabled. For example, 4.x needs a module like "Lp Swiper: Cycle" enabled.'),
      'error'
    );
  }
  elseif (empty($options[$main_frame_module])) {
    \Drupal::messenger()->addMessage(t('The options for @module does not exists.', ['@module' => $main_frame_module]), 'error');
  }
  elseif (!empty($vars['rows'])) {
    $settings = $options[$main_frame_module];
    $view = $vars['view'];
    $rows = $vars['rows'];

    // The #name element is not available on Views edit pages.
    $view_element_name = (isset($view->element['#name'])) ? $view->element['#name'] : '';
    $vss_id = $view_element_name . '-' . $view->current_display;

    // Give each swiper a unique id if there are more than one on the page.
    static $instances = [];
    if (isset($instances[$vss_id])) {
      $instances[$vss_id]++;
      $vss_id .= "_" . $instances[$vss_id];
    }
    else {
      $instances[$vss_id] = 1;
    }

    // Building our default methods.
    $methods = [
      'goToSlide' => [],
      'nextSlide' => [],
      'pause' => [],
      'play' => [],
      'previousSlide' => [],
      'transitionBegin' => [],
      'transitionEnd' => [],
    ];


    // print('<pre>');
    // print(serialize($methods[][].'Fida Muhammad Afridi'));
    // print('</pre>');

    // Pull all widget info and swiper info and merge them together.
    $widgetTypeManager = \Drupal::service('plugin.manager.lp_swiper.widget_type');
    $widgetTypes = $widgetTypeManager->getDefinitions();

    $swiperTypeManager = \Drupal::service('plugin.manager.lp_swiper.swiper_type');
    $swiperTypes = $swiperTypeManager->getDefinitions();

    $addons = array_merge($widgetTypes, $swiperTypes);

    // Loop through all the addons and call their methods if needed.
    foreach ($addons as $addon_id => $addon_info) {
      foreach ($addon_info['accepts'] as $imp_key => $imp_value) {
        if (is_array($imp_value)) {
          $methods[$imp_key][] = \Drupal::service('lp_swiper.format_addons_name')->format($addon_id);
        }
        else {
          $methods[$imp_value][] = \Drupal::service('lp_swiper.format_addons_name')->format($addon_id);
        }
      }
    }

    $vars['#attached']['library'][] = 'lp_swiper/widget_info';
    $vars['#attached']['drupalSettings']['lpSwiper'][$vss_id] = [
      'methods' => $methods,
      'paused' => 0,
    ];

    // Process Skins.
    $skinManager = \Drupal::service('plugin.manager.lp_swiper.swiper_skin');
    $skin = $skinManager->createInstance($options['swiper_skin']);

    $vars['skin'] = $skin->getClass();

    // print('<pre>');
    // print(serialize($vars['skin'].'Fida Muhammad'));
    // print('</pre>');

    foreach ($skin->getLibraries() as $library) {
      $vars['#attached']['library'][] = $library;
    }

    // Process Widgets.
    // Build weights.
    $weight = [];
    for ($i = 1; $i <= count($widgetTypes); $i++) {
      $weight['top'][$i] = [];
      $weight['bottom'][$i] = [];
    }

    $slide_count = count($view->result);
    if ($slide_count && $vars['view']->style_plugin->options['swiper_type'] == 'lp_swiper_cycle') {
      $items_per_slide = $vars['view']->style_plugin->options['lp_swiper_cycle']['items_per_slide'];
      $slide_count = $slide_count / $items_per_slide;
    }
    foreach ($widgetTypes as $widgetTypeId => $widgetTypeName) {
      foreach ($weight as $location => $order) {
        if ($options['widgets'][$location][$widgetTypeId]['enable']) {
          // If hide on single slide and only a single slide skip rendering.
          if ($options['widgets'][$location][$widgetTypeId]['hide_on_single_slide'] && $slide_count <= 1) {
            continue;
          }
          $widgetWeight = ($options['widgets'][$location][$widgetTypeId]['weight'] > count($widgetTypes)) ? count($widgetTypes) : $options['widgets'][$location][$widgetTypeId]['weight'];

          $weight[$location][$widgetWeight][] = [
            'widgetId' => $widgetTypeId,
            'widgetSettings' => $options['widgets'][$location][$widgetTypeId],
          ];
        }
      }
    }

    // Build our widgets.
    foreach ($weight as $location => $order) {
      $vars[$location . '_widget_rendered'] = [];
      foreach ($order as $widgets) {
        if (is_array($widgets)) {
          foreach ($widgets as $widgetData) {
            $vars[$location . '_widget_rendered'][] = [
              '#theme' => $view->buildThemeFunctions($widgetData['widgetId'] . '_widget'),
              '#vss_id' => $vss_id,
              '#view' => $view,
              '#settings' => $widgetData['widgetSettings'],
              '#location' => $location,
              '#rows' => $rows,
            ];
          }
        }
      }
    }

    // Process Swiper.
    $slides = [
      '#theme' => $view->buildThemeFunctions($main_frame_module . '_main_frame'),
      '#vss_id' => $vss_id,
      '#view' => $view,
      '#settings' => $settings,
      '#rows' => $rows,
    ];

    $vars['swiper'] = [
      '#theme' => $view->buildThemeFunctions('lp_swiper_main_section'),
      '#vss_id' => $vss_id,
      '#slides' => $slides,
      '#plugin' => $main_frame_module,
    ];
  }
}

/**
 * Lp Swiper: pager.
 */
function template_preprocess_lp_swiper_pager_widget(&$vars) {
  // Add JavaScript settings for the pager type.
  $vars['#attached']['library'][] = 'lp_swiper/widget_info';
  $vars['#attached']['drupalSettings']['lpSwiperPager'][$vars['vss_id']] = [
    $vars['location'] => [
      'type' => \Drupal::service('lp_swiper.format_addons_name')->format($vars['settings']['type']),
    ],
  ];

  // Create some attributes.
  $attributes['class'][] = 'widget_pager widget_pager_' . $vars['location'];
  $attributes['id'] = 'widget_pager_' . $vars['location'] . '_' . $vars['vss_id'];

  $vars['widget'] = [
    '#theme' => $vars['view']->buildThemeFunctions($vars['settings']['type']),
    '#vss_id' => $vars['vss_id'],
    '#view' => $vars['view'],
    '#settings' => $vars['settings'],
    '#location' => $vars['location'],
    '#attributes' => $attributes,
  ];
}

/**
 * Theme pager fields.
 */
function template_preprocess_lp_swiper_pager_fields(&$vars) {  
  // Add JavaScript settings for the field.
  $vars['#attached']['library'][] = 'lp_swiper/widget_info';
  $vars['#attached']['drupalSettings']['lpSwiperPagerFields'][$vars['vss_id']] = [
    $vars['location'] => [
      'activatePauseOnHover' => $vars['settings']['lp_swiper_pager_fields']['lp_swiper_pager_fields_hover'],
    ],
  ];

  // Add hover intent library.
  if ($vars['settings']['lp_swiper_pager_fields']['lp_swiper_pager_fields_hover']) {
    $vars['#attached']['library'][] = 'lp_swiper/jquery_hoverIntent';
  }

  $vars['widget_id'] = $vars['attributes']['id'];
  // Add our class to the wrapper.
  $vars['attributes']['class'][] = 'lp_swiper_pager_field';

  // Render all the fields unless there is only 1 slide and the user specified
  // to hide them when there is only one slide.
  $vars['rendered_field_items'] = [];
  foreach ($vars['view']->result as $count => $node) {
    $rendered_fields = [];
    foreach ($vars['settings']['lp_swiper_pager_fields']['lp_swiper_pager_fields_fields'] as $field => $use) {
      if ($use !== 0 && is_object($vars['view']->field[$field])) {
        $rendered_fields[] = [
          '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_pager_field_field'),
          '#view' => $vars['view'],
          '#label' => $vars['view']->field[$field]->options['label'],
          '#output' => $vars['view']->style_plugin->getField($count, $field),
          '#css_identifier' => Html::cleanCssIdentifier($vars['view']->field[$field]->field),
        ];
      }
    }

    $vars['rendered_field_items'][] = [
      '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_pager_field_item'),
      '#vss_id' => $vars['vss_id'],
      '#item' => $rendered_fields,
      '#count' => $count,
      '#location' => $vars['location'],
      '#length' => count($vars['view']->result),
    ];
  }
}

/**
 * Lp Swiper: pager item.
 */
function template_preprocess_lp_swiper_pager_field_item(&$vars) {
  $vars['attributes']['class'][] = 'lp_swiper_pager_field_item';
  $vars['attributes']['class'][] = ($vars['count'] % 2) ? 'views-row-even' : 'views-row-odd';
  if ($vars['count'] == 0) {
    $vars['attributes']['class'][] = 'views-row-first';
  }
  elseif ($vars['count'] == $vars['length'] - 1) {
    $vars['attributes']['class'][] = 'views-row-last';
  }
}

/**
 * Lp Swiper: Bullets pager.
 */

function template_preprocess_lp_swiper_pager_bullets(&$vars) {
  $vars['#attached']['library'][] = 'lp_swiper/widget_info';
  $vars['#attached']['library'][] = 'lp_swiper/pager_bullets';
  $vars['#attached']['drupalSettings']['lpSwiperPagerFields'][$vars['vss_id']] = [
    $vars['location'] => [
      'activatePauseOnHover' => $vars['settings']['lp_swiper_pager_bullets']['lp_swiper_pager_bullets_hover'] ?? '',
    ],
  ];

  $vars['bullet_items'] = [
    '#theme' => 'item_list',
    '#items' => [],
    '#attributes' => $vars['attributes'],
  ];
  $vars['bullet_items']['#attributes']['class'][] = 'lp-swiper-pager-bullets';
  $vars['bullet_items']['#attributes']['class'][] = 'swiper-paginationb';
  $vars['bullet_items']['#attributes']['class'][] = 'lp_swiper_pager_field';
 // $classes = $vars['bullet_items']['#attributes']['class'][] = 'swiper-paginationb'.$vars['vss_id'];
 //$final = $vars['#attached']['drupalSettings']['lp_swiper']['classes'] = $settings[$classes];
 //\Drupal::logger('Swiper Slider1')->notice(serialize($final));


  $classes = $vars['bullet_items']['#attributes']['class'][] = 'swiper-paginationb'.$vars['vss_id'];
  $settings = []; // Initialize settings array
  $vars['#attached']['drupalSettings']['lp_swiper']['classes'] = $classes;
  $final = $vars['#attached']['drupalSettings']['lp_swiper']['classes'];


  for ($i = 0; $i < count($vars['view']->result); $i++) {
    $vars['bullet_items']['#items'][] = [
      '#markup' => $i,
      '#wrapper_attributes' => [
        'id' => 'lp_swiper_pager_field_item_' . $vars['location'] . '_' . $vars['vss_id'] . '_' . $i,
      ],
    ];
  }
}

/**
 * Lp Swiper: Controls.
 */
function template_preprocess_lp_swiper_controls_widget(&$vars) {
  // Add JavaScript settings for the controls type.
  $vars['#attached']['library'][] = 'lp_swiper/widget_info';
  $vars['#attached']['drupalSettings']['lpSwiperControls'][$vars['vss_id']] = [
    $vars['location'] => [
      'type' => \Drupal::service('lp_swiper.format_addons_name')->format($vars['settings']['type']),
    ],
  ];

  $vars['widget'] = [
    '#theme' => $vars['view']->buildThemeFunctions($vars['settings']['type']),
    '#vss_id' => $vars['vss_id'],
    '#view' => $vars['view'],
    '#settings' => $vars['settings'],
    '#location' => $vars['location'],
    '#rows' => $vars['rows'],
  ];
}

/**
 * The swiper controls.
 */
function template_preprocess_lp_swiper_controls_text(&$vars) {
  $vars['#attached']['library'][] = 'lp_swiper/controls_text';

  $vars['attributes']['class'][] = 'lp_swiper_controls_text';

  $vars['rendered_control_previous'] = [
    '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_controls_text_previous'),
    '#vss_id' => $vars['vss_id'],
    '#view' => $vars['view'],
    '#settings' => $vars['settings'],
  ];

  $vars['rendered_control_pause'] = [
    '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_controls_text_pause'),
    '#vss_id' => $vars['vss_id'],
    '#view' => $vars['view'],
    '#settings' => $vars['settings'],
  ];

  $vars['rendered_control_next'] = [
    '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_controls_text_next'),
    '#vss_id' => $vars['vss_id'],
    '#view' => $vars['view'],
    '#settings' => $vars['settings'],
  ];
}

/**
 * Lp Swiper: "previous" control.
 */
function template_preprocess_lp_swiper_controls_text_previous(&$vars) {
  $vars['attributes']['class'][] = 'lp_swiper_controls_text_previous';
}

/**
 * Lp Swiper: "pause" control.
 */
function template_preprocess_lp_swiper_controls_text_pause(&$vars) {
  $vars['attributes']['class'][] = 'lp_swiper_controls_text_pause  lp-swiper-controls-text-status-play';
  $vars['start_text'] = t('Pause');
}

/**
 * Lp Swiper: "next" control.
 */
function template_preprocess_lp_swiper_controls_text_next(&$vars) {
  $vars['attributes']['class'][] = 'lp_swiper_controls_text_next';
}

/**
 * Lp Swiper: Slide Counter.
 */
function template_preprocess_lp_swiper_slide_counter_widget(&$vars) {
  $vars['widget'] = [
    '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_slide_counter'),
    '#vss_id' => $vars['vss_id'],
    '#view' => $vars['view'],
    '#settings' => $vars['settings'],
    '#location' => $vars['location'],
    '#rows' => $vars['rows'],
  ];
}

/**
 * Lp Swiper: slide counter.
 */
function template_preprocess_lp_swiper_slide_counter(&$vars) {
  $vars['attributes']['class'][] = 'lp_swiper_slide_counter';
  $vars['slide_count'] = count($vars['rows']);
}

/**
 * @} End of "defgroup vss_theme".
 */
