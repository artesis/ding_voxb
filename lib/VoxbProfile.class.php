<?php
/**
 * @file
 *
 * User profile class.
 * A VoxB user may have different amount of profiles (min 1).
 */

class VoxbProfile extends VoxbBase {
  private $userId;
  private $aliasName;
  private $profileLink;
  private $cpr;

  /**
   * VoxB itemIds on which user has already actred (tagged/reviewed/rated).
   */
  private $actedItems = array();

  /**
   * You may create VoxbProfile object without parametes
   * if you for example would like to create a new user/profile.
   *
   * @param object $xml
   * @param string $crp
   */
  public function __construct($xml = NULL, $cpr = NULL) {
    if ($xml) {
      $this->cpr = $cpr;
      $this->fetch($xml);
    }
    parent::getInstance();
  }

  /**
   * Fetching data fro simpleXml object to class attributes
   *
   * @param object $xml
   */
  private function fetch($xml) {
    $this->userId = (int)($xml->userId);
    $this->aliasName = (string)$xml->userAlias->aliasName;
    $this->profileLInk = (string)$xml->userAlias->profileLink;
  }

  /**
   * Getter function.
   */
  public function getUserId() {
    return $this->userId;
  }

  /**
   * Getter function.
   */
  public function getAliasName() {
    return $this->aliasName;
  }

  /**
   * Getter function.
   */
  public function getProfileLink() {
    return $this->getProfileLink;
  }

  /**
   * Setter function.
   */
  public function setAliasName($x) {
    $this->aliasName = $x;
  }

  /**
   * Setter function.
   */
  public function setProfileLink($x) {
    $this->profileLink = $x;
  }

  /**
   * Setter function.
   */
  public function setCpr($x) {
    $this->cpr = $x;
  }

  /**
   * Create user method creates not only an user.
   * If a user with such credentials (CPR, identity provider and institution name)
   * already exist in VoxB database only a new profile will be added to his account.
   * This business logic is on the VoxB server side.
   *
   * @param string $identityProvider
   * @param string $institutionName
   */
  public function createUser($identityProvider, $institutionName) {
    $response = $this->call('createUser', array(
      'userAlias' => array(
        'aliasName' => $this->aliasName,
        'profileLink' => $this->profileLink
      ),
      'authenticationFingerprint' => array(
        'userIdentifierValue' => $this->cpr,
        'userIdentifierType' => 'CPR',
        'identityProvider' => $identityProvider,
        'institutionName' => $institutionName
      )
    ));

    if (isset($response->Body->createUserResponse->userId)) {
      $this->userId = (int)$response->Body->createUserResponse->userId;
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Check if this user can add reviews.
   * If the user already posted a review/tag/rating
   * he is not able to perform this action.
   *
   * @param integer $faustNum
   * @return boolean
   */
  public function isAbleToReview($faustNum) {
    return $this->isServiceAvailable();
  }

  /**
   * Check if this user can add tags.
   * If the user already posted a review/tag/rating
   * he is not able to perform this action.
   *
   * @param integer $faustNum
   * @return boolean
   */
  public function isAbleToTag($faustNum) {
    // user is always able to add more tags
    return $this->isServiceAvailable();
  }

  /**
   * Check if this user can rate.
   * If the user already posted a review/tag/rating
   * he is not able to perform this action.
   *
   * @param integer $faustNum
   * @return boolean
   */
  public function isAbleToRate($faustNum) {
    return $this->isServiceAvailable();
  }

  /**
   * Retuns an array which shows user actions on items
   * array(
   *  array(
   *    voxbIdentifier:integer
   *    tags:array
   *    review:array
   *    rating:integer
   *  )
   * )
   *
   * @return array
   */
  private function getActedItems() {
    $response = $this->call('fetchMyData', array('userId' => $this->userId));
    
    if (!isset($response->Body->fetchMyDataResponse->result)) return array();

    foreach ($response->Body->fetchMyDataResponse->result as $v) { 
      if ($v->object && $v->object->objectIdentifierType == 'FAUST') {
        $this->actedItems[(string)$v->object->objectIdentifierValue] = array(
          'voxbIdentifier' => (string)$v->voxbIdentifier,
          'tags' => @$v->item->tags ? $this->prepareArray($v->item->tags->tag) : array(),
          'review' => array(
            'title' => (string)@$v->item->review->reviewTitle,
            'data' => (string)@$v->item->review->reviewData
          ),
          'rating' => (int)@$v->item->rating
        );
      }
    }

    return $this->actedItems;
  }

  /**
   * Convert SimpleXML object to array representation.
   *
   * Mainly used for tags array response.
   *
   * @param $arr
   *   Input array containing mixed values.
   * @return
   *   Transformed array.
   */
  public function prepareArray($arr) {
    $r = array();

    foreach($arr as $k => $v) {
      $r[] = (string)$v;
    }

    return $r;
  }

  /**
   * Update array of acted items.
   */
  public function updateActedItems() {
    $this->actedItems = array();
    $this->getActedItems();
  }

  /**
   * Seter function.
   *
   * @param integer $x
   */
  public function setUserId($x) {
    $this->userId = $x;
  }

  /**
   * This is a public method
   * to be used after successfull authntication to store VoxbIdentifiers in the SESSION
   */
  public function fetchMyData() {
    if (!$this->userId) return FALSE;
    $this->getActedItems();
    return TRUE;
  }

  /**
   * This method return Voxb user data on specified item
   * Or NULL if he didn't act on it yet
   * @param $faustNumber
   * @return array
   */
  public function getVoxbUserData($faustNumber) {
    $actedItems = $this->getActedItems();

    return $actedItems[$faustNumber];
  }

}
