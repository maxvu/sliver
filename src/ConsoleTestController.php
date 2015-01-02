<?php

  namespace Sliver;
  
  class ConsoleTestController {
  
    protected $suites;
    protected $numTestsRun;
    protected $numTestsPassed;
    
    public function __construct () {
      $this->suites = array();
      $this->totalTestsRun = 0;
      $this->totalTestsPassed = 0;
    }
    
    public function addSuite( $suite ) {
      $this->suites[get_class( $suite )] = $suite;
    }
    
    public function run () {
      $totalTimer = (new Timer())->start();
      foreach ( $this->suites as $suite ) {
        $numTestsRun = 0;
        $numTestsPassed = 0;
        $suiteTimer = (new Timer())->start();
        $suiteName = $suite->getName();
        echo "\n  {$suiteName}\n";
        $suite->run();
        foreach ( $suite->getTests() as $test ) {
          $this->displayTest( $test );
          $numTestsRun++;
          if ( $test->passed() )
            $numTestsPassed++;
        }
        $suiteTimer->stop();
        echo "  {$numTestsPassed} of {$numTestsRun} tests "
          . "passed in {$suiteTimer}s\n";
        $this->totalTestsRun += $numTestsRun;
        $this->totalTestsPassed += $numTestsPassed;
      }
      $totalTimer->stop();
      if ( $this->totalTestsRun > 0 ) {
        echo "\n  {$this->totalTestsPassed} of {$this->totalTestsRun} "
          . "tests passed in {$totalTimer}s\n";
      }
    }
    
    public function displaySuite ( TestSuite $suite ) {
      $suiteName = $suite->getName();
      $suiteTime = (string) $suite->getTimer();
      
      foreach ( $suite->getTests() as $test )
        $this->displayTest( $test );
    }
    
    public function displayTest ( Test $test ) {
      $labelOk = "[ \e[0;32mOK\033[0m ]";
      $labelBad = "[ \e[1;31m!!\033[0m ]";
      $testName = $test->getName();
      $testVal = serialize( $test->getValue() );
      $testExCode = $test->getExceptionCode();
      $testExMsg = $test->getExceptionMessage();
      $testCombinedEx = $testExCode === NULL ?
        '(none)' : "{$testExMsg} ({$testExCode})";
      $testTime = (string) $test->getTimer();
      
      if ( $test->passed() ) {
        echo "    {$labelOk} {$testName} [{$testTime}s]\n";
      } else {
        echo "    {$labelBad} {$testName} [{$testTime}s]\n";
        foreach ( $test->getConditions() as $cond ) {
          $condName = $cond->getName();
          if ( !$cond->isSatisfiedBy( $test ) )
            echo "      failed: {$condName}\n";
        }
        echo "      value: {$testVal}\n";
        echo "      exception: {$testCombinedEx}\n";
      }
    }
    
    public function getSuites () {
      return $this->suites;
    }
    
    public function allPassed () {
      return ( $this->totalTestsPassed == $this->totalTestsRun ) &&
        $this->totalTestsRun > 0;
    }
  
  };

?>