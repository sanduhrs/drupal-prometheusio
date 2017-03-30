<?php

namespace Drupal\prometheusio;

use Drupal\Core\Config\ConfigFactory;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\APC;
use Prometheus\Storage\Redis;

/**
 * Class PrometheusService.
 *
 * @package Drupal\prometheusio
 */
class PrometheusService {

  const CONTENT_TYPE = RenderTextFormat::MIME_TYPE;

  /**
   * The storage adapter.
   *
   * @var \Prometheus\Storage\Redis
   */
  protected $adapter;

  /**
   * The collector registry.
   *
   * @var \Prometheus\CollectorRegistry
   */
  protected $registry;

  /**
   * The metrics renderer.
   *
   * @var \Prometheus\RenderTextFormat
   */
  protected $renderer;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->configFactory = $config_factory;
    $config = $this->configFactory->get('prometheusio.settings');

    $is_apc_installed = extension_loaded('apc');
    if ($is_apc_installed) {
      $this->adapter = new APC();
    }
    else {
      $this->adapter = new Redis([
        'host' => $config->get('redis_host'),
        'port' => $config->get('redis_port'),
        'timeout' => $config->get('redis_timeout'),
        'read_timeout' => $config->get('redis_read_timeout'),
        'persistent_connections' => $config->get('redis_persistent_connections'),
      ]);
    }
    $this->registry = new CollectorRegistry($this->adapter);
    $this->renderer = new RenderTextFormat();
  }

  /**
   * Register a counter.
   *
   * @param $namespace
   * @param $name
   * @param $help
   * @param array $labels
   *
   * @return \Prometheus\Counter
   */
  public function registerCounter($namespace, $name, $help, $labels = []) {
    return $this->registry->registerCounter($namespace, $name, $help, $labels);
  }

  /**
   * Get a counter.
   *
   * @param $namespace
   * @param $name
   *
   * @return \Prometheus\Counter
   */
  public function getCounter($namespace, $name) {
    return $this->registry->getCounter($namespace, $name);
  }

  /**
   * Get or Register a counter.
   *
   * @param $namespace
   * @param $name
   * @param $help
   * @param array $labels
   *
   * @return \Prometheus\Counter
   */
  public function getOrRegisterCounter($namespace, $name, $help, $labels = array()) {
    return $this->registry->getOrRegisterCounter($namespace, $name, $help, $labels);
  }

  /**
   * Register a gauge.
   *
   * @param $namespace
   * @param $name
   * @param $help
   * @param array $labels
   *
   * @return \Prometheus\Gauge
   */
  public function registerGauge($namespace, $name, $help, $labels = []) {
    return $this->registry->registerGauge($namespace, $name, $help, $labels);
  }

  /**
   * Get a gauge.
   *
   * @param $namespace
   * @param $name
   *
   * @return \Prometheus\Gauge
   */
  public function getGauge($namespace, $name) {
    return $this->registry->getGauge($namespace, $name);
  }

  /**
   * Get or Register a gauge.
   *
   * @param $namespace
   * @param $name
   * @param $help
   * @param array $labels
   *
   * @return \Prometheus\Gauge
   */
  public function getOrRegisterGauge($namespace, $name, $help, $labels = array()) {
    return $this->registry->getOrRegisterGauge($namespace, $name, $help, $labels);
  }

  /**
   * Register a histogram.
   *
   * @param $namespace
   * @param $name
   * @param $help
   * @param array $labels
   * @param null $buckets
   *
   * @return \Prometheus\Histogram
   */
  public function registerHistogram($namespace, $name, $help, $labels = [], $buckets = NULL) {
    return $this->registry->registerHistogram($namespace, $name, $help, $labels, $buckets);
  }

  /**
   * Get a histogram.
   *
   * @param $namespace
   * @param $name
   *
   * @return \Prometheus\Histogram
   */
  public function getHistogram($namespace, $name) {
    return $this->registry->getHistogram($namespace, $name);
  }

  /**
   * Get or Register a histogram.
   *
   * @param $namespace
   * @param $name
   * @param $help
   * @param array $labels
   * @param null $buckets
   *
   * @return \Prometheus\Histogram
   */
  public function getOrRegisterHistogram($namespace, $name, $help, $labels = array(), $buckets = null) {
    return $this->registry->getOrRegisterHistogram($namespace, $name, $help, $labels, $buckets);
  }

  /**
   * Get metrics.
   *
   * @return string
   */
  public function getMetrics() {
    return $this->renderer->render(
      $this->registry->getMetricFamilySamples()
    );
  }

  /**
   * Flush the server.
   */
  public function flush() {
    if (method_exists($this->adapter, 'flushRedis')) {
      $this->adapter->flushRedis();
    }
    if (method_exists($this->adapter, 'flushAPC')) {
      $this->adapter->flushAPC();
    }
  }

}
