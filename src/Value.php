<?php

  namespace Sliver;

  class Value {
  
    private $value;
    
    private function __construct ( $value ) {
      $this->value = $value;
    }
    
    public static function of ( $value ) {
      if ( is_a( $value, '\Sliver\Value' ) )
        return $value;
      return new Value( $value );
    }
    
    public function get () {
      return $this->value;
    }
    
    public function __invoke () {
      return $this->value;
    }
    
    public function __toString () {
      if ( $this->value === NULL )
        return 'NULL';
      else if ( is_bool( $this->value ) )
        return $this->value ? 'TRUE' : 'FALSE';
      else if ( is_array( $this->value ) ) {
        $output = '{';
        foreach ( $this->value as $k => $v )
          $output .= Value::of( $k ) . ' => ' . Value::of( $v ) . ' ';
        return $output . '}';
      } else if ( is_string( $this->value ) )
        return '"' . $this->value . '"';
      else if ( is_int( $this->value ) )
        return "int {$this->value}";
      else if ( is_float( $this->value ) )
        return "float {$this->value}";
      else if ( is_a( $this->value, 'Exception' ) )
        return 'exception "' . $this->value->getMessage() . '" (code ' .
          $this->value->getCode() . ')';
      else if ( is_resource( $this->value ) )
        return 'resource ' . get_resource_type( $this->value );
      else if ( is_object( $this->value ) )
        return 'object ' . get_class( $this->value )
          . ' ' . Value::of( get_object_vars( $this->value ) );
      else
        return '(unknown type)';
    }
  
  };

?>