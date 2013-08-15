/**
 * @file
 *
 * JavaScript for the item.
 */

(function ($) {
  Drupal.ajax.prototype.commands['voxb_rating_callback'] = function (ajax, response, status) {
    // update rating count
    var parent = $('.voxb-details.isbn-' + response.item_id + ' .voxb-rating');
    parent.find('.rating-count span').html('(' + response.rating_count + ')');

    // item without rating.
    if (Drupal.voxb_item.details[response.item_id] == undefined) {
      Drupal.voxb_item.details[response.item_id] = {rating: null};
    }
    // Update the rating in the previously fetched details.
    Drupal.voxb_item.details[response.item_id].rating = response.rating * 20;

    parent.find('div.rating:lt(' + response.rating + ')').removeClass('star-off').removeClass('star-hover').addClass('star-on');
    parent.find('div.rating:gt(' + (response.rating - 1) + ')').removeClass('star-on').removeClass('star-hover').addClass('star-off');

    parent.cyclicFade({
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
    details : [],

    // Init function, binds method to user input and sets variables
    init: function() {

      $('.voxb-rating.rate-enabled').mouseenter(function() {
        var item_id = Drupal.extractTingId($(this).parent());
        // Divide by 20 since, rating is up to 100 and we have 5 stars.
        Drupal.voxb_item.initial_rating = (Drupal.voxb_item.details[item_id].rating) / 20;
      });
      // Bind ratings on mouse over and out
      $('.voxb-rating.rate-enabled .rating').mouseover(function() {
        if (!Drupal.voxb_item.rating_set) {
          var parent = $(this).parent();
          parent.find('.rating:lt(' + ($(this).index() + 1) + ')').removeClass('star-off').removeClass('star-on').addClass('star-hover');
          parent.find('.rating:gt(' + $(this).index() + ')').removeClass('star-hover').removeClass('star-on').addClass('star-off');
        }
      });

      // Restore the stars after mouseout
      $('.voxb-rating.rate-enabled').mouseleave(function() {
        if (!Drupal.voxb_item.rating_set) {
          var ele = $(this);
          if (Drupal.voxb_item.initial_rating == 0) {
            ele.find('.rating').removeClass('star-on').removeClass('star-hover').addClass('star-off');
          }
          else {
            ele.find('.rating:lt(' + Drupal.voxb_item.initial_rating + ')').removeClass('star-off').removeClass('star-hover').addClass('star-on');
            ele.find('.rating:gt(' + (Drupal.voxb_item.initial_rating - 1) + ')').removeClass('star-on').removeClass('star-hover').addClass('star-off');
          }
        }
      });

      // Show the rating ajax animation
      $('.voxb-rating.rate-enabled .rating').click(function() {
        if (!Drupal.voxb_item.rating_set) {
          $('.voxb div.ratings-container .ajax-anim').show();
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
