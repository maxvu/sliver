<?php

  require __DIR__ . '/../vendor/autoload.php';
  
  $target_dir = realpath( './' );
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
  
?>