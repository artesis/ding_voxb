/******************************************************************************************************************
 * @name: bPopup
 * @type: jQuery
 * @author: Bjoern Klinggaard (http://dinbror.dk/bpopup)
 * @version: 0.4.1
 * @requires jQuery 1.3
 *
 * DEFAULT VALUES:
 * amsl(Above Mean Sea Level): 150px // Vertical distance from the middle of the window, + = above, - = under
 * appendTo: 'body' // Which element the popup should append to (append to 'form' when ASP.net)
 * closeClass: 'bClose' // Class to bind the close event to
 * content: 'ajax' // [iframe, ajax, xlink] COMING SOON
 * contentContainer: null //if null, contentContainer == $(this)
 * escClose: true // Close on esc
 * fadeSpeed: 250 // Animation speed on fadeIn/out
 * follow: true // Should the popup follow the screen on scroll/resize? 
 * followSpeed: 500 // Animation speed for the popup on scroll/resize
 * loadUrl: null // External page or selection to load in popup
 * modal: true // Modal overlay
 * modalClose: true // Shold popup close on click on modal overlay?
 * modalColor: #000 // Modal overlay color
 * opacity: 0.7 // Transparency, from 0.1 to 1.0 (filled)
 * scrollBar: true // Scrollbars visible
 * vStart: null // Vertical start position for popup
 * zIndex: 9999 // Popup z-index, modal overlay = popup z-index - 1
 *
 * TODO: REFACTOR CODE!!!
 *******************************************************************************************************************/ 
