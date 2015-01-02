<?php
  
  class ConditionTest extends \Sliver\TestSuite {
    
    public function __construct () {
      
      $this->test( 'all types applicable', function () {
        $t = new \Sliver\Test( NULL, function () {});
        $t
          ->equals(1)
          ->fuzzyEquals(1)
          ->doesNotEqual(1)
          ->doesNotExcept()
          ->excepts()
          ->exceptsWithCode(1)
          ->takesLessThan(1);
        return sizeof( $t->getConditions() ) === 7;
      });
      
      /*
        equals()
      */
    
      $this->test( 'equals() true on equality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->equals(2)->run()->passed();
      
      })->equals( TRUE );
      
      $this->test( 'equals() false on inequality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->equals(3)->run()->passed();
      
      })->equals( FALSE );
      
      $this->test( 'equals() false on fuzzy equality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->equals('2')->run()->passed();
      
      })->equals( FALSE );
      
      $this->test( 'equals() false on exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          throw new Exception();
        }))->equals('2')->run()->passed();
      
      })->equals( FALSE );
      
      /*
        fuzzyEquals()
      */
      
      $this->test( 'fuzzyEquals() true on equality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->fuzzyEquals(2)->run()->passed();
      
      })->equals( TRUE );
      
      $this->test( 'fuzzyEquals() false on inequality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->fuzzyEquals(3)->run()->passed();
      
      })->equals( FALSE );
      
      $this->test( 'fuzzyEquals() true on fuzzy equality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->fuzzyEquals('2')->run()->passed();
      
      })->equals( TRUE );
      
      $this->test( 'fuzzyEquals() false on exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          throw new Exception();
        }))->fuzzyEquals('2')->run()->passed();
      
      })->equals( FALSE );
      
      /*
        doesNotEqual()
      */
      
      $this->test( 'doesNotEqual() false on equality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->doesNotEqual(2)->run()->passed();
      
      })->equals( FALSE );
      
      $this->test( 'doesNotEqual() true on inequality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->doesNotEqual(3)->run()->passed();
      
      })->equals( TRUE );
      
      $this->test( 'doesNotEqual() false on fuzzy equality', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return 1 + 1;
        }))->doesNotEqual('2')->run()->passed();
      
      })->equals( FALSE );
      
      $this->test( 'doesNotEqual() false on exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          throw new Exception();
        }))->doesNotEqual('2')->run()->passed();
      
      })->equals( FALSE );
      
      /*
        doesNotExcept()
      */
      
      $this->test( 'doesNotExcept() false on exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          throw new \Exception();
        }))->doesNotExcept()->run()->passed();
      
      })->equals( FALSE );
      
      $this->test( 'doesNotExcept() true on no exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return NULL;
        }))->doesNotExcept()->run()->passed();
      
      })->equals( TRUE );
    
      /*
        excepts()
      */
      
      $this->test( 'excepts() true on exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          throw new \Exception();
        }))->excepts()->run()->passed();
      
      })->equals( TRUE );
      
      $this->test( 'excepts() false on no exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return NULL;
        }))->excepts()->run()->passed();
      
      })->equals( FALSE );
      
      /*
        exceptsWithCode()
      */
      
      $this->test( 'exceptsWithCode() true on right exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          throw new \Exception( 'test', 65 );
        }))->exceptsWithCode(65)->run()->passed();
      
      })->equals( TRUE );
      
      $this->test( 'exceptsWithCode() false on wrong exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          throw new \Exception();
        }))->exceptsWithCode(65)->run()->passed();
      
      })->equals( FALSE );
      
      $this->test( 'exceptsWithCode() false on no exception', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return NULL;
        }))->exceptsWithCode(65)->run()->passed();
      
      })->equals( FALSE );
      
      /*
        takesLessThan()
      */
      
      $this->test( 'takesLessThan() true on quick method', function () {
      
        return (new \Sliver\Test( NULL, function () {
          return NULL;
        }))->takesLessThan(1)->run()->passed();
      
      })->equals( TRUE );
      
      $this->test( 'takesLessThan() false on long method', function () {
      
        return (new \Sliver\Test( NULL, function () {
          usleep(10000);
        }))->takesLessThan(0)->run()->passed();
      
      })->equals( FALSE );
      
      
    }
    
  };

?>
