<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class KeysCommandTest extends CommandTestCase {
  /**
   * @dataProvider commandArguments
   */
  function testArguments($pattern): void {
    $redis  = $this->getphpInstance();
    $kredis = $this->getKphpInstance();

    // TODO: добавить добавление данных

    $this->assertSame($redis->keys($pattern), $kredis->keys($pattern));
  }

  public function commandArguments(): array {
    return [
      [""],
      ["*"],
      ["user*"],
      ["user*2"],
    ];
  }
}
