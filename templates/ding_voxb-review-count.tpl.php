<?php
/**
 * @file
 *
 * Template for review count only.
 */
?>
<div class="voxb-details ting-object-id-<?php echo $object->localId; ?>">
  <div class="voxb-reviews">
    <p class="review-count">
      <a href="/ting/collection/<?php echo $object->id; ?>#reviews" target="_blank"><?php print t('Reviews'); ?> <span class="count"></span></a>
    </p>
  </div>
</div>
