<?php
/**
 * @file
 * Theme & preprocess functions for the Lp Swiper: cycle module.
 */

use Drupal\Component\Utility\Html;
//use Drupal\Core\State\StateInterface;
/**
 * Lp Swiper: Theme the main frame wrapper.
 *
 * @ingroup vss_theme
 */
function template_preprocess_lp_swiper_cycle_main_frame(&$vars) {
  $settings = $vars['settings'];
  $rows = $vars['rows'];
  $vss_id = $vars['vss_id'];

  //\Drupal::logger('Swiper main 10')->notice(serialize($vars));

  // $pre = $vars['settings']['items_per_slide_cycle_swipper'];

  //\Drupal::logger('Swiper main unique1')->notice(serialize($vss_id));

  // Cast the strings into int or bool as necessary.
  $new_settings = [];
  foreach ($settings as $key => $value) {
    if (is_string($value)) {
      $value = str_replace("\n", ' ', $value);

      $value = trim($value);

      if (is_numeric($value)) {
        $value = (int) $value;
      }
      elseif (strtolower($value) == 'true') {
        $value = TRUE;
      }
      elseif (strtolower($value) == 'false') {
        $value = FALSE;
      }
    }

    $new_settings[$key] = $value;
  }

  $num_divs = count($rows);
  if ($settings['items_per_slide_first']) {
    $num_divs -= $settings['items_per_slide_first_number'];
    $num_divs = $num_divs / $settings['items_per_slide_cycle_swipper'];
    $num_divs += 1;
  }
  else {
    $num_divs = $num_divs / $settings['items_per_slide_cycle_swipper'];
  }

 //\Drupal::logger('Swiper main 00 ')->notice(serialize($num_divs));

  $settings = array_merge(
    [
      'num_divs' => ceil($num_divs),
      'id_prefix' => '#lp_swiper_cycle_main_',
      'div_prefix' => '#lp_swiper_cycle_div_',
      'vss_id' => $vss_id,
    ],
    $new_settings
  );

  // We need to go through the current js setting values to make sure the one we
  // want to add is not already there. If it is already there then append -[num]
  // to the id to make it unique.
  $swiper_count = 1;
  global $_lp_swiper_processedCycles;
  if (is_array($_lp_swiper_processedCycles)) {
    foreach ($_lp_swiper_processedCycles as $processedCycle) {
      if (stristr($processedCycle, '#lp_swiper_cycle_main_' . $vss_id)) {
        $swiper_count++;
      }
    }
  }

//\Drupal::logger('Swiper main')->notice(serialize($_lp_swiper_processedCycles));

  if ($swiper_count > 1) {
    $vss_id .= '-' . $swiper_count;
    $settings['vss_id'] = $vss_id;
  }

  // Load the json2 library.
  if (!empty($settings['advanced_options'])) {
    $vars['#attached']['library'][] = 'lp_swiper_cycle/json2';
  }

  // Load the pause library.
  if (!empty($settings['pause_in_middle'])) {
    $vars['#attached']['library'][] = 'lp_swiper_cycle/jquery_pause';
  }

  // Load Swiper library.
  $vars['#attached']['library'][] = 'lp_swiper_cycle/jquery_swiper';

  // Load jQuery Cycle library.
  //$vars['#attached']['library'][] = 'lp_swiper_cycle/jquery_cycle';

  // Load our custom cycle library.
  //$vars['#attached']['library'][] = 'lp_swiper_cycle/lp_swiper_cycle';
  $vars['#attached']['library'][] = 'lp_swiper_cycle/swiper_slide';  
  $swiper_id = $vars['#attached']['drupalSettings']['lpSwiperCycle']['#lp_swiper_cycle_main_' . $vss_id] = $settings;

  $vars['#attached']['drupalSettings']['lp_swiper']['allswipers'][$vss_id]=$settings['items_per_slide_cycle_swipper'];

//\Drupal::logger('lp_swiper')->notice(serialize($vars['#attached']['drupalSettings']['lp_swiper']['allswipers']));


  $vars['#attached']['drupalSettings']['lp_swiper']['mobile_color_body'] = $settings['items_per_slide_mobile_cycle_swipper'];
  
  $_lp_swiper_processedCycles['#lp_swiper_cycle_main_' . $vss_id] = '#lp_swiper_cycle_main_' . $vss_id;

  $swiper_class = $vars['#attached']['drupalSettings']['lpSwiperCycle']['.swiper-'.$vss_id] = $settings;


  // Add hover intent library.
  if ($settings['pause']) {
    $vars['#attached']['library'][] = 'lp_swiper/jquery_hoverIntent';
  }

  // Add the swiper elements.
  $vars['attributes']['class'][] = 'lp_swiper_cycle_teaser_section';
  $vars['attributes']['class'][] = 'swiper-wrapper';

  $vars['items_per_slide_cycle_swipper'] = $settings['items_per_slide_cycle_swipper'];

  $items = [];
  $index = 0;
  $vars['rendered_rows'] = [];
  //print serialize($rows);
  foreach ($rows as $count => $item) {
     //print serialize("imran");
    $items[] = $item;
      $vars['rendered_rows'][] = [
        '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_cycle_main_frame_row'),
        '#vss_id' => $vss_id,
        '#items' => $items,
        '#count' => $index,
        '#view' => $vars['view'],
      ];

      $items = [];
      $index++;
      /*
    if ($settings['items_per_slide_first'] && empty($vars['rendered_rows'])) {
      print serialize("noman");
      if (count($items) == $settings['items_per_slide_first_number'] || $count == (count($rows) - 1)) {
        print serialize("asif");
        $vars['rendered_rows'][] = [
          '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_cycle_main_frame_row'),
          '#vss_id' => $vss_id,
          '#items' => $items,
          '#count' => $index,
          '#view' => $vars['view'],
        ];

        $items = [];
        $index++;
      }
    }
    elseif (count($items) == $settings['items_per_slide_cycle_swipper'] || $count == (count($rows) - 1)) {
      print serialize("ibrar");
      $vars['rendered_rows'][] = [
        '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_cycle_main_frame_row'),
        '#vss_id' => $vss_id,
        '#items' => $items,
        '#count' => $index,
        '#view' => $vars['view'],
      ];

      $items = [];
      $index++;
    }
    */
  }
}

