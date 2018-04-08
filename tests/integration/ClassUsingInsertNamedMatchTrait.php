<?php

namespace Shiyan\LiteSqlInsert\tests\integration;

use Shiyan\LiteSqlInsert\Connection;
use Shiyan\LiteSqlInsert\Iterate\Scenario\InsertNamedMatchTrait;

class ClassUsingInsertNamedMatchTrait {

  use InsertNamedMatchTrait;

  public function __construct(\PDO $pdo) {
    $this->connection = new Connection($pdo);
    $this->table = 'vars';
  }

  protected function getPattern(): string {
    return '/^name: (?<name>\w+)(; value: (?<value>.*))?$/';
  }

}
