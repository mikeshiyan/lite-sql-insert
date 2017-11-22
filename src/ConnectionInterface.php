<?php

namespace Shiyan\LiteSqlInsert;

/**
 * Interface for Connection classes.
 */
interface ConnectionInterface {

  /**
   * Returns a new Insert object for given table and fields.
   *
   * @param string $table
   *   Table name.
   * @param string[] $fields
   *   Field names.
   *
   * @return \Shiyan\LiteSqlInsert\InsertInterface
   *   Insert object.
   */
  public function insert(string $table, array $fields): InsertInterface;

  /**
   * Prepares a statement for execution and returns a statement object.
   *
   * @param string $statement
   *   SQL statement template for the target database server.
   *
   * @return \PDOStatement
   *   PDOStatement object.
   *
   * @throws \Shiyan\LiteSqlInsert\Exception
   *   If the database server cannot successfully prepare the statement.
   */
  public function prepare(string $statement): \PDOStatement;

  /**
   * Executes a prepared statement.
   *
   * @param \PDOStatement $pdo_statement
   *   PDOStatement object.
   * @param array $input_parameters
   *   An array of values with as many elements as there are bound parameters
   *   in the SQL statement being executed.
   *
   * @throws \Shiyan\LiteSqlInsert\Exception
   *   If executing a statement fails.
   */
  public function executeStatement(\PDOStatement $pdo_statement, array $input_parameters): void;

  /**
   * Initiates a transaction.
   *
   * @throws \Shiyan\LiteSqlInsert\Exception
   *   If PDO method fails.
   */
  public function beginTransaction(): void;

  /**
   * Commits a transaction.
   *
   * @throws \Shiyan\LiteSqlInsert\Exception
   *   If PDO method fails.
   */
  public function commit(): void;

  /**
   * Rolls back a transaction.
   *
   * @param \Throwable $exception
   *   (optional) The previous throwable used for the exception chaining.
   *
   * @throws \Shiyan\LiteSqlInsert\Exception
   *   If PDO method fails.
   */
  public function rollBack(\Throwable $exception = NULL): void;

}
