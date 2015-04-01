<?php

/**
 * @file
 * Contains class \CleverReachBlock.
 */

/**
 * The class used for cleverreach block entities.
 */
class CleverReachBlock extends Entity {

  /**
   * Serial id of the block entity.
   *
   * @var int
   */
  public $bid;

  /**
   * Machine readable name.
   *
   * @var string
   */
  public $name = '';

  /**
   * Human readable name.
   *
   * @var string
   */
  public $label = '';

  /**
   * ID of the CleverReach group.
   *
   * @var int
   */
  public $listid = NULL;

  /**
   * Field configuration for group fields.
   *
   * @var array
   */
  public $fields = array();

  /**
   * Indicates if block shall be available in block module.
   *
   * @var int
   */
  public $active = 0;

  /**
   * Setting to send activation mail or not.
   *
   * @var int
   */
  public $send_activation_mail = 0;

  /**
   * ID of the selected associated form configuration.
   *
   * @var null
   */
  public $formid = NULL;

  /**
   * {@inheritdoc}
   */
  public function __construct($values = array()) {
    parent::__construct($values, 'cleverreach_block');
  }

  /**
   * {@inheritdoc}
   */
  protected function defaultLabel() {
    return $this->name;
  }

  /**
   * @return \CleverReachGroup
   */
  public function getGroup() {
    if ($this->listid) {
      return cleverreach_get_single_group($this->listid);
    }
  }

}
