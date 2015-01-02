<?php

  namespace Sliver;
  
  class Condition {
  
    protected $name;
    protected $closure;
    
    public function __construct ( $name, $closure ) {
      $this->name = $name;
      $this->closure = $closure;
    }
    
    public function getName () {
      return $this->name;
    }
    
    public function getClosure () {
      return $this->closure;
    }
    
    public function isSatisfiedBy ( Test $test ) {
      return call_user_func( $this->closure, $test ) === TRUE;
    }
    
    /*
      Basic definitions
    */
    
    public static function equals ( $value ) {
      return new Condition( 
        'equals ' . serialize( $value ), 
        function ( Test $test ) use ( $value ) {
          return $test->getValue() === $value;
        }
      );
    }
    
    public static function fuzzyEquals ( $value ) {
      return new Condition( 
        "fuzzy equals " . serialize( $value ), 
        function ( Test $test ) use ( $value ) {
          return $test->getValue() == $value;
        }
      );
    }
    
    public static function doesNotEqual ( $value ) {
      return new Condition( 
        "doesn't equal " . serialize( $value ), 
        function ( Test $test ) use ( $value ) {
          return $test->getValue() != $value && $test->getException() == FALSE;
        }
      );
    }
    
    public function doesNotExcept () {
      return new Condition( 
        "doesn't throw exception", 
        function ( Test $test ) {
          return $test->getException() === NULL;
        }
      );
    }
    
    public function excepts () {
      return new Condition( 
        "throws exception", 
        function ( Test $test ) {
          return $test->getException() != NULL;
        }
      );
    }
    
    public function exceptsWithCode ( $code ) {
      return new Condition( 
        "throws exception with code " . serialize( $code ), 
        function ( Test $test ) use ( $code ) {
          return $test->getException() != NULL &&
            $test->getExceptionCode() === $code;
        }
      );
    }
    
    public function takesLessThan ( $secs ) {
      return new Condition( 
        "takes less than {$secs} seconds", 
        function ( Test $test ) use ( $secs ) {
          return $test->getTimer()->getTime() < $secs;
        }
      );
    }
  
  };

?>