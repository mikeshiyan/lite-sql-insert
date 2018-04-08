<?php

namespace Shiyan\LiteSqlInsert\Iterate\Scenario;

/**
 * Defines a basic LiteSqlInsert feature for Iterate Scenarios.
 *
 * Implementation classes need to implement at least the getConnection(),
 * getTable() and getFields() methods.
 */
trait InsertTrait {

  use BaseInsertTrait;

  /**
   * Gets the iterator to get its current element.
   *
   * @return \Iterator
   *   An instance of object implementing Iterator.
   *
   * @see \Shiyan\Iterate\Scenario\BaseScenario::getIterator()
   */
  abstract protected function getIterator(): \Iterator;

  /**
   * Gets field values to insert into row.
   *
   * If not overridden, this method gets and returns the current iterator's
   * element. So elements must be associative arrays, which keys should
   * intersect with field names from getFields() method.
   *
   * @return array
   *   Array of values to insert, in format 'field_name' => $value. Missing
   *   elements are replaced with NULLs.
   *
   * @throws \RuntimeException
   *   If the current iterator's element is not an array.
   *
   * @see \Shiyan\LiteSqlInsert\Iterate\Scenario\InsertTrait::getFields()
   */
  protected function getValues(): array {
    $values = $this->getIterator()->current();

    if (!is_array($values)) {
      throw new \RuntimeException('The insert feature is only enabled when iterator elements are associative arrays.');
    }

    return $values;
  }

  /**
   * Executes a prepared statement with the current iterator's element.
   *
   * @throws \Exception
   *   If exception is caught while executing a statement.
   *
   * @see \Shiyan\Iterate\Scenario\ScenarioInterface::onEach()
   * @see \Shiyan\LiteSqlInsert\InsertInterface::values()
   */
  public function onEach(): void {
    if ($this->insert) {
      $this->insert->values($this->getValues());
    }
  }

}
