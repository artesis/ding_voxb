<?php

/**
 * @file
 *
 * Module settings form
 * 
 */

function ding_voxb_admin($form_state) {
  $form = array();  
  
  $form['voxb'] = array(
    '#type' => 'fieldset',
    '#title' => t('VoxB service settings'),
    '#tree' => FALSE,
  );

  $form['voxb']['voxb_service_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Service URL'),
    '#description' => t('URL to the VoxB webservice, e.g. https://voxb.addi.dk/1.0/xml/voxb.wsdl'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_service_url', 'https://voxb.addi.dk/1.0/xml/voxb.wsdl')
  );

  $form['voxb']['voxb_identity_provider'] = array(
    '#type' => 'textfield',
    '#title' => t('Identity Provider'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_identity_provider', 'Testbibliotek')
  );

  $form['voxb']['voxb_institution_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Institution name'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_institution_name', 'Testbibliotek')
  );

  $form['voxb']['voxb_default_userid'] = array(
    '#type' => 'textfield',
    '#title' => t('Default userId'),
    '#description' => t('Default voxb userId is used for administrator actions'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_default_userid', '1')
  );

  $form['voxb']['voxb_reviews_per_page'] = array(
      '#type' => 'textfield',
      '#title' => t('Reviews per page'),
      '#description' => t('Text reviews shown per page.'),
      '#required' => TRUE,
      '#default_value' => variable_get('voxb_reviews_per_page', VOXB_COMMENTS_PER_PAGE),
  );

  return system_settings_form($form);
}
