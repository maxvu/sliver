<?php

  namespace Sliver;
  
  class ConsoleTestController {
  
    protected $suites;
    protected $timer;
    
    protected $passed;
    protected $failed;
    
    public function __construct () {
      $this->suites = array();
      $this->passed = array();
      $this->failed = array();
    }
    
    public function addSuite( $suite ) {
      $this->suites[get_class( $suite )] = $suite;
    }
    
    public function run () {
      $controllerTimer = (new Timer())->start();
      foreach ( $this->suites as $suite ) {
        $suite->run();
        $this->displaySuite( $suite );
      }
      $controllerTimer->stop();
      
      $totalPass = sizeof( $this->passed );
      $total = $totalPass + sizeof( $this->failed );
      $totalTime = (string) $controllerTimer;
      $nSuites = sizeof( $this->suites );
      echo "\n";
      echo "  $totalPass out of $total tests in $nSuites suites " .
        "passed in {$totalTime}s\n\n";
      return $this;
    }
    
    public function displaySuite ( $suite ) {
      $passed = array();
      $failed = array();
      
      foreach ( $suite->getTests() as $test )
        if ( $test->passed() ) {
          $passed[] = $test;
          $this->passed[] = $test;
        } else {
          $failed[] = $test;
          $this->failed[] = $test;
        }
      
      $suiteName = get_class( $suite );
      $nPass = sizeof( $passed );
      $nTotal = sizeof( $passed ) + sizeof( $failed );
      $time = (string) $suite->getTimer();
      $labelOk = "[ \e[0;32mOK\033[0m ]";
      $labelBad = "[ \e[1;31m!!\033[0m ]";
      echo "\n";
      if ( empty( $this->failed ) ) {
        echo "  {$labelOk} $suiteName, $nPass / $nTotal passed [{$time}s]\n";
      } else {
        echo "  {$labelBad} $suiteName, $nPass / $nTotal passed [{$time}s]\n";
        foreach ( $this->failed as $failedTest ) {
          $testName = $failedTest->getName();
          $result = $failedTest->getResult();
          $value = (string) $result->getValue();
          $ex = $result->getException();
          $time = (string) $result->getTimer();
          $output = $result->getOutput();
          $outputStr = $result->getOutput()->get() == "" ?
            '(none)' :  "$output";
          
          echo "    $testName:\n";
          echo "      value:     $value\n";
          echo "      exception: $ex\n";
          echo "      time:      {$time}s\n";
          echo "      output:    {$outputStr}\n";
          
          foreach ( $failedTest->getConditions() as $condition ) {
            if ( !$failedTest->getResult()->satisfies( $condition ) )
              echo '      failed:    ' . $condition->getName() . "\n";
          }
        }
      }
    }
    
    public function allPassed () {
      return sizeof( $this->passed ) == sizeof( $this->failed );
    }
  
  };

?>