<?php

namespace Tests\Unit;

use Kredis\Command\KredisCommand;
use PHPUnit\Framework\TestCase;

final class CommandTest extends TestCase {
  public function testNotArguments(): void {
    $expected = "COMMAND\r\n";
    $actual   = KredisCommand::commandBuilder("COMMAND");

    $this->assertEquals($expected, $actual);
  }

  public function testOneArguments(): void {
    $expected = "COMMAND \"arg\"\r\n";
    $actual   = KredisCommand::commandBuilder("COMMAND", "arg");

    $this->assertEquals($expected, $actual);
  }

  public function testManyArguments(): void {
    $expected = "COMMAND \"arg1\" \"arg2\" \"arg3\"\r\n";
    $actual   = KredisCommand::commandBuilder("COMMAND", ["arg1", "arg2", "arg3"]);

    $this->assertEquals($expected, $actual);
  }
}
