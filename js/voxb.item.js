/**
 * @file
 *
 * JavaScript for the item.
 */

Drupal.ajax.prototype.commands['voxb_rating_callback'] = function (ajax, response, status) {
  // update rating count
  jQuery('.ratingCountSpan').html('(' + response.rating_count + ')');
  // show thank message
  jQuery('.ratingsContainer .ajax_message').show();
  // unbind mouse over/out on start
  jQuery('div.userRate').hide();
  // update rating
  jQuery("div.ratingStars div.rating:lt(" + response.rating + ")").removeClass('star-off').addClass('star-on');
  jQuery("div.ratingStars div.rating:gt(" + (response.rating - 1) + ")").removeClass('star-on').addClass('star-off');
}

Drupal.voxb_item = {
  init: function() {
    // Bind ratings on mouse over and out
    jQuery('div.userRate div.rating').mouseover(function(){
      jQuery("div.userRate div.rating:lt(" + (jQuery(this).index() + 1) + ")").removeClass('star-off').addClass('star-on');
      jQuery("div.userRate div.rating:gt(" + jQuery(this).index() + ")").removeClass('star-on').addClass('star-off');
    });
    
    jQuery('div.userRate div.rating').mouseleave(function() { 
      jQuery("div.userRate div.rating").removeClass('star-on').addClass('star-off');
    });
  }
};
  
jQuery(document).ready(function() {
  Drupal.voxb_item.init();
});
