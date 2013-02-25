<?php
/**
 * @file
 * ding_voxb.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function ding_voxb_field_group_info() {
  $export = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_collection_list|ting_object|ting_object|collection_list';
  $field_group->group_name = 'group_collection_list';
  $field_group->entity_type = 'ting_object';
  $field_group->bundle = 'ting_object';
  $field_group->mode = 'collection_list';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'collection list',
    'weight' => '1',
    'children' => array(
      0 => 'ting_title',
      1 => 'ting_type',
      2 => 'ting_series',
      3 => 'ting_author',
      4 => 'ting_subjects',
      5 => 'ding_entity_buttons',
      6 => 'ting_details_series_description',
      7 => 'voxb_rating',
      8 => 'ting_details_genre',
      9 => 'ting_details_description',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'collection list',
      'instance_settings' => array(
        'classes' => '',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_collection_list|ting_object|ting_object|collection_list'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_collection_object|ting_object|ting_object|collection_primary';
  $field_group->group_name = 'group_collection_object';
  $field_group->entity_type = 'ting_object';
  $field_group->bundle = 'ting_object';
  $field_group->mode = 'collection_primary';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'collection object',
    'weight' => '1',
    'children' => array(
      0 => 'ting_title',
      1 => 'ting_series',
      2 => 'ting_abstract',
      3 => 'ting_author',
      4 => 'ting_subjects',
      5 => 'ting_details_classification',
      6 => 'ting_details_isbn',
      7 => 'ting_details_series_description',
      8 => 'voxb_rating',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'collection object',
      'instance_settings' => array(
        'classes' => '',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_collection_object|ting_object|ting_object|collection_primary'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_content|ting_object|ting_object|teaser';
  $field_group->group_name = 'group_content';
  $field_group->entity_type = 'ting_object';
  $field_group->bundle = 'ting_object';
  $field_group->mode = 'teaser';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Content group',
    'weight' => '1',
    'children' => array(
      0 => 'ting_title',
      1 => 'ting_series',
      2 => 'ting_abstract',
      3 => 'ting_author',
      4 => 'ting_subjects',
      5 => 'ting_details_series_description',
      6 => 'voxb_rating',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Content group',
      'instance_settings' => array(
        'classes' => '',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_content|ting_object|ting_object|teaser'] = $field_group;

  return $export;
}
