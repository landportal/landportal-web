<?php

namespace Drupal\calendar\Plugin\views\style;

use Drupal\calendar\CalendarDateInfo;
use Drupal\calendar\CalendarHelper;
use Drupal\calendar\CalendarStyleInfo;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\views\Entity\View;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\calendar\Plugin\views\row\Calendar as CalendarRow;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Component\Datetime\TimeInterface;

/**
 * Views style plugin for the Calendar module.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "calendar",
 *   title = @Translation("Calendar"),
 *   help = @Translation("Present view results as a Calendar."),
 *   theme = "calendar_style",
 *   display_types = {"normal"},
 *   even_empty = TRUE
 * )
 */
class Calendar extends StylePluginBase {

  use StringTranslationTrait;

  /**
   * Does the style plugin for itself support to add fields to it's output.
   *
   * @var bool
   */
  protected $usesFields = TRUE;

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Does the style plugin support grouping of rows.
   *
   * @var bool
   */
  protected $usesGrouping = FALSE;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The date info for this calendar.
   *
   * @var \Drupal\calendar\CalendarDateInfodateInfo
   *   The calendar date info object.
   */
  protected $dateInfo;

  /**
   * The style info for this calendar.
   *
   * @var \Drupal\calendar\CalendarStyleInfostyleInfo
   *   The calendar style info object.
   */
  protected $styleInfo;

  /**
   * Calendar items contains the keys for the start date and time of the event.
   *
   * @var array
   *
   * Example:
   *
   * @code
   * $items = [
   *   "2015-10-20" => [
   *     "00:00:00" => [
   *        0 => Drupal\calendar\CalendarEvent,
   *      ],
   *   ],
   *   "2015-10-21" => [
   *     "00:00:00" => [
   *        0 => Drupal\calendar\CalendarEvent,
   *      ],
   *   ],
   * ];
   * @endcode
   */
  protected $items;

  /**
   * The current day date object.
   *
   * @var \DateTime
   */
  protected $currentDay;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The time interface.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Slots to keep the flag whether calendar cell is filled up with an event or not.
   *
   * @var array
   *
   * Example:
   *
   * @code
   * $cellSlots = [
   *   [1,0,0,0,1],
   *   [0,1,1,0,1],
   *   [0,1,1,1,0],
   * ];
   * @endcode
   */
  protected $cellSlots;

