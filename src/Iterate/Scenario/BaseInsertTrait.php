<?php

namespace Shiyan\LiteSqlInsert\Iterate\Scenario;

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
   * Gets a ConnectionInterface instance.
   *
   * @return \Shiyan\LiteSqlInsert\ConnectionInterface
   *   A ConnectionInterface instance.
   */
  abstract protected function getConnection(): ConnectionInterface;

  /**
   * Gets a name of a table to insert into.
   *
   * @return string
   *   Table name.
   */
  abstract protected function getTable(): string;

  /**
   * Gets names of fields to insert into.
   *
   * @return string[]
   *   Field names.
   */
  abstract protected function getFields(): array;

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
