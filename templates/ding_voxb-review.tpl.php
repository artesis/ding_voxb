<?php
/**
 * @file
 * Template file for reviews block.
 */
?>
<div class="voxb">
  <div class="reviews-container">
    <div id="<?php echo drupal_html_id('reviews') ?>" class="user-reviews">
      <?php print($reviews); ?>
    </div>
    <?php print($pagination); ?>
    <div class="add-review-container">
      <?php print($review_form); ?>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
