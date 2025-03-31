<?php

namespace Drupal\lp_swiper\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Style plugin to render each item in a swiper.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "swiper",
 *   title = @Translation("Swiper"),
 *   help = @Translation("Display the results as a swiper."),
 *   theme = "views_view_swiper",
 *   display_types = {"normal"}
 * )
 */
class Swiper extends StylePluginBase {

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Does the style plugin support custom css class for the rows.
   *
   * @var bool
   */
  protected $usesRowClass = TRUE;

  /**
   * Does the style plugin support grouping of rows.
   *
   * @var bool
   */
  protected $usesGrouping = FALSE;

  /**
   * Does the style plugin for itself support to add fields to it's output.
   *
   * This option only makes sense on style plugins without row plugins, like
   * for example table.
   *
   * @var bool
   */
  protected $usesFields = TRUE;

  /**
   * {@inheritdoc}
   */

///////////////////////////////////

  function lp_swiper_page_attachments(array &$page) {
  $computedData = \Drupal\lp_swiper\MyData::getData();
  $page['#attached']['html_head'][] = [
    [
      '#tag'   => 'script',
      '#value' => "var lp_swiperComputedData = $computedData",
    ],
    'lp_swiper_computed_data_javascript_access',
  ];
}

//////////////////////////////////

  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['row_class_custom'] = ['default' => ''];
    $options['row_class_default'] = ['default' => TRUE];
    $options['swiper_type'] = ['default' => 'lp_swiper_cycle'];
    $options['swiper_skin'] = ['default' => 'default'];

     //print('<pre>');
     //print_r($options);
     //print('</pre>');


    $typeManager = \Drupal::service('plugin.manager.lp_swiper.swiper_type');
    foreach ($typeManager->getDefinitions() as $id => $definition) {
      $instance = $typeManager->createInstance($id, []);
      $options[$id] = $instance->defaultConfiguration();


    }
      //print('<pre>');
      //print_r($options);
      //print('</pre>');
    //  exit;
    $widgetTypeManager = \Drupal::service('plugin.manager.lp_swiper.widget_type');
    $widgetTypes = $widgetTypeManager->getDefinitions();
    foreach (['top', 'bottom'] as $location) {
      foreach ($widgetTypes as $widgetTypeId => $widgetTypeDefinition) {
        $options['widgets']['contains'][$location]['contains'][$widgetTypeId]['contains'] = $widgetTypeManager->createInstance($widgetTypeId, [])->defaultConfiguration();
      }
    }
     // print('<pre>');
     // print_r($options);
     // print('</pre>');
      //exit;
    return $options;
    // drupal_set_message('<pre>'. print_r($options, TRUE). 'Fida Muhammad' .'</pre>');
  }

// print(serialize($options));

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    // Wrap all the form elements to help style the form.
    $form['lp_swiper_wrapper'] = [
      '#markup' => '<div id="lp-swiper-form-wrapper">',
    ];

    // Skins.
    $form['swiper_skin_header'] = [
      '#markup' => '<h2>' . $this->t('Style') . '</h2>',
    ];


// drupal_set_message('<pre>'. print_r($form, TRUE). 'Fida Muhammad' .'</pre>');


    /* @var \Drupal\Component\Plugin\PluginManagerInterface */
    $skinManager = \Drupal::service('plugin.manager.lp_swiper.swiper_skin');

    // Get all skins to create the option list.
    $skins = [];

    // print(serialize($skins));
    foreach ($skinManager->getDefinitions() as $id => $definition) {
      $skins[$id] = $definition['label'];
    }
    asort($skins);

    // Create the drop down box so users can choose an available skin.
    $form['swiper_skin'] = [
      '#type' => 'select',
      '#title' => $this->t('Skin custom testing'),
      '#options' => $skins,
      '#default_value' => $this->options['swiper_skin'],
      '#description' => $this->t('Select the skin to use for this display.  Skins allow for easily swappable layouts of things like next/prev links and thumbnails.  Note that not all skins support all widgets, so a combination of skins and widgets may lead to unpredictable results in layout.'),
    ];

    // print('<pre>');
    // print(serialize($options['swiper_skin']));
    // print('</pre>');


// \Drupal::logger('lp_swiper')->notice('<pre><code>' . print_r($form, TRUE) . '</code></pre>' );
    
