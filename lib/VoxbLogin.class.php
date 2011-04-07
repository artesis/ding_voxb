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
    $this->loginStatus['status'] = FALSE;
  }

  /**
   * User authentification in VoxB.
   *
   * Its not an anthentication really, we just check if such user exists in VoxB database
   * and save his voxb userId to _SESSION to use it in user actions rating/reviewing etc.
   *
   * @param object $account
   */
  public function login($account) {
    $obj = new VoxbUser();
    if ($obj->getUserBySSN($account->name, variable_get('voxb_identity_provider', ''), variable_get('voxb_institution_name', ''))) {

      /**
       * Each user in Voxb can have several profiles
       * but we take just the first one
       */
      $profiles = $obj->getProfiles();
      $_SESSION['voxb']['userId'] = $profiles[0]->getUserId();
      $_SESSION['voxb']['aliasName'] = $profiles[0]->getAliasName();
      return TRUE;
    }
    else {

      /**
       * Create a new user
       *
       * Use his username as user CPR and aliasName
       * (we will give the possibility to update it later).
       * Use user email as profile link.
       *
       * @todo Replace profile link with a real linkto users profiles in artesis system.
       */
      return $this->createUser($account->name, $account->name, $account->email);
    }
  }

  /**
   * Create a new user (with 1 profile).
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
      $_SESSION['voxb']['userId'] = $obj->getUserId();
      $_SESSION['voxb']['aliasName'] = $aliasName;
      return TRUE;
    }
    return FALSE;
  }
}
