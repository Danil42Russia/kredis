<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class PingCommandTest extends CommandTestCase {
  /**
   * @param string $message
   * @dataProvider commandArguments
   */
  function testArguments($message): void {
    $redis  = $this->getPhpInstance();
    $kredis = $this->getKphpInstance();

    $this->assertSame($redis->ping($message), $kredis->ping($message));
  }

  public function commandArguments(): array {
    return [
      [null],
      ["PING"],
      ["PONG"],
      ["HELLO"],
    ];
  }
}
