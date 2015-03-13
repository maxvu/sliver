<?php

  namespace Sliver\Utility\TestHelpers;
  
  class Bomb {
    
    public function __construct () {
      throw new \Exception('BOOM!');
    }
    
  }
  
?>