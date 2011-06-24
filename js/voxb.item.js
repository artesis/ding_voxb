/**
 * @file
 *
 * JavaScript for the item.
 */

(function ($) {
  Drupal.ajax.prototype.commands['voxb_rating_callback'] = function (ajax, response, status) {
   // update rating count
    $('.rating-count-span').each(function() {
      $(this).html('(' + response.rating_count + ')');
    });
    
    // update rating
    $('div.user-rate').each(function() {
      $(this).find('div.rating:lt(' + response.rating + ')').removeClass('star-off').removeClass('star-black').addClass('star-on');
      $(this).find('div.rating:gt(' + (response.rating - 1) + ')').removeClass('star-on').removeClass('star-black').addClass('star-off');
    });

    $('.add-rating-container').cyclicFade({
      repeat: 3,
      params: [
        {fadeout:200, stayout:100, opout:0, fadein:200, stayin:100, opin:1},
        {fadeout:200, stayout:100, opout:0, fadein:200, stayin:100, opin:1},
        {fadeout:200, stayout:100, opout:0, fadein:200, stayin:100, opin:1}
      ]
    });
    
    Drupal.voxb_item.rating_set = false;
  }

  Drupal.ajax.prototype.commands['voxb_tag_callback'] = function (ajax, response, status) {
    $('.tags-container input[type=text]').each(function() {
      $(this).val('');
    });
    
    $('.record-tag-highlight .tag:last').cyclicFade({
      repeat: 3,
      params: [
        {fadeout:200, stayout:100, opout:0, fadein:200, stayin:100, opin:1},
        {fadeout:200, stayout:100, opout:0, fadein:200, stayin:100, opin:1},
        {fadeout:200, stayout:100, opout:0, fadein:200, stayin:100, opin:1}
      ]
    });
  }

  Drupal.voxb_item = {
    initial_rating : 0,
    rating_set : false,

    // Init function, binds method to user input and sets variables
    init: function() {
      Drupal.voxb_item.initial_rating = $('div.user-rate div.star-on').length;
      // Bind ratings on mouse over and out
      $('div.user-rate div.rating').mouseover(function() {
        if (!Drupal.voxb_item.rating_set) {
          $("div.user-rate div.rating:lt(" + ($(this).index() + 1) + ")").removeClass('star-off').removeClass('star-on').addClass('star-black');
          $("div.user-rate div.rating:gt(" + $(this).index() + ")").removeClass('star-black').removeClass('star-on').addClass('star-off');
        }
      });
      
      // Restore the stars after mouseout
      $('div.user-rate').mouseleave(function() {
        if (!Drupal.voxb_item.rating_set) {
          $("div.user-rate div.rating:lt(" + Drupal.voxb_item.initial_rating + ")").removeClass('star-off').removeClass('star-black').addClass('star-on');
          $("div.user-rate div.rating:gt(" + (Drupal.voxb_item.initial_rating - 1) + ")").removeClass('star-on').removeClass('star-black').addClass('star-off');
        }
      });

      // Show the rating ajax animation
      $('div.user-rate div.rating').click(function() {
        if (!Drupal.voxb_item.rating_set) {
          $('div.ratings-container .ajax-anim').show();
          Drupal.voxb_item.rating_set = true;
        }
      });
    }
  };

  Drupal.behaviors.voxb_init = {
    attach: function(context) {
      Drupal.voxb_item.init();
    }
  }
})(jQuery);
