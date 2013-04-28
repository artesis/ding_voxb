<?php
/**
 * @file
 *
 * VoxbReviews class.
 * This class handles reviews colection.
 */

class VoxbReviewsController {
  private $handlers;

  public function __construct(array $handlers = array()) {
    $this->handlers = $handlers;
  }

  public function fetch($sXml) {
    foreach ($this->handlers as $handler) {
      $handler->fetch($sXml);
    }
  }

  public function get($type) {
    if ($this->handlers[$type]) {
      return $this->handlers[$type];
    }
  }
}
