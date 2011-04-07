<?php
/**
 * @file
 *
 * The VoxB main template. Controls the output of all VoxB content.
 * 
 */

?>

<div id="voxb">
  // @todo Localization
  <h2>Brugerskabte Data</h2>
  <?php 
    $ac_identifier = $object->record['ac:identifier'][''][0];
    $ac_identifier = explode('|', $ac_identifier);
    $faust_number = $ac_identifier[0];

    require_once(VOXB_PATH . '/lib/VoxbItem.class.php');
    require_once(VOXB_PATH . '/lib/VoxbProfile.class.php');
    require_once(VOXB_PATH . '/lib/VoxbComments.class.php');
    
    $voxb_item = new VoxbItem();
    $voxb_item->addReviewHandler('comment', new VoxbComments());
    $voxb_item->fetchByFaust($faust_number);
    $profile = new VoxbProfile();
    $profile->setUserId($_SESSION['voxb']['userId']);
  ?>
  <div class="tagsContainer">
    <h3><?php print t('Tags'); ?></h3>
    <div class="recordTagHighlight">
    <?php 
      foreach ($voxb_item->getTags() as $v) {
        echo theme('voxb_tag_record', array('tag_name' => $v->getName()));
      }
    ?>
    </div>
    <div class="clearfix">&nbsp;</div>
    <?php 
	    if (($user->uid != 0 && $profile->isAbleToTag($faust_number))) {
	    	echo drupal_render(drupal_get_form('ding_voxb_tag_form', $faust_number));
	    } 
    ?>
  </div>
  <div class="ratingsContainer">
    <h3><?php print t('Ratings'); ?></h3>
    <div class="ratingStars">
      <?php 
        $rating = $voxb_item->getRating();
        $rating = intval($rating / 20);
	      for ($i = 1; $i <= 5; $i++) {
	        echo '<div class="rating ' . ($i <= $rating ? 'star-on' : 'star-off') . '"></div>';
	      }
	      if ($voxb_item->getRatingCount() > 0) {
	        echo '<span class="ratingCountSpan">(<span class="ratingVotesNumber">' . $voxb_item->getRatingCount().'</span>)</span>';
	      }
      ?>
    </div>
    <?php if ($user->uid != 0 && $profile->isAbleToRate($faust_number)) : ?>
      <div class="addRatingContainer">
        <?php print t('Please rate this object'); ?><br />
        <div class="userRate">
          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/1" class="use-ajax rating star-off"></div>
          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/2" class="use-ajax rating star-off"></div>
          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/3" class="use-ajax rating star-off"></div>
          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/4" class="use-ajax rating star-off"></div>
          <div href="/voxb/ajax/rating/<?php echo $faust_number; ?>/5" class="use-ajax rating star-off"></div>
        </div>
      </div>
      <p class="ajax_message"><?php echo t('Thank you for contributing.'); ?></p>
    <?php ;endif ?>
  </div>

  <div class="clearfix">&nbsp;</div>

  <div class="reviewsContainer">
    <h3><?php print t('User reviews'); ?></h3>
    <div class="userReviews">
    <?php 
      $limit = variable_get('voxb_reviews_per_page', VOXB_COMMENTS_PER_PAGE);
      
      foreach ($voxb_item->getReviews('comment') as $k=>$v) {
        if ($k >= $limit) {
          break;
        }
        echo theme('voxb_review_record', 
          array('author' => $v->getAuthorName(), 'review' => $v->getText())
        );
      }
    ?>
    </div>
    <?php

    /**
     * Display pagination links.
     */
    // Review count
      $reviews = $voxb_item->getReviews('comment')->getCount();
      $pages = -1;

      if ($reviews > $limit) {
        echo '<div id="pager_block">';
          echo '<ul>';
            // Hidden tab to keep track of first page
            echo '<li class="page_first" style="display: none;">'.l('','voxb/ajax/reviews/'.$faust_number.'/page/1', array('attributes' => array('class' => array('use-ajax')))).'</li>';
            echo '<li class="prev_page">'.l('<<','voxb/ajax/reviews/'.$faust_number.'/page/1', array('attributes' => array('class' => array('use-ajax')))).'</li>';

            $pages = ceil($reviews / variable_get('voxb_reviews_per_page', VOXB_COMMENTS_PER_PAGE));

            // Draw 5 tabs/buttons/links
            for ($i = 0; $i < 5; $i++) {
              echo '<li class="page_num';
              // Highlight the middle one
              if ($i == 2) {
                echo '  active_page"';
              }
              echo '">';

              if ($i > 1 && $i < $pages + 2) {
                echo l(($i - 1),'voxb/ajax/reviews/'.$faust_number.'/page/'.($i - 1).'', array('attributes' => array('class' => array('use-ajax'))));
              }
              else {
                echo '<a href="#"></a>';
              }

              echo '</li>';
            }
            echo '<li class="next_page">'.l('>>','voxb/ajax/reviews/'.$faust_number.'/page/2', array('attributes' => array('class' => array('use-ajax')))).'</li>';
          echo '</ul>';
        echo '</div>';
        echo '<div style="clear: both;"></div><br />';
      }
      $inline = "var pages = ".$pages.";";
      drupal_add_js($inline, 'inline');

    ?>
    <?php if ($user->uid != 0 && $profile->isAbleToReview($faust_number)) : ?>
    <div class="addReviewContainer">
      <?php print drupal_render(drupal_get_form('ding_voxb_review_form', $faust_number)); ?>
    </div>
    <p class="ajax_message"><?php echo t('Thank you for contributing.'); ?></p>
    <?php ;endif ?>
  </div>
  <div class="errorPopup">
    <p class="close"><a href="javascript: void();"><img src="/<?php echo VOXB_PATH; ?>/img/cancel-on.png" alt="" /></a></p>
    <p></p>
  </div>
</div>
