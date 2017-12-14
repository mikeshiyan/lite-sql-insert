<?php

namespace Shiyan\LiteSqlInsert\tests\unit;

use PHPUnit\Framework\TestCase;
use Shiyan\LiteSqlInsert\Connection;
use Shiyan\LiteSqlInsert\Insert;

class InsertTest extends TestCase {

  public function testValues(): void {
    $connection = $this->createMock(Connection::class);

    $insert = new Insert($connection, '', ['a' => 1]);
    $this->assertSame($insert, $insert->values([]));
  }

  public function testValues_Exception(): void {
    $connection = $this->createMock(Connection::class);
    $connection->method('executeStatement')
      ->willThrowException(new \Exception());

    $insert = new Insert($connection, '', []);
    $this->expectException(\Exception::class);
    $insert->values([]);
  }

  public function testCommit(): void {
    $connection = $this->createMock(Connection::class);

    $insert = new Insert($connection, '', []);
    $this->assertSame($insert, $insert->commit());
  }

}
