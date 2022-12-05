<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class SetCommandTest extends CommandTestCase {
  /**
   * @param mixed  $value
   * @dataProvider commandArguments
   */
  function testArguments(string $key, $value): void {
    $redis  = $this->getPhpInstance();
    $kredis = $this->getKphpInstance();

    $this->assertSame($redis->set($key, $value), $kredis->set($key, $value));
    $this->assertSame($redis->get($key), $kredis->get($key));
  }

  public function commandArguments(): array {
    return [
      ["arg1", "value1"],
      ["arg3", "value_3"],
      ["arg4", 1234],
      ["arg2", true],
    ];
  }
}
