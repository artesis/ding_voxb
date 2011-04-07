<?php

/**
 * @file
 */

require_once(dirname(__FILE__) . '/VoxbTest.php');

class VoxbTagTest extends VoxbTest {

  public function setUp() {
    parent::setUp();

    parent::createUser(5);
  }

  public function tearDown() {
    parent::tearDown();
  }

  public function testAddTag() {
    $item = new VoxbItem();
    $item->fetchByFaust('111111111');
    $tagsNumBefore = $item->getTags()->getCount();

    $tag = new VoxbTagRecord();
    $tag->create('111111111', 'testTag', $this->users[4]);

    $item->fetchByFaust('111111111');
    $tagsNumAfter = $item->getTags()->getCount();

    $this->assertEquals($tagsNumAfter, ($tagsNumBefore + 1));
  }
}

?>
