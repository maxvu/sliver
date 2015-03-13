<?php

  namespace Sliver\Tests\TestHelpers;
  
  class ValueCollection {
    protected $values;
    public function __construct () { $this->values = array(); }
    public function addValue ( $v ) { $this->values[] = $v; }
    public function getValues ( $v ) { return array_map( function ( $v ) {
      return $v->get();
    }); }
  }
  
?>