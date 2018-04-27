<?php

namespace Shiyan\LiteSqlInsert\tests\integration;

use PHPUnit\DbUnit\Database\Connection as DbUnitConnection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\TestCase;
use Shiyan\LiteSqlInsert\Connection;

class LiteSqlInsertTest extends TestCase {

  protected $pdo;
  protected $dbUnitConnection;

  protected function getPdo(): \PDO {
    if (!isset($this->pdo)) {
      $this->pdo = new \PDO('sqlite::memory:');
      $this->pdo->exec('CREATE TABLE vars (name TEXT, value TEXT DEFAULT NULL)');
    }

    return $this->pdo;
  }

  protected function getConnection(): DbUnitConnection {
    if (!isset($this->dbUnitConnection)) {
      $this->dbUnitConnection = $this->createDefaultDBConnection($this->getPdo(), ':memory:');
    }

    return $this->dbUnitConnection;
  }

  protected function getDataSet(): IDataSet {
    return $this->createArrayDataSet([]);
  }

  public function testInsert(): void {
    $connection = new Connection($this->getPdo());

    $connection->insert('vars', ['name', 'value'])
      ->values(['value' => 1, 'name' => 'a'])
      ->commit();

    $expected = $this->createArrayDataSet(['vars' => [['name' => 'a', 'value' => 1]]]);
    $actual = $this->getConnection()->createDataSet(['vars']);
    $this->assertDataSetsEqual($expected, $actual);

    // Insert some rows and commit.
    $insert = $connection->insert('vars', ['name', 'value']);
    foreach ([2 => 'b', 'c', 'd'] as $value => $name) {
      $insert->values(['name' => $name, 'value' => $value]);
    }
    $insert->commit();
    unset($insert);

    $rows = [
      ['name' => 'a', 'value' => 1],
      ['name' => 'b', 'value' => 2],
      ['name' => 'c', 'value' => 3],
      ['name' => 'd', 'value' => 4],
    ];
    $expected = $this->createArrayDataSet(['vars' => $rows]);
    $actual = $this->getConnection()->createDataSet(['vars']);
    $this->assertDataSetsEqual($expected, $actual);

    // Insert a row and don't commit.
    $insert = $connection->insert('vars', ['name', 'value']);
    $insert->values(['name' => 'e', 'value' => 5]);
    unset($insert);

    $actual = $this->getConnection()->createDataSet(['vars']);
    $this->assertDataSetsEqual($expected, $actual);
  }

  public function testInsertNamedMatchTrait(): void {
    $trait = new ClassUsingInsertNamedMatchTrait($this->getPdo());

    $trait->preRun();

    $this->assertSame(TRUE, $this->getPdo()->inTransaction());

    $trait->onMatch([0 => 'name: a', 'name' => 'a'], '');
    $trait->onMatch([0 => 'name: b; value: 2', 'name' => 'b', 'value' => '2'], '');
    $trait->postRun();

    $this->assertSame(FALSE, $this->getPdo()->inTransaction());
    $rows = [
      ['name' => 'a', 'value' => NULL],
      ['name' => 'b', 'value' => 2],
    ];
    $expected = $this->createArrayDataSet(['vars' => $rows]);
    $actual = $this->getConnection()->createDataSet(['vars']);
    $this->assertDataSetsEqual($expected, $actual);
  }

  public function testInsertTrait(): void {
    $iterator = new \ArrayIterator([
      ['name' => 'a'],
      ['name' => 'b', 'value' => 2],
    ]);

    $trait = new ClassUsingInsertTrait($this->getPdo(), $iterator);

    $trait->preRun();
    $trait->onEach();
    $iterator->next();
    $trait->onEach();
    $trait->postRun();

    $rows = [
      ['name' => 'a', 'value' => NULL],
      ['name' => 'b', 'value' => 2],
    ];
    $expected = $this->createArrayDataSet(['vars' => $rows]);
    $actual = $this->getConnection()->createDataSet(['vars']);
    $this->assertDataSetsEqual($expected, $actual);
  }

}
