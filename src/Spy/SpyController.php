<?php

  namespace Sliver\Spy;
  use InvalidArgumentException;
  use ReflectionClass;
  
  class SpyController {
    
    private $spy;
    private $methods;
    private $members;
    private $callHistory;
    private $reflector;
    
    public function __construct ( $spy, $reflector ) {
      $this->spy = $spy;
      $this->methods = array();
      $this->members = array();
      $this->callHistory = new MethodCallHistory();
      $this->reflector = $reflector;
    }
    
    public function setMethod ( $name, $closure ) {
      $this->methods[$name] = $closure->bindTo( $this->spy );
      return $this;
    }
    
    public function stub ( $methodName, $closure ) {
      return $this->setMethod( $methodName, $closure );
    }
    
    public function getMethod ( $name ) {
      if ( $this->hasMethod( $name ) )
        return $this->methods[$name];
      return NULL;
    }
    
    public function hasMethod ( $name ) {
      return isset( $this->methods[$name] );
    }
    
    public function setMember ( $name, $value ) {
      $this->members[$name] = $value;
      return $this;
    }
    
    public function hasMember ( $name ) {
      return isset( $this->members[$name] );
    }
    
    public function getMember ( $name ) {
      if ( $this->hasMember( $name ) )
        return $this->members[$name];
      return NULL;
    }
    
    public function unsetMember ( $name ) {
      if ( isset( $this->members[$name] ) )
        unset( $this->members[$name] );
    }
    
    public function recordCall ( $method, $args ) {
      $this->callHistory->addCall( $method, $args );
    }
    
    public function getReflector () {
      return $this->reflector;
    }
    
    public function construct ( $args = array() ) {
      $constructor = $this->reflector->getConstructor();
      if ( $constructor === NULL ) return;
      $constructorName = $constructor->getName();
      return call_user_func_array( $this->methods[$constructorName], $args );
    }
    
  };

?>