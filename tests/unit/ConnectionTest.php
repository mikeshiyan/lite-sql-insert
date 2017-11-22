<?php

use Shiyan\LiteSqlInsert\Connection;
use Shiyan\LiteSqlInsert\Exception;
use Shiyan\LiteSqlInsert\Insert;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase {

  public function testInsert(): void {
    $pdo_statement = $this->createMock(PDOStatement::class);

    $pdo = $this->createMock(PDO::class);
    $pdo->method('prepare')->willReturn($pdo_statement);
    $pdo->method('beginTransaction')->willReturn(TRUE);

    $connection = new Connection($pdo);
    $this->assertInstanceOf(Insert::class, $connection->insert('', []));
  }

  public function testPrepare(): void {
    $pdo_statement = $this->createMock(PDOStatement::class);

    $pdo = $this->createMock(PDO::class);
    $pdo->method('prepare')->willReturn($pdo_statement);

    $connection = new Connection($pdo);
    $this->assertEquals($pdo_statement, $connection->prepare(''));
  }

  public function testPrepare_Exception(): void {
    $pdo = $this->createMock(PDO::class);
    $pdo->method('prepare')->willReturn(FALSE);

    $connection = new Connection($pdo);
    $this->expectException(Exception::class);
    $connection->prepare('');
  }

  public function testExecuteStatement(): void {
    $pdo_statement = $this->createMock(PDOStatement::class);
    $pdo_statement->method('execute')->willReturn(TRUE);

    $pdo = $this->createMock(PDO::class);

    $connection = new Connection($pdo);
    $this->assertEmpty($connection->executeStatement($pdo_statement, []));
  }

  public function testExecuteStatement_Exception(): void {
    $pdo_statement = $this->createMock(PDOStatement::class);
    $pdo_statement->method('execute')->willReturn(FALSE);

    $pdo = $this->createMock(PDO::class);

    $connection = new Connection($pdo);
    $this->expectException(Exception::class);
    $connection->executeStatement($pdo_statement, []);
  }

  public function testBeginTransaction(): void {
    $pdo = $this->createMock(PDO::class);
    $pdo->method('beginTransaction')->willReturn(TRUE);

    $connection = new Connection($pdo);
    $this->assertEmpty($connection->beginTransaction());
  }

  public function testBeginTransaction_Exception(): void {
    $pdo = $this->createMock(PDO::class);
    $pdo->method('beginTransaction')->willReturn(FALSE);

    $connection = new Connection($pdo);
    $this->expectException(Exception::class);
    $connection->beginTransaction();
  }

  public function testCommit(): void {
    $pdo = $this->createMock(PDO::class);
    $pdo->method('commit')->willReturn(TRUE);

    $connection = new Connection($pdo);
    $this->assertEmpty($connection->commit());
  }

  public function testCommit_Exception(): void {
    $pdo = $this->createMock(PDO::class);
    $pdo->method('commit')->willReturn(FALSE);

    $connection = new Connection($pdo);
    $this->expectException(Exception::class);
    $connection->commit();
  }

  public function testRollBack(): void {
    $pdo = $this->createMock(PDO::class);
    $pdo->method('rollBack')->willReturn(TRUE);

    $connection = new Connection($pdo);
    $this->assertEmpty($connection->rollBack());
  }

  public function testRollBack_Exception(): void {
    $pdo = $this->createMock(PDO::class);
    $pdo->method('rollBack')->willReturn(FALSE);

    $connection = new Connection($pdo);
    $this->expectException(Exception::class);
    $connection->rollBack();
  }

}
