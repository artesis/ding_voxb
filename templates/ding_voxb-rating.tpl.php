<?php
/**
 * @file
 *
 */
?>
<div class="voxb-details">
  <div class="voxb-rating <?php echo $rating_block_class; ?>">
    <?php for ($i = 1; $i <= 5; $i++): ?>
    <div href="/voxb/ajax/rating/<?php echo $object->localId; ?>/<?php echo $i; ?>" class="rating left <?php echo $star_class; ?>"></div>
    <?php ;endfor ?>
    <p class="rating-count left"><span></span></p>
  </div>
  <div class="clear"></div>
</div>
