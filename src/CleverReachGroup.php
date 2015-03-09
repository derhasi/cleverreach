<?php

/**
 * @file
 * Contains class \CleverReachGroup.
 */

/**
 * The class used for cleverreach group entities.
 */
class CleverReachGroup {

  /**
   * @var int
   */
  public $id;

  /**
   * @var string
   */
  public $name;

  /**
   * @var int
   */
  public $last_mailing;

  /**
   * @var int
   */
  public $last_changed;

  /**
   * @var int
   */
  public $count;

  /**
   * @var int
   */
  public $inactive_count;

  /**
   * @var int
   */
  public $total_count;

  /**
   * @var CleverReachGroupAttribute[]
   */
  public $attributes = array();

  /**
   * @var array
   */
  public $globalAttributes = array();

  public function label() {
    return $this->name;
  }

  public function __construct($data) {
    $this->setUp($data);
  }

  protected function setUp($data) {
    foreach ($data as $key => $val) {

      if ($key == 'attributes') {
        $this->attributes = array();
        foreach ($val as $attribute) {
          $attr = new CleverReachGroupAttribute($attribute);
          $this->{$key}[$attr->key] = $attr;
        }
      }
      else {
        $this->{$key} = $val;
      }
    }
  }

  /**
   * Retrieve attribute definitions for the given group.
   *
   * @return CleverReachGroupAttribute[]
   */
  public function getAttributes() {
    return $this->attributes;
  }

}
