<?php

namespace Drupal\prometheusio\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\prometheusio\PrometheusService;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\APC;
use Prometheus\Storage\Redis;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MetricsController.
 *
 * @package Drupal\prometheusio\Controller
 */
class MetricsController extends ControllerBase {

  /**
   * Access callback.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   An access result.
   */
  public function access() {
    return AccessResult::allowed();
  }

  /**
   * Metrics.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   A metrics response.
   */
  public function metrics() {
    /** @var \Drupal\prometheusio\PrometheusService $prometheus */
    $prometheus = \Drupal::service('prometheusio.default');
    $headers = [
      'Content-type' => PrometheusService::CONTENT_TYPE,
    ];
    $content = $prometheus->getMetrics();
    return new Response($content, 200, $headers);
  }

}
