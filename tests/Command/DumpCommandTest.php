<?php

namespace Tests\Command;

use Tests\CommandTestCase;

final class DumpCommandTest extends CommandTestCase {
  /**
   * @param string $key
   * @dataProvider commandArguments
   */
  function testArguments($key): void {
    $redis  = $this->getPhpInstance();
    $kredis = $this->getKphpInstance();

    $this->assertSame($redis->set("user", 123), $kredis->set("user", 123));

    $this->assertSame($redis->dump($key), $kredis->dump($key));
  }

  public function commandArguments(): array {
    return [
      [""],
      ["user"],
    ];
  }
}
