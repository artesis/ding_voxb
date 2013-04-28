<?php
/**
 * @file
 *
 */
if (!empty($object->isbn)):
?>
<div class="voxb-details isbn-<?php echo $object->isbn[0]; ?>">
  <div class="voxb-rating <?php echo $rating_block_class; ?>">
    <?php for ($i = 1; $i <= 5; $i++): ?>
    <a href="/voxb/ajax/rating/<?php echo $object->isbn[0]; ?>/<?php echo $i; ?>" class="rating left <?php echo $star_class; ?>"></a>
    <?php endfor; ?>
    <p class="rating-count left"><span></span></p>
  </div>
  <div class="clear"></div>
</div>
<?php
endif;
