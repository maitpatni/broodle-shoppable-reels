=== Shoppable Reels for WooCommerce ===
Contributors: Broodle
Tags: reels, shoppable videos, woocommerce reels, video commerce, product videos, shoppable reels, video shopping, instagram reels, tiktok shop, short video, video carousel, product carousel, video slider, woocommerce video, video marketing, social commerce, video gallery, product video, shop by video, video storefront
Requires at least: 5.0
Tested up to: 6.7.1
Stable tag: 2.0.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add Instagram-style shoppable video reels to your WooCommerce store. Let customers discover and buy products directly from short-form video content.

== Description ==

**Shoppable Reels for WooCommerce** brings short-form video commerce natively into your store. Create video reels, attach WooCommerce products, and let customers shop directly from the video — no third-party embeds, no iframes, no monthly fees.

Drop a shortcode on any page and your products come alive with video. Customers see product details, pricing, size options, and add-to-cart — all within a modern, responsive popup without ever leaving the page.

**Perfect for:** Fashion stores, beauty brands, electronics shops, food & beverage, home decor, jewelry, fitness gear, and any WooCommerce store that wants to boost engagement and conversions with video.

= Key Features =

* **Shoppable Video Reels** — Link any WooCommerce product to a video reel with one click
* **Modern Popup Modal** — Sleek product popup with video, image gallery, size selector, and buy buttons
* **Responsive Carousel** — 3 slides on desktop, 2 on mobile with smooth Slick-powered transitions
* **Smart Lazy Loading** — Videos load sequentially with preloading for zero-spinner slide transitions
* **Google Fonts** — 34+ fonts to match your brand across all plugin elements
* **Category Shortcodes** — Display reels by category slug or ID with optional limits
* **Single Product Page Reels** — Auto-display related reels on product pages with floating video widget
* **Custom Loading Backgrounds** — Branded background image while videos buffer
* **Related Products** — Cross-sell up to 4 related products inside the popup
* **Clean Admin Editor** — Purpose-built reel editor, no bloat from third-party plugins
* **Auto-Updates** — Seamless updates via GitHub, shown in standard WordPress update screen
* **Fully Mobile Responsive** — Optimized popup and carousel for all screen sizes

= Shortcodes =

* `[broodle_reel_slider]` — All published reels
* `[broodle_reel_slider 12,34,56]` — Specific reels by ID
* `[broodle_reel_category slug="fashion"]` — Reels by category slug
* `[broodle_reel_category category="5,8" limit="6"]` — Reels by category ID with limit

== Installation ==

1. Upload the `broodle-shoppable-reels` folder to `/wp-content/plugins/`.
2. Activate from **Plugins** in WordPress admin.
3. WooCommerce must be active — the plugin deactivates with a notice if missing.
4. Go to **Shoppable Reels → Add New** to create your first reel.
5. Use `[broodle_reel_slider]` on any page to display reels.

Updates are delivered automatically from the public GitHub repository.

== Frequently Asked Questions ==

= How do I display reels on a page? =
Use `[broodle_reel_slider]` for all reels, or `[broodle_reel_category slug="fashion"]` for category-specific reels.

= What popup layouts are available? =
Three options in **Shoppable Reels → Settings**: Reel + Product (side by side), Reel only, or Product only.

= Can I customize the font? =
Yes. Go to **Shoppable Reels → Settings → Google Font** and choose from 34+ popular fonts.

= Does this work without WooCommerce? =
No. WooCommerce is required and must be active.

= How do updates work? =
Updates are delivered automatically from GitHub. When a new version is tagged, it appears in **Dashboard → Updates**.

== Screenshots ==

1. Reel carousel on the frontend
2. Product popup modal (desktop)
3. Product popup modal (mobile)
4. Reel editor in WordPress admin
5. Plugin settings page

== Changelog ==

= 2.0 =
* Major release consolidating all improvements since v1.0
* Smart sequential lazy loading with beforeChange preloading
* Google Fonts integration (34+ fonts)
* Complete mobile popup redesign (70/30 video/content split)
* Plugin listing with Author, Version, Changelog, and Get Premium links
* Rounded gallery images in popup
* Larger carousel product thumbnails with pop-out effect
* 2-line product name clamp across carousel and popup
* Clean reel editor with third-party meta boxes removed
* Film-strip menu icon with play button

= 1.9.1 =
* Preload next slide on beforeChange so video is ready before scrolling into view
* Eliminated spinner flash when sliding to next batch
* Smarter queue system that skips already-loaded slides

= 1.9 =
* Version consolidation release

= 1.8.2 =
* Rewrote lazy loading to be fully sequential (video 1 then 2 then 3)
* Changed to preload auto for faster individual loads
* Queue-based system with one-by-one loading on slide change

= 1.8.1 =
* Mobile popup 70/30 video/content split
* Mobile carousel product image reverted to original small size
* White close button on mobile popup
* Gallery arrows and dot indicators in mobile popup

= 1.8 =
* Fixed mobile popup overflow with 100dvh viewport constraint
* Film-strip menu icon with sprocket holes and play triangle
* Third-party meta boxes removed from reel editor

= 1.7 =
* Google Fonts setting with 34 popular fonts
* Plugin listing row meta links (Author, Version, Changelog, Get Premium)
* Rounded gallery images and larger product thumbnails
* Product name 2-line clamp in popup

= 1.6 =
* Complete popup modal redesign with modern styling
* Mobile popup as bottom sheet with video top, product bottom
* Pill-shaped buttons, refined size selector, hover effects
* Mobile video height reverted to 300px

= 1.5 =
* Custom plugin icon on updates screen
* Redesigned reel editor with two-column layout
* Video preview and product columns in reels list table
* Admin CSS overhaul

= 1.4 =
* Product thumbnail pop-out effect on carousel
* Popup gallery increased to 300px with modern slider dots
* Popup max-height increased to 600px

= 1.3 =
* Category-based shortcode [broodle_reel_category]
* Modern admin UI with card-based settings
* Custom reel SVG menu icon
* Carousel height increased, product info restructured

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.0 =
Major update: smart video preloading, Google Fonts, redesigned mobile popup, clean admin editor, and 10+ improvements. Recommended for all users.

== Privacy Policy ==

This plugin does not collect or store any personal data.

== Support ==

For support, visit [Broodle Marketplace](https://broodle.one/marketplace).
