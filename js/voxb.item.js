/**
 * @file
 *
 * JavaScript for the item.
 */

var VoxbItem = {
	ratingSet: false,
	faustNumber: '',
	
	// Check tag existence in tag list
  tagExists: function(tag) {

    var exists = false;

    jQuery('div.recordTagHighlight a').each(function() {
      if (jQuery(this).html() == tag) {
        exists = true;
        return false;
      }
    });

    return exists;
  },

	 // Show a popup
  showPopup: function(msg) {
    jQuery('div.errorPopup p:last').html(msg);
    jQuery('div.errorPopup').bPopup({zIndex:9900});
  },

  // Update item 'stars' on rate
  updateItemStars: function(rating) {
    var src = '';

    jQuery('span.ratingStars:first img').each(function() {
      if ((jQuery(this).index()+1) <= rating) {
        src = 'star-on';
      }
      else {
        src = 'star-off';
      }

      jQuery(this).attr('src', voxb_images+src+'.png');
    });
  },

  // Display the comments
  populateComments: function(page) {
    jQuery('div.reviewsContainer div.voxbReview').not('div#review_tpl').remove();
    jQuery('div.reviewsContainer textarea').val('');

    var boundary = (page - 1) * comments_shown;
    var comments = user_comments.length;

    for (i = 0; i < comments_shown; i++) {
      if ((i + boundary) > user_comments['data'].length - 1) { break; }
      ddvv = jQuery('div.reviewsContainer div#review_tpl').clone().appendTo('div.reviewsContainer');
      ddvv.removeAttr('style').removeAttr('id');
      ddvv.find('span.title').html(user_comments['data'][i + boundary]['title']);
      ddvv.find('em').html(user_comments['data'][i + boundary]['authorName']);
      ddvv.find('div.reviewContent').html(user_comments['data'][i + boundary]['text']);
    }

    jQuery('div.addReviewContainer').appendTo('div.reviewsContainer');
  },

  // Get comments from VoxB
  getComments: function() {
    jQuery.ajax({
      type: 'POST',
      url: '/voxb/ajaxResponder',
      data: {
        'action': 'getReviews',
        'faustNumber': VoxbItem.faustNumber
      },
      dataType: 'json',
      success: function(msg) {
        if (msg['status'] == true) {
          user_comments = msg;
          pager_data.current_page = 1;
          pager_data.pages = Math.ceil(user_comments['data'].length / comments_shown);
          redrawPager();
        }
      }
    });
  },
  
  init: function() {
  	VoxbItem.getComments();
  	
  	// Animate the rating 'stars' on mouseover
    jQuery('span.userRate').each(function() {
      jQuery(this).find('img').mouseover(function() {
        if (VoxbItem.ratingSet == false) {
          var rate_until = jQuery(this).index();
          var rate_images = jQuery(this).parent().find('img');
          var src = '';

          for (i = 0; i < 5; i++) {
            if (i <= rate_until) {
              src = 'star-on';
            }
            else {
              src = 'star-off';
            }
            rate_images.eq(i).attr('src', '/'+voxb_images+src+'.png');
          }
        }
      });
    });
    
    jQuery('span.userRate').mouseleave(function() {
      if (VoxbItem.ratingSet == false) {
        jQuery(this).find('img').attr('src', '/'+voxb_images+'star-off.png');
      }
    });

    // Lock and submit the rating when clicked on a 'star'
    jQuery('span.userRate img').click(function() {
      if (VoxbItem.ratingSet == false) {
        VoxbItem.ratingSet = true;
        jQuery('div.addRatingContainer img.ajax_anim').show();
        jQuery.ajax({
          type: 'POST',
          url: '/voxb/ajaxResponder',
          data: {
            'action': 'rate',
            'rating': jQuery(this).index() + 1,
            'faustNumber': VoxbItem.faustNumber
          },
          dataType: 'json',
          success: function(msg) {
            if (msg['status'] == false) {
              VoxbItem.ratingSet = false;
              if (msg['error'] != undefined) {
              	VoxbItem.showPopup(msg['error'][0]);
              }
              else {
              	VoxbItem.showPopup('The request could not be completed or you rated this item already.');
              }
            }
            // ToDo handle 'status == true' response
            if (msg['status'] == true) {
              jQuery('div.addRatingContainer p.ajax_message').show();
              jQuery.ajax({
                type: 'POST',
                url: '/voxb/ajaxResponder',
                data: {
                  'action': 'getRating',
                  'faustNumber': VoxbItem.faustNumber
                },
                dataType: 'json',
                success: function(msg) {
                  jQuery('span.ratingCountSpan').html('('+msg.data.ratingCount+')');
                  VoxbItem.updateItemStars(msg.data.rating);
                  jQuery('p.ajax_message').show();
                }
              });
            }
            jQuery('div.addRatingContainer img.ajax_anim').hide();
          }
        });
      }
    });

    // Submit a new tag by hitting Enter
    jQuery('div.addTagContainer input[name=tag_name]').keydown(function(event){
      if (event.which == '13') {
        jQuery('div.addTagContainer input[name=add_tag_btn]').click();
      }
    });

    // Submit a new tag
    jQuery('div.addTagContainer input[name=add_tag_btn]').click(function() {
        div_container = jQuery(this).parent();
        var tagName = div_container.find('input[name=tag_name]').val();
      
        if (tagName != '') {
          if (VoxbItem.tagExists(tagName) == false) {
            jQuery('div.addTagContainer input[name=add_tag_btn]').hide();
            jQuery('div.addTagContainer input[name=tag_name]').hide();
            jQuery('div.addTagContainer img.ajax_anim').show();
            jQuery.ajax({
              type: 'POST',
              url: '/voxb/ajaxResponder',
              data: {
                'action': 'addTag',
                'tag': tagName,
                'faustNumber': VoxbItem.faustNumber
              },
              dataType: 'json',
              success: function(msg) {
                if (msg['status'] == false) {
                  if(msg['error'] != undefined) {
                  	VoxbItem.showPopup(msg['error'][0]);
                  }
                  else {
                  	VoxbItem.showPopup('The request could not be completed or you tagged this item already.');
                  }
                  jQuery('div.addTagContainer input[name=add_tag_btn]').show();
                  jQuery('div.addTagContainer input[name=tag_name]').show();
                }
                // ToDo handle 'status == true' response
                else if(msg['status'] == true) {
                  jQuery('div.recordTagHighlight').append('<span class="tag"><a href="/search/ting/'+tag_name+'">'+tag_name+'</a></span>&nbsp;');
                  jQuery('div.addTagContainer input[name=tag_name]').val('');
                  jQuery('div.addTagContainer p.ajax_message').show();
                }
                
                jQuery('div.addTagContainer img.ajax_anim').hide();
              }
            });
          }
          else {
          	VoxbItem.showPopup('Tag already exists.');
          }
        }
      });

    // Submit a new comment
    jQuery('div.addReviewContainer input.form-submit').click(function() {
      var review = jQuery('div.addReviewContainer textarea.addReviewTextarea').val();

      if (review != '') {
        jQuery('div.addReviewContainer input.form-submit').hide();
        jQuery('div.addReviewContainer img.ajax_anim').show();
        jQuery.ajax({
          type: 'POST',
          url: '/voxb/ajaxResponder',
          data: {
            'action': 'addReview',
            'review': review,
            'faustNumber': VoxbItem.faustNumber
          },
          dataType: 'json',
          success: function(msg) {
            if (msg['status'] == false) {
              if(msg['error'] != undefined) {
              	VoxbItem.showPopup(msg['error'][0]);
              }
              else {
              	VoxbItem.showPopup('The request could not be completed or you posted a comment already.');
              }
              jQuery('div.addReviewContainer textarea').show();
              jQuery('div.addReviewContainer input.form-submit').show();
            }
            // ToDo handle 'status == true' response
            else if (msg['status'] == true) {
              VoxbItem.getComments();
              VoxbItem.populateComments(1);
              jQuery('div.addReviewContainer p.ajax_message').show();
              jQuery('div.addReviewContainer textarea').hide();
            }
            jQuery('div.addReviewContainer img.ajax_anim').hide();
          }
        });
      }
    });
  }
};
  
jQuery(document).ready(function() {
	VoxbItem.faustNumber = jQuery('#voxbItem .faustNum').html();
	VoxbItem.init();

 jQuery('div.errorPopup p.close').click(function(){
   jQuery('div.errorPopup').bPopup().close();
 });
});