//////////////////////////////////custom coding start////////////////////////////////////

    // $form['testTwo'] = [
    //   '#type' => 'checkbox',
    //   '#title' => $this->t('Show Dots'),
    //   '#description' => $this->t('Select the checkbox to activate dots in swiper slider'),
    // ];


    // $slideItems = [
    //   'None' => '--None--',
    //   'OneSlide' => 'OneSlide',
    //   'TwoSlide' => 'TwoSlide',
    //   'ThreeSlide' => 'ThreeSlide',
    //   'FourSlide' => 'FourSlide',
    //   'FiveSlide' => 'FiveSlide',
    // ];
    // $form['testThree'] = [
    //   '#type' => 'select',
    //   '#title' => $this->t('Show Dots'),
    //   '#options'=> $slideItems,
    //   '#description' => $this->t('Select the checkbox to activate dots in swiper slider'),
    // ];

   
/*
    $radioButton = array(
      'Small' => t('Small Screen 320px'),
      'Medium Small' => t('Medium Small 480px'), 
      'Medium' => t('Medium Sreen 767'),
      'Large' => t('Large Screen 991px above'),
    );
    $form['testFour'] = array(
      '#type' => 'radios',
      '#title' => t('SlidePreView on Screen'),
      '#options' => $radioButton,
      '#description' => t('Select your screen Resolution'),
      '#default_value' => $radioButton['Small'],
    );
*/

// print(serialize($radioButton));

    // $form['testFive'] = [
    //   '#type' => 'textfield',
    //   '#title' => $this->t('Give the number of SlidePreView'),
    //   '#placeholder' => $this->t('Give number of slides'),
    //   '#description' => $this->t('Put the Number of Slides to be shown in the Screen'),
    // ];


