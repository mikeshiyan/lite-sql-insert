<?php

use Shiyan\LiteSqlInsert\Connection;
use PHPUnit\DbUnit\Database\Connection as DbUnitConnection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class LiteSqlInsertTest extends TestCase {

  use TestCaseTrait;

  protected static $pdo;
  protected $dbUnitConnection;

  protected function getPdo(): PDO {
    if (!isset(self::$pdo)) {
      self::$pdo = new PDO('sqlite::memory:');
      self::$pdo->exec('CREATE TABLE vars (name TEXT, value TEXT)');
    }

    return self::$pdo;
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

}
