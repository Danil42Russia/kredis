<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class GetCommandTest extends CommandTestCase {
  /**
   * @dataProvider commandArguments
   */
  function testArguments(string $key): void {
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
