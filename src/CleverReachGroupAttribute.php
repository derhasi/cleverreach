<?php
/**
 * @file
 * Contains \CleverReachGroupAttribute
 */

class CleverReachGroupAttribute {

  public $key;

  public $type;

  public $variable;

  public function __construct($data) {
    $data = (array) $data;
    $this->key = $data['key'];
    $this->type = $data['type'];
    $this->variable = $data['variable'];
  }

  public function getDisplayTypes() {
    $options = array();

    switch ($this->type) {
      case 'text':
        $options = array(
          'textfield' => t('Textfield'),
          'select' => t('Select box'),
        );
        break;

      case 'number':
        $options = array(
          'textfield' => t('Textfield'),
        );
        break;

      case 'gender':
        $options = array(
          'select' => t('Select box'),
        );
        break;

      case 'date':
        $options = array(
          'date' => t('Date select box'),
        );
        break;
    }

    return $options;
  }
}
