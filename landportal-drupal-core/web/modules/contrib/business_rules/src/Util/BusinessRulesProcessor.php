<?php

namespace Drupal\business_rules\Util;

use Symfony\Contracts\EventDispatcher\Event;
use Drupal\business_rules\BusinessRulesItemObject;
use Drupal\business_rules\Entity\Action;
use Drupal\business_rules\Entity\BusinessRule;
use Drupal\business_rules\Entity\Condition;
use Drupal\business_rules\Entity\Variable;
use Drupal\business_rules\Events\BusinessRulesEvent;
use Drupal\business_rules\VariableObject;
use Drupal\business_rules\VariablesSet;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Database\DatabaseExceptionWrapper;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Link;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\dbug\Dbug;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BusinessRulesProcessor.
 *
 * Process the business rules.
 *
 * @package Drupal\business_rules\Util
 */
class BusinessRulesProcessor {

  use StringTranslationTrait;

  /**
   * The business rule id being executed.
   *
   * @var \Drupal\business_rules\Entity\BusinessRule
   */
  public $ruleBeingExecuted;

  /**
   * The action manager.
   *
   * @var \Drupal\business_rules\Plugin\BusinessRulesActionManager
   */
  protected $actionManager;

  /**
   * A configuration object with business_rules settings.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The array for debug.
   *
   * @var array
   */
  protected $debugArray = [];

  /**
   * Array of already evaluated variables.
   *
   * @var array
   */
  protected $evaluatedVariables = [];

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcher
   */
  protected $eventDispatcher;

  /**
   * Array of already processed rules.
   *
   * @var array
   */
  protected $processedRules = [];

  /**
   * Process Id. Used to identify the process and avoid infinite loops.
   *
   * @var string
   *   The process id.
   */
  protected $processId;

  /**
   * The variable manager.
   *
   * @var \Drupal\business_rules\Plugin\BusinessRulesVariableManager
   */
  protected $variableManager;

  /**
   * The condition manager.
   *
   * @var \Drupal\business_rules\Plugin\BusinessRulesConditionManager
   */
  private $conditionManager;

  /**
   * The storage.
   *
   * @var \Drupal\Core\Config\StorageInterface
   */
  private $storage;

  /**
   * The Business Rules Util.
   *
   * @var \Drupal\business_rules\Util\BusinessRulesUtil
   */
  private $util;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Generates a UUID v4 (RFC 4122 section 4.4) using PHP code.
   *
   * @var \Drupal\Component\Uuid\Php
   */
  protected $uuid;

  /**
   * BusinessRulesProcessor constructor.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Drupal container.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(ContainerInterface $container) {
    $this->configFactory = $container->get('config.factory');
    $this->storage = $container->get('config.storage');
    $this->util = $container->get('business_rules.util');
    $this->actionManager = $container->get('plugin.manager.business_rules.action');
    $this->conditionManager = $container->get('plugin.manager.business_rules.condition');
    $this->variableManager = $container->get('plugin.manager.business_rules.variable');
    $this->config = $this->configFactory->get('business_rules.settings');
    $this->eventDispatcher = $container->get('event_dispatcher');
    $this->entityTypeManager = $container->get('entity_type.manager');
    $this->messenger = $container->get('messenger');
    $this->uuid = $container->get('uuid');
  }

  /**
   * Process rules.
   *
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   */
  public function process(BusinessRulesEvent $event) {

    // Check if it's running in safe mode.
    if (($this->config->get('enable_safemode') == TRUE && $this->util->request->get('brmode') == 'safe')
      || (is_null($this->config->get('enable_safemode')) && $this->util->request->get('brmode') == 'safe')) {
      return;
    }

    if ($this->avoidInfiniteLoop($event)) {
      return;
    }

    // Dispatch a event before start the processing.
    $this->eventDispatcher->dispatch($event, 'business_rules.before_process_event');

    if (!$event->hasArgument('variables')) {
      $event->setArgument('variables', new VariablesSet());
    }

    $reacts_on_definition = $event->getArgument('reacts_on');
    $trigger              = $reacts_on_definition['id'];
    $triggered_rules      = $this->getTriggeredRules($event, $trigger);
    $this->processTriggeredRules($triggered_rules, $event);

    $this->saveDebugInfo();

    // Dispatch a event after processing the business rule.
    $this->eventDispatcher->dispatch($event, 'business_rules.after_process_event');
  }

