/**
 * @file
 *
 * JavaScript for the login.
 *
 */

jQuery(document).ready(function() {
  
  function showProfilesPopup(profiles) {
    var select = jQuery('#voxb-profiles-list');
    var options = select.attr('options');
    jQuery('option', select).remove();

    for (var i = 0; i < profiles.length; i++) {
      options[options.length] = new Option(profiles[i].name, profiles[i].id);
    }
    
    jQuery('#voxb-select-profile-okbtn').unbind().click(function(){
      jQuery(this).hide();
      jQuery.ajax({
        type: 'POST',
        url: '/voxb/login',
        data: {
          'name': jQuery('#edit-name').attr('value'),
          'pass': jQuery('#edit-pass').attr('value'),
          'profileId': jQuery("select#voxb-profiles-list").val(),
          'action': 'profile_choosen'
        },
        dataType: 'json',
        success: function(msg) { jQuery('#voxb-select-profile-okbtn').show(); handleLoginResponses(msg); }
      });
    });
    
    jQuery('#voxb-select-profile').bPopup({
      modalClose:false,
      zIndex:9700,
      follow:false
    });
  }
  
  function showChooseAliasNamePopup() {
    
    jQuery('#voxb-choose-aliasname-okbtn').unbind().click(function(){
      jQuery(this).hide();
      jQuery.ajax({
        type: 'POST',
        url: '/voxb/login',
        data: {
          'aliasName': jQuery("#voxb-choose-aliasname-name").val(),
          'profileLink': jQuery("#voxb-choose-aliasname-profile").val(),
          'name': jQuery('#edit-name').attr('value'),
          'pass': jQuery('#edit-pass').attr('value'),
          'action': 'alias_selected'
        },
        dataType: 'json',
        success: function(msg) { jQuery('#voxb-choose-aliasname-okbtn').show(); handleLoginResponses(msg); }
      });
    });
    
    jQuery('#voxb-choose-aliasname').bPopup({
      modalClose:false,
      zIndex:9700,
      follow:false
    });
  }
  
  function handleLoginResponses(msg) {
    if (msg.status) {
      // successfully authenticated
      if (msg.data.auth) {
        location.reload(true);
      }
    } else {
      if (!msg.data) {
        // @todo This should be refactored.
        alert('Invalid username or password');
        return;
      }
      // select profile
      if (msg.data.profiles) {
        showProfilesPopup(msg.data.profiles);
      }
      // choose an aliasname
      if (msg.data.selectAliasName) {
        showChooseAliasNamePopup();
      }
      if (msg.data.userAliasSuggestion) {
        jQuery('#voxb-choose-aliasname-info').html('Please select an other aliasname');
      }
    }
  }
  
  jQuery('#user-login-form').submit(function() {
    jQuery("#edit-actions--2").hide();
    jQuery.ajax({
      type: 'POST',
      url: '/voxb/login',
      data: {
        'name': jQuery('#edit-name').attr('value'),
        'pass': jQuery('#edit-pass').attr('value'),
        'action': 'login'
      },
      dataType: 'json',
      success: function(msg) { 
        jQuery("#edit-actions--2").show();
        handleLoginResponses(msg); 
      }
    });
    return false;
  });
});
