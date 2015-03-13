<?php

  namespace Sliver\Utility\TestHelpers;
  
  class Value {
    protected $value;
    public function __construct ( $value ) { $this->value = $value; }
    public function get () { return $this->value; }
  }
  
?>