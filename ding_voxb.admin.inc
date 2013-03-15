<?php
/**
 * @file
 *
 * Admin pages for the Ding VoxB module.
 */

/**
 * Form builder for the VoxB settings form.
 */
function ding_voxb_admin($form, $form_state) {
  $form = array();

  $form['voxb'] = array(
    '#type' => 'fieldset',
    '#title' => t('VoxB service settings'),
  );

  $form['voxb']['voxb_service_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Service URL'),
    '#description' => t('URL to the VoxB webservice, e.g. https://voxb.addi.dk/1.0/'),
    '#default_value' => variable_get('voxb_service_url', ''),
    '#required' => TRUE,
  );

  $form['voxb']['voxb_identity_provider'] = array(
    '#type' => 'textfield',
    '#title' => t('Identity Provider'),
    '#description' => t('Identity provider for the service, e.g. Testbibliotek'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_identity_provider', ''),
  );

  $form['voxb']['voxb_institution_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Institution name'),
    '#description' => t('Institution name for the service, e.g. Testbibliotek'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_institution_name', ''),
  );

  $form['voxb']['voxb_reviews_per_page'] = array(
    '#type' => 'textfield',
    '#title' => t('Reviews per page'),
    '#description' => t('Text reviews shown per page.'),
    '#required' => TRUE,
    '#default_value' => variable_get('voxb_reviews_per_page', VOXB_DEFAULT_REVIEWS_PER_PAGE),
  );

  $form['#submit'] = array('ding_voxb_admin_submit');

  return system_settings_form($form);
}

/**
 * Form submit handler.
 *
 * @param type $form
 * @param type $form_state
 */
function ding_voxb_admin_submit($form, $form_state) {
  drupal_set_message(t('You must log out to changes take effect'), 'warning');

  // Clear all item list cache.
  module_invoke('ding_item_cache', 'clear_all');
}
