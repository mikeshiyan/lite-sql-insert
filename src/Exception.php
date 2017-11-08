<?php

namespace LiteSqlInsert;

/**
 * Exception thrown in LiteSqlInsert\ classes.
 */
class Exception extends \Exception {

  /**
   * Exception constructor.
   *
   * @param string $message
   *   The Exception message to throw.
   * @param \Throwable $previous
   *   (optional) The previous throwable used for the exception chaining.
   */
  public function __construct(string $message, \Throwable $previous = NULL) {
    parent::__construct($message, 0, $previous);
  }

}
