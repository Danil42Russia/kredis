<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class TimeCommandTest extends CommandTestCase {
  function testArgument(): void {
    $redis  = $this->getphpInstance();
    $kredis = $this->getKphpInstance();

    $this->assertType($redis->time(), $kredis->time());
  }
}
