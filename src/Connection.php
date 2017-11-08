<?php

namespace LiteSqlInsert;

/**
 * Provides a service which constructs Insert objects.
 */
class Connection implements ConnectionInterface {

  /**
   * PDO connection object.
   *
   * @var \PDO
   */
  protected $pdo;

  /**
   * Connection constructor.
   *
   * @param \PDO $pdo
   *   PDO connection object.
   */
  public function __construct(\PDO $pdo) {
    $this->pdo = $pdo;
  }

  /**
   * {@inheritdoc}
   */
  public function insert(string $table, array $fields): InsertInterface {
    return new Insert($this, $table, $fields);
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(string $statement): \PDOStatement {
    if (!$pdo_statement = $this->pdo->prepare($statement)) {
      throw new Exception('\PDO::prepare failed.');
    }

    return $pdo_statement;
  }

  /**
   * {@inheritdoc}
   */
  public function executeStatement(\PDOStatement $pdo_statement, array $input_parameters): void {
    if (!$pdo_statement->execute($input_parameters)) {
      throw new Exception('\PDOStatement::execute failed.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function beginTransaction(): void {
    if (!$this->pdo->beginTransaction()) {
      throw new Exception('\PDO::beginTransaction failed.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function commit(): void {
    if (!$this->pdo->commit()) {
      throw new Exception('\PDO::commit failed.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function rollBack(\Throwable $exception = NULL): void {
    if (!$this->pdo->rollBack()) {
      throw new Exception('\PDO::rollBack failed.', $exception);
    }
  }

}
