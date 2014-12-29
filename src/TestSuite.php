<?php

  namespace Sliver;

  class TestSuite {
  
    protected $tests;
    
    public function test ( $name, $closure ) {
      if ( !isset( $this->tests ) )
        $this->tests = array();
      return $this->tests[] = new Test( $name, $closure->bindTo( $this ) );
    }
    
    public function run () {
      foreach ( $this->tests as $test )
        $test->run();
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