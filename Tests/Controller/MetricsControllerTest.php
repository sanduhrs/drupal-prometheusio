<?php

namespace Drupal\prometheusio\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the prometheusio module.
 */
class MetricsControllerTest extends WebTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "prometheusio MetricsController's controller functionality",
      'description' => 'Test Unit for module prometheusio and controller MetricsController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests prometheusio functionality.
   */
  public function testMetricsController() {
    // Check that the basic functions of module prometheusio.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via App Console.');
  }

}