  /**
   * Overrides \Drupal\views\Plugin\views\style\StylePluginBase::init().
   *
   * The style info is set through the parent function, but we also initialize
   * the date info object here.
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    if (empty($view->dateInfo)) {
      // @todo This used to say $this->dateInfo. Make sure that what we do here
      // is right.
      $this->view->dateInfo = new CalendarDateInfo();
      $this->view->styleInfo = new CalendarStyleInfo();
    }
    $this->dateInfo = &$this->view->dateInfo;
    $this->styleInfo = &$this->view->styleInfo;
  }

  /**
   * Constructs a Calendar object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Datetime\DateFormatter $date_formatter
   *   The date formatter service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time interface.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DateFormatter $date_formatter, MessengerInterface $messenger, TimeInterface $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->definition = $plugin_definition + $configuration;
    $this->dateFormatter = $date_formatter;
    $this->messenger = $messenger;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('date.formatter'), $container->get('messenger'), $container->get('datetime.time'));
  }

  /**
   * {@inheritdoc}
   */
  public function evenEmpty() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['calendar_type'] = ['default' => 'month'];
    $options['name_size'] = ['default' => 3];
    $options['month_name_size'] = ['default' => 99];
    $options['mini'] = ['default' => 0];
    $options['link_to_date'] = ['default' => 1];
    $options['with_weekno'] = ['default' => 0];
    $options['multiday_theme'] = ['default' => 1];
    $options['theme_style'] = ['default' => 1];
    $options['max_items'] = ['default' => 0];
    $options['max_items_behavior'] = ['default' => 'more'];
    $options['groupby_times'] = ['default' => 'hour'];
    $options['groupby_times_custom'] = ['default' => ''];
    $options['groupby_field'] = ['default' => ''];
    $options['multiday_hidden'] = ['default' => []];
    $options['granularity_links'] = [
      'default' => [
        'day' => '',
        'week' => '',
        'month' => '',
      ],
    ];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['calendar_type'] = [
      '#title' => $this->t('Calendar type'),
      '#type' => 'select',
      '#description' => $this->t('Select the calendar time period for this display.'),
      '#default_value' => $this->options['calendar_type'],
      '#options' => CalendarHelper::displayTypes(),
    ];
    $form['mini'] = [
      '#title' => $this->t('Display as mini calendar'),
      '#default_value' => $this->options['mini'],
      '#type' => 'radios',
      '#options' => [
        0 => $this->t('No'),
        1 => $this->t('Yes'),
      ],
      '#description' => $this->t('Display the mini style calendar, with no item details. Suitable for a calendar displayed in a block.'),
      '#dependency' => ['edit-style-options-calendar-type' => ['month']],
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => ['value' => 'month'],
        ],
      ],
    ];
    $form['link_to_date'] = [
      '#title' => $this->t('Link to date'),
      '#default_value' => $this->options['link_to_date'],
      '#type' => 'radios',
      '#options' => [
        0 => $this->t('No'),
        1 => $this->t('Yes'),
      ],
      '#description' => $this->t('Links day to day view'),
      '#dependency' => ['edit-style-options-calendar-type' => ['month']],
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => ['value' => 'month'],
        ],
      ],
    ];
    $form['month_name_size'] = [
      '#title' => $this->t('Calendar month names'),
      '#default_value' => $this->options['month_name_size'],
      '#type' => 'radios',
      '#options' => [
        1 => $this->t('Abbreviated name'),
        99 => $this->t('Full name'),
      ],
      '#description' => $this->t('The way month names should be displayed in a year calendar.'),
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => ['value' => 'year'],
        ],
      ],
    ];
    $form['name_size'] = [
      '#title' => $this->t('Calendar day of week names'),
      '#default_value' => $this->options['name_size'],
      '#type' => 'radios',
      '#options' => [
        1 => $this->t('First letter of name'),
        2 => $this->t('First two letters of name'),
        3 => $this->t('Abbreviated name'),
        99 => $this->t('Full name'),
      ],
      '#description' => $this->t('The way day of week names should be displayed in a calendar.'),
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => [
            ['value' => 'year'],
            ['value' => 'month'],
            ['value' => 'week'],
          ],
        ],
      ],
    ];
    $form['with_weekno'] = [
      '#title' => $this->t('Show week numbers'),
      '#default_value' => $this->options['with_weekno'],
      '#type' => 'radios',
      '#options' => [
        0 => $this->t('No'),
        1 => $this->t('Yes'),
      ],
      '#description' => $this->t('Whether or not to show week numbers in the left column of calendar weeks and months.'),
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => ['value' => 'month'],
        ],
      ],
    ];
    $form['max_items'] = [
      '#title' => $this->t('Maximum items'),
      '#type' => 'select',
      '#options' => [
        0 => $this->t('Unlimited'),
        1 => $this->formatPlural(1, '1 item', '@count items'),
        3 => $this->formatPlural(3, '1 item', '@count items'),
        5 => $this->formatPlural(5, '1 item', '@count items'),
        10 => $this->formatPlural(10, '1 item', '@count items'),
      ],
      '#default_value' => $this->options['calendar_type'] != 'day' ? $this->options['max_items'] : 0,
      '#description' => $this->t('Maximum number of items to show in calendar cells, used to keep the calendar from expanding to a huge size when there are lots of items in one day.'),
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => ['value' => 'month'],
        ],
      ],
    ];
    $form['max_items_behavior'] = [
      '#title' => $this->t('Too many items'),
      '#type' => 'select',
      '#options' => [
        'more' => $this->t("Show maximum, add 'more' link"),
        'hide' => $this->t('Hide all, add link to day'),
      ],
      '#default_value' => $this->options['calendar_type'] != 'day' ? $this->options['max_items_behavior'] : 'more',
      '#description' => $this->t('Behavior when there are more than the above number of items in a single day. When there more items than this limit, a link to the day view will be displayed.'),
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => ['value' => 'month'],
        ],
      ],
    ];
    $form['groupby_times'] = [
      '#title' => $this->t('Time grouping'),
      '#type' => 'select',
      '#default_value' => $this->options['groupby_times'],
      '#description' => $this->t("Group items together into time periods based on their start time."),
      '#options' => [
        '' => $this->t('None'),
        'hour' => $this->t('Hour'),
        'half' => $this->t('Half hour'),
        'custom' => $this->t('Custom'),
      ],
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => [
            ['value' => 'day'],
            ['value' => 'week'],
          ],
        ],
      ],
    ];
    $form['groupby_times_custom'] = [
      '#title' => $this->t('Custom time grouping'),
      '#type' => 'textarea',
      '#default_value' => $this->options['groupby_times_custom'],
      '#description' => $this->t("When choosing the 'custom' Time grouping option above, create custom time period groupings as a comma-separated list of 24-hour times in the format HH:MM:SS, like '00:00:00,08:00:00,18:00:00'. Be sure to start with '00:00:00'. All items after the last time will go in the final group."),
      '#states' => [
        'visible' => [
          ':input[name="style_options[groupby_times]"]' => ['value' => 'custom'],
        ],
      ],
    ];
    $form['theme_style'] = [
      '#title' => $this->t('Overlapping time style'),
      '#default_value' => $this->options['theme_style'],
      '#type' => 'select',
      '#options' => [
        0 => $this->t('Do not display overlapping items'),
        1 => $this->t('Display overlapping items, with scrolling'),
        2 => $this->t('Display overlapping items, no scrolling'),
      ],
      '#description' => $this->t('Select whether calendar items are displayed as overlapping items. Use scrolling to shrink the window and focus on the selected items, or omit scrolling to display the whole day. This only works if hour or half hour time grouping is chosen!'),
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => [
            ['value' => 'day'],
            ['value' => 'week'],
          ],
        ],
      ],
    ];

    // Create a list of fields that are available for grouping.
    $field_options = [];
    $fields = $this->view->display_handler->getOption('fields');
    foreach ($fields as $field_name => $field) {
      $field_options[$field_name] = $field['field'];
    }
    $form['groupby_field'] = [
      '#title' => $this->t('Field grouping'),
      '#type' => 'select',
      '#default_value' => $this->options['groupby_field'],
      '#description' => $this->t("Optionally group items into columns by a field value, for instance select the content type to show items for each content type in their own column, or use a location field to organize items into columns by location. NOTE! This is incompatible with the overlapping style option."),
      '#options' => ['' => ''] + $field_options,
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => ['value' => 'day'],
        ],
      ],
    ];
    $form['multiday_theme'] = [
      '#title' => $this->t('Multi-day style'),
      '#default_value' => $this->options['multiday_theme'],
      '#type' => 'select',
      '#options' => [
        0 => $this->t('Display multi-day item as a single column'),
        1 => $this->t('Display multi-day item as a multiple column row'),
      ],
      '#description' => $this->t('If selected, items which span multiple days will displayed as a multi-column row.  If not selected, items will be displayed as an individual column.'),
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => [
            ['value' => 'month'],
            ['value' => 'week'],
          ],
        ],
      ],
    ];
    $form['multiday_hidden'] = [
      '#title' => $this->t('Fields to hide in Multi-day rows'),
      '#default_value' => $this->options['multiday_hidden'],
      '#type' => 'checkboxes',
      '#options' => $field_options,
      '#description' => $this->t('Choose fields to hide when displayed in multi-day rows. Usually you only want to see the title or Colorbox selector in multi-day rows and would hide all other fields.'),
      '#states' => [
        'visible' => [
          ':input[name="style_options[calendar_type]"]' => [
            ['value' => 'month'],
            ['value' => 'week'],
            ['value' => 'day'],
          ],
        ],
      ],
    ];

    // Allow custom Day and Week links.
    $form['granularity_links'] = ['#tree' => TRUE];
    $form['granularity_links']['day'] = [
      '#title' => $this->t('Day link displays'),
      '#type' => 'select',
      '#default_value' => $this->options['granularity_links']['day'],
      '#description' => $this->t("Optionally select a View display to use for Day links."),
      '#options' => ['' => $this->t('Default display')] + $this->viewOptionsForGranularity('day'),
    ];
    $form['granularity_links']['week'] = [
      '#title' => $this->t('Week link displays'),
      '#type' => 'select',
      '#default_value' => $this->options['granularity_links']['week'],
      '#description' => $this->t("Optionally select a View display to use for Week links."),
      '#options' => ['' => $this->t('Default display')] + $this->viewOptionsForGranularity('week'),
    ];
    $form['granularity_links']['month'] = [
      '#title' => $this->t('Month link displays'),
      '#type' => 'select',
      '#default_value' => $this->options['granularity_links']['month'],
      '#description' => $this->t("Optionally select a View display to use for Month links."),
      '#options' => ['' => $this->t('Default display')] + $this->viewOptionsForGranularity('month'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function validateOptionsForm(&$form, FormStateInterface $form_state) {
    $groupby_times = $form_state->getValue(['style_options', 'groupby_times']);
    $groupby_times_custom_is_empty = $form_state->isValueEmpty([
      'style_options',
      'groupby_times_custom',
    ]);
    if ($groupby_times == 'custom' && $groupby_times_custom_is_empty) {
      $form_state->setErrorByName('groupby_times_custom', $this->t('Custom groupby times cannot be empty.'));
    }

    $theme_style_is_empty = $form_state->isValueEmpty([
      'style_options',
      'theme_style',
    ]);
    $is_hour_half_hour = in_array($groupby_times, ['hour', 'half']);
    $hour_half_hour_grouping = (empty($groupby_times) || !$is_hour_half_hour);
    if (!$theme_style_is_empty && $hour_half_hour_grouping) {
      $form_state->setErrorByName('theme_style', $this->t('Overlapping items only work with hour or half hour groupby times.'));
    }

    $groupby_field_is_empty = $form_state->isValueEmpty([
      'style_options',
      'group_by_field',
    ]);
    if (!$theme_style_is_empty && !$groupby_field_is_empty) {
      $form_state->setErrorByName('theme_style', $this->t('You cannot use overlapping items and also try to group by a field value.'));
    }
    if ($groupby_times != 'custom') {
      $form_state->setValue(['style_options', 'groupby_times_custom'], NULL);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    $multiday_hidden = $form_state->getValue([
      'style_options',
      'multiday_hidden',
    ]);
    $form_state->setValue([
      'style_options',
      'multiday_hidden',
    ], array_filter($multiday_hidden));
    parent::submitOptionsForm($form, $form_state);
  }

  /**
   * Checks if this view uses the calendar row plugin.
   *
   * @return bool
   *   True if it does, false if it doesn't.
   */
  protected function hasCalendarRowPlugin() {
    return $this->view->rowPlugin instanceof CalendarRow;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    // @todo Move to $this->validate()
    if (empty($this->view->rowPlugin) || !$this->hasCalendarRowPlugin()) {
      dump('\Drupal\calendar\Plugin\views\style\CalendarStyle: The calendar row plugin is required when using the calendar style, but it is missing.');
      return;
    }
    if (!$argument = CalendarHelper::getDateArgumentHandler($this->view)) {
      dump('\Drupal\calendar\Plugin\views\style\CalendarStyle: A calendar date argument is required when using the calendar style, to add a date argument in a view, please go to Advanced > Contextual Filters on the views configuration panel.');
      return;
    }

    $this->initCellSlots();

    if (!$argument->validateValue()) {
      if (!$argument->getDateArg()->getValue()) {
        $msg = 'No calendar date argument value was provided.';
      }
      else {
        $msg = $this->t('The value <strong>@value</strong> is not a valid date argument for @granularity',
          [
            '@value' => $argument->getDateArg()->getValue(),
            '@granularity' => $argument->getGranularity(),
          ]
        );
      }
      $this->messenger->addError($msg);
      return;
    }

    $first_day = \Drupal::config('system.date')->get('first_day');

    // Add information from the date argument to the view.
    $this->dateInfo->setGranularity($argument->getGranularity());
    $this->dateInfo->setCalendarType($this->options['calendar_type']);
    $this->dateInfo->setDateArgument($argument->getDateArg());
    $this->dateInfo->setMinYear($argument->getMinDate()->format('Y'));
    $this->dateInfo->setMinMonth($argument->getMinDate()->format('n'));
    $this->dateInfo->setMinDay($argument->getMinDate()->format('j'));
    // @todo We shouldn't use DATETIME_DATE_STORAGE_FORMAT.
    $min_week = CalendarHelper::dateWeek(date_format($argument->getMinDate(), DateTimeItemInterface::DATE_STORAGE_FORMAT));
    $this->dateInfo->setMinWeek($min_week);
    // $this->dateInfo->setRange($argument->options['calendar']['date_range']);
    $min_date = $argument->getMinDate();
    $max_date = $argument->getMaxDate();
    $max_date->add(\DateInterval::createFromDateString($first_day . ' day'));
    $this->dateInfo->setMinDate($min_date);
    $this->dateInfo->setMaxDate($max_date);
    // @todo implement limit
    // $this->dateInfo->limit = $argument->limit;
    // @todo What if the display doesn't have a route?
    // $this->dateInfo->url = $this->view->getUrl();
    $forbid = $argument->getDateArg()->forbid ?? FALSE;
    $this->dateInfo->setForbid($forbid);

    // Add calendar style information to the view.
    $this->styleInfo->setCalendarPopup($this->displayHandler->getOption('calendar_popup'));
    $this->styleInfo->setMonthNameSize($this->options['month_name_size']);
    $this->styleInfo->setNameSize($this->options['name_size']);
    $this->styleInfo->setMini($this->options['mini']);
    $this->styleInfo->setShowWeekNumbers($this->options['with_weekno']);
    $this->styleInfo->setMultiDayTheme($this->options['multiday_theme']);
    $this->styleInfo->setThemeStyle($this->options['theme_style']);
    $this->styleInfo->setMaxItems($this->options['max_items']);
    $this->styleInfo->setMaxItemsStyle($this->options['max_items_behavior']);
    if (!empty($this->options['groupby_times_custom'])) {
      $this->styleInfo->setGroupByTimes(explode(',', $this->options['groupby_times_custom']));
    }
    else {
      $this->styleInfo->setGroupByTimes(calendar_groupby_times($this->options['groupby_times']));
    }
    $this->styleInfo->setCustomGroupByField($this->options['groupby_field']);

    // @todo make this an option setting.
    $showtimesEmpty = !empty($this->options['groupby_times_custom']) ? TRUE : FALSE;
    $this->styleInfo->setShowEmptyTimes($showtimesEmpty);

    // Set up parameters for the current view that can be used by row plugin.
    $display_timezone = date_timezone_get($this->dateInfo->getMinDate());
    $this->dateInfo->setTimezone($display_timezone);

    // @todo min and max date timezone info shouldn't be stored separately.
    $date = clone($this->dateInfo->getMinDate());
    date_timezone_set($date, $display_timezone);
    $date = clone($this->dateInfo->getMaxDate());
    date_timezone_set($date, $display_timezone);
    // Let views render fields the way it thinks they should look before we
    // start massaging them.

    $this->renderFields($this->view->result);

    // Invoke the row plugin to massage each result row into calendar items.
    // Gather the row items into an array grouped by date and time.
    $items = [];
    foreach ($this->view->result as $row_index => $row) {
      $this->view->row_index = $row_index;
      $events = $this->view->rowPlugin->render($row);
      // @todo Check what comes out here.
      /** @var \Drupal\calendar\CalendarEvent $event_info */
      foreach ($events as $event_info) {
        $item_start = $event_info->calendar_start_date->format('Y-m-d');
        $item_end = $event_info->calendar_end_date->format('Y-m-d');
        $time_start = $event_info->calendar_start_date->format('H:i:s');
        $event_info->setRenderedFields($this->rendered_fields[$row_index]);
        $items[$item_start][$time_start][] = $event_info;
      }
    }

    ksort($items);

    $rows = [];
    $this->currentDay = clone($this->dateInfo->getMinDate());
    $this->items = $items;

    // Retrieve results array using method for the granularity of the display.
    switch ($this->options['calendar_type']) {
      case 'year':
        $rows = [];
        $this->styleInfo->setMini(TRUE);
        for ($i = 1; $i <= 12; $i++) {
          $rows[$i] = $this->calendarBuildMiniMonth();
        }
        $this->styleInfo->setMini(FALSE);
        break;

      case 'month':
        $rows = !empty($this->styleInfo->isMini()) ? $this->calendarBuildMiniMonth() : $this->calendarBuildMonth();
        break;

      case 'day':
        $rows = $this->calendarBuildDay();
        break;

      case 'week':
        $rows = $this->calendarBuildWeek();
        // Merge the day names in as the first row.
        $rows = array_merge([CalendarHelper::weekHeader($this->view)], $rows);
        break;
    }

    // Send the sorted rows to the right theme for this type of calendar.
    $this->definition['theme'] = 'calendar_' . $this->options['calendar_type'];

    // Adjust the theme to match the currently selected default.
    // Only the month view needs the special 'mini' class,
    // which is used to retrieve a different, more compact, theme.

    if ($this->options['calendar_type'] == 'month' && !empty($this->styleInfo->isMini())) {
      $this->definition['theme'] = 'calendar_mini';
    }
    // If the overlap option was selected, choose overlap version of the theme.
    elseif (in_array($this->options['calendar_type'], [
        'week',
        'day',
      ]) && !empty($this->options['multiday_theme'])) {
      $this->definition['theme'] .= '_overlap';
    }

    $output = [
      '#theme' => $this->themeFunctions(),
      '#view' => $this->view,
      '#options' => $this->options,
      '#rows' => $rows,
    ];

    unset($this->view->row_index);
    return $output;
  }

  /**
   * Build one month.
   */
  public function calendarBuildMonth() {
    $week_days = CalendarHelper::weekDays(TRUE);
    $week_days = CalendarHelper::weekDaysOrdered($week_days);
    $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $min_date = $this->dateInfo->getMinDate()->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $max_date = $this->dateInfo->getMaxDate()->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $today = new \DateTime();
    $today = $today->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $day = $this->currentDay->format('j');
    $this->currentDay->modify('-' . strval($day - 1) . ' days');
    $rows = [];

    do {
      $init_day = clone($this->currentDay);
      $month = $this->currentDay->format('n');
      $week = CalendarHelper::dateWeek($current_day_date);

      $first_day = \Drupal::config('system.date')->get('first_day');
      [$multiday_buckets, $singleday_buckets, $total_rows] = array_values($this->calendarBuildWeek(TRUE));

      $output = [];
      $final_day = clone($this->currentDay);

      $iehint = 0;
      $max_multirow_count = 0;
      $max_singlerow_count = 0;

      for ($i = 0; $i < intval($total_rows + 1); $i++) {
        $inner = [];

        // If we're displaying the week number, add it as the first cell in the
        // week.
        if ($i == 0
          && !empty($this->styleInfo->isShowWeekNumbers())
          && !in_array($this->dateInfo->getGranularity(), ['day', 'week'])
        ) {
          $url = CalendarHelper::getURLForGranularity($this->view, 'week', [$this->dateInfo->getMinYear() . $week]);
          if (!empty($url)) {
            $week_number = [
              '#type' => 'link',
              '#url' => $url,
              '#title' => $week,
            ];
          }
          else {
            // Do not link week numbers, if week views are disabled.
            $week_number = $week;
          }
          $item = [
            'entry' => $week_number,
            'colspan' => 1,
            'rowspan' => $total_rows + 1,
            'id' => $this->view->id() . '-weekno-' . $current_day_date,
            'class' => 'week',
          ];
          $inner[] = [
            '#theme' => 'calendar_month_col',
            '#item' => $item,
          ];
        }

        $this->currentDay = clone($init_day);

        // Move backwards to the first day of the week.
        $day_week_day = $this->currentDay->format('w');
        $this->currentDay->modify('-' . ((7 + $day_week_day - $first_day) % 7) . ' days');

        foreach ($week_days as $week_day => $day_object) {
          $item = NULL;
          $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
          $in_month = !(
            $current_day_date < $min_date
            || $current_day_date > $max_date
            || $this->currentDay->format('n') != $month
          );

          // Add the datebox.
          if ($i == 0) {
            $item = [
              'entry' => [
                '#theme' => 'calendar_datebox',
                '#date' => $current_day_date,
                '#view' => $this->view,
                '#items' => $this->items,
                '#selected' => ($in_month) ? (bool) (count($multiday_buckets[$week_day]) + count($singleday_buckets[$week_day])) : FALSE,
              ],
              'colspan' => 1,
              'rowspan' => 1,
              'class' => 'date-box',
              'date' => $current_day_date,
              'id' => $this->view->id() . '-' . $current_day_date . '-date-box',
              'header_id' => $day_object->render(),
              'day_of_month' => $this->currentDay->format('j'),
            ];
            $item['class'] .= ($current_day_date == $today && $in_month ? ' today' : '') .
              ($current_day_date < $today ? ' past' : '') .
              ($current_day_date > $today ? ' future' : '') .
              ($this->isPastMonth($this->currentDay->format('n'), $month) ? ' past-month' : '') .
              ($this->isFutureMonth($this->currentDay->format('n'), $month) ? ' future-month' : '');

            if (count($singleday_buckets[$week_day]) == 0) {
              if ($max_multirow_count == 0) {
                $item['class'] .= ' no-entry';
              }
            }
          }
          else {
            $index = $i - 1;
            $multi_count = count($multiday_buckets[$week_day]);

            // Process multiday buckets first.
            if ($index < $multi_count) {
              // If this item is filled with either a blank or an entry.
              if (isset($multiday_buckets[$week_day][$index]['filled']) && ($multiday_buckets[$week_day][$index]['filled'])) {

                // Add item and add class.
                $item = $multiday_buckets[$week_day][$index];
                $item['class'] = 'multi-day';
                $item['date'] = $current_day_date;

                // Check wheter this is an entry.
                if (!$multiday_buckets[$week_day][$index]['avail']) {

                  // If the item either starts or ends on today,then add tags so
                  // we can style the borders.
                  if ($current_day_date == $today && $in_month) {
                    $item['class'] .= ' starts-today';
                  }

                  // Calculate on which day of this week this item ends on.
                  $end_day = clone($this->currentDay);
                  $span = $item['colspan'] - 1;
                  $end_day->modify('+' . $span . ' day');
                  $end_day_date = $end_day->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);

                  // If it ends today, add class.
                  if ($end_day_date == $today && $in_month) {
                    $item['class'] .= ' ends-today';
                  }
                }
              }

              // If this is an actual entry, add classes regarding the state of
              // the item.
              if (isset($multiday_buckets[$week_day][$index]['avail']) && $multiday_buckets[$week_day][$index]['avail']) {
                $item['class'] .= ' ' . $week_day . ' ' . $index . ' no-entry ';
                $item['class'] .= ($current_day_date == $today && $in_month ? ' today' : '') .
                  ($current_day_date < $today ? ' past' : '') .
                  ($current_day_date > $today ? ' future' : '') .
                  ($this->isPastMonth($this->currentDay->format('n'), $month) ? ' past-month' : '') .
                  ($this->isFutureMonth($this->currentDay->format('n'), $month) ? ' future-month' : '');
              }
            }
            elseif ($index == $multi_count) {
              $single_day_count = 0;
              $single_days = '';
              // If it's empty, add class.
              if (count($singleday_buckets[$week_day]) == 0) {
                if ($max_multirow_count == 0) {
                  $class = ($multi_count > 0) ? 'single-day no-entry noentry-multi-day' : 'single-day no-entry';
                }
                else {
                  $class = 'single-day';
                }
              }
              else {
                $single_days = [];
                foreach ($singleday_buckets[$week_day] as $day) {
                  foreach ($day as $event) {
                    $single_day_count++;
                    if (isset($event['more_link'])) {
                      // @todo more logic
                    }
                    else {
                      $single_days[] = $event['entry'];
                    }
                  }
                }
                $class = 'single-day';
              }

              $rowspan = $total_rows - $index;
              $item = [
                'entry' => $single_days,
                'colspan' => 1,
                'rowspan' => $rowspan,
                'class' => $class,
                'date' => $current_day_date,
                'id' => $this->view->id() . '-' . $current_day_date . '-' . $index,
                'header_id' => $week_days[$week_day],
                'day_of_month' => $this->currentDay->format('j'),
              ];

              // Hack for ie to help it properly space single day rows.
              // @todo do we still need this?
              if ($rowspan > 1 && $in_month && $single_day_count > 0) {
                $max_multirow_count = max($max_multirow_count, $single_day_count);
              }
              else {
                $max_singlerow_count = max($max_singlerow_count, $single_day_count);
              }

              // If the single row is bigger than the multi-row, then null out
              // ieheight - I'm estimating that a single row is twice the size
              // of multi-row. This is really the best that can be done with ie.
              if ($max_singlerow_count >= $max_multirow_count || $max_multirow_count <= $multi_count / 2) {
                $iehint = 0;
              }
              elseif ($rowspan > 1 && $in_month && $single_day_count > 0) {
                // Calculate how many rows we need to adjust.
                $iehint = max($iehint, $rowspan - 1);
              }

              // Set the class.
              $item['class'] .= ($current_day_date == $today && $in_month ? ' today' : '') .
                ($current_day_date < $today ? ' past' : '') .
                ($current_day_date > $today ? ' future' : '') .
                ($this->isPastMonth($this->currentDay->format('n'), $month) ? ' past-month' : '') .
                ($this->isFutureMonth($this->currentDay->format('n'), $month) ? ' future-month' : '');
            }
          }

          // If there isn't an item, then add empty class.
          if ($item != NULL) {
            if (!$in_month) {
              $item['class'] .= ' empty';
            }
            // Style this entry - it will be a <td>.
            $inner[] = [
              '#theme' => 'calendar_month_col',
              '#item' => $item,
            ];
          }
          $this->currentDay->modify('+1 day');
        }

        if ($i == 0) {
          $output[] = [
            '#theme' => 'calendar_month_row',
            '#inner' => $inner,
            '#class' => 'date-box',
            '#iehint' => $iehint,
          ];
        }
        elseif ($i == $total_rows) {
          $output[] = [
            '#theme' => 'calendar_month_row',
            '#inner' => $inner,
            '#class' => 'single-day',
            '#iehint' => $iehint,
          ];
          $iehint = 0;
          $max_singlerow_count = 0;
          $max_multirow_count = 0;
        }
        else {
          // Style all the columns into a row.
          $output[] = [
            '#theme' => 'calendar_month_row',
            '#inner' => $inner,
            '#class' => 'multi-day',
            '#iehint' => 0,
          ];
        }
      }
      $this->currentDay = $final_day;

      // Add the row into the row array.
      $rows[] = ['data' => $output];

      $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
      $current_day_month = $this->currentDay->format('n');
    }
    while ($current_day_month == $month && $current_day_date <= $max_date);

    // Merge the day names in as the first row.
    $rows = array_merge([CalendarHelper::weekHeader($this->view)], $rows);
    return $rows;
  }

  /**
   * Build one mini month.
   */
  public function calendarBuildMiniMonth() {
    $month = $this->currentDay->format('n');
    $day = $this->currentDay->format('j');
    $this->currentDay->modify('-' . strval($day - 1) . ' days');
    $max_date = $this->dateInfo->getMaxDate()->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $rows = [];

    do {
      $rows = array_merge($rows, $this->calendarBuildMiniWeek());
      $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
      $current_day_month = $this->currentDay->format('n');
    }
    while ($current_day_month == $month && $current_day_date <= $max_date);

    // Merge the day names in as the first row.
    $rows = array_merge([CalendarHelper::weekHeader($this->view)], $rows);
    return $rows;
  }

  /**
   * Build one week row.
   *
   * @param bool $check_month
   *   TRUE to check the rest of the month, FALSE otherwise.
   *
   * @return array
   *   An assosiative array containing the multiday buckets, the singleday
   *   buckets and the total row count.
   */
  public function calendarBuildWeek($check_month = FALSE) {
    $week_days = CalendarHelper::weekDays(TRUE);
    $week_days = CalendarHelper::weekDaysOrdered($week_days);
    $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $min_date = $this->dateInfo->getMinDate()->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $max_date = $this->dateInfo->getMaxDate()->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $month = $this->currentDay->format('n');
    $first_day = \Drupal::config('system.date')->get('first_day');

    // Set up buckets.
    $total_rows = 0;

    // Move backwards to the first day of the week.
    $day_week_day = $this->currentDay->format('w');
    $this->currentDay->modify('-' . ((7 + $day_week_day - $first_day) % 7) . ' days');
    $multiday_theme = !empty($this->styleInfo->getMultiDayTheme()) && $this->styleInfo->getMultiDayTheme() == '1';

    foreach ($week_days as $i => $day_object) {
      // Create each bucket on the fly so it goes with the correct key order.
      $multiday_buckets[$i] = [];
      $singleday_buckets[$i] = [];

      if ($check_month && ($current_day_date < $min_date || $current_day_date > $max_date || $this->currentDay->format('n') != $month)) {
        $singleday_buckets[$i][][] = [
          'entry' => [
            '#theme' => 'calendar_empty_day',
            '#curday' => $current_day_date,
            '#view' => $this->view,
          ],
          'item' => NULL,
        ];
      }
      else {
        $this->calendarBuildWeekDay($i, $multiday_buckets, $singleday_buckets);
      }

      if ($multiday_theme) {
        $total_rows = max(count($multiday_buckets[$i]) + 1, $total_rows);
      } else {
        $total_rows = max(count($singleday_buckets[$i]) + 1, $total_rows);
      }

      $this->currentDay->modify('+1 day');
      $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    }

    return [
      'multiday_buckets' => $multiday_buckets,
      'singleday_buckets' => $singleday_buckets,
      'total_rows' => $total_rows,
    ];
  }

  /**
   * Build one mini week row.
   *
   * @param bool $check_month
   *   TRUE to check the rest of the month, FALSE otherwise.
   *
   * @return array
   *   An array of rows with render info.
   */
  public function calendarBuildMiniWeek($check_month = FALSE) {
    $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $weekdays = CalendarHelper::untranslatedDays();
    $today = $this->dateFormatter->format($this->time->getRequestTime(), 'custom', DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $min_date = $this->dateInfo->getMinDate()->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $max_date = $this->dateInfo->getMaxDate()->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $month = $this->currentDay->format('n');
    $week = CalendarHelper::dateWeek($current_day_date);

    $first_day = \Drupal::config('system.date')->get('first_day');
    // Move backwards to the first day of the week.
    $day_week_day = $this->currentDay->format('w');
    $this->currentDay->modify('-' . ((7 + $day_week_day - $first_day) % 7) . ' days');

    $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);

    if (!empty($this->styleInfo->isShowWeekNumbers())) {
      $url = CalendarHelper::getURLForGranularity($this->view, 'week', [$this->dateInfo->getMinYear() . sprintf('%02s', $week)]);
      if (!empty($url)) {
        $week_number = [
          '#type' => 'link',
          '#url' => $url,
          '#title' => $week,
        ];
      }
      else {
        // Do not link week numbers, if Week views are disabled.
        $week_number = $week;
      }
      $rows[$week][] = [
        'data' => $week_number,
        'class' => 'mini week',
        'id' => $this->view->id() . '-weekno-' . $current_day_date,
      ];
    }

    for ($i = 0; $i < 7; $i++) {
      $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
      $class = strtolower($weekdays[$this->currentDay->format('w')] . ' mini');

      if ($check_month && ($current_day_date < $min_date || $current_day_date > $max_date || $this->currentDay->format('n') != $month)) {
        $class .= ' empty';

        $content = [
          'date' => '',
          'datebox' => '',
          'empty' => [
            '#theme' => 'calendar_empty_day',
            '#curday' => $current_day_date,
            '#view' => $this->view,
          ],
          'link' => '',
          'all_day' => [],
          'items' => [],
        ];
      }
      else {
        $content = $this->calendarBuildDay();
        $class .= ($current_day_date == $today ? ' today' : '') .
          ($current_day_date < $today ? ' past' : '') .
          ($current_day_date > $today ? ' future' : '') .
          ($this->isPastMonth($this->currentDay->format('n'), $month) ? ' past-month' : '') .
          ($this->isFutureMonth($this->currentDay->format('n'), $month) ? ' future-month' : '') .
          (empty($this->items[$current_day_date]) ? ' has-no-events' : ' has-events');
      }
      $rows[$week][] = [
        'data' => $content,
        'class' => $class,
        'id' => $this->view->id() . '-' . $current_day_date,
      ];
      $this->currentDay->modify('+1 day');
    }
    return $rows;
  }

  /**
   * {@inheritDoc}
   */
  private function isPastMonth(int $month, int $current_month) {
    if ($current_month == 1 && $month == 12) {
      return TRUE;
    }
    elseif ($current_month == 12 && $month == 1) {
      return FALSE;
    }
    else {
      return $month < $current_month;
    }
  }

  /**
   * {@inheritDoc}
   */
  private function isFutureMonth(int $month, int $current_month) {
    if ($current_month == 12 && $month == 1) {
      return TRUE;
    }
    elseif ($current_month == 1 && $month == 12) {
      return FALSE;
    }
    else {
      return $month > $current_month;
    }
  }

  /**
   * Empty cell slots
   */
  public function initCellSlots () {
    $this->cellSlots = [];
  }

  /**
   * Check if slot is empty or not
   *
   * @param int $wday
   *   The index of the day.
   * @param int $index
   *   The index of row.
   */
  public function isCellSlotEmpty ($index, $wday) {
    if (!isset($this->cellSlots[$index][$wday])) return true;
    return ($this->cellSlots[$index][$wday] === 0);
  }

  /**
   * Fill slot
   *
   * @param any $initial_value
   *   The initial value.
   */
  public function defaultCellSlotRow ($initial_value = 0) {
    $row = [];
    for ($i = 0; $i < 30; $i++) {
      $row[$i] = $initial_value;
    }
    return $row;
  }

  /**
   * Fill slot
   *
   * @param int $wday
   *   The index of the day.
   * @param int $index
   *   The index of row.
   * @param boolean|any $data
   *   The slot data.
   */
  public function setCellSlot ($index, $wday, $length = 1, $data = 1) {
    if (!isset($this->cellSlots[$index])) $this->cellSlots[$index] = $this->defaultCellSlotRow();
    for ($i = $wday; $i < $wday + $length; $i++) {
      if ($i < 31) $this->cellSlots[$index][$i] = $data;
    }
  }

  /**
   * Find empty slot
   *
   * @param int $wday
   *   The index of the day.
   * @param int $index
   *   The index of row.
   * @param boolean|any $data
   *   The slot data.
   */
  public function findEmptyCellFloor ($wday, $length = 1, $only_append = false) {

    $empty_found = false;

    if (!$only_append) {
      for ($i = 0; $i < count($this->cellSlots); $i++) {
        if ($length > 0) $empty_found = true;
        for ($j=$wday; $j<$wday + $length; $j++) {
          if (!$this->isCellSlotEmpty($i, $j)) {
            $empty_found = false;
            break;
          }
        }
        if ($empty_found) {
          return $i;
        }
      }
    }

    $this->cellSlots[] = $this->defaultCellSlotRow();
    return count($this->cellSlots) - 1;
  }

  /**
   * Find empty slot
   *
   * @param int $wday
   *   The index of the day.
   * @param int $index
   *   The index of row.
   * @param boolean|any $data
   *   The slot data.
   */
  public function findCellFloorById ($wday, $data) {
    for ($i = 0; $i < count($this->cellSlots); $i++) {
      if ($this->cellSlots[$i][$wday] == $data) {
        return $i;
      }
    }

    return false;
  }


  /**
   * Fill in the selected day info into the event buckets.
   *
   * @param int $wday
   *   The index of the day to fill in the event info for.
   * @param array $multiday_buckets
   *   The buckets holding multiday event info for a week.
   * @param array $singleday_buckets
   *   The buckets holding singleday event info for a week.
   */
  public function calendarBuildWeekDay($wday, array &$multiday_buckets, array &$singleday_buckets) {
    // Initialize $cellSots if it's start of the week.
    $first_day = \Drupal::config('system.date')->get('first_day');
    if ($wday == $first_day) $this->initCellSlots();

    $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $day_value = intval($this->currentDay->format('j'));

    $max_events = $this->dateInfo->getCalendarType() == 'month' && !empty($this->styleInfo->getMaxItems()) ? $this->styleInfo->getMaxItems() : 0;
    $hide = !empty($this->styleInfo->getMaxItemsStyle()) ? ($this->styleInfo->getMaxItemsStyle() == 'hide') : FALSE;
    $multiday_theme = !empty($this->styleInfo->getMultiDayTheme()) && $this->styleInfo->getMultiDayTheme() == '1';
    $current_count = 0;
    $total_count = 0;
    $ids = [];

    // If we are hiding, count before processing further.
    if ($max_events != CALENDAR_SHOW_ALL) {
      foreach ($this->items as $date => $day) {
        if ($date == $current_day_date) {
          foreach ($day as $time => $hour) {
            foreach ($hour as $key => $item) {
              $total_count++;
              $ids[] = $item->date_id;
            }
          }
        }
      }
    }

    // If we haven't already exceeded the max or we'll showing all, then process
    // the items.
    if ($max_events == CALENDAR_SHOW_ALL || !$hide || $total_count < $max_events) {
      // Count currently filled items.
      foreach ($multiday_buckets[$wday] as $bucket) {
        if (!$bucket['avail']) {
          $current_count++;
        }
      }
      foreach ($this->items as $date => $day) {
        if ($date == $current_day_date) {
          ksort($day);
          foreach ($day as $time => $hour) {
            /** @var \Drupal\calendar\CalendarEvent $item */
            foreach ($hour as $key => $item) {
              $all_day = $item->isAllDay();

              // Parse out date part.
              $start_ydate = $this->dateFormatter->format($item->getStartDate()->getTimestamp(), 'custom', 'Y-m-d');
              $end_ydate = $this->dateFormatter->format($item->getEndDate()->getTimestamp(), 'custom', 'Y-m-d');
              $cur_ydate = $this->dateFormatter->format($this->currentDay->getTimestamp(), 'custom', 'Y-m-d');

              $cur_ydate_1 = date('Y-m-d', strtotime($cur_ydate . ' +1 day'));

              $is_multi_day = ($start_ydate < $cur_ydate || $end_ydate > $cur_ydate);

              $day = \DateTime::createFromFormat('Y-m-d', $cur_ydate_1);
              $day->setISODate((int)$day->format('o'), (int) $day->format('W'), 1);
              $f = date('Y-m-d', strtotime($day->format('Y-m-d') . ' -1 day'));

              // Check if the event spans over multiple days.
              if ($multiday_theme && ($is_multi_day || $all_day)) {

                // Remove multiday items from the total count. We can't hide
                // them or they will break.
                $total_count--;

                // If this the first day of the week, or is the start date of
                // the multi-day event, then record this item, otherwise skip
                // over.
                $day_no = $this->currentDay->format('d');
                if ($wday == $first_day || $start_ydate == $cur_ydate || ($this->dateInfo->getGranularity() == 'week')  || ($this->dateInfo->getGranularity() == 'month') || ($all_day && !$is_multi_day)) {
                  // Calculate the colspan for this event.
                  // If the last day of this event exceeds the end of the
                  // current month or week, truncate the remaining days.
                  $diff = CalendarHelper::difference($this->currentDay, $this->dateInfo->getMaxDate(), 'days');
                  $remaining_days = $diff - 1;
                  if ($this->dateInfo->getGranularity() == 'month') {
                    // Need to limit to week days
                    $week_limit = ($wday < $first_day) ? ($first_day - $wday - 1) : (6 - $wday + $first_day);
                    $remaining_days = min($week_limit, $diff);
                  }

                  // if ($start_ydate == $cur_ydate || $cur_ydate == $f) {
                  if ($start_ydate == $cur_ydate || $wday == $first_day) {

                    // The bucket_cnt defines colspan. colspan = bucket_cnt + 1.
                    $days = CalendarHelper::difference($this->currentDay, $item->getEndDate(), 'days');
                    $bucket_cnt = max(0, min($days, $remaining_days));

                    // Add continuation attributes.
                    $item->continuation = $item->getStartDate() < $this->currentDay;
                    $item->continues = $days > $bucket_cnt;
                    $item->is_multi_day = TRUE;
                    // Get new floor index available.
                    $tr_floor = $this->findEmptyCellFloor($day_value - 1, $bucket_cnt + 1);
                    $this->setCellSlot($tr_floor, $day_value - 1, $bucket_cnt + 1, $item->getEntityId());

                    // Assign the item to the available bucket.
                    // $multiday_buckets[$wday][$bucket_index] = [
                    $multiday_buckets[$wday][$tr_floor] = [
                      'colspan' => $bucket_cnt + 1,
                      'rowspan' => 1,
                      'filled' => TRUE,
                      'avail' => FALSE,
                      'all_day' => $all_day,
                      'item' => $item,
                      'wday' => $wday,
                      'entry' => [
                        '#theme' => 'calendar_item',
                        '#view' => $this->view,
                        '#rendered_fields' => $item->getRenderedFields(),
                        '#item' => $item,
                      ],
                    ];

                  } else {

                    // The bucket_cnt defines colspan. colspan = bucket_cnt + 1.
                    $days = CalendarHelper::difference($this->currentDay, $item->getEndDate(), 'days');
                    $bucket_cnt = max(0, min($days, $remaining_days));

                    // Get new floor index available.
                    $tr_floor = $this->findCellFloorById($day_value - 1, $item->getEntityId());
                    if ($tr_floor !== false) {

                      // Add continuation attributes.
                      $item->continuation = $item->getStartDate() < $this->currentDay;
                      $item->continues = $days > $bucket_cnt;
                      $item->is_multi_day = TRUE;

                      // Assign the item to the available bucket.
                      // $multiday_buckets[$wday][$bucket_index] = [
                      $multiday_buckets[$wday][$tr_floor] = [
                        'colspan' => 0,
                        'rowspan' => 1,
                        'filled' => TRUE,
                        'avail' => FALSE,
                        'all_day' => $all_day,
                        'item' => $item,
                        'wday' => $wday,
                        "dnone" => 'dnone',
                        'entry' => [
                          '#theme' => 'calendar_item',
                          '#view' => $this->view,
                          '#rendered_fields' => $item->getRenderedFields(),
                          '#item' => $item,
                        ],
                      ];

                    }
                  }
                }
              } elseif ($max_events == CALENDAR_SHOW_ALL || $current_count < $max_events) {

                //
                // If you need to add single-day event amid of multiday events, following block should be enabled
                // by removing "false && " in if statement.
                //
                if (false && $multiday_theme) {
                  //
                  // Get new floor index available.
                  $tr_floor = $this->findEmptyCellFloor($day_value - 1, 1);
                  $this->setCellSlot($tr_floor, $day_value - 1, 1, $item->getEntityId());

                  //
                  // Assign the item to the available bucket,
                  // as if it's multiday event.
                  //
                  $multiday_buckets[$wday][$tr_floor] = [
                    'colspan' => 1,
                    'rowspan' => 1,
                    'filled' => TRUE,
                    'avail' => FALSE,
                    'all_day' => $all_day,
                    'item' => $item,
                    'wday' => $wday,
                    'entry' => [
                      '#theme' => 'calendar_item',
                      '#view' => $this->view,
                      '#rendered_fields' => $item->getRenderedFields(),
                      '#item' => $item,
                    ],
                  ];

                } else {
                  $current_count++;
                  // Assign to single day bucket.
                  $singleday_buckets[$wday][$time][] = [
                    'entry' => [
                      '#theme' => 'calendar_item',
                      '#view' => $this->view,
                      '#rendered_fields' => $item->getRenderedFields(),
                      '#item' => $item,
                    ],
                    'item' => $item,
                    'colspan' => 1,
                    'rowspan' => 1,
                    'filled' => TRUE,
                    'avail' => FALSE,
                    'wday' => $wday,
                  ];
                }

              }

            }

          }
        }
      }
    }

    // Add a more link if necessary.
    if ($max_events != CALENDAR_SHOW_ALL && $total_count > 0 && $current_count < $total_count) {
      if (!empty($entry)) {
        $singleday_buckets[$wday][][] = [
          'entry' => [
            '#theme' => 'calendar_' . $this->dateInfo->getCalendarType() . '_multiple_entity',
            '#curday' => $current_day_date,
            '#count' => $total_count,
            '#view' => $this->view,
            '#ids' => $ids,
          ],
          'more_link' => TRUE,
          'item' => NULL,
        ];
      }
    }
    //
    // Fix multiday blank cell issue
    //
    foreach($multiday_buckets as $i => $row) {
      for ($j = 0; $j < count($multiday_buckets[$i]); $j++) {
        if (!isset($multiday_buckets[$i][$j])) {
          $is_empty = $this->isCellSlotEmpty($i, $j);
          $multiday_buckets[$i][$j] = [
            'entry' => '',
            'colspan' => 1,
            'rowspan' => 1,
            'filled' => TRUE,
            'avail' => $is_empty ? TRUE :FALSE,
            'wday' => $i,
            'item' => NULL,
          ];
        }
      }
    }
  }

  /**
   * Build the datebox information for the current day.
   *
   * @return array
   *   An array with information on the current day for use in a datebox.
   *
   * @todo expand documentation
   * If a day has no events, the empty day theme info is added to the return
   * array.
   */
  public function calendarBuildDay() {
    $current_day_date = $this->currentDay->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $selected = FALSE;
    $max_events = !empty($this->styleInfo->getMaxItems()) ? $this->styleInfo->getMaxItems() : 0;
    $ids = [];
    $inner = [];
    $all_day = [];
    $empty = '';
    $link = '';
    $count = 0;
    foreach ($this->items as $date => $day) {
      if ($date == $current_day_date) {
        $count = 0;
        $selected = TRUE;
        ksort($day);
        foreach ($day as $time => $hour) {
          /** @var \Drupal\calendar\CalendarEvent $item */
          foreach ($hour as $key => $item) {
            $count++;
            $ids[$item->getType()] = $item;
            if (empty($this->styleInfo->isMini()) && ($max_events == CALENDAR_SHOW_ALL || $count <= $max_events || ($count > 0 && $max_events == CALENDAR_HIDE_ALL))) {
              if ($item->isAllDay()) {
                $item->setIsMultiDay(TRUE);
                $all_day[] = $item;
              }
              else {
                $this->dateFormatter->format($item->getStartDate()
                  ->getTimestamp(), 'custom', 'H:i:s');
                $inner[$key][] = $item;
              }
            }
          }
        }
      }
    }
    ksort($inner);

    if (empty($inner) && empty($all_day)) {
      $empty = [
        '#theme' => 'calendar_empty_day',
        '#curday' => $current_day_date,
        '#view' => $this->view,
      ];
    }
    // We have hidden events on this day, use the theme('calendar_multiple_')
    // to show a link.
    if ($max_events != CALENDAR_SHOW_ALL && $count > 0 && $count > $max_events && $this->dateInfo->getCalendarType() != 'day' && !$this->styleInfo->isMini()) {
      if ($this->styleInfo->getMaxItemsStyle() == 'hide' || $max_events == CALENDAR_HIDE_ALL) {
        $all_day = [];
        $inner = [];
      }
      $link = [
        '#theme' => 'calendar_' . $this->dateInfo->getCalendarType() . '_multiple_entity',
        '#curday' => $current_day_date,
        '#count' => $count,
        '#view' => $this->view,
        '#ids' => $ids,
      ];
    }

    $content = [
      '#date' => $current_day_date,
      'datebox' => [
        '#theme' => 'calendar_datebox',
        '#date' => $current_day_date,
        '#view' => $this->view,
        '#items' => $this->items,
        '#selected' => $selected,
      ],
      'empty' => $empty,
      'link' => $link,
      'all_day' => $all_day,
      'items' => $inner,
    ];
    return $content;
  }

  /**
   * {@inheritdoc}
   */
  public function validate() {
    $errors = parent::validate();
    $display_id = $this->displayHandler->display['id'];
    if ($display_id == 'default') {
      // @todo Update default display in templates to validate.
      return $errors;
    }
    // @todo Validate row plugin
    $argument = CalendarHelper::getDateArgumentHandler($this->view, $display_id);
    if (empty($argument)) {
      $errors[] = $this->t('\Drupal\calendar\Plugin\views\style\CalendarStyle: A calendar date argument is required when using the calendar style, to add a date argument in a view, please go to Advanced > Contextual Filters on the views configuration panel.');
    }
    return $errors;

  }

  /**
   * Get options for Views displays that support Calendar with set granularity.
   *
   * @param mixed $granularity
   *   Set Granularity Option.
   *
   * @return array
   *   An array with information of the option for the Views displays.
   */
  protected function viewOptionsForGranularity($granularity) {
    $options = [];
    $view_displays = Views::getApplicableViews('uses_route');
    foreach ($view_displays as $view_display) {
      [$view_id, $display_id] = $view_display;

      $view = View::load($view_id);
      $view_exec = $view->getExecutable();
      if ($argument = CalendarHelper::getDateArgumentHandler($view_exec, $display_id)) {
        if ($argument->getGranularity() == $granularity) {
          $route_name = CalendarHelper::getDisplayRouteName($view_id, $display_id);
          $options[$route_name] = $view->label() . ' : ' . $view_exec->displayHandlers->get($display_id)->display['display_title'];
        }
      }

    }
    return $options;
  }

}
