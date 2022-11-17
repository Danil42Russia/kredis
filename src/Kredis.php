<?php

declare(strict_types = 1);

namespace Kredis;

use Kredis\Command\KredisCommand as Command;
use Kredis\Exception\KredisException;

class Kredis {
  const CRLF_LEN = 2;

  /** @var mixed $resource */
  private $resource = null;

  /**
   * @throws KredisException
   */
  function connect(string $host, int $port = 6379, float $timeout = 5.0): self {
    if ($this->resource !== null) {
      return $this;
    }

    $address = $host . ":" . $port;
    $result  = stream_socket_client($address, $error_code, $error_message, $timeout, STREAM_CLIENT_CONNECT);
    if (!$result) {
      throw new KredisException("Error connect to Redis: $error_message $error_code");
    }

    $this->resource = $result;

    return $this;
  }

  /**
   * @link https://redis.io/commands/ping
   *
   * @return string|boolean
   *
   * @throws KredisException
   */
  function ping(?string $message = null) {
    $this->write("PING", $message);

    $resp = (string)$this->read();
    if ($message === null) {
      return $resp === "PONG";
    }

    return $resp;
  }

  /**
   * @link https://redis.io/commands/get
   *
   * @throws KredisException
   */
  function get(string $key) {
    $this->write("GET", $key);

    return $this->read();
  }

  /**
   * @link https://redis.io/commands/dump
   *
   * @return string|false
   *
   * @throws KredisException
   */
  function dump(string $key) {
    $this->write("DUMP", $key);

    return $this->read();
  }

  /* @link https://redis.io/commands/discard
   *
   * @throws KredisException
   */
  function discard(): bool {
    $this->write("DISCARD");

    // Тут ошибка, надо подумать
    // Кажется ошибка в php-storm stubs
    return (bool)$this->read();
  }

  /**
   * @link https://redis.io/commands/exists
   *
   * @param string|string[] $key
   *
   * @throws KredisException
   */
  public function exists($key): int {
    $this->write("EXISTS", $key);

    return (int)($this->read());
  }

  /**
   * @link https://redis.io/commands/echo
   *
   * @throws KredisException
   */
  public function echo(string $message): string {
    $this->write("ECHO", $message);

    $resp = $this->read();
    return (string)$resp;
  }

  /**
   * @link https://redis.io/commands/time
   *
   * @throws KredisException
   */
  public function time(): array {
    $this->write("TIME");

    return (array)($this->read());
  }

  /**
   * @param string|array $key
   * @param string       ...$otherKeys
   *
   * @throws KredisException
   */
  public function del($key, ...$otherKeys): int {
    if (is_string($key)) {
      $key = [$key];
    }

    $keys = [];
    if (!empty($otherKeys)) {
      $keys = array_merge($key, $otherKeys);
    }

    $this->write("DEL", $keys);

    return (int)$this->read();
  }

  /**
   * @throws KredisException
   */
  public function keys(string $pattern): array {
    $this->write("keys", $pattern);

    return (array)$this->read();
  }

  function close(): bool {
    if ($this->resource != null) {
      fclose($this->resource);
    }

    $this->resource = null;
    return true;
  }

  /**
   * @param string|array|null $options
   */
  private function write(string $command, $options = null) {
    $command = Command::commandBuilder($command, $options);

    fwrite($this->resource, $command);
  }

  /**
   * @return string|int|array|false
   * @throws KredisException
   */
  private function read() {
    $buffer = fgets($this->resource);

    if ($buffer === false) {
      throw new KredisException();
    }

    $type  = $buffer[0];
    $value = substr($buffer, 1, -self::CRLF_LEN);

    switch ($type) {
      case "+":
      {
        return $value;
      }

      case "$":
      {
        $size = (int)$value;
        if ($size === -1) {
          return false;
        }

        $size = $size + self::CRLF_LEN;

        $data = fread($this->resource, $size);

        return substr($data, 0, -self::CRLF_LEN);
      }

      case "-":
      {
        throw new KredisException("Type " . $type . " unsupported");
      }

      case ":":
      {
        return (int)$value;
      }

      case "*":
      {
        $count = (int)$value;

        $data = [];

        for ($i = 0; $i < $count; ++$i) {
          $data[] = $this->read();
        }

        return $data;
      }

      default:
      {
        throw new KredisException("Unknown response type: $type");
      }
    }
  }
}
