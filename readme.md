Staff Area
==========

Creates a staff area on a page called 'staff'.

Registers 'staff-resource' custom post type, with a custom taxonomy 'resource_category'. Accessed by 'staff_members' and 'staff_supervisor'.

Registers 'management-resource' custom post type, with a custom taxonomy 'resource_category'.

Registers custom user roles:

* 'staff_member' an ordinary staff member
* 'staff_manager' staff member with management privileges

Builds a filter for staff resources.

##TODO

###Custom Sidebar
Register custom sidebar for this plugin:

~~~
function staff_area_sidebar_registration(){
  register_sidebar( /* arguments */ );
}

add_action( 'wp_loaded', 'staff_area_sidebar_registration' );

~~~

`wp_loaded` should guarantee that sidebar is registered after theme sidebars. Latest hook you can (reliably use to register sidebar)[http://wordpress.stackexchange.com/questions/2553/how-to-register-sidebar-without-messing-up-the-order].

###Body Class
The class "staff-area" is added to the body class - by hooking into the WP `body_class` filter.

TODO: Add a CSS class determined by user role.

###Postmeta
On the test site, postmeta are added to CPTs by means of the ACF plugin. Custom meta boxes should be defined within the plugin, so that it isn't dependent on ACF.

Maybe add the CMB2 class - this should do most of the heavy lifting.

###Key Pages
These should be user-defined in the plugin options (like in EDD), with defaults as a fallback.

Should the plugin create pages on activation if they do not exist?

If they do exist, they should not be the defaults - give site admin opportunity to specify/confirm key pages.
