<?php

  namespace Sliver\TestCondition;
  
  class ExceptionTestCondition extends TestCondition {
  
    public function expectsException () {
      return TRUE;
    }
    
  };
  
?>