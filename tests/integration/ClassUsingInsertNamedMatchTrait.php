<?php

namespace Shiyan\LiteSqlInsert\tests\integration;

use Shiyan\LiteSqlInsert\Connection;
use Shiyan\LiteSqlInsert\ConnectionInterface;
use Shiyan\LiteSqlInsert\IteratorRegex\Scenario\InsertNamedMatchTrait;

class ClassUsingInsertNamedMatchTrait {

  use InsertNamedMatchTrait;

  public $connection;

  public function __construct(\PDO $pdo) {
    $this->connection = new Connection($pdo);
  }

  protected function getConnection(): ConnectionInterface {
    return $this->connection;
  }

  protected function getPattern(): string {
    return '/^name: (?<name>\w+)(; value: (?<value>.*))?$/';
  }

  protected function getTable(): string {
    return 'vars';
  }

}
