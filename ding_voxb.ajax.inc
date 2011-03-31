<?php 

/**
 * @file
 *
 */

/**
 * Ajax responder method for reviewing/rating/tagging
 */
function voxb_ajax_responder() {
  $userId = $_SESSION['voxb']['userId'];
  
  require_once(drupal_get_path('module', 'ding_voxb') . '/lib/VoxbItem.class.php');
  require_once(drupal_get_path('module', 'ding_voxb') . '/lib/VoxbComments.class.php');
  
  $obj = new VoxbItem();
  $obj->addReviewHandler('comment', new VoxbComments());

  $error = array();
  if (!isset($_REQUEST['faustNumber']) || $_REQUEST['faustNumber'] == '') {
    $response = false;
    $error[] = "Item could not be identified.";
  }
  
  if (empty($error)) {
    switch($_REQUEST['action']) {
      case 'addTag': 
      	$tag = new VoxbTagRecord();
      	$tag->create($_REQUEST['faustNumber'], $_REQUEST['tag'], $userId);
        if (!$response) {
          $error[] = 'You already submitted.';
        }
        break;
      case 'addReview':
      	$comment = new VoxbCommentRecord();
      	$response = $comment->create($_REQUEST['faustNumber'], $_REQUEST['review'], $userId);
        if (!$response) {
          $error[] = 'You already submitted.';
        }
        break;
      case 'rate': 
        $response = $obj->rateItem($_REQUEST['faustNumber'], $_REQUEST['rating'], $userId);
        if (!$response) {
          $error[] = 'You already submitted.';
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
        $response = false;
    }
  }
  $response = array('status' => (empty($error) ? true : false), 'data' => $response);
  if ($error) {
    $response['error'] = $error;
  }
  echo json_encode($response);
}