  /**
   * Check if the event was already processed during the current request.
   *
   * Get the processed events subject and reactsOn event then compare with the
   * current event subject and reactsOn. If the there is one event with the
   * current subject and reactsOn arguments is already processed, then stop the
   * tell the processor to stop processing. It's necessary to avoid infinite
   * loops when there is a business rule being executed on entity update for
   * example.
   *
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event being processed.
   *
   * @return bool
   *   TRUE|FALSE
   */
  private function avoidInfiniteLoop(BusinessRulesEvent $event) {
    $processed_events = &drupal_static(__CLASS__ . '-process', []);
    $loop_control     = $event->hasArgument('loop_control') ? $event->getArgument('loop_control') : $event->getSubject();
    $serialized_data  = json_encode($loop_control) . json_encode($event->getArgument('reacts_on'));
    if (count($processed_events)) {
      foreach ($processed_events as $processed_event) {
        if ($serialized_data == $processed_event) {
          return TRUE;
        }
      }
    }
    $this->processId = $this->uuid->generate();
    $processed_events[$this->processId] = $serialized_data;

    return FALSE;
  }

  /**
   * Check if there is a Business rule configured for the given event.
   *
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   * @param string $trigger
   *   The trigger.
   *
   * @return array
   *   Array of triggered rules.
   */
  public function getTriggeredRules(BusinessRulesEvent $event, $trigger) {
    $entity_type     = $event->getArgument('entity_type_id');
    $bundle          = $event->getArgument('bundle');
    $rule_names      = $this->storage->listAll('business_rules.business_rule');
    $rules           = $this->storage->readMultiple($rule_names);
    $triggered_rules = [];

    // Dispatch a event before check the triggered rules.
    $this->eventDispatcher->dispatch($event, 'business_rules.before_check_the_triggered_rules');

    foreach ($rules as $rule) {
      $rule = new BusinessRule($rule);
      if ($rule->isEnabled() && $trigger == $rule->getReactsOn() &&
        ($entity_type == $rule->getTargetEntityType() || empty($rule->getTargetEntityType())) &&
        ($bundle == $rule->getTargetBundle() || empty($rule->getTargetBundle()))
      ) {
        $triggered_rules[$rule->id()] = $rule;
      }
    }

    // Dispatch a event after check the triggered rules.
    $this->eventDispatcher->dispatch($event, 'business_rules.after_check_the_triggered_rules');

    return $triggered_rules;
  }

  /**
   * Process the triggered rules.
   *
   * @param array $triggered_rules
   *   Array of triggered rules.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   */
  public function processTriggeredRules(array $triggered_rules, BusinessRulesEvent $event) {
    /** @var \Drupal\business_rules\Entity\BusinessRule $rule */
    foreach ($triggered_rules as $rule) {
      $items                   = $rule->getItems();
      $this->ruleBeingExecuted = $rule;
      $this->processItems($items, $event, $rule->id());
      $this->processedRules[$rule->id()]     = $rule->id();
      $this->debugArray['triggered_rules'][] = $rule;
    }

  }

  /**
   * Save the debug information.
   */
  public function saveDebugInfo() {

    if ($this->config->get('debug_screen')) {
      $array      = $this->getDebugRenderArray();
      $key_value  = $this->util->getKeyValueExpirable('debug');
      $session_id = session_id();

      $current = $key_value->get($session_id);
      if (isset($current['triggered_rules']) && count($current['triggered_rules'])) {
        foreach ($current['triggered_rules'] as $key => $item) {
          $array['triggered_rules'][$key] = $item;
        }
      }

      $array = (object) $array;
      $event = new Event($array);
      // Dispatch a event before save debug info block.
      $this->eventDispatcher->dispatch($event, 'business_rules.before_save_debug_info_block');
      $array = (array) $array;

      $key_value->set($session_id, $array);
    }

  }

