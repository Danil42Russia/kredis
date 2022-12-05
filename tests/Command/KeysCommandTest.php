<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class KeysCommandTest extends CommandTestCase {
  /**
   * @dataProvider commandArguments
   */
  function testArguments(string $pattern): void {
    $redis  = $this->getPhpInstance();
    $kredis = $this->getKphpInstance();

//    $this->fillData($this->prepareData());

    $this->assertSame($redis->keys($pattern), $kredis->keys($pattern));
  }

  public function commandArguments(): array {
    return [
      [""],
      ["*"],
      ["user*"],
      ["user*2"],
      ["key"],
    ];
  }

  /**
   * @return (tuple(string, string))[]
   */
  public function prepareData(): array {
    return [
      ["user22", "u22"],
      ["user1", "u1"],
      ["user12", "u12"],
      ["key", 1234],
    ];
  }
}
