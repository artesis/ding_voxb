<?php
/**
 * @file
 * ding_voxb.features.field.inc
 */

/**
 * Implements hook_field_default_fields().
 */
function ding_voxb_field_default_fields() {
  $fields = array();

  // Exported field: 'ting_object-ting_object-voxb_rating'.
  $fields['ting_object-ting_object-voxb_rating'] = array(
    'field_config' => array(
      'active' => '1',
      'cardinality' => '1',
      'deleted' => '0',
      'entity_types' => array(),
      'field_name' => 'voxb_rating',
      'foreign keys' => array(),
      'indexes' => array(),
      'locked' => '0',
      'module' => 'ding_voxb',
      'settings' => array(),
      'storage' => array(
        'active' => '1',
        'module' => 'blackhole',
        'settings' => array(),
        'type' => 'blackhole',
      ),
      'translatable' => '0',
      'type' => 'voxb_rating',
    ),
    'field_instance' => array(
      'bundle' => 'ting_object',
      'default_value' => NULL,
      'deleted' => '0',
      'description' => '',
      'ding_entity_type' => 'ding_entity',
      'display' => array(
        'collection_list' => array(
          'label' => 'hidden',
          'module' => 'ding_voxb',
          'settings' => array(
            'link_type' => 'none',
          ),
          'type' => 'voxb_rating_default',
          'weight' => '10',
        ),
        'collection_primary' => array(
          'label' => 'hidden',
          'module' => 'ding_voxb',
          'settings' => array(
            'link_type' => 'none',
          ),
          'type' => 'voxb_rating_default',
          'weight' => '3',
        ),
        'default' => array(
          'label' => 'hidden',
          'module' => 'ding_voxb',
          'settings' => array(
            'link_type' => 'object',
          ),
          'type' => 'voxb_rating_default',
          'weight' => '6',
        ),
        'display_only' => array(
          'label' => 'hidden',
          'module' => 'ding_voxb',
          'settings' => array(
            'link_type' => 'object',
          ),
          'type' => 'voxb_rating_display_only',
          'weight' => '0',
        ),
        'teaser' => array(
          'label' => 'hidden',
          'module' => 'ding_voxb',
          'settings' => array(
            'link_type' => 'none',
          ),
          'type' => 'voxb_rating_display_only',
          'weight' => '4',
        ),
        'user_list' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => 0,
        ),
      ),
      'entity_type' => 'ting_object',
      'field_name' => 'voxb_rating',
      'label' => 'VoxB Rating',
      'required' => FALSE,
      'settings' => array(
        'user_register_form' => FALSE,
      ),
      'widget' => array(
        'module' => 'virtual_field',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => '1',
      ),
    ),
  );

  // Exported field: 'ting_object-ting_object-voxb_review'.
  $fields['ting_object-ting_object-voxb_review'] = array(
    'field_config' => array(
      'active' => '1',
      'cardinality' => '1',
      'deleted' => '0',
      'entity_types' => array(),
      'field_name' => 'voxb_review',
      'foreign keys' => array(),
      'indexes' => array(),
      'locked' => '0',
      'module' => 'ding_voxb',
      'settings' => array(),
      'storage' => array(
        'active' => '1',
        'module' => 'blackhole',
        'settings' => array(),
        'type' => 'blackhole',
      ),
      'translatable' => '0',
      'type' => 'voxb_review',
    ),
    'field_instance' => array(
      'bundle' => 'ting_object',
      'default_value' => NULL,
      'deleted' => '0',
      'description' => '',
      'ding_entity_type' => 'ding_entity',
      'display' => array(
        'collection_list' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => '0',
        ),
        'collection_primary' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => '0',
        ),
        'count_only' => array(
          'label' => 'hidden',
          'module' => 'ding_voxb',
          'settings' => array(
            'link_type' => 'object',
          ),
          'type' => 'voxb_review_count_only',
          'weight' => '0',
        ),
        'default' => array(
          'label' => 'hidden',
          'module' => 'ding_voxb',
          'settings' => array(
            'link_type' => 'object',
          ),
          'type' => 'voxb_review_default',
          'weight' => '4',
        ),
        'teaser' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => '21',
        ),
        'user_list' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => 0,
        ),
      ),
      'entity_type' => 'ting_object',
      'field_name' => 'voxb_review',
      'label' => 'VoxB Review',
      'required' => FALSE,
      'settings' => array(
        'user_register_form' => FALSE,
      ),
      'widget' => array(
        'module' => 'virtual_field',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => '1',
      ),
    ),
  );

  // Exported field: 'ting_object-ting_object-voxb_tags'.
  $fields['ting_object-ting_object-voxb_tags'] = array(
    'field_config' => array(
      'active' => '1',
      'cardinality' => '1',
      'deleted' => '0',
      'entity_types' => array(),
      'field_name' => 'voxb_tags',
      'foreign keys' => array(),
      'indexes' => array(),
      'locked' => '0',
      'module' => 'ding_voxb',
      'settings' => array(),
      'storage' => array(
        'active' => '1',
        'module' => 'blackhole',
        'settings' => array(),
        'type' => 'blackhole',
      ),
      'translatable' => '0',
      'type' => 'voxb_tags',
    ),
    'field_instance' => array(
      'bundle' => 'ting_object',
      'default_value' => NULL,
      'deleted' => '0',
      'description' => '',
      'ding_entity_type' => 'ding_entity',
      'display' => array(
        'collection_list' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => '0',
        ),
        'collection_primary' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => '0',
        ),
        'default' => array(
          'label' => 'hidden',
          'module' => 'ding_voxb',
          'settings' => array(
            'link_type' => 'object',
          ),
          'type' => 'voxb_tags_default',
          'weight' => '3',
        ),
        'teaser' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => '23',
        ),
        'user_list' => array(
          'label' => 'above',
          'settings' => array(),
          'type' => 'hidden',
          'weight' => 0,
        ),
      ),
      'entity_type' => 'ting_object',
      'field_name' => 'voxb_tags',
      'label' => 'VoxB Tags',
      'required' => FALSE,
      'settings' => array(
        'user_register_form' => FALSE,
      ),
      'widget' => array(
        'module' => 'virtual_field',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => '1',
      ),
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('VoxB Rating');
  t('VoxB Review');
  t('VoxB Tags');

  return $fields;
}
