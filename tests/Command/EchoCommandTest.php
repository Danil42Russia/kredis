<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class EchoCommandTest extends CommandTestCase {
  /**
   * @param string $message
   * @dataProvider commandArguments
   */
  function testArguments($message): void {
    $redis  = $this->getPhpInstance();
    $kredis = $this->getKphpInstance();

    $this->assertSame($redis->echo($message), $kredis->echo($message));
  }

  public function commandArguments(): array {
    return [
      ["HELLO"],
      ["Hello Redis"],
    ];
  }
}