;(function($) {
  $.fn.bPopup = function(options, callback) {
    if($.isFunction(options)) {
        callback = options;
        options = null;
    }
    o = $.extend({}, $.fn.bPopup.defaults, options); 
    //HIDE SCROLLBAR?  
    if(!o.scrollBar)
        $('html').css('overflow', 'hidden');


    var $selector = $(this),
        $modal = $('<div class="bModal"></div>'),
        d = $(document),
        w = $(window),
        cp = getCenterPosition($selector, o.amsl),
        vPos = cp[0],
        hPos = cp[1],
        isIE6 = $.browser.msie && parseInt($.browser.version) == 6 && typeof window['XMLHttpRequest'] != 'object'; // browser sniffing is bad
     
    //PUBLIC FUNCTION - call it: $(element).bPopup().close();
    this.close = function() {
        o = $selector.data('bPopup');
        close();
    }
        
    return this.each(function() { 
          if($selector.data('bPopup'))return; //POPUP already exists?
          // MODAL OVERLAY
          if(o.modal) {
             $modal
                .css(getModalStyle())
                .appendTo(o.appendTo)   
                .animate({'opacity': o.opacity}, o.fadeSpeed);
          }   
          $selector.data('bPopup', o);
          // CREATE POPUP  
          create();
    }); 
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // HELP FUNCTIONS - PRIVATE
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function create() {
        var hasInputField = $('input[type=text]', $selector).length != 0;
        var t = o.vStart != null ? o.vStart : d.scrollTop() + vPos;
        $selector
            .css( {'left': d.scrollLeft() + hPos, 'position': 'absolute', 'top': t, 'z-index': o.zIndex } )
            .appendTo(o.appendTo)
            .hide(function(){
                if(hasInputField) {
                    // Resets input fields
                    $selector.each(function() {
                        $selector.find('input[type=text]').val('');    
                    });
                } 
                if(o.loadUrl != null) 
                    createContent();                         
            })
            .fadeIn(o.fadeSpeed, function(){
                if(hasInputField) {
                    $selector.find('input[type=text]:first').focus();
                } 
                // Triggering the callback if set    
                $.isFunction(callback) && callback();          
            }); 
        //BIND EVENTS
        bindEvents(); 
    }
    function close() { 
        if(o.modal) {
            $('.bModal:last')   
                .fadeOut(o.fadeSpeed, function(){
                    $('.bModal:last').remove();
                });  
        }
        $selector.fadeOut(o.fadeSpeed, function(){
            if(o.loadUrl != null && o.content != 'xlink') {
                o.contentContainer.empty();
            }
        });  
        unbindEvents();
        return false;
    }
    function getModalStyle() {
        if(isIE6) {
            var dd = getDocumentDimensions();
            return {'background-color': o.modalColor,'height': dd[0], 'left': getDistanceToBodyFromLeft(), 'opacity': 0, 'position': 'absolute', 'top': 0, 'width': dd[1], 'z-index': o.zIndex - 1};
        }
        else
            return {'background-color': o.modalColor,'height': '100%', 'left': 0, 'opacity': 0, 'position': 'fixed', 'top': 0, 'width': '100%', 'z-index': o.zIndex - 1};     
    }
    function createContent() {
        o.contentContainer = o.contentContainer == null ? $selector : $(o.contentContainer);
        switch(o.content){
            case('ajax'):
                o.contentContainer.load(o.loadUrl); 
                break;
            case('iframe'):               
                $('<iframe width="100%" height="100%"></iframe>').attr('src',o.loadUrl).appendTo(o.contentContainer);
                break;
            case('xlink'):
                //Better implementation coming soon!
                $('a#bContinue').attr({'href': o.loadUrl});
                $('a#bContinue .btnLink').text($('a.xlink').attr('title'))
                break;
        }
    }
    function bindEvents() {
       $('.' + o.closeClass).live('click', close);
       if(o.modalClose) {
            $('.bModal').live('click', close).css('cursor','pointer');
       }
       if(o.follow) {
           w.bind('scroll.bPopup', function() { 
                $selector
                   .stop()
                   .animate({'left': d.scrollLeft() + hPos, 'top': d.scrollTop() + vPos }, o.followSpeed);
           })
           .bind('resize.bPopup', function() {
                // MODAL OVERLAY IE6
                if(o.modal && isIE6) {
                    var dd = getDocumentDimensions(); 
                    $modal
                        .css({ 'height': dd[0], 'width': dd[1], 'left': getDistanceToBodyFromLeft() });
                }
                // POPUP
                var pos = getCenterPosition($selector, o.amsl);
                vPos = pos[0];
                hPos = pos[1];
                $selector
                    .stop()
                    .animate({'left': d.scrollLeft() + hPos, 'top': d.scrollTop() + vPos }, o.followSpeed);               
           });
       } 
       if(o.escClose) {
           d.bind('keydown.bPopup', function(e) {
                if(e.which == 27) {  //escape
                    close();
                }
           });  
       }   
    }
    function unbindEvents() {
        if(!o.scrollBar)  {
            $('html').css('overflow', 'auto');
        }
        $('.' + o.closeClass).die('click');
        $('.bModal').die('click');
        d.unbind('keydown.bPopup');
        w.unbind('.bPopup');
        $selector.data('bPopup', null);
    }
    function getDocumentDimensions() {
        return [d.height(), d.width()];
    }	
    function getDistanceToBodyFromLeft() {
        return (w.width() < $('body').width()) ? 0 : ($('body').width() - w.width()) / 2;
    }
    function getCenterPosition(s, a) {
        var vertical = ((w.height() - s.outerHeight(true)) / 2) - a;
        var horizontal = ((w.width() - s.outerWidth(true)) / 2) + getDistanceToBodyFromLeft(); 
        return [vertical < 20 ? 20 : vertical, horizontal];
    } 
  };
  $.fn.bPopup.defaults = {
        amsl: 150, 
        appendTo: 'body',
        closeClass: 'bClose',
        content: 'ajax',
        contentContainer: null,
        escClose: true,
        fadeSpeed: 250,
        follow: true,
        followSpeed: 500,
        loadUrl: null,
        modal: true,
        modalClose: true,
        modalColor: '#000',
        opacity: 0.7,
        scrollBar: true,
        vStart: null,
        zIndex: 9999
  };
})(jQuery);