/**
 * Lp Swiper swiper rows.
 *
 * @ingroup vss_theme
 */
function template_preprocess_lp_swiper_cycle_main_frame_row(&$vars) {
  $current = $vars['count'] + 1;
  $vars['attributes']['class'][] = 'lp_swiper_cycle_slide';
  $vars['attributes']['class'][] = 'swiper-slide';
  $vars['attributes']['class'][] = 'lp_swiper_slide views-row-' . $current;

  if ($vars['count']) {
    $vars['attributes']['class'][] = 'lp_swiper_cycle_hidden';
  }
  $vars['attributes']['class'][] = ($vars['count'] % 2) ? 'views-row-even' : 'views-row-odd';

  $vars['rendered_items'] = [];
  foreach ($vars['items'] as $item_count => $item) {
    $vars['rendered_items'][] = [
      '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_cycle_main_frame_row_item'),
      '#item' => $item,
      '#item_count' => $item_count,
      '#count' => $vars['count'],
      '#view' => $vars['view'],
      '#length' => count($vars['view']->result),
    ];
    break;
  }
}

/**
 * Lp Swiper swiper row items.
 *
 * @ingroup vss_theme
 */
function template_preprocess_lp_swiper_cycle_main_frame_row_item(&$vars) {
  $vars['attributes']['class'][] = 'views-row views-row-' . $vars['count'];
  $vars['attributes']['class'][] = ($vars['count'] % 2) ? 'views-row-even' : 'views-row-odd';
  if ($vars['count'] == 0) {
    $vars['attributes']['class'][] = 'views-row-first';
  }
  elseif ($vars['count'] == $vars['length'] - 1) {
    $vars['attributes']['class'][] = 'views-row-last';
  }

  // Support custom row classes.
  if ($row_class = $vars['view']->style_plugin->getRowClass($vars['count'])) {
    $vars['attributes']['class'][] = $row_class;
  }
}

/**
 * Implements hook_preprocess_lp_swiper_pager_fields().
 *
 * @ingroup vss_theme
 */
function _lp_swiper_cycle_preprocess_lp_swiper_pager_fields(&$vars) {
  $slide_count = count($vars['view']->result);
  $options = $vars['view']->style_plugin->options['lp_swiper_cycle'];
  $items_per_slide = $options['items_per_slide_cycle_swipper'];

  if ($vars['settings']['hide_on_single_slide'] && $slide_count == 1) {
    return;
  }

  $vars['rendered_field_items'] = [];
  for ($slide = 0, $count = 0; $slide < $slide_count; $count++) {
    $rendered_fields = [];
    foreach ($vars['settings']['lp_swiper_pager_fields']['lp_swiper_pager_fields_fields'] as $field => $use) {
      if ($use !== 0 && is_object($vars['view']->field[$field])) {
        $rendered_fields[] = [
          '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_pager_field_field'),
          '#view' => $vars['view'],
          '#label' => $vars['view']->field[$field]->options['label'],
          '#output' => $vars['view']->style_plugin->getField($slide, $field),
          '#css_identifier' => Html::cleanCssIdentifier($vars['view']->field[$field]->field),
        ];
      }
    }
    if (empty($vars['rendered_field_items']) && $options['items_per_slide_first']) {
      $slide += $options['items_per_slide_first_number'];
    }
    elseif ($options['items_per_slide_cycle_swipper']) {
      $slide += $options['items_per_slide_cycle_swipper'];
    }
    else {
      $slide += 1;
    }
    $vars['rendered_field_items'][] = [
      '#theme' => $vars['view']->buildThemeFunctions('lp_swiper_pager_field_item'),
      '#vss_id' => $vars['vss_id'],
      '#item' => $rendered_fields,
      '#count' => $count,
      '#location' => $vars['location'],
    ];
  }
}

/**
 * Implements hook_preprocess_lp_swiper_slide_counter().
 *
 * @ingroup vss_theme
 */
function _lp_swiper_cycle_preprocess_lp_swiper_slide_counter(&$vars) {
  $options = $vars['view']->style_plugin->options['lp_swiper_cycle'];
  if ($options['items_per_slide_first']) {
    $slide_count = $vars['slide_count'] - $options['items_per_slide_first_number'];
    $slide_count = $slide_count / $options['items_per_slide_cycle_swipper'];
    $slide_count += 1;
  }
  else {
    $slide_count = $vars['slide_count'] / $options['items_per_slide_cycle_swipper'];
  }
  $vars['slide_count'] = ceil($slide_count);
}



