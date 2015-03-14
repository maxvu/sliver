<?php

  namespace Sliver;
  use Sliver\Runner\CommandLine\CommandLineRunner;
  use Sliver\TestSuite\TestSuite;
  use Sliver\Runner\TestSuiteSniffer as Sniffer;
  use Sliver\Utility\CommandLineWriter;
  use Sliver\Utility\DirectoryScanner;
  use Sliver\Utility\Timer;
  use ReflectionClass;
  
  class Sliver {
  
    protected $containers;
    protected $verbosity;
    
    private static $sliverTestSuite = 'Sliver\\TestSuite\\TestSuite';
    private static $phpFile = "/\.(php|php4|php5)$/";
    
    public static function run ( $event ) {
      
      $timer = new Timer();
      $writer = new CommandLineWriter( $event->getIO() );
      $testDirs = [];
      $testFiles = [];
      $srcDirs = [];
      $srcFiles = [];
      $verbosity = array_search(
        'verbose', $event->getArguments()
       ) !== FALSE ? 2 : 1;
       
       $writer->newline()->writeNeutral('Starting sliver');
      
      // Gather cues from config
      foreach ( $event->getComposer()->getPackage()->getDevAutoload() as $psr )
        $testDirs = array_merge( $testDirs, array_values( $psr ));
      foreach ( $event->getComposer()->getPackage()->getAutoload() as $psr )
        $srcDirs = array_merge( $srcDirs, array_values( $psr ));
      
      // Recursively scan those directories
      $scanner = new DirectoryScanner( static::$phpFile );
      $writer->writeNeutral('Scanning autoload directories', 1);
      foreach ( $testDirs as $dir )
        $testFiles = array_merge( $testFiles, $scanner->scan( $dir ) );
      foreach ( $srcDirs as $dir )
        $srcFiles = array_merge( $srcFiles, $scanner->scan( $dir ) );
      
      // Peek into them individually and see if they should be include()'ed
      $testFiles = array_filter( $testFiles, function ( $t ) {
        return (new Sniffer( $t ))->smells();
      });

      // Rope each candidate test in, scan all loaded classes to verify
      $runner = new CommandLineRunner( $verbosity, $writer );
      foreach ( $testFiles as $target )
        include_once ( $target );
      $allClasses = get_declared_classes();
      sort( $allClasses );
      foreach ( $allClasses as $class ) {
        $reflector = new ReflectionClass( $class );
        if ( $reflector->isSubclassOf( static::$sliverTestSuite ) )
          $runner->addTestSuite( $class );
      }
      
      $writer->writeNeutral(
        "Found " . sizeof( $runner->getTestSuites() ) . 
        " test suites in " . $timer->stop() . 's', 1
      );
      $writer->newline();
      
      // Run!
      return $runner->run();
      
    }
    
  };