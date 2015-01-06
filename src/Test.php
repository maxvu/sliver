<?php

  namespace Sliver;
  
  class Test {
  
    protected $name;            // name of the test
    protected $closure;         // closure that performs the test
    protected $conditions;      // list of bound conditions
    
    protected $result;
    
    public function __construct ( $name, callable $closure ) {
      $this->name = $name;
      $this->closure = $closure;
      $this->result = NULL;
      $this->conditions = array();
      
      $this->doesNotExcept();
    }
    
    public function run () {
      $timer = (new Timer())->start();
      ob_start();
      try {
        $value = call_user_func( $this->closure );
        $exception = NULL;
      } catch ( \Exception $ex ) {
        $exception = $ex;
        $value = NULL;
      } finally {
        $timer->stop();
        $output = ob_get_clean();
      }
      $this->result = new TestResult( 
        Value::of( $value ), 
        Value::of( $exception ), 
        $timer, 
        Value::of( $output )
      );
      return $this;
    }
    
    // Accessors
    
    public function getName () {
      return $this->name;
    }
    
    public function getConditions () {
      return $this->conditions;
    }
    
    public function getResult () {
      return $this->result;
    }
    
    public function passed () {
      if ( $this->result === NULL ) return FALSE;
      foreach ( $this->conditions as $cond ) {
        if ( !$this->result->satisfies( $cond ) )
          return FALSE;
      }
      return TRUE;
    }
    
    // Conditions
    
    public function addCondition ( $name, callable $closure ) {
      return $this->conditions[] = new Condition( $name, $closure );
    }
    
    public function equals ( $x ) {
      $x = Value::of( $x );
      $this->addCondition( "returns exactly $x", function () use ( $x ) {
        return $this->value != NULL && $this->value->get() === $x();
      });
      return $this;
    }
    
    public function fuzzyEquals ( $x ) {
      $x = Value::of( $x );
      $this->addCondition( "returns fuzzy-equals $x", function () use ( $x ) {
        return $this->value->get() != NULL && $this->value->get() == $x();
      });
      return $this;
    }
    
    public function doesNotEqual ( $x ) {
      $x = Value::of( $x );
      $this->addCondition( "return value does not equal $x", function () use ( $x ) {
        return $this->value->get() != NULL && $this->value->get() != $x();
      });
      return $this;
    }
    
    public function excepts () {
      foreach( $this->conditions as $i => $cond ) {
        if ( $cond->getName() === "does not throw exception" )
          unset( $this->conditions[$i] );
      }
      $this->addCondition( "throws exception", function () {
        return $this->exception->get() !== NULL && $this->value->get() === NULL;
      });
      return $this;
    }
    
    public function doesNotExcept () {
      $this->addCondition( "does not throw exception", function () {
        return $this->exception->get() === NULL;
      });
      return $this;
    }
    
    public function takesLessThan ( $secs ) {
      $this->addCondition(
        "takes less than $secs seconds to complete",
        function () use ( $secs ) {
          return $this->timer->getTime() < $secs;
        }
      );
      return $this;
    }
  
  };

?>