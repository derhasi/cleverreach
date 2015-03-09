<?php

/**
 * @file
 * Contains handling for subscribe forms.
 */

/**
 * Function for buildung the subscribing blockform.
 */
function cleverreach_subscribe_form($form, &$form_state, CleverReachBlock $block) {
  $form = array();
  $form['#tree'] = TRUE;

  $form['block_name'] = array(
    '#type' => 'value',
    '#value' => $block->name,
  );

  $form['fields'] = array(
    '#tree' => TRUE,
  );

  $form['fields']['email'] = array(
    '#type' => 'textfield',
    '#title' => isset($block->fields['email']['label']) ? t($block->fields['email']['label']) : t('E-Mail:'),
    '#required' => 1,
    '#size' => 25,
    '#weight' => isset($block->fields['email']['weight']) ? t($block->fields['email']['weight']) : 0,
    '#parents' => array('email'),
  );

  foreach ($block->fields as $field_name => $field) {
    if ($field['active']) {
      $form['fields'][$field_name] = _cleverreach_build_block_field($field_name, $field);
    }
  }

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('subscribe'),
    '#weight' => 100,
  );
  return $form;
}

/**
 * Implements hook_form_validate().
 */
function cleverreach_subscribe_form_validate($form, &$form_state) {
  if (!valid_email_address($form_state['values']['email'])) {
    form_set_error('email', t('Please enter a valid email address.'));
  }
}

/**
 * Function for assign a customer to a group.
 */
function cleverreach_subscribe_form_submit($form, &$form_state) {

  $block = cleverreach_get_block($form_state['values']['block_name']);

  // Collect information from the fields.
  $subscribe_attributes = array();
  foreach ($block->fields as $field_name => $field) {

    if ($field['active']) {
      if ($field['display'] == 'date') {
        $subscribe_attributes[] = array(
          'key' => $field_name,
          'value' => sprintf('%s-%s-%s',
            $form_state['values']['fields'][$field_name]['year'],
            $form_state['values']['fields'][$field_name]['month'],
            $form_state['values']['fields'][$field_name]['day']
          ),
        );
      }
      else {
        $subscribe_attributes[] = array(
          'key' => $field_name,
          'value' => $form_state['values']['fields'][$field_name],
        );
      }
    }
  }


  if (count($subscribe_attributes)) {
    $params = array(
      "email" => $form_state['values']['email'],
      "registered" => time(),
      "activated" => time(),
      "source" => variable_get('site_name', 'CleverReach Drupal Module'),
      "attributes" => $subscribe_attributes,
    );
  }
  else {
    $params = array(
      "email" => $form_state['values']['email'],
      "registered" => time(),
      "activated" => time(),
      "source" => variable_get('site_name', 'CleverReach Drupal Module'),
      "attributes" => $subscribe_attributes,
    );
  }

  $settings = _cleverreach_get_settings();
  $api = new SoapClient($settings["url"]);
  $result = $api->receiverAdd($settings["key"], $block->listid, $params);

  if ($result->status == "SUCCESS") {
    drupal_set_message(t('Your submission was successful.'));
  }
  else {
    drupal_set_message(t('Error: Your submission failed.'), 'error');
  }

}

/**
 * Function for buildung the blockform fields.
 */
function _cleverreach_build_block_field($name, $field_data) {
  $field = array(
    '#title' => isset($field_data['label']) ? $field_data['label'] : ucwords($name) . ":",
    '#required' => $field_data["required"],
    '#weight' => isset($field_data['weight']) ? $field_data['weight'] : 0,
  );

  if ($field_data["display"] == "textfield") {
    $field['#type'] = 'textfield';
    $field['#size'] = 25;
    $field['#default_value'] = isset($field_data["display_options"]) ? $field_data["display_options"] : '';
  }

  elseif ($field_data["display"] == "select") {
    $field['#type'] = 'select';
    $field['#multiple'] = FALSE;
    $options = explode("\n", $field_data["display_options"]);
    $select_options = array();

    foreach ($options as $value) {
      $tmp = explode("|", $value);

      if (count($tmp) == 2) {
        $select_options[trim($tmp[0])] = trim($tmp[1]);
      }

      else {
        $select_options[trim($tmp[0])] = trim($tmp[0]);
      }

    }

    $field['#options'] = isset($select_options) ? $select_options : array();
  }

  elseif ($field_data["display"] == "date") {
    $field['#type'] = 'date';
  }

  else {
    $field['#type'] = 'textfield';
  }

  return $field;
}