  /**
   * Process the items.
   *
   * @param array $items
   *   Array of items to pe processed. Each item must be a instance of
   *   BusinessRulesItemObject.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   * @param string $parent_id
   *   The Item parent Id. It can be the Business Rule or other item.
   */
  public function processItems(array $items, BusinessRulesEvent $event, $parent_id) {
    // Dispatch a event before process business rule items.
    $this->eventDispatcher->dispatch($event, 'business_rules.before_process_items');

    /** @var \Drupal\business_rules\BusinessRulesItemObject $item */
    foreach ($items as $item) {
      if ($item->getType() == BusinessRulesItemObject::ACTION) {
        $action = Action::load($item->getId());
        if (!empty($action)) {
          $this->executeAction($action, $event);

          $this->debugArray['actions'][$this->ruleBeingExecuted->id()][] = [
            'item'   => $action,
            'parent' => $parent_id,
          ];
        }
        else {
          $this->util->logger->error('Action id: %id not found', ['%id' => $item->getId()]);
          $this->messenger->addError($this->t('Business Rules - Action id: %id not found.', ['%id' => $item->getId()]));
        }
      }
      elseif ($item->getType() == BusinessRulesItemObject::CONDITION) {
        $condition = Condition::load($item->getId());

        if (empty($condition)) {
          $this->util->logger->error('Condition id: %id not found', ['%id' => $item->getId()]);
          $this->messenger->addError($this->t('Business Rules Condition id: %id not found.', ['%id' => $item->getId()]));
        }
        else {
          $success = $this->isConditionValid($condition, $event);

          if ($success) {
            $condition_items = $condition->getSuccessItems();

            $this->debugArray['conditions'][$this->ruleBeingExecuted->id()]['success'][] = [
              'item'   => $condition,
              'parent' => $parent_id,
            ];
          }
          else {
            $condition_items = $condition->getFailItems();

            $this->debugArray['conditions'][$this->ruleBeingExecuted->id()]['fail'][] = [
              'item'   => $condition,
              'parent' => $parent_id,
            ];
          }

          if (is_array($condition_items)) {
            $this->processItems($condition_items, $event, $condition->id());
          }
        }
      }
    }

    // Dispatch a event after process business rule items.
    $this->eventDispatcher->dispatch($event, 'business_rules.after_process_items');
  }

  /**
   * Generates the render array for business_rules debug.
   *
   * @return array
   *   The render array.
   */
  public function getDebugRenderArray() {
    /** @var \Drupal\business_rules\Entity\BusinessRule $rule */

    $triggered_rules     = isset($this->debugArray['triggered_rules']) ? $this->debugArray['triggered_rules'] : [];
    $evaluates_variables = isset($this->debugArray['variables']) ? $this->debugArray['variables'] : [];
    $output              = [];

    if (!count($triggered_rules)) {
      return $output;
    }

    foreach ($triggered_rules as $rule) {
      $rule_link = Link::createFromRoute($rule->id(), 'entity.business_rule.edit_form', ['business_rule' => $rule->id()]);

      $output['triggered_rules'][$rule->id()] = [
        '#type'        => 'details',
        '#title'       => $rule->label(),
        '#description' => $rule_link->toString() . '<br>' . $rule->getDescription(),
        '#collapsible' => TRUE,
        '#collapsed'   => TRUE,
      ];

      if (isset($evaluates_variables[$rule->id()]) && is_array($evaluates_variables[$rule->id()])) {
        $output['triggered_rules'][$rule->id()]['variables'] = [
          '#type'        => 'details',
          '#title'       => $this->t('Variables'),
          '#collapsible' => TRUE,
          '#collapsed'   => TRUE,
        ];

        /** @var \Drupal\business_rules\VariableObject $evaluates_variable */
        foreach ($evaluates_variables[$rule->id()] as $evaluates_variable) {
          $variable = Variable::load($evaluates_variable->getId());
          if ($variable instanceof Variable) {
            $variable_link  = Link::createFromRoute($variable->id(), 'entity.business_rules_variable.edit_form', ['business_rules_variable' => $variable->id()]);
            $variable_value = empty($evaluates_variable->getValue()) ? 'NULL' : $evaluates_variable->getValue();

            if (!is_string($variable_value) && !is_numeric($variable_value)) {
              $serialized = serialize($variable_value);
              if (is_object($variable_value)) {
                // Transform the serialized object into serialized array.
                $arr    = explode(':', $serialized);
                $arr[0] = 'a';
                unset($arr[1]);
                unset($arr[2]);
                $serialized = implode(':', $arr);
              }
              $unserialized   = unserialize($serialized);
              $variable_value = Dbug::debug($unserialized, 'array');
            }

            $output['triggered_rules'][$rule->id()]['variables'][$evaluates_variable->getId()] = [
              '#type'        => 'details',
              '#title'       => $variable->label(),
              '#description' => $variable_link->toString() . '<br>' . $variable->getDescription() . '<br>' . $this->t('Value:') . '<br>',
              '#collapsible' => TRUE,
              '#collapsed'   => TRUE,
            ];

            $output['triggered_rules'][$rule->id()]['variables'][$evaluates_variable->getId()]['value'] = [
              '#type'   => 'markup',
              '#markup' => $variable_value,
            ];
          }
        }
      }

      $output['triggered_rules'][$rule->id()]['items'] = [
        '#type'        => 'details',
        '#title'       => $this->t('Items'),
        '#collapsible' => TRUE,
        '#collapsed'   => TRUE,
      ];

      $items = $rule->getItems();

      $output['triggered_rules'][$rule->id()]['items'][] = $this->getDebugItems($items, $rule->id());
    }

    return $output;
  }

