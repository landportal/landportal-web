<?php

namespace Drupal\landportal_alias_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\landportal_alias_import\Controller\AliasPageController;

class ImportCsvForm extends FormBase {

  public function getFormId() {
    return 'node_alias_import_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $languages = \Drupal::languageManager()->getLanguages();
    $option = [];
    foreach ($languages as $langcode => $language) {
      $option[$langcode] = $language->getName();
    }
     //print_r($languages);
    $option['und'] = $this->t('Not specified');
    //$option['not_applicable'] = $this->t('Not applicable');

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
    
    $selected_language = $form_state->getValue('select_list');

    $validators = ['file_validate_extensions' => ['csv']];
    $file = file_save_upload('csv_file', $validators, FALSE, 0);

    if ($file) {
      $file->setPermanent();
      $file->save();

      $file_uri = $file->getFileUri();


      $alias_controller = new AliasPageController();
      $alias_controller->importAliases($file_uri, $selected_language);

      \Drupal::messenger()->addMessage($this->t('Aliases imported successfully.'));
    } else {
      \Drupal::messenger()->addError($this->t('Failed to upload CSV file.'));
    }
  }
}
