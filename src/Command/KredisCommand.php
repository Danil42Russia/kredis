<?php

declare(strict_types = 1);

namespace Kredis\Command;

abstract class KredisCommand {
  /**
   * @param string $name
   * @return string[]
   */
  private static function createBlock(string $name): array {
    return [
      "$" . strlen($name) . "\r\n",
      $name . "\r\n",
    ];
  }

  /**
   * @param string              $command
   * @param string|mixed[]|null $args
   * @param mixed[]|null        $options
   * @return string
   */
  public static function commandBuilder(string $command, $args = null, array $options = null): string {
    if (is_string($args)) {
      $args = [$args];
    }

    $request = [$command];
    if (!is_null($args)) {
      $request = array_merge($request, $args);
    }

    /** @var string[] $response */
    $response = [];
    foreach ($request as $item) {
      $block = static::createBlock((string)$item);

      $response = array_merge($response, $block);
    }

    if (!is_null($options)) {
      foreach ($options as $key => $value) {
        $key   = static::createBlock((string)$key);
        $value = static::createBlock((string)$value);

        $response = array_merge($response, $key, $value);
      }
    }

    $start    = ["*" . (count($response) / 2) . "\r\n"];
    $response = array_merge($start, $response);

    return implode("", $response);
  }
}
