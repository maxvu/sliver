<?php

  namespace Sliver\Test;
  
  class TestResult {
  
    private $returnValue;
    private $exception;
    private $duration;
    private $output;
    
    public function __construct ( $value, $ex, $duration, $output ) {
      $this->returnValue = $value;
      $this->exception = $ex;
      $this->duration = $duration;
      $this->output = $output;
    }
    
    public function getValue () { return $this->returnValue; }
    public function getException () { return $this->exception; }
    public function getDuration () { return $this->duration; }
    public function getOutput () { return $this->output; }
    
    public function satisfies ( $condition ) {
      return call_user_func_array(
        $condition->getClosure(),
        array( $this )
      ) === TRUE;
    }
    
  };
  
?>