To Do
======

## 23 October 2015

* Custom header template for the plugin
* Rationalise JS filters DONE
* Collect phone number, display phone number
* Set business units as a repeater field in options

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
