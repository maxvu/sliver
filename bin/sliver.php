#!/usr/bin/php
<?php
  
  if ( is_readable( __DIR__ . '/../vendor/autoload.php' ) ) {
    require( __DIR__ . '/../vendor/autoload.php' );
  } else if ( is_readable( __DIR__ . ' /../../autoload.php' ) ) {
    require( __DIR__ . ' /../../autoload.php' );
  } else {
    echo "Couldn't find autoload.php";
    exit(1);
  }
  
  $target_dir = getcwd();
  if ( isset( $argv[1] ) && file_exists( realpath( $argv[1] ) ) )
    $target_dir = realpath( $argv[1] );

  $it = new RegexIterator(
    new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator( $target_dir )
    ),
    '/^.+\.php$/i',
    RecursiveRegexIterator::GET_MATCH
  );

  foreach ($it as $file) {
    if ( is_readable( $file[0] ) )
      require_once $file[0];
  }
  
  $controller = new \Sliver\ConsoleTestController();
  
  foreach ( get_declared_classes() as $class ) {
    if ( is_subclass_of( $class, '\Sliver\TestSuite' ) )
      $controller->addSuite(new $class());
  }
  
  $controller->run();
  
  if ( $controller->allPassed() )
    exit(0);
  
  if ( sizeof( $controller->getSuites() ) == 0 )
    echo "No tests found. (Is the tests directory reachable from here?)\n";
  
  exit(1);
  
?>
