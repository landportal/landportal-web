<?php

namespace Drupal\cache_purge\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CachePurgeSettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['cache_purge.settings'];
  }

  public function getFormId() {
    return 'cache_purge_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cache_purge.settings');

    $form['size_limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Cache table size limit (MB)'),
      '#default_value' => $config->get('size_limit') ?: 100,
      '#min' => 1,
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('cache_purge.settings')
      ->set('size_limit', $form_state->getValue('size_limit'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
