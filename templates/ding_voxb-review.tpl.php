<?php
/**
 * @file
 *
 */
?>
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