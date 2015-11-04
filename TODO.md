To Do
======

## 4 Nov 2015

Fix table height issue - results from filter JS on ALL staff-area pages, setting height on the parent elements of tables.

Just give all tables a parent div. Means they can be filtered in the future if necessary. Or add ".cw-filter" class to relevant tables and use this for targeting.

## 23 October 2015

* Custom header template for the plugin
* Rationalise JS filters DONE
* Collect phone number, display phone number
* Remove staff management page
* Resources can be linked to business unit
* Important resources - flagged in sidebar
* Ordinary staff: no toolbar
* Ordinary staff: unit specific contact numbers in header

Get toolbar custom nodes @ mobile. See: https://premium.wpmudev.org/forums/topic/how-display-toolbar-menu-on-mobile

## Resources

* Make file downloads a repeater field, display downloads as a ul

## Medium Term: Remove Dependency on ACF
At the moment, this plugin is deployed on a site that uses ACF plugin to save and display custom fields.

The following functions are dependent on ACF. The plan is to build in custom metaboxes into the plugin itself, to remove the ACF dependency.

The following functions currently (28/10/2015) depend upon ACF:

* `Staff_Area\Display\Single_Resource::display_file_download()` see: `display/single-resource.php`

## Content Display
Main Staff page receives a management specific & staff specific content block.
