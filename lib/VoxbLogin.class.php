<?php 

require_once(VOXB_PATH . '/lib/VoxbUser.class.php');

/**
 * @file
 *
 * This class handles all the login logic to Drupal
 */
class VoxbLogin {
  
  private $loginStatus;
  
  public function __construct() {
    $this->loginStatus['status'] = false;
  }
  
  /**
   * User authentification in VoxB.
   * Its not an anthentication really, we just check if such user exists in VoxB database
   * and saving his userid to _SESSION to use it user actions rating/reviewing etc.
   * 
   * @param string $name
   * @param string $pass
   */
  public function login($name, $profileUserId) {  
    global $user;
    $obj = new VoxbUser();
    // Check if such user exist
    if ($obj->getUserBySSN($user->name, variable_get('voxb_identity_provider', ''), variable_get('voxb_institution_name', ''))) {
      // If this user has move than 1 account
      if (count($obj->getProfiles()) > 1) {
        // Walk through profiles
        foreach ($obj->getProfiles() as $v) {
          if (intval($v->getUserId())) {
            // If a profile is already choosen - finish login
            if ($profileUserId == $v->getUserId()) {
              $_SESSION['voxb']['userId'] = $v->getUserId();
              $_SESSION['voxb']['aliasName'] = $v->getAliasName();
              $this->setLoginStatus(true, array('auth' => true));
              return true;
            }
            $profiles[] = array('id' => $v->getUserId(), 'name' => $v->getAliasName());
          }
        }

        /**
         * If a profile is not choosen then return list of profiles
         * this will cause creating a popup with profiles list on the JS side.
         */
        $this->setLoginStatus(false, array('profiles' => $profiles));
        return false;
      } else {

        /**
         * If a user has only 1 profile, we just use it
         * as result user is successfully authenticated.
         */
        $profiles = $obj->getProfiles();
        $_SESSION['voxb']['userId'] = $profiles[0]->getUserId();
        $_SESSION['voxb']['aliasName'] = $profiles[0]->getAliasName();
        $this->setLoginStatus(true, array('auth' => true));
        return true;
      }
    } else {

      /**
       * No user with such credentials, so we will need to create him.
       */
      $this->setLoginStatus(false, array('selectAliasName' => true));
      return false;
    }
  }
  
  private function setLoginStatus($status, $data = null, $error = null) {
    $this->loginStatus['status'] = $status;
    if ($data) $this->loginStatus['data'] = $data;
    if ($error) $this->loginStatus['error'] = $error;
  }
  
  /**
   * Return login status.
   */
  public function getLoginStatus() {
    return $this->loginStatus;
  }
  
  /**
   * Cretae a new user (with 1 profile).
   * 
   * @param string $cpr
   * @param string $aliasName
   * @param string $profileLink
   */
  public function createUser($cpr, $aliasName, $profileLink) {
    $obj = new VoxbProfile();
    $obj->setCpr($cpr);
    $obj->setAliasName($aliasName);
    $obj->setProfileLink($profileLink);
    if ($obj->createUser(variable_get('voxb_identity_provider', ''), variable_get('voxb_institution_name', ''))) {
      // User successfully created
      $this->setLoginStatus(true, array('auth' => true, 'userId' => $obj->getUserId()));
      $_SESSION['voxb']['userId'] = $obj->getUserId();
      $_SESSION['voxb']['aliasName'] = $aliasName;
      return true;
    } else {
      // If this userAlias as occupied by another user, we will get user sugestion
      $this->setLoginStatus(false, array('userAliasSuggestion' => $obj->getUserAliasSuggestion()));
      return false;
    }
  }
}
