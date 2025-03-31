<?php

namespace Drupal\lp_swiper\Tests\Plugin;

use Drupal\views\Entity\View;
use Drupal\Tests\views\Functional\ViewTestBase;
use Drupal\views\Tests\ViewTestData;

/**
 * Tests the swiper style views plugin.
 *
 * @group views
 */
class StyleSwiperTest extends ViewTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'views',
    'lp_swiper',
    'lp_swiper_cycle',
    'views_test_config',
    'lp_swiper_test',
  ];

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['test_style_swiper'];

  /**
   * {@inheritdoc}
   */
  protected function setUp($import_test_views = TRUE) {
    parent::setUp();

    $this->enableViewsTestModule();
    if ($import_test_views) {
      ViewTestData::createTestViews(get_class($this), ['lp_swiper_test']);
    }
  }

  /**
   * Test swiper display.
   */
  public function testSwiper() {
    $this->drupalGet('test-style-swiper');

    $result = $this->cssSelect('.lp_swiper_main');
    $this->assertEqual(count($result), 1, 'Swiper displayed on page');
  }

  /**
   * Test swiper control widgets.
   */
  public function testSwiperWidgets() {
    $this->drupalGet('test-style-swiper');

    // Ensure no controls are displayed.
    $this->assertFalse(count($this->cssSelect('.lp-swiper-controls-top')));
    $this->assertFalse(count($this->cssSelect('.lp-swiper-controls-bottom')));

    // Test top widget position.
    $view = View::load('test_style_swiper');
    $display = &$view->getDisplay('default');
    $display['display_options']['style']['options']['widgets'] = [
      'top' => [
        'lp_swiper_controls' => [
          'enable' => TRUE,
          'weight' => 1,
          'hide_on_single_slide' => 0,
          'type' => 'lp_swiper_controls_text',
        ],
      ],
    ];
    $view->save();

    $this->drupalGet('test-style-swiper');
    $this->assertTrue(count($this->cssSelect('.lp-swiper-controls-top')));
    $this->assertFalse(count($this->cssSelect('.lp-swiper-controls-bottom')));

    // Test bottom widget position.
    $view = View::load('test_style_swiper');
    $display = &$view->getDisplay('default');
    $display['display_options']['style']['options']['widgets'] = [
      'bottom' => [
        'lp_swiper_controls' => [
          'enable' => TRUE,
          'weight' => 1,
          'hide_on_single_slide' => 0,
          'type' => 'lp_swiper_controls_text',
        ],
      ],
      'top' => [],
    ];
    $view->save();

    $this->drupalGet('test-style-swiper');
    $this->assertFalse(count($this->cssSelect('.lp-swiper-controls-top')));
    $this->assertTrue(count($this->cssSelect('.lp-swiper-controls-bottom')));
  }

}
