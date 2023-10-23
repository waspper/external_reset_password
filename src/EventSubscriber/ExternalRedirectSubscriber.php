<?php

namespace Drupal\external_reset_password\EventSubscriber;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Subscribes to the Kernel Request event.
 *
 * Redirects to the external page when the user wants to open user
 * reset password page.
 */
class ExternalRedirectSubscriber implements EventSubscriberInterface {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $currentRouteMatch;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * ExternalRedirectSubscriber constructor.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $current_route_match
   *   The currently active route match object.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(RouteMatchInterface $current_route_match, ConfigFactoryInterface $config_factory) {
    $this->currentRouteMatch = $current_route_match;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['kernel.request'][] = ['onRequest', 0];
    return $events;
  }

  /**
   * Handler for the kernel request event.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The request event.
   */
  public function onRequest(RequestEvent $event) {
    $route_name = $this->currentRouteMatch->getRouteName();
    if ($route_name == 'user.pass') {
      $config = $this->configFactory->get('external_reset_password.settings');
      if ($external_url = $config->get('url')) {
        $event->setResponse(new TrustedRedirectResponse($external_url));
      }
    }
  }

}
