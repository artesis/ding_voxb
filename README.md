
VoxB module
==========
This module provides capability for the user to review, tag and rate on an item in Ding! through the VoxB service.

Installation
-----------------
1. Include the module in `ting/ting_object_overview.tpl.php`.
2. Activate the module.
3. Configure the module with the values given as examples.

Improvements
----------------------------------
In order to make the item landing page look & feel nice, content types for showing VoxB is needed ([Chaos tool suite] [ctools]?).

At this moment the module is included in `ting/ting_object_details.tpl.php`.
This is not the right solution.

VoxB related issues
-----------------------------
### Current bugs in the service:
* `updateMyRequest` needs all former posts in the request.

### Current deficiencies in the service:
* Webservice is slow.
* Reviews and tags not sorted after date of creation.
* `objectIdentifier` not supported when using `updateMyRequest` (= not possible to update post).

### Current features to the service:
* Expand error messages, eventually with error codes (only 2 messages exist at the moment).
* More documentation.
* Abolish several user profiles.
* Lists and user lists (highest rated items, mostly reviewed items, popular items, etc.).
* Rating of reviews in order to hide inappropriate reviews.

[ctools]: http://drupal.org/project/ctools