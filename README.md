# Broodle Shoppable Reels

Add interactive, shoppable video reels to your WooCommerce store. Let customers browse and buy products directly from engaging short-form video content — optimized for both desktop and mobile.

## Features

- **Shoppable Video Reels** — Attach WooCommerce products to short video reels. Customers can view product details and add to cart without leaving the video.
- **Responsive Slider** — Slick-powered carousel that adapts from 3 slides on desktop to 2 on mobile.
- **Popup Layouts** — Three configurable popup styles: Reel + Product, Reel only, or Product only.
- **Single Product Page Reels** — Automatically display related reels on individual product pages with a floating video widget and fullscreen modal.
- **Lazy Video Loading** — Videos load on-demand as slides come into view, with configurable loading background image and spinner animation.
- **Related Products** — Show related products within the reel popup modal.
- **Category Support** — Organize reels with a dedicated taxonomy (Reels Categories).
- **Customizable Loading Background** — Set a custom background image from the plugin settings that displays while videos are loading.
- **Auto-Updates from GitHub** — Public GitHub repository integration using [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) for seamless updates.

## Requirements

- WordPress 5.0+
- WooCommerce (must be active)
- PHP 7.4+

## Installation

### Manual Upload

1. Download or clone this repository.
2. Upload the `broodle-shoppable-reels` folder to `/wp-content/plugins/`.
3. Activate the plugin from **Plugins** in your WordPress admin.
4. WooCommerce must be active — the plugin will deactivate itself with a notice if WooCommerce is missing.

### Auto-Updates

The plugin automatically checks for updates from its [GitHub repository](https://github.com/maitpatni/broodle-shoppable-reels). When a new version is pushed to the `main` branch, WordPress will show the update in the standard **Dashboard → Updates** screen — no configuration needed.

## Usage

### Shortcodes

Display all reels that have "Show in Home Slider" enabled:

```
[broodle_reel_slider]
```

Display specific reels by post ID:

```
[broodle_reel_slider 12,34,56]
```

Display reels from a specific category by slug:

```
[broodle_reel_category slug="fashion"]
```

Display reels from multiple categories by ID, with a limit:

```
[broodle_reel_category category="5,8" limit="6"]
```

### Creating a Reel

1. Go to **Shoppable Reels → Add New** in the WordPress admin.
2. Upload a video file using the media uploader.
3. Set the reel view count (displayed as social proof).
4. Select a primary product from the dropdown — this product's details appear in the popup.
5. Optionally select up to 4 related products.
6. Check **Hide/Show In Home Slider** to include the reel in the default shortcode output.
7. Publish the reel.

### Adding a Video to a Product Page

1. Edit any WooCommerce product.
2. Scroll to the **Shoppable Reels Layout** meta box.
3. Upload a video in the **Right Video** field.
4. Save the product.

A floating video widget will appear on that product's frontend page. Clicking it opens a fullscreen modal with playback controls.

### Settings

Navigate to **Shoppable Reels → Settings** to configure:

| Setting | Description |
|---|---|
| **Slider Popup Design** | Choose popup layout: Reels And Product, Reels only, or Product only |
| **Related Product** | Show/hide related products in the popup modal |
| **Single Product Page Reels** | Show/hide reels on individual product pages |
| **Loading Background Image** | Custom background image shown while reel videos are loading |

## File Structure

```
broodle-shoppable-reels/
├── broodle-shoppable-reels.php    # Main plugin file
├── README.md
├── assets/
│   ├── css/
│   │   ├── broodle-sr.css         # Frontend styles
│   │   ├── broodle-sr-admin.css   # Admin styles
│   │   ├── popup-product.css      # Popup modal styles
│   │   ├── product-single-reel.css# Single product reel styles
│   │   ├── bootstrap.min.css
│   │   ├── slick.min.css
│   │   ├── slick-theme.min.css
│   │   └── all.min.css            # Font Awesome
│   ├── js/
│   │   ├── broodle-sr.js          # Frontend slider + AJAX logic
│   │   ├── broodle-sr-admin.js    # Admin media uploader
│   │   ├── product-single-reel.js # Single product page video controls
│   │   ├── multiselect-dropdown.js# Multi-select for related products
│   │   ├── bootstrap.bundle.min.js
│   │   └── slick.min.js
│   ├── img/
│   │   └── placeholder.jpg
│   └── webfonts/                  # Font Awesome webfonts
└── includes/
    └── plugin-update-checker/     # GitHub auto-update library
```

## Custom Post Type & Taxonomy

- **Post Type:** `broodle_sr_reels` — stores each reel with video, product associations, and view count.
- **Taxonomy:** `broodle_sr_reels_cat` — categorize reels into groups.

## Hooks & Filters

The plugin uses standard WordPress hooks:

- `wp_enqueue_scripts` — loads frontend CSS/JS assets
- `admin_enqueue_scripts` — loads admin CSS/JS assets
- `wp_footer` — renders the single product page video modal
- `woocommerce_after_add_to_cart_button` — injects reels on product pages
- `wp_ajax_` / `wp_ajax_nopriv_` — handles the product detail AJAX endpoint

## License

GPL-2.0-or-later — see [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html).

## Author

Built by [Broodle](https://broodle.one/)
