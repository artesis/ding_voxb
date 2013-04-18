<?php
/**
 * @file
 *
 */

/**
 * Items layer class
 */
class VoxbItems extends VoxbBase {

  private $items = array();

  public function __construct() {
    parent::getInstance();
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
    return $this->fetchBy_($faustNums, 'FAUST');
  }

  /**
   * Get VoxB data by ISBN identifiers.
   *
   * @param array $ids
   *   ISNB numbers of items.
   *
   * @return bool
   *   TRUE in case of success.
   *   FALSE if an error occurred.
   */
  public function fetchByISBN($ids) {
    return $this->fetchBy_($ids, 'ISBN');
  }

  /**
   * Fetch VoxB data for items.
   * @param array $ids
   *   See voxb:objectIdentifierValue.
   * @param string $type
   *   See voxb:objectIdentifierType.
   *
   * @return boolean
   *   TRUE in case of success.
   *   FALSE if an error occurred.
   */
  protected function fetchBy_($ids, $type) {
    $fetch = array();
    $type = strtoupper($type);
    $ids = array_unique($ids);

    foreach ($ids as $id) {
      $fetch[] = array(
        'objectIdentifierValue' => $id,
        'objectIdentifierType'  => $type,
      );
    }

    $data = array(
      'fetchData' => $fetch,
      'output' => array('contentType' => 'all'),
    );

    $this->reviews = new VoxbReviewsController();
    try {
      $o = $this->call('fetchData', $data);

      if (!empty($o->Body->fetchDataResponse->totalItemData)) {
        foreach ($o->Body->fetchDataResponse->totalItemData as $k => $v) {
          $id = (string) $v->fetchData->objectIdentifierValue;
          $this->items[$id] = new VoxbItem();
          $this->items[$id]->addReviewHandler('review', new VoxbReviews());
          $this->items[$id]->fetchData($v);
        }
      }
      elseif(!empty($o->Body->fetchDataResponse->error)) {
        ding_voxb_log(WATCHDOG_ERROR, 'IDs: @ids. Error: @error',
          array('ids' => $ids, 'error' => $o->Body->fetchDataResponse->error)
        );
        return FALSE;
      }
    }
    catch (Exception $e) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Getter function. Returns voxbItem object by its identifier.
   *
   * @param string $id
   *   Object identifier.
   *
   * @return object
   */
  public function getItem($id) {
    if (isset($this->items[$id])) {
      return $this->items[$id];
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
