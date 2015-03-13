<?php

  namespace Sliver\Spy;

  class MethodCallHistory {
  
    protected $calls;
    
    public function __construct () {
      $this->calls = array();
    }
    
    public function getCalls () {
      return $this->calls;
    }
    
    public function addCall ( $name, $args = array() ) {
      $this->calls[] = new MethodCall( $name, $args );
    }
    
    public function findCalls ( $methodName, $args = array() ) {
      $find = function ( $call ) use ( $methodName, $args ) {
        return $call->getMethodName() === $methodName &&
          ( $args === array() || $args === $call->getArguments() );
      };
      return array_filter( $this->calls, $find );
    }
  
  };

?>