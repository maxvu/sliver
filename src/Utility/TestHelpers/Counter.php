<?php

  namespace Sliver\Tests\TestHelpers;
  use Sliver\Tests\Helpers\Value;
  
  class Counter extends Value {
    public function inc () { $this->value++; }
  }

?>