<?php

namespace Kredis\Command;

/**
 * @param string|array|null $options
 */
abstract class KredisCommand {
  public static function commandBuilder(string $command, $options = null): string {
    if (is_string($options)) {
      $options = [$options];
    }

    if (is_array($options)) {
      $options = array_map(function($option) {
        return '"' . $option . '"';
      }, $options);

      $options = implode(" ", $options);
    }

    if (!is_null($options)) {
      $command = $command . " " . $options;
    }

    return $command . "\r\n";
  }
}
