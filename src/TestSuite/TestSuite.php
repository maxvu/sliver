<?php

  namespace Sliver\TestSuite;
  use Sliver\Spy\Spy;
  use Sliver\Test\Test;
  use Sliver\TestCondition\TestCondition;
  use Sliver\TestCondition\ExceptionTestCondition;
  use Sliver\Utility\String;
  
  class TestSuite {
    
    public $__currentTest;
    
    protected function assert ( $value ) {
      return $this->__currentTest->getConditionFactory( $value );
    }
    
    protected function spy ( $what, $ctorArgs = NULL ) {
      return Spy::create( $what, $ctorArgs );
    }
    
    protected function shouldThrowException ( $msg = NULL, $code = NULL ) {
      $this->__currentTest->addCondition( new ExceptionTestCondition(
        "throws Exception" .
          ( $code === NULL ? '' : ' with code ' . String::serialize( $code ) ) .
          ( $msg === NULL ? '' : ' with message ' . String::serialize( $msg ) ),
        function ( $result ) use ( $code, $msg ) {
          $ex = $result->getException();
          $doesExcept = $ex !== NULL;
          $matchesCode = $doesExcept && 
            ($code === NULL || $ex->getCode() === $code);
          $matchesMsg = $doesExcept && 
            ($msg === NULL || $ex->getMessage() === $msg);
          return $doesExcept && $matchesCode && $matchesMsg;
        }
      ));
    }
    
    protected function shouldTakeLessThan ( $secs ) {
      $this->__currentTest->addCondition( new Test(
        "takes less than {$secs}s",
        function ( $result ) use ( $secs ) {
          return $result->getDuration()->getTime() < $secs;
        }
      ));
    }
    
    protected function shouldReturn ( $value ) {
      $this->__currentTest->addCondition( new Test(
        "returnv value should equal " . String::serialize( $value ),
        function ( $result ) use ( $value ) {
          return $result->getValue() === $value;
        } 
      ));
    }
    
    protected function shouldOutput ( $txt ) {
      $this->__currentTest->addCondition( new Test(
        "output should equal " . String::serialize( $txt ),
        function ( $result ) use ( $txt ) {
          return $result->getOutput() === $txt;
        } 
      ));
    }

  };

?>