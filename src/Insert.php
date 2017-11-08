<?php

namespace LiteSqlInsert;

/**
 * Provides an insert query abstraction.
 */
class Insert {

  /**
   * Connection object.
   *
   * @var \LiteSqlInsert\Connection
   */
  protected $connection;

  /**
   * Field names.
   *
   * @var string[]
   */
  protected $fields;

  /**
   * Prepared statement object.
   *
   * @var \PDOStatement
   */
  protected $statement;

  /**
   * Insert constructor.
   *
   * @param \LiteSqlInsert\Connection $connection
   *   Connection object.
   * @param string $table
   *   Table name.
   * @param string[] $fields
   *   Field names.
   */
  public function __construct(Connection $connection, string $table, array $fields) {
    $this->connection = $connection;
    $this->fields = $fields;

    $field_names = implode(', ', $fields);
    $placeholders = implode(', ', array_fill(0, count($fields), '?'));
    $sql = "INSERT INTO $table ($field_names) VALUES ($placeholders)";

    $this->statement = $connection->prepare($sql);
    $connection->beginTransaction();
  }

  /**
   * Executes a prepared statement with given values.
   *
   * @param array $values
   *   Array of values to insert, in format 'field_name' => $value. Missing
   *   elements are replaced with NULLs.
   *
   * @return $this
   *   The called object.
   *
   * @throws \Exception
   *   If exception is caught while executing a statement.
   */
  public function values(array $values): self {
    $values_in_order = [];

    foreach ($this->fields as $name) {
      $values_in_order[] = $values[$name] ?? NULL;
    }

    try {
      $this->connection->executeStatement($this->statement, $values_in_order);
    }
    catch (\Exception $exception) {
      $this->connection->rollBack($exception);

      throw $exception;
    }

    return $this;
  }

  /**
   * Commits a transaction.
   *
   * @return $this
   *   The called object.
   */
  public function commit(): self {
    $this->connection->commit();

    return $this;
  }

}