  /**
   * Executes one Action.
   *
   * @param \Drupal\business_rules\Entity\Action $action
   *   The action.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   *
   * @return array
   *   Render array to display action result on debug block.
   *
   * @throws \ReflectionException
   */
  public function executeAction(Action $action, BusinessRulesEvent $event) {

    // Dispatch a event before execute an action.
    $this->eventDispatcher->dispatch(new Event($event, $action), 'business_rules.before_execute_action');

    $action_variables = $action->getVariables();
    $this->evaluateVariables($action_variables, $event);
    $result = $action->execute($event);

    $this->debugArray['action_result'][$this->ruleBeingExecuted->id()][$action->id()] = $result;

    // Dispatch a event after execute an action.
    $this->eventDispatcher->dispatch(new Event($event, $action), 'business_rules.after_execute_action');

    return $result;
  }

  /**
   * Checks if one condition is valid.
   *
   * @param \Drupal\business_rules\Entity\Condition $condition
   *   The condition.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   *
   * @return bool
   *   True if the condition is valid or False if not.
   *
   * @throws \ReflectionException
   */
  public function isConditionValid(Condition $condition, BusinessRulesEvent $event) {

    // Dispatch a event before check if condition is valid.
    $this->eventDispatcher->dispatch(new Event($event, $condition), 'business_rules.before_check_if_condition_is_valid');

    $condition_variables = $condition->getVariables();
    $this->evaluateVariables($condition_variables, $event);
    $result = $condition->process($event);
    $result = $condition->isReverse() ? !$result : $result;

    // Dispatch a event after check if condition is valid.
    $this->eventDispatcher->dispatch(new Event($event, $condition), 'business_rules.after_check_if_condition_is_valid');

    return $result;

  }

