=== Broodle Shoppable Reels ===
Contributors: Broodle
Tags: reels, video reels, shoppable videos, woocommerce, product videos
Requires at least: 5.0
Tested up to: 6.7.1
Stable tag: 1.7
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add interactive, shoppable video reels to your WooCommerce store. Let customers browse and buy products directly from engaging short-form video content.

== Description ==

Broodle Shoppable Reels brings short-form video commerce to your WooCommerce store. Create video reels, attach products, and let customers shop directly from the video — all within a responsive, mobile-friendly interface.

**Key Features:**

* Shoppable video reels with WooCommerce product integration
* Responsive Slick-powered carousel (3 slides desktop, 2 mobile)
* Three popup layout options: Reel + Product, Reel only, Product only
* Automatic reel display on single product pages with floating video widget
* Lazy video loading with configurable background image and spinner
* Related products shown inside the popup modal
* Dedicated Reels Categories taxonomy for organizing content
* Auto-updates from GitHub repository

== Installation ==

1. Upload the `broodle-shoppable-reels` folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. WooCommerce must be active. The plugin will deactivate with a notice if WooCommerce is missing.
4. Go to **Shoppable Reels → Add New** to create your first reel.
5. Use the shortcode `[broodle_reel_slider]` to display reels on any page or post.

Updates are delivered automatically from the public GitHub repository — no extra configuration needed.

== Frequently Asked Questions ==

= How do I display reels on a page? =

Use the shortcode `[broodle_reel_slider]` to show all published reels. To show specific reels by ID: `[broodle_reel_slider 12,34,56]`. To show reels by category: `[broodle_reel_category slug="fashion"]` or `[broodle_reel_category category="5,8" limit="6"]`.

= How do I add a video to a product page? =

Edit any WooCommerce product, scroll to the **Shoppable Reels Layout** meta box, upload a video in the **Right Video** field, and save. A floating video widget will appear on that product page.

= What popup layouts are available? =

Three options configurable from **Shoppable Reels → Settings**: Reels And Product (side by side), Reels only, or Product only.

= Does this plugin work without WooCommerce? =

No. WooCommerce is required. The plugin will deactivate itself if WooCommerce is not active.

= How do I set a custom loading background? =

Go to **Shoppable Reels → Settings** and use the **Loading Background Image** field to upload or select an image. This image displays while reel videos are loading. Leave it empty for a plain dark background.

== Changelog ==

= 1.2 =
* Added configurable loading background image setting
* Added lazy video loading with spinner animation
* Added null safety checks throughout AJAX handlers
* Added floating video widget and fullscreen modal on product pages
* Improved mobile responsive layout and font sizing
* Three configurable popup layout options
* Related products support in popup modal
* Reels Categories taxonomy for content organization
* Auto-update support from private GitHub repository

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.2 =
Major update with configurable loading backgrounds, lazy video loading, improved mobile experience, and auto-update support.

== Privacy Policy ==

This plugin does not collect or store any personal data.

== Support ==

For support, visit [Broodle Marketplace](https://broodle.one/marketplace).
