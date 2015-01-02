<?php
  
  class TestSuiteTest extends \Sliver\TestSuite {
    
    public function __construct () {
    
      $this->six = 6;
    
      $this->test( 'closure has access to suite methods', function () {
        return $this->six;
      })->equals( $this->six );
      
      $this->amInATestClosure = FALSE;
      $this->beforeEach( function() {  $this->amInATestClosure = TRUE; });
      $this->afterEach( function() { $this->amInATestClosure = FALSE; });
      
      $this->test( 'beforeEach() is run', function () {
        return $this->amInATestClosure;
      })->equals( TRUE );
      
    }
    
  };

?>