  /**
   * Helper function to prepare the render array for the Business Rules Items.
   *
   * @param array $items
   *   Array of items.
   * @param string $parent_id
   *   The parent item id.
   *
   * @return array
   *   The render array.
   */
  protected function getDebugItems(array $items, $parent_id) {
    /** @var \Drupal\business_rules\BusinessRulesItemObject $item */
    /** @var \Drupal\business_rules\Entity\Action $executed_action */
    /** @var \Drupal\business_rules\Entity\Condition $executed_condition */
    $actions_executed   = isset($this->debugArray['actions'][$this->ruleBeingExecuted->id()]) ? $this->debugArray['actions'][$this->ruleBeingExecuted->id()] : [];
    $conditions_success = isset($this->debugArray['conditions'][$this->ruleBeingExecuted->id()]['success']) ? $this->debugArray['conditions'][$this->ruleBeingExecuted->id()]['success'] : [];
    $output             = [];

    foreach ($items as $item) {
      if ($item->getType() == BusinessRulesItemObject::ACTION) {
        $action = Action::load($item->getId());
        if (!empty($action)) {
          $action_link = Link::createFromRoute($action->id(), 'entity.business_rules_action.edit_form', ['business_rules_action' => $action->id()]);

          $style = 'fail';
          foreach ($actions_executed as $executed) {
            $action_parent   = $executed['parent'];
            $executed_action = $executed['item'];
            if ($action_parent == $parent_id) {
              $style = ($executed_action->id() == $action->id()) ? 'success' : 'fail';
              if ($style == 'success') {
                break;
              }
            }
          }

          $action_label           = $this->t('Action');
          $output[$item->getId()] = [
            '#type'        => 'details',
            '#title'       => $action_label . ': ' . $action->label(),
            '#description' => $action_link->toString() . '<br>' . $action->getDescription(),
            '#attributes'  => ['class' => [$style]],
            '#collapsible' => TRUE,
            '#collapsed'   => TRUE,
          ];

          if (isset($this->debugArray['action_result'][$this->ruleBeingExecuted->id()][$item->getId()])) {
            $output[$item->getId()]['action_result'][$this->ruleBeingExecuted->id()] = $this->debugArray['action_result'][$this->ruleBeingExecuted->id()][$item->getId()];
          }
        }

      }
      elseif ($item->getType() == BusinessRulesItemObject::CONDITION) {
        $condition = Condition::load($item->getId());
        if (!empty($condition)) {
          $condition_link = Link::createFromRoute($condition->id(), 'entity.business_rules_condition.edit_form', ['business_rules_condition' => $condition->id()]);

          $style = 'fail';
          foreach ($conditions_success as $success) {
            $condition_parent   = $success['parent'];
            $executed_condition = $success['item'];
            if ($condition_parent == $parent_id) {
              $style = ($executed_condition->id() == $condition->id()) ? 'success' : 'fail';
              if ($style == 'success') {
                break;
              }
            }
          }

          $title                  = $condition->isReverse() ? $this->t('(Not)') . ' ' . $condition->label() : $condition->label();
          $condition_label        = $this->t('Condition');
          $output[$item->getId()] = [
            '#type'        => 'details',
            '#title'       => $condition_label . ': ' . $title,
            '#description' => $condition_link->toString() . '<br>' . $condition->getDescription(),
            '#attributes'  => ['class' => [$style]],
            '#collapsible' => TRUE,
            '#collapsed'   => TRUE,
          ];

          $success_items = $condition->getSuccessItems();
          if (is_array($success_items) && count($success_items)) {
            $output[$item->getId()]['success']   = [
              '#type'        => 'details',
              '#title'       => $this->t('Success items'),
              '#attributes'  => ['class' => [$style]],
              '#collapsible' => TRUE,
              '#collapsed'   => TRUE,
            ];
            $output[$item->getId()]['success'][] = $this->getDebugItems($success_items, $condition->id());
          }

          $fail_items = $condition->getFailItems();
          if (is_array($fail_items) && count($fail_items)) {
            $output[$item->getId()]['fail']   = [
              '#type'        => 'details',
              '#title'       => $this->t('Fail items'),
              '#attributes'  => ['class' => [$style == 'success' ? 'fail' : 'success']],
              '#collapsible' => TRUE,
              '#collapsed'   => TRUE,
            ];
            $output[$item->getId()]['fail'][] = $this->getDebugItems($fail_items, $condition->id());
          }
        }
      }
    }

    return $output;
  }

  /**
   * Evaluate all variables from a VariableSet for a given event.
   *
   * @param \Drupal\business_rules\VariablesSet $variablesSet
   *   The variable set.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   *
   * @throws \Exception
   */
  public function evaluateVariables(VariablesSet $variablesSet, BusinessRulesEvent $event) {
    // Dispatch a event before evaluate variables.
    $this->eventDispatcher->dispatch(new Event($event, $variablesSet), 'business_rules.before_evaluate_variables');

    /** @var \Drupal\business_rules\VariableObject $variable */
    /** @var \Drupal\business_rules\VariablesSet $eventVariables */
    if ($variablesSet->count()) {
      foreach ($variablesSet->getVariables() as $variable) {
        $varObject = Variable::load($variable->getId());
        if ($varObject instanceof Variable) {
          $this->evaluateVariable($varObject, $event);
        }
      }
    }

    // Dispatch a event after evaluate variables.
    $this->eventDispatcher->dispatch(new Event($event, $variablesSet), 'business_rules.after_evaluate_variables');
  }

