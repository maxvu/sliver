<?php

  namespace Sliver\Spy;

  class MethodCall {
  
    protected $methodName;
    protected $arguments;
    
    public function __construct ( $methodName, $arguments ) {
      $this->methodName = $methodName;
      $this->arguments = $arguments;
    }
    
    public function getMethodName () { return $this->methodName; }
    public function getArguments () { return $this->arguments; }
  
  };

?>