<?php 

require_once(VOXB_PATH . '/lib/VoxbBase.class.php');

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
  
  private $userAliasSuggestion;
  
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
  public function __construct($xml = null, $cpr = null) {
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
    $this->userId = intval($xml->userId);
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
   * Returns user aliasName suggestion.
   * This variable is only not empty if you tries to create a new profiles
   * and there was already any user in VoxB database with such aliasName.
   * 
   * @return string
   */
  public function getUserAliasSuggestion() {
    return $this->userAliasSuggestion;
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
    if (isset($response->userId)) {
      $this->userId = $response->userId;
      return true;
    }

    /**
     * If a user/profiles is not created but because this
     * aliasName is not available we will get a suggestion.
     */
    if (isset($response->userAliasSuggestion)) {
      $this->userAliasSuggestion = $response->userAliasSuggestion;
    }
    return false;
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
    if (in_array($faustNum, $this->getActedItems())) {
      return false;
    }

    // Additional validation is welcome
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
    if (in_array($faustNum, $this->getActedItems())) {
      return false;
    }

    // Additional validation is welcome
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
    if (in_array($faustNum, $this->getActedItems())) {
      return false;
    }

    // Additional validation is welcome
    return $this->isServiceAvailable();
  }
  
  /**
   * Select faust numbers on which this user has already acted.
   * 
   * @return array
   */
  private function getActedItems() {
    if (empty($this->actedItems)) {
      $response = $this->call('fetchMyData', array('userId' => $this->userId));
    
      foreach ($response->result as $v) {
        if ($v->object && $v->object->objectIdentifierType == 'FAUST') {
          $this->actedItems[] = $v->object->objectIdentifierValue;
        }
      }
    }    
    return $this->actedItems;
  }
  
  /**
   * Add a new item to profile if user acted on it.
   * 
   * @param string $faustNum
   */
  public function addActedItem($faustNum) {
    $this->actedItems[] = $faustNum;
  }
  
  /**
   * Seter function.
   *
   * @param integer $x
   */
  public function setUserId($x) {
    $this->userId = $x;
  }
}
