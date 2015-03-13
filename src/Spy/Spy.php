<?php

  namespace Sliver\Spy;
  use ReflectionClass;
  use InvalidArgumentException;
  
  class Spy {
    
    public $__controller;
     
    public function __call ( $name, $args ) {
      if ( !$this->__controller->hasMethod( $name ) )
        throw new InvalidArgumentException( "Spy: no method '$name'" );
      $this->__controller->recordCall( $name, $args );
      $fn = $this->__controller->getMethod( $name )->bindTo( $this );
      return call_user_func_array( $fn, $args );
    }
    
    public function __set ( $name, $value ) {
      $this->__controller->setMember( $name, $value );
    }
    
    public function __get ( $name ) {
      if ( !$this->__controller->hasMember( $name ) )
        throw new InvalidArgumentException( "Spy: no member '$name'" );
      return $this->__controller->getMember( $name );
    }
    
    public function __unset ( $name ) {
      $this->__controller->unsetMember( $name );
    }
    
    public function __isset ( $name ) {
      return $this->__controller->hasMember( $name );
    }
    
    public function summon () {
      return $this->__controller;
    }
    
    public static function create ( $className, $ctorArgs = NULL ) {
      if ( is_object( $className ) )
        $className = get_class( $className );
      if ( !class_exists( $className ) )
        throw new InvalidArgumentException( "No such class: $className" );

      // Initialize
      $reflection = new ReflectionClass( $className );
      $spy = new Spy();
      $spy->__controller = new SpyController( $spy, $reflection );

      // Pilfer methods
      $instance = $reflection->newInstanceWithoutConstructor();
      foreach ( $reflection->getMethods() as $method ) {
        if ( $method->isStatic() ) continue;
        $methodName = $method->getName();
        $closure = $method->getClosure( $instance )->bindTo( $spy );
        $spy->__controller->setMethod( $methodName, $closure );
      }

      // Optionally, call constructor
      if ( $ctorArgs !== NULL ) {
        $ctor = $reflection->getConstructor();
        if ( $ctor === NULL )
          throw new InvalidArgumentException(
            "Spy: class $className has no constructor."
          );
        $ctor = $reflection->getConstructor()->getClosure( $instance );
        call_user_func_array( $ctor->bindTo( $spy ), $ctorArgs );
      }

      return $spy;
    }
    
  };

?>