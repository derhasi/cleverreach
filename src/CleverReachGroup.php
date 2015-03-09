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
   * @var CleverReachGroupAttribute[]
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
          $attr = new CleverReachGroupAttribute($attribute, FALSE);
          $this->attributes[$attr->key] = $attr;
        }
      }
      elseif ($key == 'globalAttributes') {
        $this->globalAttributes = array();
        foreach ($val as $attribute) {
          $attr = new CleverReachGroupAttribute($attribute, TRUE);
          $this->globalAttributes[$attr->key] = $attr;
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
  public function getAllAttributes() {
    return array_merge($this->globalAttributes, $this->attributes);
  }

}
