<?php

  namespace Sliver;

  class TestResult {
  
    private $value;
    private $exception;
    private $timer;
    private $output;
    
    public function __construct ( $value, $exception, $timer, $output ) {
      $this->value = $value;
      $this->exception = $exception;
      $this->timer = $timer;
      $this->output = $output;
    }
    
    public function getValue () { return $this->value; }
    public function getException () { return $this->exception; }
    public function getTimer() { return $this->timer; }
    public function getOutput () { return $this->output; }
    
    public function satisfies ( Condition $c ) {
      return call_user_func( $c->getClosure()->bindTo( $this ) ) === TRUE;
    }
    
    public function __get ( $key ) {
      return isset( $this->{$key} ) ? $this->{$key} : NULL;
    }
    

  };

?>