<?php
/**
 * @file
 *
 * Template for review count only.
 */
?>
<div class="voxb-details ting-object-id-<?php echo $object_local_id; ?>">
  <div class="voxb-reviews" style="display: none;">
    <p class="review-count">
      <a href="/ting/collection/<?php echo $object_id; ?>#reviews" target="_blank"><?php print t('Anmeldelser'); ?><span class="count"></span></a>
    </p>
  </div>
</div>
