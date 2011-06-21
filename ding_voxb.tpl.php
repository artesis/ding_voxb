<?php
/**
 * @file
 *
 * The VoxB main template. Controls the output of all VoxB content.
 * 
 */

drupal_add_library('system', 'drupal.ajax');
drupal_add_library('system', 'jquery.form');
?>

<div id="voxb">
  // @todo Localization
  <h2>Brugerskabte Data</h2>
  <?php 
    $ac_identifier = $object->record['ac:identifier'][''][0];
    $ac_identifier = explode('|', $ac_identifier);
    $faust_number = $ac_identifier[0];
    
    $voxb_item = new VoxbItem();
    $voxb_item->addReviewHandler('review', new VoxbReviews());
    $voxb_item->fetchByFaust($faust_number);
    $profile = unserialize($_SESSION['voxb']['profile']);
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
      <?php 
        $rating = $voxb_item->getRating();
        $rating = intval($rating / 20);
      ?>
    <?php if ($user->uid != 0) : ?>
      <div class="addRatingContainer">
        <div <?php echo ($profile->isAbleToRate($faust_number) ? 'class="userRate"' : ''); ?>>
          <?php for ($i = 1; $i <= 5; $i++) : ?>
          <div href="/voxb/ajax/rating/<?php echo $faust_number . "/" . $i; ?>" class="<?php echo ($profile->isAbleToRate($faust_number) ? 'use-ajax' : ''); ?> rating <?php echo ($i <= $rating ? 'star-on' : 'star-off'); ?>"></div>
          <?php ;endfor ?>
        </div>
        <?php ;endif ?>
      </div>
    <?php
          echo '<span class="ratingCountSpan">(<span class="ratingVotesNumber">' . (($voxb_item->getRatingCount() > 0) ? $voxb_item->getRatingCount() : 0) . '</span>)</span>';
        ?>
      <div class="ajax_anim">&nbsp;</div>
      <div class="clearfix"></div>
  </div>

  <div class="clearfix">&nbsp;</div>

  <div class="reviewsContainer">
    <h3><?php print t('User reviews'); ?></h3>
    <div class="userReviews">
    <?php 
      $limit = variable_get('voxb_reviews_per_page', VOXB_DEFAULT_REVIEWS_PER_PAGE);
      
      foreach ($voxb_item->getReviews('review') as $k=>$v) {
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
      $reviews = $voxb_item->getReviews('review')->getCount();
      $pages = -1;

      echo '<div id="pager_block" '.(($reviews < $limit) ? 'style="display: none;"' : '').'>';
      echo '<ul>';
        // Hidden tab to keep track of first page
      echo '<li class="page_first" style="display: none;">'.l('','voxb/ajax/reviews/'.$faust_number.'/page/1', array('attributes' => array('class' => array('use-ajax')))).'</li>';
      echo '<li class="prev_page">'.l('<<','voxb/ajax/reviews/'.$faust_number.'/page/1', array('attributes' => array('class' => array('use-ajax')))).'</li>';

      $pages = ceil($reviews / variable_get('voxb_reviews_per_page', VOXB_DEFAULT_REVIEWS_PER_PAGE));

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
        
      $inline = "var pages = ".$pages.";";
      drupal_add_js($inline, 'inline');


      if ($user->uid) :
        $data = $profile->getVoxbUserData($faust_number);
        if ($data['review']['title'] != 'videoreview') :
          if ($data['review']['title'] == 'review') {
            $params = array(
              'faust_number' => $faust_number,
              'review_content' => $data['review']['data'],
              'action' => 'update',
            );
          }
          else {
            $params = array(
              'faust_number' => $faust_number,
              'review_content' => '',
              'action' => 'submit',
            );
          }
    ?>
    <div class="addReviewContainer">
      <?php print drupal_render(drupal_get_form('ding_voxb_review_form', $params)); ?>
    </div>
    <div class="clearfix"></div>
    <?php ;endif ;endif ?>
  </div>
</div>
