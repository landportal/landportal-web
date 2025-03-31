<?php

namespace Drupal\landportal_alias_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\landportal_alias_import\Controller\TaxonomyController;

class TaxonomyCsvForm extends FormBase {

  public function getFormId() {
    return 'taxonomy_alias_import_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $tax_languages = \Drupal::languageManager()->getLanguages();
    $option = [];
    foreach ($tax_languages as $langcode => $language) {
      $option[$langcode] = $language->getName();
    }
     //print_r($languages);
    $option['und'] = $this->t('Not specified');
    $option['nl'] = $this->t('Dutch');

    $form['select_list'] = [ 
      '#type' => 'select',
      '#title' => $this->t('Select Language'),
      '#description' => $this->t('Select Language For Importing.'),
      '#options' => $option,
      '#required' => TRUE,
    ];

    $form['csv_file'] = [
      '#type' => 'file',
      '#title' => $this->t('Enter CSV File'),
      '#description' => $this->t('Upload CSV file to import alias.'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
    ];

    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $tax_selected_language = $form_state->getValue('select_list');

    $validators = ['file_validate_extensions' => ['csv']];
    $tax_file = file_save_upload('csv_file', $validators, FALSE, 0);

    if ($tax_file) {
      $tax_file->setPermanent();
      $tax_file->save();

      $tax_file_uri = $tax_file->getFileUri();


      $alias_controller_tax = new TaxonomyController();
      $alias_controller_tax->importAliasesTax($tax_file_uri, $tax_selected_language);

      \Drupal::messenger()->addMessage($this->t('Taxonomy aliases imported successfully.'));
    }
    else {
      \Drupal::messenger()->addError($this->t('Failed to upload CSV file.'));
    }
  }
}
