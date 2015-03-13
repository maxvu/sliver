<?php

  namespace Sliver\TestCondition;
  
  class TestCondition {
  
    private $name;
    private $closure;
  
    public function __construct ( $name, $closure ) {
      if ( !is_callable( $closure ) ) {
        throw new \Exception( '??? ' );
      }
      $this->name = $name;
      $this->closure = $closure;
    }
    
    public function getName () { return $this->name; }
    public function getClosure () { return $this->closure; }
    
    public function isSatisfiedBy ( $result ) {
      return call_user_func( $this->closure->bindTo( $result ) ) === TRUE;
    }
    
    public function expectsException () {
      return FALSE;
    }
    
  };
  
?>