<?php

  namespace Sliver;

  class TestSuite {
  
    protected $tests;
    protected $timer;
    protected $assertBuffer;
    
    protected $__beforeAll;
    protected $__afterAll;
    protected $__beforeEach;
    protected $__afterEach;
    
    public function __construct () {
      $this->tests = array();
      $this->result = NULL;
      $this->assertBuffer = array();
      
      // collect test definitions
      $this->define();
      
      $this->__beforeAll = method_exists( $this, 'beforeAll' ) ?
        function () { $this->beforeAll(); } : function () {};
      $this->__afterAll = method_exists( $this, 'afterAll' ) ?
        function () { $this->afterAll(); } : function () {};
      $this->__beforeEach = method_exists( $this, 'beforeEach' ) ?
        function () { $this->beforeEach(); } : function () {};
      $this->__afterEach = method_exists( $this, 'afterEach' ) ?
        function () { $this->afterEach(); } : function () {};
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
      $this->timer = (new Timer())->start();
      call_user_func( $this->__beforeAll->bindTo( $this ) );
      foreach ( $this->tests as $test ) {
        call_user_func( $this->__beforeEach->bindTo( $this ) );
        $test->run();
        foreach ( $this->assertBuffer as $assert )
          $test->addCondition( $assert->getName(), $assert->getClosure() );
        $this->assertBuffer = array();
        call_user_func( $this->__afterEach->bindTo( $this ) );
      }
      call_user_func( $this->__afterAll->bindTo( $this ) );
      $this->timer->stop();
      return new TestSuiteResult( $this->tests, $this->timer );
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