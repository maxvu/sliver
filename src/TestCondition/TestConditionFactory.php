<?php

  namespace Sliver\TestCondition;
  use Sliver\Utility\String;
  
  class TestConditionFactory {

    protected $test;
    protected $value;
    
    public static function create ( $test, $value ) {
      if ( is_array( $value ) )
        return new ArrayTestConditionFactory( $test, $value );
      else if ( is_string( $value ) )
        return new StringTestConditionFactory( $test, $value );
      else if ( is_numeric( $value ) )
        return new NumericTestConditionFactory( $test, $value );        
      else
        return new TestConditionFactory( $test, $value );
    }

    public function __construct ( $test, $value = NULL ) {
      $this->test = $test;
      $this->value = $value;
    }
    
    public function equalTo ( $other ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
        String::serialize( $value ) . ' === ' . String::serialize( $other ),
        function ( $result ) use ( $value, $other ) {
          return $value === $other;
        }
      ));
      return $this;
    }
    
    public function equals ( $other ) {
      return $this->equalTo( $other );
    }
    
    public function eq ( $other ) {
      return $this->equalTo( $other );
    }

    public function notEqualTo ( $other ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
      String::serialize( $value ) . ' !== ' . String::serialize( $other ),
        function ( $result ) use ( $value, $other ) {
          return $value !== $other;
        }
      ));
      return $this;
    }
    
    public function notEquals ( $other ) {
      return $this->notEqualTo( $other );
    }
    
    public function ne ( $other ) {
      return $this->notEqualTo( $other );
    }
    
    public function isNull () {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
        String::serialize( $value ) . ' is NULL',
        function ( $result ) use ( $value ) { return $value === NULL; }
      ));
    }
    
    public function null () {
      return $this->isNull();
    }
    
    public function isNotNull () {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
        String::serialize( $value ) . ' is not NULL',
        function ( $result ) use ( $value ) { return $value != NULL; }
      ));
    }
    
    public function notNull () {
      return $this->isNotNull();
    }
    
    public function true () {
      return $this->equalTo( TRUE );
    }
    
    public function isTrue () {
     return $this->true(); 
    }
    
    public function false () {
      return $this->equalTo( FALSE );
    }
    
    public function isFalse () {
      return $this->false();
    }

  };

?>