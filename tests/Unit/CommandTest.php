<?php

namespace Tests\Unit;

use Kredis\Command\KredisCommand;
use PHPUnit\Framework\TestCase;

final class CommandTest extends TestCase {
  public function testNotArguments(): void {
    $expected = [
      "*1\r\n",
      "$7\r\n",
      "COMMAND\r\n",
    ];
    $actual   = KredisCommand::commandBuilder("COMMAND");

    $this->assertCommand($expected, $actual);
  }

  public function testEmptyStringArgument(): void {
    $expected = [
      "*2\r\n",
      "$7\r\n",
      "COMMAND\r\n",
      "$0\r\n",
      "\r\n",
    ];
    $actual   = KredisCommand::commandBuilder("COMMAND", "");

    $this->assertCommand($expected, $actual);
  }

  public function testOneArgumentString(): void {
    $expected = [
      "*2\r\n",
      "$7\r\n",
      "COMMAND\r\n",
      "$3\r\n",
      "ARG\r\n",
    ];
    $actual   = KredisCommand::commandBuilder("COMMAND", "ARG");

    $this->assertCommand($expected, $actual);
  }

  public function testOneArgumentArray(): void {
    $expected = [
      "*2\r\n",
      "$7\r\n",
      "COMMAND\r\n",
      "$3\r\n",
      "ARG\r\n",
    ];
    $actual   = KredisCommand::commandBuilder("COMMAND", ["ARG"]);

    $this->assertCommand($expected, $actual);
  }

  public function testNullArgument(): void {
    $expected = [
      "*2\r\n",
      "$7\r\n",
      "COMMAND\r\n",
      "$0\r\n",
      "\r\n",
    ];
    $actual   = KredisCommand::commandBuilder("COMMAND", [null]);

    $this->assertCommand($expected, $actual);
  }

  public function testManyArguments(): void {
    $expected = [
      "*4\r\n",
      "$7\r\n",
      "COMMAND\r\n",
      "$4\r\n",
      "ARG1\r\n",
      "$4\r\n",
      "ARG2\r\n",
      "$4\r\n",
      "ARG3\r\n",
    ];
    $actual   = KredisCommand::commandBuilder("COMMAND", ["ARG1", "ARG2", "ARG3"]);

    $this->assertCommand($expected, $actual);
  }

  public function testArgsOptions(): void {
    $expected = [
      "*7\r\n",
      "$7\r\n",
      "COMMAND\r\n",
      "$4\r\n",
      "ARG1\r\n",
      "$4\r\n",
      "ARG2\r\n",
      "$4\r\n",
      "OPT1\r\n",
      "$6\r\n",
      "V_OPT1\r\n",
      "$4\r\n",
      "OPT2\r\n",
      "$4\r\n",
      "1234\r\n",
    ];
    $actual   = KredisCommand::commandBuilder(
      "COMMAND",
      ["ARG1", "ARG2"],
      ["OPT1" => "V_OPT1", "OPT2" => 1234]
    );

    $this->assertCommand($expected, $actual);
  }

  public function testMixedArguments(): void {
    $expected = [
      "*5\r\n",
      "$7\r\n",
      "COMMAND\r\n",
      "$6\r\n",
      "STRING\r\n",
      "$1\r\n",
      "1\r\n",
      "$4\r\n",
      "1234\r\n",
      "$5\r\n",
      "12.34\r\n",
    ];
    $actual   = KredisCommand::commandBuilder("COMMAND", ['STRING', true, 1234, 12.34]);

    $this->assertCommand($expected, $actual);
  }

  /**
   * @param string[] $expected
   */
  private function assertCommand(array $expected, string $actual, string $message = '') {
    $expected = join("", $expected);

    $this->assertEquals($expected, $actual, $message);
  }
}
