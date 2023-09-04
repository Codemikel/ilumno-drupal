<?php

namespace Drupal\test_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Test module form.
 */
class TestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'test_module_test';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Apellido'),
      '#required' => TRUE,
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => TRUE,
    ];
    
    $form['document_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Tipo de documento'),
      '#options' => $this->getDocumentType(),
      '#required' => TRUE,
    ];
    
    $form['document'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Documento'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Correo electrónico'),
      '#required' => TRUE,
    ];
    
    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Teléfono'),
      '#required' => TRUE,
    ];

    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('País'),
      '#options' => $this->getCountries(),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('name') == null) {
      $form_state->setErrorByName('name', $this->t('El campo es obligatorio'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $connection = \Drupal::database();
    $connection->insert('test_module_example')
      ->fields([
        'last_name' => $values['last_name'],
        'name' => $values['name'],
        'document_type' => $values['document_type'],
        'document' => $values['document'],
        'email' => $values['email'],
        'phone' => $values['phone'],
        'country' => $values['country']
      ])
      ->execute();

    $this->messenger()->addStatus($this->t('Los datos se han guardado correctamente.'));
  }

  /**
   * This function returns the name of the all countries on the vocabulary cit_countries_information
   */
  public function getCountries() {
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('cit_countries_information');
    
    $options = [];

    foreach ($terms as $term) {
      $options[$term->tid] = $term->name;
    }

    return $options;
  }

  /**
   * This function returns the name of the all countries on the vocabulary document_type
   */
  public function getDocumentType() {
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('document_type');
    
    $options = [];

    foreach ($terms as $term) {
      $options[$term->tid] = $term->name;
    }

    return $options;
  }
  

}
