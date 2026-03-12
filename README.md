# Shoppable Reels for WooCommerce

Turn your WooCommerce store into a video-first shopping experience. Add Instagram-style shoppable video reels that let customers browse, discover, and buy products directly from short-form video content — fully responsive on desktop, tablet, and mobile.

**Built for WooCommerce · Fully Responsive · Auto-Updates via GitHub**

---

## Why Shoppable Reels?

Short-form video is the #1 driver of product discovery. Shoppable Reels brings that experience natively into your WooCommerce store — no third-party embeds, no iframes, no monthly fees. Upload your product videos, link them to products, and drop a shortcode. That's it.

## Key Features

- **Shoppable Video Reels** — Attach any WooCommerce product to a video reel. Customers see product details, pricing, and add-to-cart — all without leaving the video.
- **Modern Popup Modal** — Sleek, responsive product popup with video playback, image gallery with slider navigation, size selector, and action buttons. Three layout modes: Reel + Product, Reel only, or Product only.
- **Responsive Carousel** — Slick-powered slider showing 3 reels on desktop, 2 on mobile. Smooth transitions with hover effects and modern card design.
- **Smart Lazy Loading** — Videos load sequentially (1st → 2nd → 3rd) instead of all at once. Next slides preload before they scroll into view using `beforeChange` detection. Far-away slides auto-unload to save memory.
- **Google Fonts Integration** — Choose from 34+ popular Google Fonts in plugin settings. Applied to product titles, prices, buttons, and badges across carousel and popup.
- **Category-Based Shortcodes** — Display reels by category slug or ID with optional limits. Organize content with the dedicated Reels Categories taxonomy.
- **Single Product Page Reels** — Automatically show related reels on product pages with a floating video widget and fullscreen modal.
- **Custom Loading Backgrounds** — Set a branded background image that displays while videos buffer. Configurable from plugin settings.
- **Related Products in Popup** — Show up to 4 related products inside the reel popup for cross-selling.
- **Clean Admin Experience** — Purpose-built reel editor with video preview, product selector, and related products picker. All third-party meta boxes (Rank Math, Yoast, Slider Revolution, etc.) are automatically removed from the reel editor.
- **Admin List Table** — Video preview thumbnails, linked product names, and view counts right in the reels l
ist table.
- **Auto-Updates from GitHub** — Seamless updates via the standard WordPress update screen. No tokens, no configuration — just push to `main` and tag a release.
- **Plugin Listing Links** — Author, Version, Changelog, and Get Premium links shown directly in the WordPress plugins list.

## Requirements

- WordPress 5.0+
- WooCommerce (must be active)
- PHP 7.4+

## Installation

1. Download or clone this repository.
2. Upload the `broodle-shoppable-reels` folder to `/wp-content/plugins/`.
3. Activate from **Plugins** in WordPress admin.
4. WooCommerce must be active — the plugin deactivates with a notice if it's missing.

Updates are delivered automatically from this GitHub repository. When a new tag is pushed, WordPress shows the update in **Dashboard → Updates**.

## Shortcodes

| Shortcode | Description |
|---|---|
| `[broodle_reel_slider]` | Display all published reels |
| `[broodle_reel_slider 12,34,56]` | Display specific reels by post ID |
| `[broodle_reel_category slug="fashion"]` | Display reels from a category by slug |
| `[broodle_reel_category category="5,8" limit="6"]` | Display reels by category ID with limit |

## Settings

Navigate to **Shoppable Reels → Settings**:

| Setting | Description |
|---|---|
| Slider Popup Design | Reel + Product, Reel only, or Product only |
| Related Product | Show/hide related products in popup |
| Single Product Page Reels | Show/hide reels on product pages |
| Loading Background Image | Custom image shown while videos load |
| Google Font | Choose a Google Font for all plugin typography |

## Changelog

### 2.0
- Major release consolidating all improvements since v1.0
- Smart sequential lazy loading with `beforeChange` preloading
- Google Fonts integration (34+ fonts)
- Complete mobile popup redesign (70/30 video/content split)
- Plugin listing with Author, Version, Changelog, and Get Premium links
- Rounded gallery images in popup
- Larger carousel product thumbnails with pop-out effect
- 2-line product name clamp across carousel and popup
- Clean reel editor (third-party meta boxes removed)
- Film-strip menu icon with play button

### 1.9.1
- Preload next slide on `beforeChange` so video is ready before it scrolls into view
- Eliminated spinner flash when sliding to next batch of videos
- Smarter queue system that skips already-loaded slides

### 1.9
- Version consolidation release

### 1.8.2
- Rewrote lazy loading to be fully sequential (video 1 → 2 → 3)
- Changed from `preload="metadata"` to `preload="auto"` for faster individual loads
- Queue-based system: next batch loads one-by-one on slide change
- Immediate slick detection (polls every 150ms instead of 1s delay)

### 1.8.1
- Mobile popup video/content split changed to 70/30
- Mobile carousel product image reverted to original small size (50px)
- Mobile popup close button changed to white
- Gallery arrows and pill-shaped dot indicators added to mobile popup
- Removed conflicting gallery dot hiding rule

### 1.8
- Fixed mobile popup overflow — constrained to 100dvh viewport height
- New film-strip menu icon with sprocket holes and play triangle
- Third-party meta boxes (Rank Math, Slider Revolution, etc.) removed from reel editor
- Only Publish box, Shoppable Reels fields, and Categories kept

### 1.7
- Google Fonts setting with 34 popular fonts
- Plugin listing row meta: Author, Version, View Changelog, Get Premium links
- Rounded gallery images in popup (12px radius)
- Carousel product thumbnail increased to 93px with deeper pop-out effect
- Product name 2-line clamp added to popup modal

### 1.6
- Complete popup modal redesign — rounded corners, modern shadows, refined typography
- Mobile popup as bottom sheet (95vh) with video top, product info bottom
- Pill-shaped buttons, smoother loader, refined size selector pills
- Hover effects on carousel cards
- Mobile carousel video height reverted to 300px (desktop stays 600px)

### 1.5
- Custom plugin icon on WordPress updates screen
- Redesigned reel editor — two-column layout with video preview
- Video preview, product, and views columns in reels list table
- CPT supports stripped to title only
- Admin CSS overhaul with card-based layout

### 1.4
- Product thumbnail pop-out effect on carousel cards
- Popup gallery images increased to 300px with top padding
- Modern pill-shaped slider dots
- Flush video section in popup (no padding)
- Popup max-height increased to 600px

### 1.3
- Category-based shortcode `[broodle_reel_category]`
- Modern admin UI overhaul with card-based settings
- Custom reel SVG menu icon
- Carousel video height increased to 600px desktop
- Product info restructured as flex row (photo + name + price side by side)

## License

GPL-2.0-or-later — [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html)

## Author

Built by [Broodle](https://broodle.one/) · [Get Premium](https://broodle.one/marketplace)
