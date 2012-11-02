/**
 * @file
 * Behavior for on page load tags loading.
 */
(function ($) {
  Drupal.behaviors.voxb_tags = {
    attach : function(context) {
      $('.voxb-tags-placeholder', context).once('voxb-tags-placeholder', function() {
        var id = Drupal.extractTingId($('.ting-cover'));
        if (id) {
          var element_settings = {};
          element_settings.url = '/voxb/ajax/tags/' + id;
          element_settings.event = 'get_tags';
          element_settings.progress = { type: 'throbber' };
          base = $(this).attr('id');

          Drupal.ajax[base] = new Drupal.ajax(base, this, element_settings);
          $('.voxb-tags-placeholder').trigger('get_tags');
        }
      });
    }
  }
})(jQuery);
