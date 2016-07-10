<?php

namespace maxvu\sliver;

class Value {

    protected $value;
    protected $test;
    
    public function __construct (
        $test,
        $value
    ) {
        $this->test = $test;
        $this->value = $value;
    }
    
    public function like ( $other ) {
        $a = $this->value;
        $b = $other;
        $this->test->add_condition(
            function () use ( $a, $b ) { return $a == $b; },
            var_export( $a, true ) . ' == ' . var_export( $b, true )
        );
        return $this;
    }
    
    public function eq ( $other ) {
        $a = $this->value;
        $b = $other;
        $this->test->add_condition(
            function () use ( $a, $b ) { return $a === $b; },
            var_export( $a, true ) . ' === ' . var_export( $b, true )
        );
        return $this;
    }
    
    public function ne ( $other ) {
        $a = $this->value;
        $b = $other;
        $this->test->add_condition(
            function () use ( $a, $b ) { return $a != $b; },
            var_export( $a, true ) . ' != ' . var_export( $b, true )
        );
        return $this;
    }
    
    public function gt ( $other ) {
        $a = $this->value;
        $b = $other;
        $this->test->add_condition(
            function () use ( $a, $b ) { return $a > $b; },
            var_export( $a, true ) . ' > ' . var_export( $b, true )
        );
        return $this;
    }
    
    public function ge ( $other ) {
        $a = $this->value;
        $b = $other;
        $this->test->add_condition(
            function () use ( $a, $b ) { return $a >= $b; },
            var_export( $a, true ) . ' >= ' . var_export( $b, true )
        );
        return $this;
    }
    
    public function le ( $other ) {
        $a = $this->value;
        $b = $other;
        $this->test->add_condition(
            function () use ( $a, $b ) { return $a <= $b; },
            var_export( $a, true ) . ' <= ' . var_export( $b, true )
        );
        return $this;
    }
    
    public function lt ( $other ) {
        $a = $this->value;
        $b = $other;
        $this->test->add_condition(
            function () use ( $a, $b ) { return $a < $b; },
            var_export( $a, true ) . ' < ' . var_export( $b, true )
        );
        return $this;
    }
    
    public function null () {
        $a = $this->value;
        $this->test->add_condition(
            function () use ( $a ) { return $a === null; },
            var_export( $a, true ) . ' is null'
        );
        return $this;
    }
    
    public function true () {
        $a = $this->value;
        $this->test->add_condition(
            function () use ( $a ) { return $a === true; },
            var_export( $a, true ) . ' is (strictly) true'
        );
    }
    
    public function false () {
        $a = $this->value;
        $this->test->add_condition(
            function () use ( $a ) { return $a === false; },
            var_export( $a, true ) . ' is (strictly) false'
        );
        return $this;
    }
    
    public function truthy () {
        $a = $this->value;
        $this->test->add_condition(
            function () use ( $a ) { return !!$a; },
            var_export( $a, true ) . ' is truthy'
        );
        return $this;
    }
    
    public function falsy () {
        $a = $this->value;
        $this->test->add_condition(
            function () use ( $a ) { return !$a; },
            var_export( $a, true ) . ' is falsy'
        );
        return $this;
    }
    
    public function contains ( $item ) {
        $a = $this->value;
        $b = $item;
        
        if ( is_array( $a ) ) {
            $this->test->add_condition(
                function () use ( $a, $b ) { return in_array( $b, $a ); },
                var_export( $a, true ) . ' contains ' . var_export( $b, true )
            );
        } else if ( is_string( $a ) ) {
            $this->test->add_condition(
                function () use ( $a, $b ) {
                    return false !== strpos( strval( $a ), strval( $b ) );
                },
                var_export( $a, true ) . ' contains ' . var_export( $b, true )
            );
        } else {
            $this->test->add_condition(
                function () { return false; },
                '( contains() provided non-array, non-string value )'
            );
        }

        return $this;
    }
    
    public function matches ( $pattern ) {
        $a = $this->value;
        $b = $pattern;
        
        $this->test->add_condition(
            function () use ( $a, $b ) {
                return preg_match( $b, $a );
            },
            "'$a' matches '$b'"
        );
        
        return $this;
    }

};

?>