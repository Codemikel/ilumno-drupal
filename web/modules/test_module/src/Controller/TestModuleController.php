<?php

namespace Drupal\test_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for Test module routes.
 */
class TestModuleController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function formData() {
    
    $query = \Drupal::database()->select('test_module_example', 't')
      ->fields('t')
      ->orderBy('t.id', 'DESC')
      ->execute();

    // Build HTML table
    $rows = [];
    foreach ($query as $record) {
      $rows[] = [
        $record->last_name,
        $record->name,
        $record->document_type,
        $record->document,
        $record->email,
        $record->phone,
        $record->country,
      ];
    }

    // Data insert on HTML table
    $header = [
      'Apellido',
      'Nombre',
      'Tipo de documento',
      'Documento',
      'Correo electrónico',
      'Teléfono',
      'País'
    ];

    $output = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    return $output;
  }

  public function apiData() {

    $query = \Drupal::database()->select('test_module_example', 't')
      ->fields('t')
      ->orderBy('t.id', 'DESC')
      ->execute();
  
    $data = [];

    foreach ($query as $record) {
      // get ID's from fields that references taxonomy terms.
      $country_tid = $record->country; 
      $document_type_tid = $record->document_type; 
    

      $country_term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($country_tid);
      $document_type_term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($document_type_tid);
  
      $country_name = $country_term ? $country_term->getName() : '';
      $document_type_name = $document_type_term ? $document_type_term->getName() : '';
  
      $data[] = [
        'last_name' => $record->last_name,
        'name' => $record->name,
        'document_type' => $document_type_name,
        'document' => $record->document,
        'email' => $record->email,
        'phone' => $record->phone,
        'country' => $country_name,
      ];
    }

    return new JsonResponse($data);
  }
  
}
