<?php

class CleverReachForm {

  public $id;

  public $name;

  public $description;

  public function __construct(array $data = array()) {
    $this->setUp($data);
  }

  protected function setUp($data) {
    foreach ($data as $key => $val) {
      $this->{$key} = $val;
    }
  }
}