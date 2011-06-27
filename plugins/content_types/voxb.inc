<?php

/**
 * @file
 */
$plugin = array(
  'single' => TRUE,
  'title' => t('VoxB'),
  'description' => t('Show VoxB ratings/tags/reviews'),
  'required context' => new ctools_context_required(t('Ting object'), 'ting_object'),
  'category' => t('INLEAD'),
);

/**
 * Implements MODULE_CONTENT_content_type_render().
 * 
 * Display VoxB content type.
 */
function ding_voxb_voxb_content_type_render($subtype, $conf, $panel_args, $context) {
  $block = new stdClass();
  $object = isset($context->data) ? $context->data : NULL;
  $block->content = theme('voxb', array('object' => $object));
  
  return $block;
}

/**
 * Returns an edit form for the content type.
 *
 * We're not actually doing anything here, but we need to have this hook
 * for this content type to work.
 */
function ding_voxb_voxb_content_type_edit_form($form, &$form_state) {
  return $form;
}
