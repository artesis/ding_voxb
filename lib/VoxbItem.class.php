<?php
/**
 * @file
 *
 */

/**
 * This is the main VoxB-client class.
 * It has a method to fetch item information from VoxB server.
 * And parse it from simplexml object to different kind of VoxB-objects: tags, ratings, reviews
 */
class VoxbItem extends VoxbBase {
  private $tags;
  private $reviews;

  private $reviewHandlers = array();
  private $objectIdentifierValue;
  private $objectIdentifierType;
  private $rating = 0;
  private $ratingCount = 0;

  public function __construct() {
    parent::getInstance();

    /**
     * Review is a review too.
     */
    $this->tags = new VoxbTags();
    $this->reviews = new VoxbReviewsController();
  }

  /**
   * Fetching item from voxb server by ISBN.
   *
   * @param string $isbn
   */
  public function fetchByISBN($isbn) {
    $data = array(
      'fetchData' => array(
        'objectIdentifierValue' => $isbn,
        'objectIdentifierType'  => 'ISBN',
      ),
      'output' => array('contentType' => 'all'),
    );

    try {
      $o = $this->call('fetchData', $data);
      if ($o->totalItemData) {
        $this->fetchData($o->totalItemData);
      }
    }
    catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Fetching item from voxb server by faust number.
   *
   * @param string $faustNum
   */
  public function fetchByFaust($faustNum) {
    $data = array(
      'fetchData' => array(
        'objectIdentifierValue' => $faustNum,
        'objectIdentifierType' => 'FAUST'
      ),
      'output' => array('contentType' => 'all')
    );
    $this->reviews = new VoxbReviewsController($this->reviewHandlers);

    try {
      $o = $this->call('fetchData', $data);

      if ($o->Body->fetchDataResponse->totalItemData) {
        $this->fetchData($o->Body->fetchDataResponse->totalItemData);
      }
    }
    catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
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

  /**
   * Method is fetching data from a VoxB object.
   */
  public function fetchData($o) {

    $this->objectIdentifierValue = $o->fetchData->objectIdentifierValue;
    $this->objectIdentifierType = $o->fetchData->objectIdentifierType;

    // Fetch Tags
    $this->tags = new VoxbTags();
    $this->tags->fetch($o->summaryTags);

    // Fetch Reviews
    $this->reviews = new VoxbReviewsController($this->reviewHandlers);
    $this->reviews->fetch($o->userItems);

    // Fetch Rating
    $this->rating = (int) $o->totalRatings->averageRating;
    $this->ratingCount = (int) $o->totalRatings->totalNumberOfRaters;
  }

  /**
   * Getter function.
   */
  public function getTags() {
    return $this->tags;
  }

  /**
   * Getter function.
   */
  public function getReviews($type) {
    return $this->reviews->get($type);
  }

  /**
   * Getter function.
   */
  public function getRating() {
    return $this->rating;
  }

  /**
   * Getter function, returns amount of users rated this item.
   */
  public function getRatingCount() {
    return $this->ratingCount;
  }

  /**
   * Rate the item.
   *
   * @param string $faustNum
   * @param integer $rating (0 to 100)
   * @param integer $userId
   */
  public function rateItem($faustNum, $rating, $userId) {
    try {
      $this->call('createMyData', array(
        'userId' => $userId,
        'item' => array(
          'rating' => $rating,
        ),
        'object' => array(
          'objectIdentifierValue' => $faustNum,
          'objectIdentifierType' => 'FAUST',
        ),
      ));
    }
    catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * This method is updating user item rating
   *
   * @param $record_id
   * @param $rating
   */
  public function updateRateItem($record_id, $rating) {
    try {
      $this->call('updateMyData', array(
        'voxbIdentifier' => $record_id,
        'item' => array(
          'rating' => $rating,
        ),
      ));
    }
    catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
  }
}
