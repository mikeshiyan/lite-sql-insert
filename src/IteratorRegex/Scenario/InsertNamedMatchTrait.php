<?php

namespace Shiyan\LiteSqlInsert\IteratorRegex\Scenario;

use Shiyan\LiteSqlInsert\Iterate\Scenario\InsertNamedMatchTrait as NewInsertNamedMatchTrait;

/**
 * Defines a basic LiteSqlInsert feature for IteratorRegex Scenarios.
 *
 * @deprecated in LiteSqlInsert 2.1.0 and might be removed before the next major
 *   release. This trait exists to preserve the backwards compatibility, but it
 *   is deprecated now. Implementations are encouraged to use the
 *   \Shiyan\LiteSqlInsert\Iterate\Scenario\InsertNamedMatchTrait instead. That
 *   new trait is compatible with both iterator-regex 1.x.x and iterate 2.x.x.
 *
 * @see \Shiyan\LiteSqlInsert\Iterate\Scenario\InsertNamedMatchTrait
 */
trait InsertNamedMatchTrait {

  use NewInsertNamedMatchTrait;

}
