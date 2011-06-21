/**
 * @file
 *
 * JavaScript for the item.
 */

(function ($) {
  Drupal.ajax.prototype.commands['voxb_rating_callback'] = function (ajax, response, status) {
   // update rating count
    $('.ratingCountSpan').each(function() {
      $(this).html('(' + response.rating_count + ')');
    });
    
    // update rating
    $('div.userRate').each(function() {
      $(this).find('div.rating:lt(' + response.rating + ')').removeClass('star-off').removeClass('star-black').addClass('star-on');
      $(this).find('div.rating:gt(' + (response.rating - 1) + ')').removeClass('star-on').removeClass('star-black').addClass('star-off');
    });

    $('.addRatingContainer').cyclicFade({
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
    $('.tagsContainer input[type=text]').each(function() {
      $(this).val('');
    });
    
    $('.recordTagHighlight .tag:last').cyclicFade({
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
      Drupal.voxb_item.initial_rating = $('div.userRate div.star-on').length;
      // Bind ratings on mouse over and out
      $('div.userRate div.rating').mouseover(function() {
        if (!Drupal.voxb_item.rating_set) {
          $("div.userRate div.rating:lt(" + ($(this).index() + 1) + ")").removeClass('star-off').removeClass('star-on').addClass('star-black');
          $("div.userRate div.rating:gt(" + $(this).index() + ")").removeClass('star-black').removeClass('star-on').addClass('star-off');
        }
      });
      
      // Restore the stars after mouseout
      $('div.userRate').mouseleave(function() {
        if (!Drupal.voxb_item.rating_set) {
          $("div.userRate div.rating:lt(" + Drupal.voxb_item.initial_rating + ")").removeClass('star-off').removeClass('star-black').addClass('star-on');
          $("div.userRate div.rating:gt(" + (Drupal.voxb_item.initial_rating - 1) + ")").removeClass('star-on').removeClass('star-black').addClass('star-off');
        }
      });

      // Show the rating ajax animation
      $('div.userRate div.rating').click(function() {
        if (!Drupal.voxb_item.rating_set) {
          $('div.ratingsContainer .ajax_anim').show();
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
