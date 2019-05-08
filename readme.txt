=== WP Security Master ===
Contributors: yeungon
Tags: security, lock dashboard, secure admin, password, disable wp-login.php
Requires at least: 4.6
Tested up to: 5.1
Requires PHP: 5.5 (to use password_hash() function @see https://php.net/manual/de/function.password-hash.php)
Stable tag: 1.0.2, release May 2019
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html


WP Security Master is great tool to add another security layer to protect your page. It automatically locks the admin page with passcode.

== Description ==

Securing your WordPress by deliberately disabling all functions in dashboard. Re-activate the site by providing the hashed password. It secures your site by providing another layer of authorization.
 
= Overview =
WP Security Master enables you to:

* Automatically lock the dashboard by providing another passcode.
* Enhancing the security

= Please Note =
* You need to remember your passcode to access. Otherwise, your admin dashboard will be locked. 
* WP Security Master only supports user with "administrator" role.

= Disclaimer =
This plugin does not require any technical knowledge. 

= Active Contributors =
Vuong Nguyen at https://tudien.net
== Screenshots ==
 
1. Main Interface when running
2. Main Interface when being locked
3. When the plugin locks the dashboard

= Installation Instructions =
1. Upload `wp-security-master` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the  WP Security Master link from the main menu

== Changelog ==

= 1.0.2 =
*Feature added: Hiding the countdown timer till the configuration is set
*Fixed: Preventing the error triggered when activating the plugin

= 1.0.1 =
*Add the clock on the top of the admin menubar.
*Now, admin can lock it immediately or update/extend/renew the timespan on the top of the admin menubar.

= 1.0 =
*Release the first stable version on April 2019, tested on WordPress 5.1

== Upgrade Notice ==


