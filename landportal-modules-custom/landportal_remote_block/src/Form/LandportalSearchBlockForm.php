<?php

namespace Drupal\landportal_remote_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Builds the search form for the search block.
 *
 * @internal
 */
class LandportalSearchBlockForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'landportal_search_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['keys'] = [
      '#type' => 'search',
      '#title' => $this->t('Search'),
      '#title_display' => 'invisible',
      '#size' => 15,
      '#default_value' => '',
      '#attributes' => ['title' => $this->t('Enter the terms you wish to search for.')],
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      // Prevent op from showing up in the query string.
      '#name' => '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (empty($form_state->getErrors())) {
      $response = new RedirectResponse('/search/site/' . trim($form_state->getValue('keys')));

      return $response->send();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // This form submits to the search page, so processing happens there.
  }

}
