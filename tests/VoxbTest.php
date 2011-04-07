<?php

require_once(dirname(__FILE__) . '/../lib/VoxbItem.class.php');
require_once(dirname(__FILE__) . '/../lib/VoxbComments.class.php');

/**
 * @file
 */

class VoxbTest extends PHPUnit_Framework_TestCase {

  public $users;
  public $voxbIdentifier;

  public function createUser($index) {
    $obj = VoxbBase::getInstance();
    $response = $obj->call('createUser', array(
      'userAlias' => array(
        'aliasName' => md5(time()) . $index,
        'profileLink' => "google.com"
      ),
      'authenticationFingerprint' => array(
        'userIdentifierValue' => '0001110001',
        'userIdentifierType' => 'CPR',
        'identityProvider' => 'Testbibliotek',
        'institutionName' => 'Testbibliotek'
      )
    ));
    $this->users[] = intval($response->userId);
  }

  public function setUp() {
    $this->createUser(1);
    $this->createUser(2);
    $this->createUser(3);
    $this->createUser(4);

    $obj = VoxbBase::getInstance();
    $response = $obj->call('createMyData', array(
      'userId' => $this->users[0],
      'item' => array(
        'rating' => 20,
        'tags' => array(
          'tag' => array(
          'tag1',
           'tag2',
          'tag3')
        ),
        'review' => array(
          'reviewTitle' => 'comment',
          'reviewData' => 'Comment 1 body',
          'reviewType' => 'TXT'
        )
      ),
      'object' => array(
        'objectIdentifierValue' => "111111111",
        'objectIdentifierType' => "FAUST"
      )
    ));

    $response = $obj->call('createMyData', array(
      'userId' => $this->users[1],
      'item' => array(
        'rating' => 40,
        'tags' => array(
          'tag' => 'tag4'
        ),
        'review' => array(
          'reviewTitle' => 'comment-no',
          'reviewData' => 'This is not a comment',
          'reviewType' => 'TXT'
        )
      ),
      'object' => array(
        'objectIdentifierValue' => "111111111",
        'objectIdentifierType' => "FAUST"
      )
    ));

    $response = $obj->call('createMyData', array(
      'userId' => $this->users[2],
      'item' => array(
        'rating' => 80,
        'tags' => array(
          'tag' => array('tag4', 'tag1')
        ),
        'review' => array(
          'reviewTitle' => 'comment',
          'reviewData' => 'Comment 2 body',
          'reviewType' => 'TXT'
        )
      ),
      'object' => array(
        'objectIdentifierValue' => "111111111",
        'objectIdentifierType' => "FAUST"
      )
    ));

    $response = $obj->call('createMyData', array(
      'userId' => $this->users[3],
      'item' => array(
        'rating' => 80,
        'tags' => array(
          'tag' => 'tag4'
        ),
        'review' => array(
          'reviewTitle' => 'comment',
          'reviewData' => 'Comment 3 body',
          'reviewType' => 'TXT'
        )
      ),
      'object' => array(
        'objectIdentifierValue' => "111111111",
        'objectIdentifierType' => "FAUST"
      )
    ));
  }

  public function tearDown() {
    $obj = VoxbBase::getInstance();

    // remove created users
    foreach ($this->users as $v) {
      $obj->call('deleteUser', array('userId' => $v));
    }

    $response = $obj->call('fetchData',  array(
      'fetchData' => array(
        'objectIdentifierValue' => '111111111',
        'objectIdentifierType' => 'FAUST'
      ),
      'output' => array('contentType' => 'all')
    ));

    // fetch all voxbIdentifier
    $voxbIdentifier = array();
    if ($response->totalItemData) {
      if ($response->totalItemData->userItems) {
        foreach ($response->totalItemData->userItems as $v) {
          $voxbIdentifier[] = intval($v->voxbIdentifier);
        }
      }
    }

    //remove user posts
    foreach ($voxbIdentifier as $v) {
      $obj->call('deleteMyData', array('voxbIdentifier' => $v));
    }
  }
}
