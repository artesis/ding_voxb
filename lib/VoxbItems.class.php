<?php
/**
 * @file
 *
 */

error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

/**
 * Fetch umultiple items with one request
 */
class VoxbItems extends VoxbBase {

  private $items;

  public function __construct() {
    parent::getInstance();

    $items = array();
  }

  /**
   * Fetching item from voxb server by faust number.
   *
   * @param string $faustNum
   *   Item faust number
   * @param bool $multiple
   *   Whether to send a multiple request
   */
  public function fetchByFaust($faustNums) {
    $fetch = array();

    foreach($faustNums as $k => $v) {
      $fetch[] = array(
        'objectIdentifierValue' => $v,
        'objectIdentifierType' => 'FAUST'
      );
    }

    $data = array(
      'fetchData' => $fetch,
      'output' => array('contentType' => 'all')
    );

    $this->reviews = new VoxbReviewsController($this->reviewHandlers);

    $o = $this->call('fetchData', $data);

    if ($o->Body->fetchDataResponse->totalItemData) {
      foreach($o->Body->fetchDataResponse->totalItemData as $k => $v) {
        $this->items[(string)$v->fetchData->objectIdentifierValue] = new VoxbItem();
        $this->items[(string)$v->fetchData->objectIdentifierValue]->fetchData($v);
      }
    }

    if ($o->Body->Fault->faultstring) {
      return FALSE;
    }
    
    return TRUE;
  }
}
