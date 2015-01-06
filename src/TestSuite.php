<?php

  namespace Sliver;

  class TestSuite {
  
    private $tests;
    private $timer;
    private $assertBuffer;
    
    public function __construct () {
      $this->tests = array();
      $this->result = NULL;
      $this->assertBuffer = array();
    }
    
    public function getTests () {
      return $this->tests;
    }
    
    public function getTimer() {
      return $this->timer;
    }
    
    public function test ( $name, $closure ) {
      return ($this->tests[] = new Test( $name, $closure->bindTo( $this ) ));
    }
    
    public function run () {
      // collect test definitions
      $this->define();
      
      $this->timer = (new Timer())->start();
      if ( method_exists( $this, 'beforeAll' ) ) $this->beforeAll();
      foreach ( $this->tests as $test ) {
        if ( method_exists( $this, 'beforeEach' ) ) $this->beforeEach();
        $test->run();
        foreach ( $this->assertBuffer as $assert )
          $test->addCondition( $assert->getName(), $assert->getClosure() );
        $this->assertBuffer = array();
        if ( method_exists( $this, 'afterEach' ) ) $this->afterEach();
      }
      if ( method_exists( $this, 'afterAll' ) ) $this->afterAll();
      $this->timer->stop();
    }
    
    public function assert ( $description, $assertion ) {
      $this->assertBuffer[] = new Condition(
        $description,
        function () use ( $assertion ) {
          return $assertion === TRUE;
        }
      );
    }
    
    public function assertEquals ( $v1, $v2 ) {
      $v1 = Value::of( $v1 );
      $v2 = Value::of( $v2 );
      $this->assertBuffer[] = new Condition(
        "$v1 === $v2", function () use ( $v1, $v2 ) {
          return $v1() === $v2();
        }
      );
    }

  };

?>