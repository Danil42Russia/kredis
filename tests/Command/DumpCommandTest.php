<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class DumpCommandTest extends CommandTestCase {
  /**
   * @dataProvider commandArguments
   */
  function testArguments($key): void {
    $redis  = $this->getphpInstance();
    $kredis = $this->getKphpInstance();

    // TODO: добавить добавление данных

    $this->assertSame($redis->dump($key), $kredis->dump($key));
  }

  public function commandArguments(): array {
    return [
      [""],
      ["user"],
    ];
  }
}
