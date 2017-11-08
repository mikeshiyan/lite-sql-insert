<?php

namespace LiteSqlInsert;

/**
 * Provides a service which constructs Insert objects.
 */
class Connection {

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
   * Returns a new Insert object for given table and fields.
   *
   * @param string $table
   *   Table name.
   * @param string[] $fields
   *   Field names.
   *
   * @return \LiteSqlInsert\Insert
   *   Insert object.
   */
  public function insert(string $table, array $fields): Insert {
    return new Insert($this, $table, $fields);
  }

  /**
   * Prepares a statement for execution and returns a statement object.
   *
   * @param string $statement
   *   SQL statement template for the target database server.
   *
   * @return \PDOStatement
   *   PDOStatement object.
   *
   * @throws \LiteSqlInsert\Exception
   *   If the database server cannot successfully prepare the statement.
   */
  public function prepare(string $statement): \PDOStatement {
    if (!$pdo_statement = $this->pdo->prepare($statement)) {
      throw new Exception('\PDO::prepare failed.');
    }

    return $pdo_statement;
  }

  /**
   * Executes a prepared statement.
   *
   * @param \PDOStatement $pdo_statement
   *   PDOStatement object.
   * @param array $input_parameters
   *   An array of values with as many elements as there are bound parameters
   *   in the SQL statement being executed.
   *
   * @throws \LiteSqlInsert\Exception
   *   If executing a statement fails.
   */
  public function executeStatement(\PDOStatement $pdo_statement, array $input_parameters): void {
    if (!$pdo_statement->execute($input_parameters)) {
      throw new Exception('\PDOStatement::execute failed.');
    }
  }

  /**
   * Initiates a transaction.
   *
   * @throws \LiteSqlInsert\Exception
   *   If PDO method fails.
   */
  public function beginTransaction(): void {
    if (!$this->pdo->beginTransaction()) {
      throw new Exception('\PDO::beginTransaction failed.');
    }
  }

  /**
   * Commits a transaction.
   *
   * @throws \LiteSqlInsert\Exception
   *   If PDO method fails.
   */
  public function commit(): void {
    if (!$this->pdo->commit()) {
      throw new Exception('\PDO::commit failed.');
    }
  }

  /**
   * Rolls back a transaction.
   *
   * @param \Throwable $exception
   *   (optional) The previous throwable used for the exception chaining.
   *
   * @throws \LiteSqlInsert\Exception
   *   If PDO method fails.
   */
  public function rollBack(\Throwable $exception = NULL): void {
    if (!$this->pdo->rollBack()) {
      throw new Exception('\PDO::rollBack failed.', $exception);
    }
  }

}
