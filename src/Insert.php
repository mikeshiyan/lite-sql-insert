<?php

namespace Shiyan\LiteSqlInsert;

/**
 * Provides an insert query abstraction.
 */
class Insert implements InsertInterface {

  /**
   * Connection object.
   *
   * @var \Shiyan\LiteSqlInsert\ConnectionInterface
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
   * @param \Shiyan\LiteSqlInsert\ConnectionInterface $connection
   *   Connection object.
   * @param string $table
   *   Table name.
   * @param string[] $fields
   *   Field names.
   */
  public function __construct(ConnectionInterface $connection, string $table, array $fields) {
    $this->connection = $connection;
    $this->fields = $fields;

    $field_names = implode(', ', $fields);
    $placeholders = implode(', ', array_fill(0, count($fields), '?'));
    $sql = "INSERT INTO $table ($field_names) VALUES ($placeholders)";

    $this->statement = $connection->prepare($sql);
    $connection->beginTransaction();
  }

  /**
   * {@inheritdoc}
   */
  public function values(array $values): InsertInterface {
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
   * {@inheritdoc}
   */
  public function commit(): InsertInterface {
    $this->connection->commit();

    return $this;
  }

  /**
   * Insert destructor.
   */
  public function __destruct() {
    try {
      $this->connection->rollBack();
    }
    catch (\Exception $exception) {
    }
  }

}
