<?php

namespace Shiyan\LiteSqlInsert\Iterate\Scenario;

/**
 * Defines a basic LiteSqlInsert feature for regex based Iterate Scenarios.
 *
 * This trait is meant to serve for single-pattern scenarios by default. But
 * implementation scenario classes can override this behavior.
 *
 * Implementation classes need to implement at least the getConnection(),
 * getPattern() and getTable() methods.
 */
trait InsertNamedMatchTrait {

  use BaseInsertTrait;

  /**
   * Gets the pattern to try to guess field names from it.
   *
   * @return string
   *   Regular expression pattern.
   */
  abstract protected function getPattern(): string;

  /**
   * Gets names of fields to insert into.
   *
   * If not overridden, this method gets and returns the names of subpatterns.
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
   * @see \Shiyan\Iterate\Scenario\RegexScenarioInterface::onMatch()
   * @see \Shiyan\LiteSqlInsert\InsertInterface::values()
   */
  public function onMatch(array $matches, string $pattern): void {
    if ($this->insert) {
      $this->insert->values($matches);
    }
  }

}
