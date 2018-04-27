<?php

namespace Shiyan\LiteSqlInsert\tests\integration;

use Shiyan\LiteSqlInsert\Connection;
use Shiyan\LiteSqlInsert\Iterate\Scenario\InsertTrait;

class ClassUsingInsertTrait {

  use InsertTrait;

  protected $iterator;

  public function __construct(\PDO $pdo, \Iterator $iterator) {
    $this->connection = new Connection($pdo);
    $this->table = 'vars';
    $this->fields = ['name', 'value'];
    $this->iterator = $iterator;
  }

  protected function getIterator(): \Iterator {
    return $this->iterator;
  }

}
