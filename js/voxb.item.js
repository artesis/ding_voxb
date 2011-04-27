/**
 * @file
 *
 * JavaScript for the item.
 */

(function ($) {
  Drupal.ajax.prototype.commands['voxb_rating_callback'] = function (ajax, response, status) {
   // update rating count
   $('.ratingCountSpan').html('(' + response.rating_count + ')');
   // show thank message
    $('.ratingsContainer .ajax_message').show();
    // unbind mouse over/out on start
    $('div.userRate').hide();
    // update rating
   $("div.ratingStars div.rating:lt(" + response.rating + ")").removeClass('star-off').addClass('star-on');
    $("div.ratingStars div.rating:gt(" + (response.rating - 1) + ")").removeClass('star-on').addClass('star-off');
  }

  Drupal.voxb_item = {
    init: function() {
     // Bind ratings on mouse over and out
     $('div.userRate div.rating').mouseover(function(){
      $("div.userRate div.rating:lt(" + ($(this).index() + 1) + ")").removeClass('star-off').addClass('star-on');
      $("div.userRate div.rating:gt(" + $(this).index() + ")").removeClass('star-on').addClass('star-off');
     });

     $('div.userRate div.rating').mouseleave(function() {
      $("div.userRate div.rating").removeClass('star-on').addClass('star-off');
      });
    }
  };

  $(document).ready(function() {
   Drupal.voxb_item.init();
  });
})(jQuery);
