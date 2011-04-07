<?php

/**
 * @file
 */

require_once(dirname(__FILE__) . '/VoxbTest.php');

class VoxbCommentTest extends VoxbTest {

  public function setUp() {
    parent::setUp();

    parent::createUser(5);
  }

  public function tearDown() {
    parent::tearDown();
  }

  public function testAddComment() {
    $item = new VoxbItem();
    $item->addReviewHandler('comment', new VoxbComments());
    $item->fetchByFaust('111111111');

    $reviews = $item->getReviews('comment');
    $commentsNumBefore = $reviews->getCount();

    $comment = new VoxbCommentRecord();
    $comment->create('111111111', 'TestReview', $this->users[4]);

    $item = new VoxbItem();
    $item->addReviewHandler('comment', new VoxbComments());
    $item->fetchByFaust('111111111');
    $reviews = $item->getReviews('comment');
    $commentsNumAfter = $item->getReviews('comment')->getCount();

    $this->assertEquals($commentsNumAfter, ($commentsNumBefore + 1));
  }
}

?>