  /**
   * Evaluate the variable value.
   *
   * @param \Drupal\business_rules\Entity\Variable $variable
   *   The variable.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   *
   * @return \Drupal\business_rules\VariableObject|\Drupal\business_rules\VariablesSet
   *   The evaluated variable or a VariableSet which processed variables.
   *
   * @throws \Exception
   */
  public function evaluateVariable(Variable $variable, BusinessRulesEvent $event) {

    // Do note evaluate the same variable twice to avid overload.
    if ($this->isVariableAlreadyEvaluated($variable->id(), $event)) {
      return NULL;
    }

    /** @var \Drupal\business_rules\VariablesSet $eventVariables */
    /** @var \Drupal\business_rules\VariableObject $item */
    $eventVariables     = $event->getArgument('variables');
    $variable_variables = $variable->getVariables();

    $this->evaluateVariables($variable_variables, $event);
    $value = $variable->evaluate($event);

    if ($value instanceof VariableObject) {
      $this->setVariableAlreadyEvaluated($value->getId(), $event);
      $eventVariables->append($value);
      $this->debugArray['variables'][$this->ruleBeingExecuted->id()][$variable->id()] = $value;

      return $value;
    }
    elseif ($value instanceof VariablesSet) {
      if ($value->count()) {
        foreach ($value->getVariables() as $item) {
          $this->setVariableAlreadyEvaluated($item->getId(), $event);
          $eventVariables->append($item);
          $this->debugArray['variables'][$this->ruleBeingExecuted->id()][$item->getId()] = $item;
        }
      }

      return $value;
    }
    else {
      throw new \Exception(get_class($value) . '::evaluate should return instance of ' . get_class(new VariableObject()) . ' or ' . get_class(new VariablesSet()) . '.');
    }
  }

  /**
   * Check if a Business Rule exists for the entity or not.
   *
   * @param string $reacts_on
   *   The Event Name.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The Entity.
   *
   * @return bool
   *   TRUE if the business rule exists, FALSE otherwise.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function ruleExists($reacts_on, EntityInterface $entity) {

    if (!($entity instanceof ContentEntityInterface)) {
      return FALSE;
    }

    $business_rule_entity = $this->entityTypeManager
      ->getStorage('business_rule')
      ->loadByProperties(
        [
          'reacts_on' => $reacts_on,
          'target_entity_type' => $entity->getEntityTypeId(),
          'target_bundle' => $entity->bundle(),
        ]
      );
    return !empty($business_rule_entity);
  }

  /**
   * Checks whether a given variable has already been evaluated.
   *
   * @param string $id
   *   The variable ID.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   *
   * @return bool
   *   TRUE if variable has already been evaluated, FALSE otherwise.
   */
  protected function isVariableAlreadyEvaluated($id, BusinessRulesEvent $event) {
    return array_key_exists($this->buildEvaluatedVariableCacheId($id, $event), $this->evaluatedVariables);
  }

  /**
   * Sets given variable as already evaluated.
   *
   * @param string $id
   *   The variable ID.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   */
  protected function setVariableAlreadyEvaluated($id, BusinessRulesEvent $event) {
    $this->evaluatedVariables[$this->buildEvaluatedVariableCacheId($id, $event)] = TRUE;
  }

  /**
   * Builds evaluated variable cache ID.
   *
   * Cache ID is based on the event loop control argument which distinctly
   * defines the event, especially when the same event is fired multiple times
   * within the same request (but with different entities), e.g. during a
   * migration.
   *
   * @param string $id
   *   The variable ID.
   * @param \Drupal\business_rules\Events\BusinessRulesEvent $event
   *   The event.
   *
   * @return string
   *   Evaluated variable cache ID.
   */
  protected function buildEvaluatedVariableCacheId($id, BusinessRulesEvent $event) {
    return $event->getArgument('loop_control') . ':' . $id;
  }

  /**
   * Destructor.
   */
  public function __destruct() {
    if ($this->config->get('clear_render_cache')) {
      Cache::invalidateTags(['rendered']);
    }
  }

}
