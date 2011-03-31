<?php
/**
 * @file
 *
 * The VoxB main template. Controls the output of all VoxB content.
 * 
 */

drupal_add_js(VOXB_PATH.'/js/voxb.pager.js', 'file');
drupal_add_js(VOXB_PATH.'/js/jquery.bpopup.js', 'file');
drupal_add_js(VOXB_PATH.'/js/voxb.item.js', 'file');

$inline_js = "
  var voxb_images = '".VOXB_PATH.'/img/'."';
  var comments_shown = ".variable_get('voxb_comments_per_page', VOXB_COMMENTS_PER_PAGE).";
  var user_comments = null;
";

drupal_add_js($inline_js, 'inline');
drupal_add_css(VOXB_PATH.'/css/voxb-pager.css', 'file');
drupal_add_css(VOXB_PATH.'/css/voxb.css', 'file');

?>

<div id="voxb">
  <h2>Brugerskabte Data</h2>
  <?php 
    $acIdentifier = $object->record['ac:identifier'][''][0];
    $acIdentifier = explode('|', $acIdentifier);
    $faustNum = $acIdentifier[0];
    require_once(VOXB_PATH . '/lib/VoxbItem.class.php');
    require_once(VOXB_PATH . '/lib/VoxbProfile.class.php');
    require_once(VOXB_PATH . '/lib/VoxbComments.class.php');
    
    $voxbItem = new VoxbItem();
    $voxbItem->addReviewHandler('comment', new VoxbComments());
    $voxbItem->fetchByFaust($faustNum);
    
    $profile = new VoxbProfile();
    $profile->setUserId($_SESSION['voxb']['userId']);
  ?>

  <div id="voxbItem">
    <p class="faustNum"><?php echo $faustNum; ?></p>
  </div>

  <div class="tagsContainer">
    <h3><?php print t('Tags'); ?></h3>
    <div class="recordTagHighlight"><?php showTags($voxbItem->getTags()); ?></div>
    <div class="clearfix">&nbsp;</div>
    <?php if ($user->uid != 0 && $profile->isAbleToTag($faustNum)) : ?>
    <div class="addTagContainer">
      <input type="text" name="tag_name" class="form-text" />
      <input type="button" value="Tilf&oslash;j" name="add_tag_btn" class="form-submit">
      <img class="ajax_anim" src="/<?php print(VOXB_PATH); ?>/img/ajax-loader.gif" width="16" height="16" alt="" />
      <p class="ajax_message"><?php echo t('Thank you for contributing.'); ?></p>
    </div>
    <?php ;endif ?>
  </div>

  <div class="ratingsContainer">
    <h3><?php print t('Ratings'); ?></h3>
    <span class="ratingStars">
      <?php showRating($voxbItem->getRating(), $voxbItem->getRatingCount()); ?>
    </span>
    <?php if ($user->uid != 0 && $profile->isAbleToRate($faustNum)) : ?>
      <div class="addRatingContainer">
        <?php print t('Please rate this object'); ?><br />
        <span class="ratingStars userRate">
          <img src="/<?php print(VOXB_PATH); ?>/img/star-off.png" />
          <img src="/<?php print(VOXB_PATH); ?>/img/star-off.png" />
          <img src="/<?php print(VOXB_PATH); ?>/img/star-off.png" />
          <img src="/<?php print(VOXB_PATH); ?>/img/star-off.png" />
          <img src="/<?php print(VOXB_PATH); ?>/img/star-off.png" />
        </span>
        <img class="ajax_anim" src="/<?php print(VOXB_PATH); ?>/img/ajax-loader.gif" width="16" height="16" alt="" />
        <p class="ajax_message"><?php print t('Thank you for contributing.'); ?></p>
      </div>
    <?php ;endif ?>
  </div>

  <div class="clearfix">&nbsp;</div>

  <div class="reviewsContainer">
    <h3><?php print t('User reviews'); ?></h3>
    <?php showReviews($voxbItem->getReviews('comment')); ?>
    <div id="review_tpl" class="voxbReview" style="display: none;">
      <?php print t('Written by'); ?> <em></em>
      <div class="reviewContent"></div>
    </div>
    <?php if ($user->uid != 0 && $profile->isAbleToReview($faustNum)) : ?>
      <div class="addReviewContainer">
        <textarea class="addReviewTextarea" class="form-textarea"></textarea>
        <div class="clearfix">&nbsp;</div>
        <input type="button" value="<?php print t('Review'); ?>" class="form-submit" />
        <img class="ajax_anim" src="/<?php print(VOXB_PATH); ?>/img/ajax-loader.gif" width="16" height="16" alt="" />
        <p class="ajax_message"><?php print t('Thank you for contributing.'); ?></p>
      </div>
    <?php ;endif ?>
  </div>

  <?php

  /**
   * Display pagination links.
   */
  // Review count
    $reviews = $voxbItem->getReviews('comment')->getCount();
    $pages = -1;
  
    if ($reviews > variable_get('voxb_reviews_per_page', VOXB_COMMENTS_PER_PAGE)) {
      echo '<div id="pager_block">';
        echo '<ul>';
          echo '<li class="prev_page"><a href="#">&lt;&lt;</a></li>';
          $pages = ceil($reviews / variable_get('voxb_reviews_per_page', VOXB_COMMENTS_PER_PAGE));
  
          // JS variable to drive the pagination
          $inline = "var pages = ".$pages.";";
          drupal_add_js($inline, 'inline');
  
          // Draw 5 tabs/buttons/links
          for ($i = 0; $i < 5; $i++) {
            echo '<li class="page_num';
            // Highlight the middle one
            if ($i == 2) {
              echo '  active_page"';
            }
            echo '">';
  
            if ($i > 1) {
              echo '<a href="#">'.($i-1).'</a>';
            }
            else {
              echo '<a href="#"></a>';
            }
  
            echo '</li>';
          }
          echo '<li class="next_page"><a href="#">&gt;&gt;</a></li>';
        echo '</ul>';
        echo '<div class="clear"></div>';
      echo '</div>';
    }
    $inline = "var pages = ".$pages.";";
    drupal_add_js($inline, 'inline');
  
  ?>

  <div class="errorPopup">
    <p class="close"><a href="javascript: void();"><img src="/<?php echo VOXB_PATH; ?>/img/cancel-on.png" alt="" /></a></p>
    <p></p>
  </div>

  <?php

    /**
     * Markup prepared tags.
     *
     * @param array $tags
     *   An array of tags.
     */ 
    function showTags($tags) {
      foreach ($tags as $v) {
        echo '<span class="tag"><a href="/search/ting/'.htmlspecialchars($v->getName()).'">'.htmlspecialchars($v->getName()).'</a></span>&nbsp;';
      }
    }

    /**
     * Markup prepared rating.
     * 
     * @param $rating
     *   Int of the rating.
     *
     * @param $ratingCount
     *   Int of amount of ratings.
     */ 
    function showRating($rating, $ratingCount) {
      for ($i = 1; $i <= 5; $i++) {
        echo '<img src="/'.VOXB_PATH.'/img/'.($i <= $rating ? 'star-on' : 'star-off').'.png" />';
      }
      if ($ratingCount > 0) {
        echo '<span class="ratingCountSpan">(<span class="ratingVotesNumber">'.$ratingCount.'</span>)</span>';
      }
    }

    /**
     * Markup prepared reviews.
     * 
     * @param array $reviews
     *  An array of reviews.
     */ 
    function showReviews($reviews) {
      $i = 0;
      $limit = variable_get('voxb_reviews_per_page', VOXB_COMMENTS_PER_PAGE);
      foreach ($reviews as $v) {
        if ($i >= $limit) {
          break;
        }
        echo '<div class="voxbReview">';
          echo t('Written by').' <em>'.htmlspecialchars($v->getAuthorName()).'</em>';
          echo '<div class="reviewContent">'.htmlspecialchars($v->getText()).'</div>';
        echo '</div>';

        $i++;
      }
    }
  ?>
</div>
