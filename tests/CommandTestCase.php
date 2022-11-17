<?php

namespace Tests;

use Kredis\Kredis;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use Redis;

abstract class CommandTestCase extends TestCase {
  private Kredis $kphpInstance;
  private Redis $phpInstance;

//  public static function setUpBeforeClass(): void {
//    fwrite(STDOUT, "START Redis");
//
//    $dir = __DIR__;
//    exec("sh $dir/down.sh");
//    exec("sh $dir/start.sh");
//    sleep(2);
//  }
//
//  public static function tearDownAfterClass(): void {
//    fwrite(STDOUT, "STOP Redis");
//
//    $dir = __DIR__;
//    exec("sh $dir/down.sh");
//  }

  public function getKphpInstance(): Kredis {
    $this->kphpInstance = new Kredis();

    $host = getenv('KPHP_REDIS_HOST');
    $post = getenv('KPHP_REDIS_PORT');
    $this->kphpInstance->connect($host, $post);

    return $this->kphpInstance;
  }

  public function getPhpInstance(): Redis {
    $this->phpInstance = new Redis();

    $host = getenv('PHP_REDIS_HOST');
    $post = getenv('PHP_REDIS_PORT');
    $this->phpInstance->connect($host, $post);

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
