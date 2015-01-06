<?php

  namespace Sliver\Tests;
  use Sliver\Test as SliverTest;
  
  class TestSuite extends \Sliver\TestSuite {
  
    public function define () {
      $this->test();
      $ts = new TestSuite();
    }
    
  };