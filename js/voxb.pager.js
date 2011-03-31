/**
 * @file
 *
 * JavaScript for the pager.
 *
 */

jQuery(document).ready(function() {

  showComments = function() {
    if (pager_data.current_page > pager_data.pages) { pager_data.current_page = pager_data.pages; }
    if (pager_data.current_page < 1) { pager_data.current_page = 1; }

    redrawPager();
  }

  redrawPager = function() {
    jQuery('div#pager_block ul li.page_num').each(function(index) {

      var label = '';
      if (index + pager_data.current_page - 2 <= pager_data.pages && index + pager_data.current_page - 2 > 0) {
        label = index + pager_data.current_page - 2;
      }

      jQuery(this).find('a').html(label);
    });

    VoxbItem.populateComments(pager_data.current_page);
  }

  pager_data = {
    pages:1,
    current_page:1
  }

  if (pages != -1) {
    pager_data.pages = pages;
  }

  // Pager responder
  jQuery('div#pager_block ul li a').click(function() {
    var link = jQuery(this).parent().attr('class');
    var setPage = false;

    switch (link) {

      case 'prev_page':
        pager_data.current_page--;
        setPage = true;
        break;

      case 'next_page':
        pager_data.current_page++;
        setPage = true;
        break;

      case 'page_num':
        page = parseInt(jQuery(this).html());
        if (page != pager_data.current_page) { setPage = true; }
        pager_data.current_page = page;
        break;
    }

    if (setPage) {
      showComments();
    }

    return false;
  });
});
