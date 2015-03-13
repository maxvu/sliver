<?php

  namespace Sliver\Runner;
  
  interface Runner {

    public function addTestSuite ( $suite );
    public function getTestSuites ();
    public function run ();

  };

?>