(function ($) {
  // Extract ting id from classname
  Drupal.extractTingId = function(e) {
    classname = $(e).attr('class');
    id = classname.match(/ting-cover-object-id-(\S+)/);
    return id[1];
  };

  // Insert voxb into the page
  Drupal.insertVoxbDetails = function(e) {
    
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
