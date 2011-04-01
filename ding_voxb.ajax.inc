<?php
/**
 * @file
 * AJAX responder for the VoxB widget.
 */

/**
 * Ajax responder method for reviewing/rating/tagging
 */
function voxb_ajax_responder() {
  $user_id = $_SESSION['voxb']['userId'];

  require_once(VOXB_PATH . '/lib/VoxbItem.class.php');
  require_once(VOXB_PATH . '/lib/VoxbComments.class.php');

  $obj = new VoxbItem();
  $obj->addReviewHandler('comment', new VoxbComments());

  $error = array();
  if (!isset($_REQUEST['faustNumber']) || $_REQUEST['faustNumber'] == '') {
    $response = FALSE;
    $error[] = t('Item could not be identified.');
  }

  if (empty($error)) {
    switch ($_REQUEST['action']) {
      case 'addTag':
        $tag = new VoxbTagRecord();
        $response = $tag->create($_REQUEST['faustNumber'], $_REQUEST['tag'], $user_id);
        if (!$response) {
          $error[] = t('You already submitted.');
        }
        break;
      case 'addReview':
        $comment = new VoxbCommentRecord();
        $response = $comment->create($_REQUEST['faustNumber'], $_REQUEST['review'], $user_id);
        if (!$response) {
          $error[] = t('You already submitted.');
        }
        break;
      case 'rate':
        $response = $obj->rateItem($_REQUEST['faustNumber'], $_REQUEST['rating'], $user_id);
        if (!$response) {
          $error[] = t('You already submitted.');
        }
        break;
      case 'getReviews':
        $obj->fetchByFaust($_REQUEST['faustNumber']);
        $response = $obj->getReviews('comment')->toArray();
        break;
      case 'getRating':
        $obj->fetchByFaust($_REQUEST['faustNumber']);
        $response['rating'] = $obj->getRating();
        $response['ratingCount'] = $obj->getRatingCount();
        break;
      default:
        $response = FALSE;
    }
  }
  $response = array('status' => (empty($error) ? TRUE : FALSE), 'data' => $response);
  if ($error) {
    $response['error'] = $error;
  }
  drupal_json_output($response);
}
