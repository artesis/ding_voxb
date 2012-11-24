/**
 * @file
 * Behavior for on page load reviews loading.
 */
(function ($) {
  Drupal.behaviors.voxb_reviews = {
    attach : function(context) {
      $('.voxb-reviews-placeholder', context).once('voxb-reviews-placeholder', function() {
        var id = Drupal.extractTingId($('.ting-cover'));
        if (id) {
          var element_settings = {};
          element_settings.url = '/voxb/ajax/reviews/' + id;
          element_settings.event = 'get_reviews';
          element_settings.progress = { type: 'throbber' };
          base = $(this).attr('id');

          Drupal.ajax[base] = new Drupal.ajax(base, this, element_settings);
          $('.voxb-reviews-placeholder').trigger('get_reviews');
        }
      });
    }
  }
})(jQuery);
