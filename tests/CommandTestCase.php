<?php

namespace Tests;

use Kredis\Kredis;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use Redis;

abstract class CommandTestCase extends TestCase {
  private ?Kredis $kphpInstance = null;
  private ?Redis $phpInstance = null;

  protected function setUp(): void {
    $this->kphpInstance = $this->newKphpInstance();
    $this->phpInstance  = $this->newPhpInstance();

    $this->kphpInstance->flushAll();
    $this->phpInstance->flushAll();
  }

  private function newKphpInstance(): Kredis {
    $kphpInstance = new Kredis();

    $host = (string)getenv('KPHP_REDIS_HOST');
    $post = (int)getenv('KPHP_REDIS_PORT');
    $kphpInstance->connect($host, $post);

    return $kphpInstance;
  }

  private function newPhpInstance(): Redis {
    $phpInstance = new Redis();

    $host = (string)getenv('PHP_REDIS_HOST');
    $post = (int)getenv('PHP_REDIS_PORT');
    $phpInstance->connect($host, $post);

    return $phpInstance;
  }

  public function getKphpInstance(): Kredis {
    self::assertNotNull($this->kphpInstance);

    return $this->kphpInstance;
  }

  public function getPhpInstance(): Redis {
    self::assertNotNull($this->phpInstance);

    return $this->phpInstance;
  }

  /**
   * @param (tuple(string, string))[] $data
   */
  protected function fillData($data) {
    $redis  = $this->getPhpInstance();
    $kredis = $this->getKphpInstance();

    foreach ($data as $item) {
      [$key, $value] = $item;

      $redis->set($key, $value);
      $kredis->set($key, $value);
    }
  }

  /**
   * Заглушка для проверки вывода команд
   *
   * @param mixed $expected
   * @param mixed $actual
   */
  public function assertCommand($expected, $actual, string $message = ''): void {
    $this->assertSame($expected, $actual, $message);
  }

  /**
   * Заглушка для проверки вывода команд
   *
   * @param mixed $expected
   * @param mixed $actual
   */
  public function assertType($expected, $actual, string $message = ''): void {
    $expected = gettype($expected);
    $actual   = gettype($actual);

    static::assertEquals($actual, $expected, $message);
  }
}
