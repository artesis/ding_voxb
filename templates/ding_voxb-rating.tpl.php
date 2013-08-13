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
        <?php if (!$display_only) : ?>
            <a href="/voxb/nojs/rating/<?php echo $object->isbn[0]; ?>/<?php echo $i; ?>" class="rating left <?php echo $star_class; ?>" title="<?php echo t('Rate with ' . $i . ' star' . ($i > 1 ? 's' : '')); ?>"></a>
        <?php else : ?>
            <a href="javascript:void()" class="display-only rating left <?php echo $star_class; ?>"></a>
        <?php endif ?>
    <?php endfor; ?>
    <p class="rating-count left" title="<?php echo t('Number of ratings') ?>"><span></span></p>
  </div>
  <div class="clear"></div>
</div>
<?php
endif;
