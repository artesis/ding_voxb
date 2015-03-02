<?php
/**
 * @file
 *
 * Template for review count only.
 */
$isbn = $object->getIsbn();
if (!empty($isbn)):
?>
<div class="voxb-details isbn-<?php echo $isbn[0]; ?>">
  <div class="voxb-reviews">
    <p class="review-count">
      <a href="/ting/collection/<?php echo $object->id; ?>#reviews" target="_blank"><?php print t('Reviews'); ?> <span class="count"></span></a>
    </p>
  </div>
</div>
<?php
endif;
