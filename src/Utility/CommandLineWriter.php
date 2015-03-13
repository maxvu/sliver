<?php

  namespace Sliver\Utility;
  
  class CommandLineWriter {

    private $IO;
    private static $statusOk = "[ \e[0;32mOK\033[0m ]";
    private static $statusBad = "[ \e[1;31m!!\033[0m ]";
    private static $statusNeutral = "[    ]";

    public function __construct ( $IO ) {
      $this->IO = $IO;
    }
    
    public function write ( $txt, $indentLevel = 0 ) {
      $msg = str_repeat( '   ', $indentLevel + 1 ) . $txt;
      $this->lastMsg = $msg;
      $this->IO->write( $msg, TRUE );
      return $this;
    }
    
    public function writeOk ( $txt, $indentLevel = 0 ) {
      $this->write( static::$statusOk . ' ' . $txt, $indentLevel );
      return $this;
    }
    
    public function writeBad ( $txt, $indentLevel = 0 ) {
      $this->write( static::$statusBad . ' ' . $txt, $indentLevel );
      return $this;
    }
    
    public function writeNeutral ( $txt, $indentLevel = 0 ) {
      $this->write( static::$statusNeutral . ' ' . $txt, $indentLevel );
      return $this;
    }
    
    public function newline () {
      $this->IO->write('');
      return $this;
    }
    
    public function makeGreen ( $text ) {
      return "\e[0;32m{$text}\033[0m";
    }
    
    public function makeRed ( $text ) {
      return "\e[1;31m{$text}\033[0m";
    }

  }
  
?>