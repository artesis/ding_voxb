<?php
/**
 * @file
 * VoxB login template.
 */
?>
<div id="voxb-select-profile" class="popup">
  <div class="header">
    <h6>Select profile</h6>
  </div>
  <div class="content">
    <label>Select profile</label>
    <select id="voxb-profiles-list"></select>
    <input type="button" value="OK" id="voxb-select-profile-okbtn"/>
  </div>
</div>

<div id="voxb-choose-aliasname" class="popup">
  <div class="header">
    <h6>Enter your aliasname</h6>
  </div>
  <div class="content">
    <label>Aliasname:</label>
    <input type="text" id="voxb-choose-aliasname-name" />
    <label>Profile link:</label>
    <input type="text" id="voxb-choose-aliasname-profile" />
    <input type="button" value="OK" id="voxb-choose-aliasname-okbtn"/>
    <div id="voxb-choose-aliasname-info"></div>
  </div>
</div>