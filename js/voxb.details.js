(function ($) {
  // Extract ting id from classname
  Drupal.extractTingId = function(e) {
    classname = $(e).attr('class');
    id = classname.match(/ting-cover-object-id-(\S+)/);
    if (!id) {
      id = classname.match(/ting-object-id-(\S+)/);
    }

    return id[1];
  };

  // Insert voxb into the page
  Drupal.insertVoxbDetails = function(e) {
    if (e.status == true && e.items) {
      Drupal.voxb_item.details = e.items;
      $.each(e.items, function(k, v) {
        var ele = $('.voxb-details.ting-object-id-' + k);
        ele.find('.rating:lt(' + Math.round(v.rating / 20) + ')').removeClass('star-off').addClass('star-on');
        if (v.rating_count > 0) {
          ele.find('.rating-count span').html('(' + v.rating_count + ')');
        }

        var e = ele.find('.voxb-reviews');
        e.find('.count').html('(' + v.reviews + ')');
        e.hide();
        if (parseInt(v.reviews) > 0) {
          e.show();
        }
      });
    }
  };

  Drupal.behaviors.voxb_details = {
    attach : function(context) {
      var item_ids = [];
      
      $('.ting-cover', context).each(function(i, e) {
        id = Drupal.extractTingId(e);
        
        if (id != undefined) {
          item_ids.push(id);
        }
      });

      if (item_ids.length > 0) {
        $.ajax({
          url : '/voxb/ajax/details',
          type : 'POST',
          data : {
            items : item_ids
          },
          success : Drupal.insertVoxbDetails
        });
      }
    }
  }
})(jQuery);
