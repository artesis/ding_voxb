<div id="voxb">
  // @todo Localization
  <h2>Brugerskabte Data</h2>
  <div class="tagsContainer">
    <h3><?php print t('Tags'); ?></h3>
    <div class="recordTagHighlight">
      <?php print($tags); ?>
    </div>
    <div class="clearfix">&nbsp;</div>
    <?php print($tags_form); ?>
  </div>
  <div class="ratingsContainer">
    <h3><?php print t('Ratings'); ?></h3>
     <?php print($ratings); ?>
  </div>
  <div class="clearfix">&nbsp;</div>
  <div class="reviewsContainer">
    <h3><?php print t('User reviews'); ?></h3>
    <div class="userReviews">
      <?php print($reviews); ?>
    </div>
    <?php print($pagination); ?>
    <div class="addReviewContainer">
      <?php print($review_form); ?>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
