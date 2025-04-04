<?php

// @codingStandardsIgnoreStart

namespace Drupal\social_media\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listens to the dynamic route events.
 */
class SocialMediaSubscriber implements EventSubscriberInterface {

  /**
   * Public static method used by Drupals event.
   *
   * @return mixed
   *   Array of events we subscribe to.
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events['social_media.add_more_social_media'][] = ['addMoreSocialMedia', 39];
    $events['social_media.pre_execute'][] = ['executeSocialMedia', 39];
    $events['social_media.pre_render'][] = ['renderSocialMedia', 39];

    return $events;
  }

  /**
   * Subscribes to  social_media.add_more_social_media event.
   */
  public function addMoreSocialMedia($event) {
    $element = $event->getElement();
    $element['google_plus'] = 'Google plus';
    $event->setElement($element);
  }

  /**
   * Subscribes to social_media.pre_execute event.
   */
  public function executeSocialMedia($event) {
    $element = $event->getElement();
    $element['facebook_share']['weight'] = 8;
    $event->setElement($element);
  }

  /**
   * Subscribes to social_media.pre_render event.
   */
  public function renderSocialMedia($event) {
    $element = $event->getElement();
    $element['facebook_msg']['text'] = 'New facebook msg text';
    $event->setElement($element);
  }

}

// @codingStandardsIgnoreEnd
