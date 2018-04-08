<?php

namespace Shiyan\LiteSqlInsert\Iterate\Scenario;

use Shiyan\Iterate\Scenario\ScenarioInterface;
use Shiyan\LiteSqlInsert\ConnectionInterface;

/**
 * Defines common interface and methods for InsertTraits.
 *
 * Implementations should not use this trait directly, because it does not do
 * the main thing - actual inserting. Use one of its users instead - InsertTrait
 * or InsertNamedMatchTrait.
 *
 * @internal
 */
trait BaseInsertTrait {

  /**
   * An InsertInterface instance.
   *
   * @var \Shiyan\LiteSqlInsert\InsertInterface
   */
  protected $insert;

  /**
   * A ConnectionInterface instance.
   *
   * @var \Shiyan\LiteSqlInsert\ConnectionInterface
   */
  protected $connection;

  /**
   * Table name.
   *
   * @var string
   */
  protected $table;

  /**
   * Field names.
   *
   * @var string[]
   */
  protected $fields;

  /**
   * Sets ConnectionInterface instance.
   *
   * @param \Shiyan\LiteSqlInsert\ConnectionInterface $connection
   *   An instance of object implementing ConnectionInterface.
   *
   * @return $this|\Shiyan\Iterate\Scenario\ScenarioInterface
   *   The called object.
   */
  public function setConnection(ConnectionInterface $connection): ScenarioInterface {
    $this->connection = $connection;

    return $this;
  }

  /**
   * Gets a ConnectionInterface instance.
   *
   * @return \Shiyan\LiteSqlInsert\ConnectionInterface
   *   A ConnectionInterface instance.
   */
  protected function getConnection(): ConnectionInterface {
    return $this->connection;
  }

  /**
   * Sets the table name.
   *
   * @param string $table
   *   The table name to use.
   *
   * @return $this|\Shiyan\Iterate\Scenario\ScenarioInterface
   *   The called object.
   */
  public function setTable(string $table): ScenarioInterface {
    $this->table = $table;

    return $this;
  }

  /**
   * Gets a name of a table to insert into.
   *
   * @return string
   *   Table name.
   */
  protected function getTable(): string {
    return $this->table;
  }

  /**
   * Sets names of fields to insert into.
   *
   * @param string[] $fields
   *   Field names.
   *
   * @return $this|\Shiyan\Iterate\Scenario\ScenarioInterface
   *   The called object.
   */
  public function setFields(array $fields): ScenarioInterface {
    $this->fields = $fields;

    return $this;
  }

  /**
   * Gets names of fields to insert into.
   *
   * @return string[]
   *   Field names.
   */
  protected function getFields(): array {
    return $this->fields;
  }

  /**
   * Prepares a statement and initiates a transaction in the pre-run phase.
   *
   * @see \Shiyan\Iterate\Scenario\ScenarioInterface::preRun()
   */
  public function preRun(): void {
    $this->insert = $this->getConnection()->insert($this->getTable(), $this->getFields());
  }

  /**
   * Commits a transaction in the post-run phase.
   *
   * @see \Shiyan\Iterate\Scenario\ScenarioInterface::postRun()
   */
  public function postRun(): void {
    if ($this->insert) {
      $this->insert->commit();
    }
  }

}
