=== Hide WordPress Version ===
Contributors: Kawauso
Tags: version, security, paranoia
Requires at least: 3.0
Tested up to: 3.2
Stable tag: 1.0.1

Removes your WordPress version from various places.

== Description ==

Removes the WordPress version string from:

* The `$wp_version` global variable (frontend only)
* Generator tag output (removed entirely)
* 'Right Now' admin dashboard widget (non-admins)
* Admin dashboard footer (non-admins)
* Scripts and stylesheets enqueued without a version declared
* HTTP queries
* XML-RPC responses
* Pingbacks
* Bloginfo() calls

Also removes the update notice in the admin for non-admins.

== Installation ==

1. Upload `hide-wordpress-version.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Will this make my install secure? =
No. You still need to keep up-to-date with the latest WordPress version. This plugin will stop those less knowledgeable from finding out your version, but it is still possible to find this out.

== Changelog ==

= 1.0.1 =
* Added removal of upgrade notice in admin for non-admins

= 1.0 =
* First public release

== Upgrade Notice ==

= 1.0.1 =
Added removal of upgrade notice in admin and version in 'Right Now' admin dashboard widget for non-admins