<?php

namespace Shiyan\LiteSqlInsert\IteratorRegex\Scenario;

use Shiyan\LiteSqlInsert\ConnectionInterface;

/**
 * Defines a basic LiteSqlInsert feature for IteratorRegex Scenarios.
 *
 * This trait is meant to serve for single-pattern scenarios by default. But
 * implementation scenario classes can override this behavior.
 *
 * Implementation classes need to implement at least the getConnection(),
 * getPattern() and getTable() methods.
 */
trait InsertNamedMatchTrait {

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
   * Gets the pattern to try to guess field names from it.
   *
   * @return string
   *   Regular expression pattern.
   */
  abstract protected function getPattern(): string;

  /**
   * Gets a name of a table to insert matches into.
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
  protected function getFields(): array {
    // Search the pattern for named subpatterns and get their names.
    // @link https://stackoverflow.com/a/47753964/3260408
    preg_match_all("~(?<!\\\\)(?:\\\\{2})*\(\?(?|P?<([_A-Za-z]\w{0,31})>|'([_A-Za-z]\w{0,31})')~", $this->getPattern(), $matches);

    return $matches[1];
  }

  /**
   * Prepares a statement and initiates a transaction in the pre-run phase.
   *
   * @see \Shiyan\IteratorRegex\Scenario\ScenarioInterface::preRun()
   */
  public function preRun(): void {
    $this->insert = $this->getConnection()->insert($this->getTable(), $this->getFields());
  }

  /**
   * Executes a prepared statement with $matches.
   *
   * @param array $matches
   *   Array of values to insert, in format 'field_name' => $value. Missing
   *   elements are replaced with NULLs.
   * @param string $pattern
   *   Pattern that matched.
   *
   * @throws \Exception
   *   If exception is caught while executing a statement.
   *
   * @see \Shiyan\IteratorRegex\Scenario\ScenarioInterface::onMatch()
   * @see \Shiyan\LiteSqlInsert\InsertInterface::values()
   */
  public function onMatch(array $matches, string $pattern): void {
    if ($this->insert) {
      $this->insert->values($matches);
    }
  }

  /**
   * Commits a transaction in the post-run phase.
   *
   * @see \Shiyan\IteratorRegex\Scenario\ScenarioInterface::postRun()
   */
  public function postRun(): void {
    if ($this->insert) {
      $this->insert->commit();
    }
  }

}
