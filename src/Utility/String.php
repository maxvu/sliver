<?php

  namespace Sliver\Utility;

  abstract class String {

    public static function serialize ( $value ) {
      if ( $value === NULL )
        return 'NULL';
      else if ( is_bool( $value ) )
        return $value ? 'TRUE' : 'FALSE';
      else if ( is_array( $value ) ) {
        $pieces = array();
        foreach ( $value as $k => $v )
          $pieces[] = implode(
            ' => ',
            array( String::serialize( $k ), String::serialize( $v ) )
          );
        return '{ ' . implode( ', ', $pieces ) . ' }';
      } else if ( is_string( $value ) )
        return '"' . $value . '"';
      else if ( is_int( $value ) )
        return "int {$value}";
      else if ( is_float( $value ) )
        return "float {$value}";
      else if ( is_a( $value, 'Exception' ) )
        return 'exception "' . $value->getMessage() . '" (code ' .
          $value->getCode() . ')';
      else if ( is_resource( $value ) )
        return 'resource ' . get_resource_type( $value );
      else if ( is_object( $value ) )
        return 'object ' . get_class( $value )
          . ' ' . String::serialize( get_object_vars( $value ) );
      else
        return '(unknown type)';
    }
  
  };

?>