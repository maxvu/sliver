<?php

  namespace Sliver;
  
  class Test {
  
    protected $name;            // name of the test
    protected $closure;         // closure that performs the test
    protected $conditions;      // list of bound conditions
    
    protected $value;           // the returned value
    protected $exception;       // an exception caught during
    protected $timer;           // run time
    
    public function __construct ( $name, callable $closure ) {
      $this->name = $name;
      $this->closure = $closure;
      $this->conditions = array();
      $this->timer = new Timer();
    }
    
    public function run () {
      if ( isset( $this->value ) || isset( $this->exception ) ) return;
      $this->timer->start();
      try {
        $this->value = call_user_func( $this->closure );
        $this->exception = NULL;
      } catch ( \Exception $ex ) {
        $this->exception = $ex;
      } finally {
        $this->timer->stop();
      }
      return $this;
    }
    
    // Apply conditions
    
    public function __call ( $name, $args ) {
      if ( is_callable( '\Sliver\Condition', $name ) )
        $this->conditions[] = call_user_func_array(
          "\Sliver\Condition::{$name}", $args
        );
      else
        throw new \Exception( "No Condition {$name}" );
      return $this;
    }
    
    public function satisfiesCondition ( Condition $cond ) {
      $c = \Closure::bind( $cond->getClosure(), $this );
      return call_user_func( $c ) === TRUE;
    }
    
    public function passed () {
      foreach ( $this->conditions as $cond ) {
        if ( !$cond->isSatisfiedBy( $this ) )
          return FALSE;
      }
      return TRUE;
    }
    
    // Allow the test to be run() again.
    
    public function reset () {
      unset( $this->value );
      unset( $this->exception );
      unset( $this->timer );
      return $this;
    }
    
    // Cull conditions as bool-returning closures.
    
    public function addCondition ( $condition ) {
      $this->conditions[] = $condition->bindTo( $this );
      return $this;
    }
    
    // Accessors
    
    public function getName () {
      return $this->name;
    }
    
    public function getValue () {
      return isset( $this->value ) ? $this->value : NULL;
    }
    
    public function getException () {
      return isset( $this->exception ) ? $this->exception : NULL;
    }
    
    public function getExceptionCode () {
      return isset( $this->exception ) ? $this->exception->getCode() : NULL;
    }
    
    public function getExceptionMessage () {
      return isset( $this->exception ) ? $this->exception->getMessage() : NULL;
    }
    
    public function getTimer() {
      return $this->timer;
    }
    
    public function getConditions () {
      return $this->conditions;
    }
  
  };

?>