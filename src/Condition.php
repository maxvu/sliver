<?php

  namespace Sliver;
  
  class Condition {
  
    protected $name;
    protected $closure;
    
    public function __construct ( $name, $closure ) {
      $this->name = $name;
      $this->closure = $closure;
    }
    
    public function getName () {
      return $this->name;
    }
    
    public function getClosure () {
      return $this->closure;
    }
    
  };

?>