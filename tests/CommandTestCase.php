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

    $host = getenv('KPHP_REDIS_HOST');
    $post = getenv('KPHP_REDIS_PORT');
    $kphpInstance->connect($host, $post);

    return $kphpInstance;
  }

  private function newPhpInstance(): Redis {
    $phpInstance = new Redis();

    $host = getenv('PHP_REDIS_HOST');
    $post = getenv('PHP_REDIS_PORT');
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
