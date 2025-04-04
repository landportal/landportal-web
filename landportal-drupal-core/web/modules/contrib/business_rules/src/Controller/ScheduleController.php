<?php

namespace Drupal\business_rules\Controller;

use Drupal\Core\Link;
use Drupal\business_rules\BusinessRulesItemObject;
use Drupal\business_rules\Entity\Action;
use Drupal\business_rules\Entity\Schedule;
use Drupal\business_rules\Events\BusinessRulesEvent;
use Drupal\business_rules\Util\BusinessRulesUtil;
use Drupal\business_rules\VariablesSet;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ScheduleController.
 *
 *  Returns responses for Schedule routes.
 */
class ScheduleController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * A dateFormatter object.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  private $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public function __construct(DateFormatterInterface $dateFormatter) {

    $this->dateFormatter = $dateFormatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $dateFormatter = $container->get('date.formatter');

    return new static($dateFormatter);
  }

  /**
   * View schedule canonical.
   *
   * @return array
   *   The render array.
   */
  public function view($business_rules_schedule) {
    $schedule         = Schedule::load($business_rules_schedule);
    $output['#title'] = $schedule->label();

    $output['name'] = [
      '#type'   => 'item',
      '#title'  => $this->t('Name'),
      '#markup' => $schedule->getName(),
    ];

    $output['status'] = [
      '#type'   => 'item',
      '#title'  => $this->t('Executed'),
      '#markup' => $schedule->isExecuted() ? $this->t('Yes') : $this->t('No'),
    ];

    $output['created'] = [
      '#type'   => 'item',
      '#title'  => $this->t('Created'),
      '#markup' => $this->dateFormatter->format($schedule->getCreatedTime(), 'medium'),
    ];

    $output['changed'] = [
      '#type'   => 'item',
      '#title'  => $this->t('Changed'),
      '#markup' => $this->dateFormatter->format($schedule->getChangedTime(), 'medium'),
    ];

    $output['scheduled'] = [
      '#type'   => 'item',
      '#title'  => $this->t('Scheduled'),
      '#markup' => $this->dateFormatter->format($schedule->getScheduled(), 'medium'),
    ];

    $output['triggered_by'] = [
      '#type'   => 'item',
      '#title'  => $this->t('Triggered by'),
      '#markup' => $schedule->getTriggeredBy()
        ->toLink(NULL, 'edit-form')
        ->toString(),
    ];

    return $output;
  }

  /**
   * Manual execution of a scheduled item.
   *
   * @param string $business_rules_schedule
   *   The business_rules_schedule id.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The redirect response.
   */
  public function execute($business_rules_schedule) {
    $container = \Drupal::getContainer();
    $util      = new BusinessRulesUtil($container);

    $task = Schedule::load($business_rules_schedule);

    /** @var \Drupal\business_rules\Entity\Action $action */
    $action = $task->getTriggeredBy();
    $items = $action->getSettings('items');
    $task_event = $task->getEvent();

    try {
      $reacts_on_definition = $container
        ->get('plugin.manager.business_rules.reacts_on')
        ->getDefinition($task_event['reacts_on']);

        $entity = \Drupal::entityTypeManager()
        ->getStorage($task_event['entity_type_id'])
        ->load($task_event['entity_id']);

      if ($entity) {
        $event = new BusinessRulesEvent($entity, [
          'entity_type_id' => $task_event['entity_type_id'],
          'bundle' => $entity->bundle(),
          'entity' => $entity,
          'entity_unchanged' => $entity,
          'reacts_on' => $reacts_on_definition,
          'loop_control' => $task_event['entity_type_id'] . $entity->id(),
          'variables' => new \Drupal\business_rules\VariablesSet(),
        ]);

        // Execute items using business rules processor.
        $processor = $container->get('business_rules.processor');
        $processor->ruleBeingExecuted = $action;
        $processor->processItems(BusinessRulesItemObject::itemsArrayToItemsObject($items), $event, $task->id());

        if ($task->getUpdateEntity()) {
          $entity->save();
        }

        $task->setExecuted(1);
        $task->save();
        $util->logger->notice(t('Scheduled task id: @id, name: "@name", triggered by: "@by" has been executed at: @time', [
          '@id'   => $task->id(),
          '@name' => $task->getName(),
          '@by'   => $task->getTriggeredBy()->id(),
          '@time' => $container->get('date.formatter')
            ->format(time(), 'medium'),
        ]));
      }
      else {
        $util->logger->warning(t('Scheduled task id: @id, name: "@name", triggered by: "@by" has not been executed at: @time because target entity type: @entity_type, id: @entity_id does not exist anymore', [
          '@id'   => $task->id(),
          '@name' => $task->getName(),
          '@by'   => $task->getTriggeredBy()->id(),
          '@time' => $container->get('date.formatter')
            ->format(time(), 'medium'),
          '@entity_type' => $task_event['entity_type_id'],
          '@entity_id' => $task_event['entity_id'],
        ]));
      }
    }
    catch (\Exception $e) {
      $util->logger->error($e->getMessage());
    }

    return new RedirectResponse('/admin/config/workflow/business_rules/schedule/collection');
  }

  /**
   * Displays a Schedule  revision.
   *
   * @param int $schedule_revision
   *   The Schedule  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($schedule_revision) {
    $schedule     = $this->entityTypeManager()
      ->getStorage('business_rules_schedule')
      ->loadRevision($schedule_revision);
    $view_builder = $this->entityTypeManager()
      ->getViewBuilder('business_rules_schedule');

    // Return $view_builder->view($schedule);
    return $this->view($schedule_revision);
  }

  /**
   * Page title callback for a Schedule  revision.
   *
   * @param int $schedule_revision
   *   The Schedule  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($schedule_revision) {
    $schedule = $this->entityTypeManager()
      ->getStorage('schedule')
      ->loadRevision($schedule_revision);

    return $this->t('Revision of %title from %date', [
      '%title' => $schedule->label(),
      '%date'  => \Drupal::service('date.formatter')->format($schedule->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Schedule .
   *
   * @param string $business_rules_schedule
   *   A Schedule object id.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview($business_rules_schedule) {
    $business_rules_schedule = Schedule::load($business_rules_schedule);
    $account                 = $this->currentUser();
    $langcode                = $business_rules_schedule->language()->getId();
    $langname                = $business_rules_schedule->language()->getName();
    $languages               = $business_rules_schedule->getTranslationLanguages();
    $has_translations        = (count($languages) > 1);
    $schedule_storage        = $this->entityTypeManager()
      ->getStorage('business_rules_schedule');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title'    => $business_rules_schedule->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $business_rules_schedule->label()]);
    $header          = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all schedule revisions") || $account->hasPermission('administer schedule entities')));
    $delete_permission = (($account->hasPermission("delete all schedule revisions") || $account->hasPermission('administer schedule entities')));

    $rows = [];

    $vids = $schedule_storage->revisionIds($business_rules_schedule);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\business_rules\ScheduleInterface $revision */
      $revision = $schedule_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)
        ->isRevisionTranslationAffected()
      ) {
        $username = [
          '#theme'   => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $time = $revision->getRevisionCreationTime();
        if (is_numeric($time)) {
          $date = \Drupal::service('date.formatter')
            ->format($revision->getRevisionCreationTime(), 'short');
          if ($vid != $business_rules_schedule->getRevisionId()) {
            $link = Link::fromTextAndUrl($date, Url::fromRoute('entity.business_rules_schedule.revision', [
              'schedule'          => $business_rules_schedule->id(),
              'schedule_revision' => $vid,
            ]));
          }
          else {
            $link = $business_rules_schedule->toLink($date)->toString();
          }
        }
        else {
          $link = '';
        }

        $row    = [];
        $column = [
          'data' => [
            '#type'     => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context'  => [
              'date'     => $link,
              'username' => \Drupal::service('renderer')
                ->renderPlain($username),
              'message'  => [
                '#markup'       => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[]  = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url'   => Url::fromRoute('entity.business_rules_schedule.revision_revert', [
                'schedule'          => $business_rules_schedule->id(),
                'schedule_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url'   => Url::fromRoute('entity.business_rules_schedule.revision_delete', [
                'schedule'          => $business_rules_schedule->id(),
                'schedule_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type'  => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['schedule_revisions_table'] = [
      '#theme'  => 'table',
      '#rows'   => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
