VoxB module
==========
This module provides capability for the user to review, tag and rate on an item in Ding! through the VoxB service.

Improvements
----------------------------------
* Replace the SOAP client with [NanoSOAP] [nanosoap].
* Integrate the possibility of logging requests ([ding_devel]) [ding_devel].
* Model the pagination over [theme_pager] [theme_pager].

VoxB related issues
-----------------------------
### Current deficiencies in the service:
* `updateMyRequest` needs all former posts in the request.
* The VoxB-service is slow.
* Reviews and tags are not sorted after date of creation.

### Current features to the service:
* Expand error messages, eventually with error codes (only 2 messages exist at the moment).
* More documentation.
* Abolish several user profiles.
* Lists and user lists (highest rated items, mostly reviewed items, popular items, etc.).
* Rating of reviews in order to hide inappropriate reviews.

[nanosoap]: http://drupal.org/project/nanosoap
[theme_pager]: http://api.drupal.org/api/drupal/includes--pager.inc/function/theme_pager/5
[ding_devel]: https://github.com/ding2/ding_devel
