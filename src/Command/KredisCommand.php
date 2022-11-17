<?php

declare(strict_types = 1);

namespace Kredis\Command;

abstract class KredisCommand {
  private static function createBlock(string $name): array {
    return [
      "$" . strlen($name) . "\r\n",
      $name . "\r\n",
    ];
  }

  /**
   * @param string                  $command
   * @param string|array|null       $args
   * @param array|null              $options
   * @return string
   */
  public static function commandBuilder(string $command, $args = null, array $options = null): string {
    if (is_string($args)) {
      $args = [$args];
    }

    $request = [$command];
    if (!is_null($args)) {
      array_push($request, ...$args);
    }

    $response = [];
    foreach ($request as $item) {
      $block = static::createBlock((string)$item);

      array_push($response, ...$block);
    }

    if (!is_null($options)) {
      foreach ($options as $key => $value) {
        $key   = static::createBlock($key);
        $value = static::createBlock((string)$value);

        array_push($response, ...$key, ...$value);
      }
    }

    $start = ["*" . (count($response) / 2) . "\r\n"];
    array_unshift($response, ...$start);

    return join("", $response);
  }
}
