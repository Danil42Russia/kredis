<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class GetCommandTest extends CommandTestCase {
  /**
   * @param string $key
   * @dataProvider commandArguments
   */
  function testArguments($key): void {
    $redis  = $this->getPhpInstance();
    $kredis = $this->getKphpInstance();

    // TODO: добавить добавление данных

    $this->assertSame($redis->get($key), $kredis->get($key));
  }

  public function commandArguments(): array {
    return [
      [""],
      ["user"],
    ];
  }
}
