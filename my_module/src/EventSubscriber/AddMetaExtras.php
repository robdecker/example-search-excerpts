<?php

namespace Drupal\my_module\EventSubscriber;

use Drupal\jsonapi_search_api\Event\AddSearchMetaEvent;
use Drupal\jsonapi_search_api\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Adds Meta properties to the Json:Api Search Api Response.
 */
class AddMetaExtras implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[Events::ADD_SEARCH_META][] = ['includeExtraData'];
    return $events;
  }

  /**
   * Includes Search api index processors extra data.
   *
   * @param \Drupal\jsonapi_search_api\Event\AddSearchMetaEvent $event
   *   Jsonapi Search api event.
   *
   * @throws \Drupal\search_api\SearchApiException
   */
  public function includeExtraData(AddSearchMetaEvent $event) {
    $results = $event->getResults();
    $extra_data = [];

    foreach ($results->getResultItems() as $resultItem) {
      $entity = $resultItem->getOriginalObject()->getValue();
      $entityId = $entity->id();
      $extra_data[$entityId] = $resultItem->getAllExtraData();
      $extra_data[$entityId]['excerpt'] = $resultItem->getExcerpt();
    }

    // Include here whatever you need to.
    $event->setMeta('extra_data', $extra_data);
  }

}
