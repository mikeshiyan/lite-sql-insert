<?php

namespace LiteSqlInsert;

/**
 * Interface for Insert classes.
 */
interface InsertInterface {

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
  public function values(array $values): InsertInterface;

  /**
   * Commits a transaction.
   *
   * @return $this
   *   The called object.
   */
  public function commit(): InsertInterface;

}
