<?php

  namespace Sliver;

  class TestSuiteResult {
  
    private $testsPassed;
    private $testsFailed;
    private $timer;
    
    public function __construct ( $testsRun, Timer $timer ) {
      $this->testsPassed = array();
      $this->testsFailed = array();
      foreach ( $testsRun as $test ) {
        if ( $test->passed() )
          $this->testsPassed[] = $test;
        else
          $this->testsFailed[] = $test;
      }
      $this->timer = $timer;
    }
    
    public function numTests () {
      return sizeof( $this->testsPassed ) + sizeof( $this->testsFailed );
    }
    
    public function numTestsPassed () {
      return sizeof( $this->testsPassed );
    }
    
    public function numTestsFailed () {
      return sizeof( $this->testsFailed );
    }
    
    public function allPassed () {
      return sizeof( $this->testsFailed ) === 0;
    }
    
    public function numConditions () {
      $n = 0;
      $tests = array_merge( $this->testsPassed, $this->testsFailed );
      foreach ( $tests as $test )
        foreach ( $test->getConditions() as $cond )
          $n++;
      return $n;
    }
    
    public function numConditionsPassed () {
      $n = 0;
      $tests = array_merge( $this->testsPassed, $this->testsFailed );
      foreach ( $tests as $test )
        foreach ( $test->getConditions() as $cond )
          if ( $test->getResult()->satisfies( $cond ) )
            $n++;
      return $n;
    }
    
    public function getPassingTests () {
      return $this->testsPassed;
    }
    
    public function getFailingTests () {
      return $this->testsFailed;
    }
    
    
    public function numConditionsFailed () {
      return $this->numConditions() - $this->numConditionsPassed();
    }
    
    public function getTimer () {
      return $this->timer;
    }
    
  };

?>