<?php

  namespace Sliver;

  class TestSuite {
  
    protected $tests;
    protected $beforeEach;
    protected $afterEach;
    
    public function test ( $name, $closure ) {
      if ( !isset( $this->tests ) )
        $this->tests = array();
      return $this->tests[] = new Test( $name, $closure->bindTo( $this ) );
    }
    
    public function beforeEach ( callable $beforeEach ) {
      $this->beforeEach = $beforeEach->bindTo( $this );
    }
    
    public function afterEach ( callable $afterEach ) {
      $this->afterEach = $afterEach->bindTo( $this );
    }
    
    public function run () {
      foreach ( $this->tests as $test ) {
        if ( isset( $this->beforeEach ) )
          call_user_func( $this->beforeEach );
        $test->run();
        if ( isset( $this->afterEach ) )
          call_user_func( $this->afterEach );
      }
      return $this;
    }
    
    public function getTests () {
      return $this->tests;
    }
    
    public function getName () {
      return get_class( $this );
    }

  };

?>