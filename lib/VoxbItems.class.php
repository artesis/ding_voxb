<?php
/**
 * @file
 *
 */

/**
 * Items layer class
 */
class VoxbItems extends VoxbBase {

  private $items;

  public function __construct() {
    parent::getInstance();

    $items = array();
  }

  /**
   * Fetch multiple items with one request by list of faust numbers.
   *
   * @param string $faustNum
   *   Item faust number
   * @param bool $multiple
   *   Whether to send a multiple request
   */
  public function fetchByFaust($faustNums) {
    $fetch = array();

    foreach ($faustNums as $k => $v) {
      $fetch[] = array(
        'objectIdentifierValue' => $v,
        'objectIdentifierType' => 'FAUST'
      );
    }

    $data = array(
      'fetchData' => $fetch,
      'output' => array('contentType' => 'all')
    );

    $this->reviews = new VoxbReviewsController(@$this->reviewHandlers);

    $o = $this->call('fetchData', $data);

    if ($o->Body->fetchDataResponse->totalItemData) {
      foreach ($o->Body->fetchDataResponse->totalItemData as $k => $v) {
        $this->items[(string)$v->fetchData->objectIdentifierValue] = new VoxbItem();
        $this->items[(string)$v->fetchData->objectIdentifierValue]->addReviewHandler('review', new VoxbReviews());
        $this->items[(string)$v->fetchData->objectIdentifierValue]->fetchData($v);
      }
    }

    if ($o->Body->Fault->faultstring) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Getter function. Returns voxbItem object by faust number
   *
   * @param string $faust
   * @return object
   */
  public function getItem($faust) {
    if (isset($this->items[$faust])) {
      return $this->items[$faust];
    }

    return FALSE;
  }

  /**
   * Get amount of items in the layer
   *
   * @return integer
   */
  public function getCount() {
    return count($this->items);
  }

  /**
   * Add review handlers to factory
   *
   * @param string $name
   * @param object $object
   */
  public function addReviewHandler($name, $object) {
    $this->reviewHandlers[$name] = $object;
  }
}