////////////////////////////////custom coding end///////////////////////////////////
    // Slides.
    $form['slides_header'] = [
      '#markup' => '<h2>' . $this->t('Slides') . '</h2>',
    ];

    // Get all swiper types.
    $typeManager = \Drupal::service('plugin.manager.lp_swiper.swiper_type');
    $types = $typeManager->getDefinitions();

    if ($types) {

      // Build our swiper options for the form.
      $swiper_options = [];
      foreach ($types as $id => $definition) {
        $swiper_options[$id] = $definition['label'];
      }

      $form['swiper_type'] = [
        '#type' => 'select',
        '#title' => $this->t('Swiper Type'),
        '#options' => $swiper_options,
        '#default_value' => $this->options['swiper_type'],
      ];

      // @todo: check if default values are properly passed to the buildConfigurationForm().
      foreach ($types as $id => $definition) {
        $configuration = [];
        if (!empty($this->options[$id])) {
          $configuration = $this->options[$id];
        }
        $instance = $typeManager->createInstance($id, $configuration);

        $form[$id] = [
          '#type' => 'fieldset',
          '#title' => $this->t('@module options', ['@module' => $definition['label']]),
          '#collapsible' => TRUE,
          '#attributes' => ['class' => [$id, 'admin-swiper-container']],
          '#states' => [
            'visible' => [
              ':input[name="style_options[swiper_type]"]' => ['value' => $id],
            ],
          ],
          // print(serialize($form[$id]));
        ];

        $form = $instance->buildConfigurationForm($form, $form_state);
      }
    }
    else {
      $form['enable_module'] = [
        '#markup' => $this->t('There is no Lp Swiper plugin enabled. Go to the @modules and enable a Lp Swiper plugin module. For example Lp Swiper Cycle.', ['@modules' => Link::fromTextAndUrl($this->t('Modules Page'), Url::fromRoute('system.modules_list'))->toString()]),
      ];
    }

    // Widgets.
    // @todo: Improve the UX by using Ajax.
    $form['widgets_header'] = [
      '#markup' => '<h2>' . $this->t('Slider') . '</h2>',
    ];

    // Define the available locations.
    $location = ['top' => $this->t('Top'), 'bottom' => $this->t('Bottom')];



    /* @var \Drupal\Component\Plugin\PluginManagerInterface */
    $widgetTypeManager = \Drupal::service('plugin.manager.lp_swiper.widget_type');

    // Get all widgets types that are registered.
    $widgets = $widgetTypeManager->getDefinitions();
    if (!empty($widgets)) {

      // Build our weight values by number of widgets.
      $weights = [];
      for ($i = 1; $i <= count($widgets); $i++) {
        $weights[$i] = $i;
      }

      // Loop through our widgets and locations to build our form values for
      // each widget.
      foreach ($widgets as $widget_id => $widget_info) {

        // Determine if this widget type is compatible with any swiper type.
        $compatible_swipers = [];
        foreach ($types as $swiper_id => $swiper_info) {
          if ($widgetTypeManager->createInstance($widget_id, [])->checkCompatiblity($swiper_info)) {
            $compatible_swipers[] = $swiper_id;
          }
        }

        // Display the widget config form only if the widget type is compatible
        // with at least one swiper type.
        if (!empty($compatible_swipers)) {
          foreach ($location as $location_id => $location_name) {
            // Use Widget Checkbox.
            $form['widgets'][$location_id][$widget_id]['enable'] = [
              '#type' => 'checkbox',
              '#title' => $widget_info['label'],
              '#default_value' => $this->options['widgets'][$location_id][$widget_id]['enable'],
              '#description' => $this->t('Should @name be rendered at the @location of the slides.', ['@name' => $widget_info['label'], '@location' => $location_name]),
              '#dependency' => [
                'edit-style-options-swiper-type' => $compatible_swipers,
              ],
            ];

            // Need to wrap this so it indents correctly.
            $form['widgets'][$location_id][$widget_id]['wrapper'] = [
              '#markup' => '<div class="vs-dependent-abc">',
            ];

            // Widget weight.
            // We check to see if the default value is greater than the number
            // of widgets just in case a widget has been removed and the form
            // hasn't been saved again.
            $weight = (isset($this->options['widgets'][$location_id][$widget_id]['weight'])) ? $this->options['widgets'][$location_id][$widget_id]['weight'] : 0;
            if ($weight > count($widgets)) {
              $weight = count($widgets);
            }
            $form['widgets'][$location_id][$widget_id]['weight'] = [
              '#type' => 'select',
              '#title' => $this->t('Weight of the @name', ['@name' => $widget_info['label']]),
              '#default_value' => $weight,
              '#options' => $weights,
              '#description' => $this->t('Determines in what order the @name appears. A lower weight will cause the @name to be above higher weight items.', ['@name' => $widget_info['label']]),
              '#prefix' => '<div class="vs-dependent-def">',
              '#suffix' => '</div>',
              '#states' => [
                'visible' => [
                  ':input[name="style_options[widgets][' . $location_id . '][' . $widget_id . '][enable]"]' => ['checked' => TRUE],
                ],
              ],
            ];

            // Build the appropriate array for the states API.
            $widget_dependency = 'style_options[widgets][' . $location_id . '][' . $widget_id . ']';

            // Get the current configuration of this widget type.
            $configuration = [];
            if (!empty($this->options['widgets'][$location_id][$widget_id])) {
              $configuration = $this->options['widgets'][$location_id][$widget_id];
            }
            $configuration['dependency'] = $widget_dependency;
            $instance = $widgetTypeManager->createInstance($widget_id, $configuration);

            // Get the configuration form of this widget type.
            $form['widgets'][$location_id][$widget_id] = $instance->buildConfigurationForm($form['widgets'][$location_id][$widget_id], $form_state);

            // Close the vs-dependent wrapper.
            $form['widgets'][$location_id][$widget_id]['wrapper_close'] = [
              '#markup' => '</div>',
            ];
          }
        }
      }
    }

    // Browse locations and remove the header if no widget is available.
    foreach ($location as $location_id => $location_name) {
      // If no widget is available, the only key is "header".
      if (count(array_keys($form['widgets'][$location_id])) == 1) {
        unset($form['widgets'][$location_id]);
      }
    }

    // Remove the widget section header if there is no widget available.
    if (empty($form['widgets'])) {
      unset($form['widgets']);
      unset($form['widgets_header']);
    }

    $form['lp_swiper_wrapper_close'] = [
      '#markup' => '</div>',
    ];

    // Add a library to style the form.
    $form['#attached']['library'] = ['lp_swiper/form'];
  }
// print(serialize($form));
  /**
   * {@inheritdoc}
   */
  public function validateOptionsForm(&$form, FormStateInterface $form_state) {
    // Validate all swiper type plugins values.
    $typeManager = \Drupal::service('plugin.manager.lp_swiper.swiper_type');
    foreach ($typeManager->getDefinitions() as $id => $definition) {
      $type = $typeManager->createInstance($id);
      $type->validateConfigurationForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    // Submit all swiper type plugins values.
    $typeManager = \Drupal::service('plugin.manager.lp_swiper.swiper_type');
    foreach ($typeManager->getDefinitions() as $id => $definition) {
      $type = $typeManager->createInstance($id);
      $type->submitConfigurationForm($form, $form_state);


    }
  }

}
