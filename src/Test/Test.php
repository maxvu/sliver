<?php

  namespace Sliver\Test;
  use Sliver\Utility\Timer;
  use Sliver\TestCondition\TestConditionFactory;
  use Exception;
  
  class Test {
  
    protected $name;
    protected $closure;
    protected $conditions;
    protected $result;
    
    public function __construct ( $name, $closure ) {
      $this->name = $name;
      $this->closure = $closure;
      $this->conditions = array();
      $this->result = NULL;
    }
    
    public function addCondition ( $condition ) {
      $this->conditions[] = $condition;
      return $this;
    }
    
    public function run () {
      // Plug output, get ready to time, brace for exception, capture value.
      $value = NULL;
      $exception = NULL;
      ob_start();
      $timer = new Timer();
      try {
        $value = call_user_func( $this->closure );
      } catch ( Exception $ex ) {
        $exception = $ex;
      }
      $timer->stop();
      $output = ob_get_clean();
      $this->result = new TestResult( $value, $exception, $timer, $output );
      return $this;
    }
    
    public function passed () {
      if ( $this->result === NULL ) $this->run();
      foreach ( $this->conditions as $condition )
        if ( !$this->result->satisfies( $condition ) )
          return FALSE;
     return TRUE;
    }
    
    public function getName() {
      return $this->name;
    }
    
    public function getClosure() {
      return $this->closure;
    }
    
    public function getConditions () {
      return $this->conditions;
    }
    
    public function numConditions () {
      return sizeof( $this->conditions );
    }
    
    public function getPassingConditions () {
      if ( $this->result === NULL ) $this->run();
      return array_filter( $this->conditions, function ( $condition ) {
        return $this->result->satisfies( $condition ) === TRUE;
      });
    }
    
    public function numPassingConditions () {
      return sizeof( $this->getPassingConditions() );
    }
    
    public function getFailedConditions () {
      if ( $this->result === NULL ) $this->run();
      return array_filter( $this->conditions, function ( $condition ) {
        return $this->result->satisfies( $condition ) !== TRUE;
      });
    }
    
    public function numFailedConditions () {
      return sizeof( $this->getFailedConditions() );
    }
    
    public function getConditionFactory ( $value ) {
      return TestConditionFactory::create( $this, $value );
    }
    
    public function getResult () {
      if ( $this->result === NULL )
        $this->run();
      return $this->result;
    }
    
    public function expectsException () {
      return sizeof( array_filter( $this->conditions, function ( $condition ) { 
        return $condition->expectsException();
      })) > 0;
    }
    
  };

?>