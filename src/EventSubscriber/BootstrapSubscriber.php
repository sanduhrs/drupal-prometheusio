<?php

namespace Drupal\prometheusio\EventSubscriber;

use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class BootstrapSubscriber.
 *
 * @package Drupal\prometheusio
 */
class BootstrapSubscriber implements EventSubscriberInterface {

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $config_factory;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->config_factory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events['kernel.request'] = ['request'];
    $events['kernel.terminate'] = ['terminate'];

    return $events;
  }

  /**
   * This method is called whenever the kernel.request event is
   * dispatched.
   *
   * @param Event $event
   */
  public function request(Event $event) {
    drupal_set_message('REQUEST: Event kernel.request thrown by Subscriber in module prometheusio.', 'status', TRUE);
  }
  /**
   * This method is called whenever the kernel.terminate event is
   * dispatched.
   *
   * @param Event $event
   */
  public function terminate(Event $event) {
    drupal_set_message('TERMINATE: Event kernel.terminate thrown by Subscriber in module prometheusio.', 'status', TRUE);
  }

}
