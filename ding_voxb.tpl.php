<?php
/**
 * @file
 *
 */
?>
<div id="voxb">
  <h2>Brugerskabte Data</h2>
  <div class="tags-container">
    <h3><?php print t('Tags'); ?></h3>
    <div class="record-tag-highlight">
      <?php print($tags); ?>
    </div>
    <div class="clearfix">&nbsp;</div>
    <?php print($tags_form); ?>
  </div>
  <div class="ratings-container">
    <h3><?php print t('Ratings'); ?></h3>
     <?php print($ratings); ?>
  </div>
  <div class="clearfix">&nbsp;</div>
  <div class="reviews-container">
    <h3><?php print t('User reviews'); ?></h3>
    <div class="user-reviews">
      <?php print($reviews); ?>
    </div>
    <?php print($pagination); ?>
    <div class="add-review-container">
      <?php print($review_form); ?>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
