<?php

  namespace Sliver\TestCondition;
  use Sliver\Utility\String;
  
  class NumericTestConditionFactory extends TestConditionFactory {

    public function isGreaterThan ( $other ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
      String::serialize( $value ) . ' > ' . String::serialize( $other ),
        function ( $result ) use ( $value, $other ) {
          return $value > $other;
        }
      ));
      return $this;
    }
    
    public function gt ( $other ) {
      return $this->isGreaterThan( $other );
    }
    
    public function isGreaterThanOrEqualTo ( $other ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
      String::serialize( $value ) . ' >= ' . String::serialize( $other ),
        function ( $result ) use ( $value, $other ) {
          return $value >= $other;
        }
      ));
      return $this;
    }
    
    public function ge ( $other ) {
      return $this->isGreaterThanOrEqualTo( $other );
    }
    
    public function gte ( $other ) {
      return $this->isGreaterThanOrEqualTo( $other );
    }
    
    public function isLessThan ( $other ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
      String::serialize( $value ) . ' < ' . String::serialize( $other ),
        function ( $result ) use ( $value, $other ) {
          return $value < $other;
        }
      ));
      return $this;
    }
    
    public function lt ( $other ) {
      return $this->isLessThan( $other );
    }
    
    public function isLessThanOrEqualTo ( $other ) {
      $value = $this->value;
      $this->test->addCondition( new TestCondition(
      String::serialize( $value ) . ' <= ' . String::serialize( $other ),
        function ( $result ) use ( $value, $other ) {
          return $value <= $other;
        }
      ));
      return $this;
    }
    
    public function le ( $other ) {
      return $this->isLessThanOrEqualTo( $other );
    }
    
    public function lte ( $other ) {
      return $this->isLessThanOrEqualTo( $other );
    }
    
    public function isBetween ( $x, $y ) {
      return $this->gte( $x )->lte( $y );
    }
    
  };
  
?>