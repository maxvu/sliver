<?php

  namespace Sliver\TestCondition;
  use Sliver\Utility\String;
  
  class StringTestConditionFactory extends TestConditionFactory {
    
    public function isLength ( $len ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
        'string is of length $len', function ( $result ) use ( $len, $value ) {
          return strlen( $value ) === $len;
        }
      ));
      return $this;
    }
    
    public function len ( $len ) {
      return $this->isLength( $len );
    }
    
    public function size ( $len ) {
      return $this->isLength( $len );
    }
    
    public function isEmpty () {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
        'string is empty', 
        function ( $result ) use ( $value ) {
          return $value === "";
        }
      ));
      return $this;
    }
    
    public function hasSubstring ( $substr ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
        "string has substring $substr",
        function ( $result ) use ( $value, $substr ) {
          return strpos( $value, $substr ) !== FALSE;
        }
      ));
      return $this;
    }
    
    public function contains ( $substr ) {
      return $this->hasSubstring( $substr );
    }
    
    public function matches ( $pattern ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
        String::serialize( $value ) . " matches pattern $pattern",
        function ( $result ) use ( $value, $pattern ) {
          return preg_match( $pattern, $value ) === 1;
        }
      ));
      return $this;
    }
    
  };
  
?>