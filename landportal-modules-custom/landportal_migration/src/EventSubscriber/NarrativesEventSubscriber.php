<?php

namespace Drupal\landportal_migration\EventSubscriber;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateImportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An after migration event subscriber for narratives.
 */
class NarrativesEventSubscriber implements EventSubscriberInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a NarrativesEventSubscriber object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::POST_IMPORT][] = ['onMigratePostImport'];

    return $events;
  }

  /**
   * React after migration to add additional info to narratives.
   *
   * @param \Drupal\migrate\Event\MigrateImportEvent $event
   *   The import event object.
   */
  public function onMigratePostImport(MigrateImportEvent $event) {
    if ($event->getMigration()->getBaseId() == 'narratives_translations') {
      $db = Database::getConnection();
      $data = $this->getData();
      $map_results = $this->getMapping();
      $titles_and_bodies = [];

      foreach ($data as $value) {
        $map_item = $map_results[$value->entity_id];
        $id = $map_item->destid1;
        if (empty($map_item->destid1)) {
          $query = $db->select('node_field_data', 'n');
          $query->fields('n', ['nid']);
          $query->condition('title', $value->title);
          $result = $query->accessCheck(TRUE)->execute()->fetchAll();
          $result = reset($result);
          $id = $result->nid;
        }

        $titles_and_bodies[$id][$value->delta] = [
          'title' => $value->field_info_title_value,
          'description' => $value->field_info_description_value,
          'format' => $value->field_info_description_format,
        ];
      }

      /** @var \Drupal\node\NodeStorageInterface $node_storage */
      $node_storage = $this->entityTypeManager->getStorage('node');
      foreach ($titles_and_bodies as $id => $values) {
        /** @var \Drupal\node\NodeInterface $node */
        $node = $node_storage->load($id);
        $node->set('additional_information', $values);
        $node->save();
      }
    }
  }

  /**
   * Get the migration mapping.
   */
  protected function getMapping() {
    $db = Database::getConnection();
    $query = $db->select('migrate_map_narratives', 'mm');
    $query->fields(
      'mm',
      [
        'sourceid1',
        'destid1',
      ]
    );

    return $query->accessCheck(TRUE)->execute()->fetchAllAssoc('sourceid1');
  }

  /**
   * Get the data from the source.
   *
   * @return array
   *   Field collection.
   */
  protected function getData() {
    Database::setActiveConnection('legacy');
    $db = Database::getConnection();
    $query = $db->select('field_data_field_country_info', 'fc');
    $query->fields('fc',
      [
        'entity_id',
        'field_country_info_value',
        'delta',
      ]
    );
    $query->innerJoin('node', 'node', 'fc.entity_id = node.nid');
    $query->fields(
      'node',
      [
        'title',
      ]
    );
    $query->innerJoin('field_data_field_info_title', 'title', 'title.entity_id = fc.field_country_info_value');
    $query->fields(
      'title',
      [
        'entity_type',
        'bundle',
        'entity_id',
        'field_info_title_value',
      ]
    );
    $query->innerJoin('field_data_field_info_description', 'body', 'body.entity_id = fc.field_country_info_value');
    $query->fields('body',
      [
        'entity_type',
        'field_info_description_value',
        'field_info_description_format',
        'bundle',
        'entity_id',
      ]
    );
    $query->condition('fc.bundle', 'landbook_country');
    $results = $query->accessCheck(TRUE)->execute()->fetchAll();
    Database::setActiveConnection('default');

    return $results;
  }

}
