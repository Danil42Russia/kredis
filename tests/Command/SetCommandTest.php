<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class SetCommandTest extends CommandTestCase {
  /**
   * @dataProvider commandArguments
   */
  function testArguments($key, $value): void {
    $redis  = $this->getphpInstance();
    $kredis = $this->getKphpInstance();

    $this->assertSame($redis->set($key, $value), $kredis->set($key, $value));
    $this->assertSame($redis->get($key), $kredis->get($key));
  }

  public function commandArguments(): array {
    return [
      ["arg1", "value1"],
      ["arg3", "value_" . time()],
      ["arg4", 1234],
      ["arg2", true],
    ];
  }
}
