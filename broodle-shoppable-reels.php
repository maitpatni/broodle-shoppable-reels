<?php
/**
 * Plugin Name: Broodle Shoppable Reels
 * Description: Add interactive, shoppable videos and reels to your WordPress site, allowing users to shop directly from your engaging content for a seamless shopping experience.
 * Version: 2.0.5
 * Author: Broodle
 * Author URI: https://broodle.one/marketplace
 * Text Domain: broodle-shoppable-reels
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
defined( 'ABSPATH' ) || exit;

define( 'BROODLE_SR_PLUGIN_FILE', __FILE__ );
define( 'BROODLE_SR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BROODLE_SR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// ─── Auto-update from public GitHub repo ───
require_once BROODLE_SR_PLUGIN_DIR . 'includes/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$broodle_sr_update_checker = PucFactory::buildUpdateChecker(
	'https://github.com/maitpatni/broodle-shoppable-reels/',
	__FILE__,
	'broodle-shoppable-reels'
);

// Set the branch that contains the stable release
$broodle_sr_update_checker->setBranch('main');

// Set plugin icon for updates screen
$broodle_sr_update_checker->addResultFilter( function ( $info ) {
	$info->icons = array(
		'1x'      => BROODLE_SR_PLUGIN_URL . 'assets/img/plugin-icon.png',
		'default' => BROODLE_SR_PLUGIN_URL . 'assets/img/plugin-icon.png',
	);
	return $info;
});

// Custom plugin action links and row meta
add_filter('plugin_row_meta', 'broodle_sr_disable_view_details', 10, 2);
function broodle_sr_disable_view_details($plugin_meta, $plugin_file) {
    if (plugin_basename(__FILE__) === $plugin_file) {
        $plugin_data = get_plugin_data(__FILE__);
        $version = isset($plugin_data['Version']) ? $plugin_data['Version'] : '1.0';
        return array(
            '<a href="https://broodle.host" target="_blank">Author</a>',
            'Version ' . esc_html($version),
            '<a href="https://github.com/maitpatni/broodle-shoppable-reels/releases" target="_blank">View Changelog</a>',
            '<a href="https://broodle.one/marketplace" target="_blank" style="color:#e6500a;font-weight:600;">Get Premium</a>',
        );
    }
    return $plugin_meta;
}

// Prevent WordPress from checking for updates from wp.org repository
add_filter('pre_set_site_transient_update_plugins', 'broodle_sr_disable_wp_org_updates');
function broodle_sr_disable_wp_org_updates($transient) {
    if (isset($transient->response[plugin_basename(__FILE__)])) {
        unset($transient->response[plugin_basename(__FILE__)]);
    }
    return $transient;
}

// Hide plugin from WordPress.org API requests
add_filter('http_request_args', 'broodle_sr_disable_wp_org_requests', 10, 2);
function broodle_sr_disable_wp_org_requests($args, $url) {
    if (strpos($url, 'api.wordpress.org') !== false && strpos($url, 'plugins/info') !== false) {
        $slug = dirname(plugin_basename(__FILE__));
        if (strpos($url, $slug) !== false) {
            return false; // Block the request
        }
    }
    return $args;
}

// Function to check if WooCommerce is active
function broodle_sr_woocommerce_active() {
    return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
}

if(!function_exists('broodle_sr_display_activation_notice')){
	// Function to display a notice when WooCommerce is not active
	function broodle_sr_display_activation_notice() {
		$class = 'notice notice-error is-dismissible';
		$message = __('Shoppable Reels requires WooCommerce to be active. ', 'broodle-shoppable-reels');

		printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
		wp_add_inline_script('broodle-sr-admin-fallback', 'setTimeout(function() { window.location.reload(true); }, 100);');
	}

	function broodle_sr_check_woocommerce_after_plugin_activation() {
		if (!broodle_sr_woocommerce_active()) {
			// Deactivate custom plugin
			deactivate_plugins(plugin_basename(__FILE__));
			// Display a notice
			add_action('admin_notices', 'broodle_sr_display_activation_notice');
		}
	}
	// Check WooCommerce status after plugin activation
	add_action('admin_init', 'broodle_sr_check_woocommerce_after_plugin_activation');
}
if(!function_exists('broodle_sr_check_woocommerce_on_admin_pages')){
	// Function to check WooCommerce status on admin pages and display an alert if WooCommerce is uninstalled
	function broodle_sr_check_woocommerce_on_admin_pages() {
		if (!broodle_sr_woocommerce_active()) {
			wp_add_inline_script('broodle-sr-admin-fallback', 'alert("Shoppable Reels requires WooCommerce to be active.");');
		}
	}
	// Check WooCommerce status on admin pages
	add_action('admin_enqueue_scripts', 'broodle_sr_check_woocommerce_on_admin_pages');
}

register_activation_hook( __FILE__, 'broodle_sr_set_default_settings' );

if ( ! function_exists( 'broodle_sr_set_default_settings' ) ) {
    function broodle_sr_set_default_settings() {
        // Only set default if the option is not already set
        if ( get_option( 'slider_popup_design' ) === false ) {
            update_option( 'slider_popup_design', 'reels_product' );
        }

        if ( get_option( 'related_product' ) === false ) {
            update_option( 'related_product', 1 );
        }

        if ( get_option( 'product_page_reels' ) === false ) {
            update_option( 'product_page_reels', 1 );
        }

        if ( get_option( 'product_page_video' ) === false ) {
            update_option( 'product_page_video', 1 );
        }

        if ( get_option( 'broodle_sr_loading_bg_image' ) === false ) {
            update_option( 'broodle_sr_loading_bg_image', '' );
        }

        if ( get_option( 'broodle_sr_google_font' ) === false ) {
            update_option( 'broodle_sr_google_font', '' );
        }
    }
}

function broodle_sr_enqueue_assets() {
    // Enqueue CSS
	if (!wp_style_is('all-min', 'enqueued')) {
		wp_enqueue_style(
			'all-min',
			BROODLE_SR_PLUGIN_URL . 'assets/css/all.min.css',
			array(),
			'5.15.3',
			'all'
		);
	}

	if (!wp_style_is('bootstrap-min', 'enqueued')) {
		wp_enqueue_style(
			'bootstrap-min',
			BROODLE_SR_PLUGIN_URL . 'assets/css/bootstrap.min.css',
			array(),
			'1.0',
			'all'
		);
	}

	if (!wp_style_is('popup-product', 'enqueued')) {
		wp_enqueue_style(
			'popup-product',
			BROODLE_SR_PLUGIN_URL . 'assets/css/popup-product.css',
			array(),
			'3.4',
			'all'
		);
	}

	if (!wp_style_is('slick-min', 'enqueued')) {
		wp_enqueue_style(
			'slick-min',
			BROODLE_SR_PLUGIN_URL . 'assets/css/slick.min.css',
			array(),
			'1.0',
			'all'
		);
	}

	if (!wp_style_is('slick-theme-min', 'enqueued')) {
		wp_enqueue_style(
			'slick-theme-min',
			BROODLE_SR_PLUGIN_URL . 'assets/css/slick-theme.min.css',
			array(),
			'1.0',
			'all'
		);
	}

	if (!wp_style_is('product-single-reel', 'enqueued')) {
		wp_enqueue_style(
			'product-single-reel',
			BROODLE_SR_PLUGIN_URL . 'assets/css/product-single-reel.css',
			array(),
			'1.0',
			'all'
		);
	}

	if (!wp_style_is('broodle-sr', 'enqueued')) {
		wp_enqueue_style(
			'broodle-sr',
			BROODLE_SR_PLUGIN_URL . 'assets/css/broodle-sr.css',
			array(),
			'3.4',
			'all'
		);
	}    

	if (!wp_script_is('bootstrap-min', 'enqueued')) {
		wp_enqueue_script(
			'bootstrap-min',
			BROODLE_SR_PLUGIN_URL . 'assets/js/bootstrap.bundle.min.js',
			array('jquery'),
			'1.0',
			true
		);
	}

	if (!wp_script_is('slick-min', 'enqueued')) {
		wp_enqueue_script(
			'slick-min',
			BROODLE_SR_PLUGIN_URL . 'assets/js/slick.min.js',
			array('jquery'),
			'1.0',
			true
		);
	}

	if (!wp_script_is('product-single-reel', 'enqueued')) {
		wp_enqueue_script(
			'product-single-reel',
			BROODLE_SR_PLUGIN_URL . 'assets/js/product-single-reel.js',
			array('jquery'),
			'1.0',
			true
		);
	}

	if (!wp_script_is('broodle-sr', 'enqueued')) {
		wp_enqueue_script(
			'broodle-sr',
			BROODLE_SR_PLUGIN_URL . 'assets/js/broodle-sr.js',
			array('jquery'),
			'1.1',
			true
		);
	}

	wp_localize_script(
        'broodle-sr', 
        'broodle_sr_ajax', 
        array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('broodle_sr_nonce'),
			'loading_bg_image' => esc_url( get_option( 'broodle_sr_loading_bg_image', '' ) )
		)
    );

	// Output dynamic loading background CSS
	$loading_bg_image = get_option( 'broodle_sr_loading_bg_image', '' );
	if ( ! empty( $loading_bg_image ) ) {
		$loading_bg_css = '
			.reel-video-container,
			.videoDiv.reel-video-container,
			.reelUpSlider .reel-video-container,
			.singlePagereelUpSlider .reel-video-container {
				background: #1a1a1a url(' . esc_url( $loading_bg_image ) . ') center center !important;
				background-size: cover !important;
			}
		';
		wp_add_inline_style( 'broodle-sr', $loading_bg_css );
	}

	// Enqueue Google Font if selected
	$broodle_sr_font = get_option( 'broodle_sr_google_font', '' );
	if ( ! empty( $broodle_sr_font ) ) {
		$font_slug = str_replace( ' ', '+', $broodle_sr_font );
		wp_enqueue_style(
			'broodle-sr-google-font',
			'https://fonts.googleapis.com/css2?family=' . esc_attr( $font_slug ) . ':wght@400;500;600;700&display=swap',
			array(),
			null,
			'all'
		);
		$font_css = '
			.reelUpSlider .product_name h5,
			.reelUpSlider .sel_org_price,
			.reelUpSlider .off_views .off,
			.reelUpSlider .off_views .view,
			.singlePagereelUpSlider .product_name h5,
			.singlePagereelUpSlider .sel_org_price,
			.productData_modal .product_name h5,
			.productData_modal .sel_org_price,
			.productData_modal .addtocart_moreinfo a,
			#productDetail_modal .size .option .option_item {
				font-family: "' . esc_attr( $broodle_sr_font ) . '", sans-serif !important;
			}
		';
		wp_add_inline_style( 'broodle-sr', $font_css );
	}
}

// Hook into WordPress to load assets
add_action('wp_enqueue_scripts', 'broodle_sr_enqueue_assets');

function broodle_sr_admin_assets() {
	if (!wp_style_is('broodle-sr-admin', 'enqueued')) {
		wp_enqueue_style(
			'broodle-sr-admin',
			BROODLE_SR_PLUGIN_URL . 'assets/css/broodle-sr-admin.css',
			array(),
			'2.0',
			'all'
		);
	} 
	
	if (!wp_script_is('multiselect-dropdown', 'enqueued')) {
		wp_enqueue_script(
			'multiselect-dropdown',
			BROODLE_SR_PLUGIN_URL . 'assets/js/multiselect-dropdown.js',
			array('jquery'),
			'1.0',
			true
		);
	}

	if (!wp_script_is('broodle-sr-admin', 'enqueued')) {
		wp_enqueue_script(
			'broodle-sr-admin',
			BROODLE_SR_PLUGIN_URL . 'assets/js/broodle-sr-admin.js',
			array('jquery'),
			'1.0',
			true
		);
	}
}

// Hook into WordPress to load admin assets
add_action('admin_enqueue_scripts', 'broodle_sr_admin_assets');

if(!function_exists('broodle_sr_custom_post_type_reels')) { 
	function broodle_sr_custom_post_type_reels() {
	
		// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Reels', 'Post Type General Name', 'broodle-shoppable-reels' ),
			'singular_name'       => _x( 'Reel', 'Post Type Singular Name', 'broodle-shoppable-reels' ),
			'menu_name'           => __( 'Shoppable Reels', 'broodle-shoppable-reels' ),
			'parent_item_colon'   => __( 'Parent Reel', 'broodle-shoppable-reels' ),
			'all_items'           => __( 'All Reels', 'broodle-shoppable-reels' ),
			'view_item'           => __( 'View Reel', 'broodle-shoppable-reels' ),
			'add_new_item'        => __( 'Add New Reel', 'broodle-shoppable-reels' ),
			'add_new'             => __( 'Add New', 'broodle-shoppable-reels' ),
			'edit_item'           => __( 'Edit Reel', 'broodle-shoppable-reels' ),
			'update_item'         => __( 'Update Reel', 'broodle-shoppable-reels' ),
			'search_items'        => __( 'Search Reels', 'broodle-shoppable-reels' ),
			'not_found'           => __( 'Not Found', 'broodle-shoppable-reels' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'broodle-shoppable-reels' ),
		);
			
		$args = array(
			'label'               => __( 'reels', 'broodle-shoppable-reels' ),
			'description'         => __( 'Reels news and reviews', 'broodle-shoppable-reels' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'taxonomies'          => array( 'broodle_sr_reels_cat' ),
			'rewrite' => array('slug' => 'broodle-reels'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 80,
			'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><rect x="2" y="4" width="20" height="3"/><rect x="2" y="17" width="20" height="3"/><line x1="6" y1="4" x2="6" y2="7"/><line x1="10" y1="4" x2="10" y2="7"/><line x1="14" y1="4" x2="14" y2="7"/><line x1="18" y1="4" x2="18" y2="7"/><line x1="6" y1="17" x2="6" y2="20"/><line x1="10" y1="17" x2="10" y2="20"/><line x1="14" y1="17" x2="14" y2="20"/><line x1="18" y1="17" x2="18" y2="20"/><polygon points="10,9.5 16,12 10,14.5" fill="black" stroke="none"/></svg>'),
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest' => true,
		);
			
		// Registering your Custom Post Type
		register_post_type( 'broodle_sr_reels', $args );
		
		$labels = array(
			'name' => _x( 'Reels Categories', 'taxonomy general name', 'broodle-shoppable-reels' ),
			'singular_name' => _x( 'Reels Category', 'taxonomy singular name', 'broodle-shoppable-reels' ),
			'search_items' =>  __( 'Search Categories', 'broodle-shoppable-reels' ),
			'all_items' => __( 'All Categories', 'broodle-shoppable-reels' ),
			'parent_item' => __( 'Parent Category', 'broodle-shoppable-reels' ),
			'parent_item_colon' => __( 'Parent Category:', 'broodle-shoppable-reels' ),
			'edit_item' => __( 'Edit Category', 'broodle-shoppable-reels' ), 
			'update_item' => __( 'Update Category', 'broodle-shoppable-reels' ),
			'add_new_item' => __( 'Add New Category', 'broodle-shoppable-reels' ),
			'new_item_name' => __( 'New Category Name', 'broodle-shoppable-reels' ),
			'menu_name' => __( 'Categories', 'broodle-shoppable-reels' ),
		);
						
		// Now register the taxonomy
		register_taxonomy('broodle_sr_reels_cat',array('broodle_sr_reels'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'broodle-reels-cat' ),
		));

		// Add custom fields to the post type
		register_post_meta('broodle_sr_reels', 'video', array(
			'type' => 'string',
			'description' => 'Video URL',
			'single' => true,
			'show_in_rest' => true,
		));

		register_post_meta('broodle_sr_reels', 'thumbnail', array(
			'type' => 'string',
			'description' => 'Thumbnail URL',
			'single' => true,
			'show_in_rest' => true,
		));

		register_post_meta('broodle_sr_reels', 'reel_views', array(
			'type' => 'integer',
			'description' => 'Reel Views',
			'single' => true,
			'show_in_rest' => true,
		));
		register_post_meta('broodle_sr_reels', 'productsData', array(
			'type' => 'string',
			'description' => 'products Data',
			'single' => true,
			'show_in_rest' => true,
		));
		register_post_meta('broodle_sr_reels', 'reelSliderProduct', array(
			'type' => 'string',
			'description' => 'Reel Slider Product',
			'single' => true,
			'show_in_rest' => true,
		));
	}
	add_action( 'init', 'broodle_sr_custom_post_type_reels', 0 );
}

// ─── Add video preview + product columns to reels list table ───
add_filter( 'manage_broodle_sr_reels_posts_columns', 'broodle_sr_reels_columns' );
function broodle_sr_reels_columns( $columns ) {
	$new = array();
	$new['cb']              = $columns['cb'];
	$new['broodle_sr_thumb'] = __( 'Preview', 'broodle-shoppable-reels' );
	$new['title']           = $columns['title'];
	$new['broodle_sr_product'] = __( 'Product', 'broodle-shoppable-reels' );
	$new['broodle_sr_views']   = __( 'Views', 'broodle-shoppable-reels' );
	if ( isset( $columns['taxonomy-broodle_sr_reels_cat'] ) ) {
		$new['taxonomy-broodle_sr_reels_cat'] = $columns['taxonomy-broodle_sr_reels_cat'];
	}
	$new['date'] = $columns['date'];
	return $new;
}

add_action( 'manage_broodle_sr_reels_posts_custom_column', 'broodle_sr_reels_column_content', 10, 2 );
function broodle_sr_reels_column_content( $column, $post_id ) {
	if ( $column === 'broodle_sr_thumb' ) {
		$video = get_post_meta( $post_id, 'medium_video', true );
		if ( $video ) {
			echo '<video muted preload="metadata" style="width:60px;height:80px;object-fit:cover;border-radius:6px;background:#1a1a1a;" src="' . esc_url( $video ) . '#t=0.5"></video>';
		} else {
			echo '<span style="display:inline-block;width:60px;height:80px;background:#f0f2f5;border-radius:6px;"></span>';
		}
	}
	if ( $column === 'broodle_sr_product' ) {
		$pid = get_post_meta( $post_id, 'reelSliderProduct', true );
		if ( $pid && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $pid );
			if ( $product && $product->exists() ) {
				echo esc_html( $product->get_name() );
			} else {
				echo '—';
			}
		} else {
			echo '—';
		}
	}
	if ( $column === 'broodle_sr_views' ) {
		$views = get_post_meta( $post_id, 'reels_view', true );
		echo esc_html( $views ? $views . 'k' : '—' );
	}
}

if(!function_exists('broodle_sr_wporg_add_custom_box')){
	function broodle_sr_wporg_add_custom_box()
	{
		global $post;
		$screens = [ 'broodle_sr_reels'];
		foreach ($screens as $screen) {
			add_meta_box(
				'broodle_sr_box_id1',
				'Shoppable Reels Layout',
				'broodle_sr_wporg_custom_box_html1',
				$screen,
				'normal',
				'high'
			);
		}

		$screens = [ 'product'];
		foreach ($screens as $screen) {
			add_meta_box(
				'broodle_sr_box_id2',
				'Shoppable Reels Layout',
				'broodle_sr_wporg_custom_box_html2',
				$screen,
				'normal',
				'high'
			);
		}
		
	}
	add_action('add_meta_boxes', 'broodle_sr_wporg_add_custom_box',1);

	// Remove all third-party meta boxes from reel editor — keep only ours
	add_action('add_meta_boxes', 'broodle_sr_remove_third_party_metaboxes', 999);
	function broodle_sr_remove_third_party_metaboxes() {
		global $wp_meta_boxes;
		$screen = 'broodle_sr_reels';
		if ( ! isset( $wp_meta_boxes[ $screen ] ) ) {
			return;
		}
		$keep = array(
			'broodle_sr_box_id1',   // Our reel fields
			'submitdiv',            // Publish box
			'broodle_sr_reels_catdiv', // Our taxonomy
			'slugdiv',              // Slug (WP core)
		);
		foreach ( $wp_meta_boxes[ $screen ] as $context => $priorities ) {
			foreach ( $priorities as $priority => $boxes ) {
				foreach ( $boxes as $id => $box ) {
					if ( ! in_array( $id, $keep, true ) ) {
						remove_meta_box( $id, $screen, $context );
					}
				}
			}
		}
	}
	function broodle_sr_wporg_custom_box_html1($post)
	{
		$medium_video = get_post_meta($post->ID, 'medium_video', true);
		$reels_view   = get_post_meta($post->ID, 'reels_view', true);
		$productsData = get_post_meta($post->ID, 'productsData', true);

		$product_args = array( 'post_type' => 'product', 'orderby' => 'ID', 'post_status' => 'publish', 'order' => 'DESC', 'posts_per_page' => -1 );
		$result_products = new WP_Query( $product_args );

		$reel_args = array( 'post_type' => 'broodle_sr_reels', 'orderby' => 'ID', 'post_status' => 'publish', 'order' => 'DESC', 'posts_per_page' => -1 );
		$result_reels = new WP_Query( $reel_args );

		$reelSliderProductArray = [];
		if ( $result_reels->have_posts() ) {
			while ( $result_reels->have_posts() ) {
				$result_reels->the_post();
				$pid = get_post_meta( get_the_ID(), 'reelSliderProduct', true );
				if ( $pid ) $reelSliderProductArray[] = $pid;
			}
		}

		$currentReelProduct        = get_post_meta( $post->ID, 'reelSliderProduct', true );
		$CurrentReelRelatedproducts = maybe_unserialize( $productsData );
		wp_nonce_field( 'broodle_sr_save_data', 'broodle_sr_nonce' );
		?>
		<div class="broodle-sr-editor">
			<!-- Left: Video Preview -->
			<div class="broodle-sr-editor-left">
				<div class="broodle-sr-video-preview">
					<?php if ( ! empty( $medium_video ) ) : ?>
						<video controls class="broodle-sr-video upload_image_src upload_image_button" data-class="upload_image" data-class1="upload_image_src">
							<source src="<?php echo esc_url( $medium_video ); ?>" type="video/mp4">
						</video>
						<button type="button" class="broodle-sr-video-remove" title="Remove video"><span class="dashicons dashicons-no-alt"></span></button>
					<?php else : ?>
						<div class="broodle-sr-video-empty upload_image_src upload_image_button" data-class="upload_image" data-class1="upload_image_src">
							<span class="dashicons dashicons-video-alt3"></span>
							<span>Click to upload video</span>
						</div>
					<?php endif; ?>
					<input class="upload_image medium_video newreeluploadvideo" type="hidden" name="medium_video" value="<?php echo esc_url( $medium_video ); ?>" />
				</div>
			</div>
			<!-- Right: Fields -->
			<div class="broodle-sr-editor-right">
				<div class="broodle-sr-field">
					<label>Reel Views</label>
					<input type="text" name="reels_view" class="reels_view" value="<?php echo esc_attr( $reels_view ); ?>" placeholder="e.g. 12">
					<p class="broodle-sr-hint">Displayed as social proof (shown as Xk)</p>
				</div>
				<div class="broodle-sr-field">
					<label>Primary Product</label>
					<select name="reelSliderProduct" class="reelSliderProduct">
						<option value="">— Select Product —</option>
						<?php
						if ( $result_products->have_posts() ) {
							while ( $result_products->have_posts() ) {
								$result_products->the_post();
								$pid = get_the_ID();
								if ( $pid == $currentReelProduct ) {
									echo '<option value="' . esc_attr( $pid ) . '" selected>' . esc_html( get_the_title() ) . '</option>';
								} elseif ( ! in_array( $pid, $reelSliderProductArray ) ) {
									echo '<option value="' . esc_attr( $pid ) . '">' . esc_html( get_the_title() ) . '</option>';
								}
							}
						}
						?>
					</select>
				</div>
				<div class="broodle-sr-field">
					<label>Related Products <span class="broodle-sr-badge">Max 4</span></label>
					<select name="productsData[]" id="productsData" multiple multiselect-search="true" multiselect-max-items="4">
						<?php
						if ( $result_products->have_posts() ) {
							while ( $result_products->have_posts() ) {
								$result_products->the_post();
								$pid = get_the_ID();
								$sel = ( ! empty( $CurrentReelRelatedproducts ) && is_array( $CurrentReelRelatedproducts ) && in_array( $pid, $CurrentReelRelatedproducts ) ) ? ' selected' : '';
								echo '<option value="' . esc_attr( $pid ) . '"' . $sel . '>' . esc_html( get_the_title() ) . '</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<?php
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_media();
	}

	function broodle_sr_wporg_custom_box_html2($post)
	{
			$right_video = get_post_meta($post->ID, 'right_video', true);
		?>	
			<span id="pluginsPath" style="display:none;"><?php echo esc_attr( BROODLE_SR_PLUGIN_URL ); ?></span>
			<div class="pkg_img_wrap5" style="margin-top: 10px;">
				<div class="custom_img_box_wrap5">
					<?php wp_nonce_field('broodle_sr_save_data', 'broodle_sr_nonce'); ?>
					<?php if (!empty($right_video)) { ?>
					<div class="box">
						<label>Right Video</label>	
						<div class="video_box">		
							<video controls class="upload_image_src upload_image_button" data-class="upload_image"
									data-class1="upload_image_src">
								<source  src="<?php  echo esc_url($right_video); ?>" type="video/mp4">
							</video>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
							<input class="upload_image medium_video" type="hidden" size="25" name="right_video"
								value="<?php echo esc_url($right_video);?>" /><br>
						</div>	
					</div>
					<?php } else { ?>
					<div class="box">
						<label>Right Video</label>
						<video controls width="10%" class="upload_image_src upload_image_button" data-class="upload_image" poster="<?php echo esc_url(BROODLE_SR_PLUGIN_URL);?>/assets/img/placeholder.jpg"
								data-class1="upload_image_src">
							<source  src="" type="video/mp4">
						</video>
						<input class="upload_image medium_video newreeluploadvideo" type="hidden" size="25" name="right_video"
							value="" />	
					</div>
					<?php } ?>						
				</div>
			</div>
		<?php
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_media();	
	}
}
if (!function_exists('broodle_sr_save_postdata')) {
    function broodle_sr_save_postdata($post_id)
    {
        // Verify the nonce
        if (!isset($_POST['broodle_sr_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['broodle_sr_nonce'])), 'broodle_sr_save_data')) {
            return;
        }

        // Prevent autosave & bulk edits
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if user has permission to edit the post
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['medium_video'])) {
            $medium_video = sanitize_text_field(wp_unslash($_POST['medium_video']));
            $medium_video = esc_url_raw($medium_video);
            update_post_meta($post_id, 'medium_video', $medium_video);
        }        

        if (isset($_POST['reels_view'])) {
            $reels_view = sanitize_text_field(wp_unslash($_POST['reels_view']));
            $reels_view = absint($reels_view);
            update_post_meta($post_id, 'reels_view', $reels_view);
        }

		if (isset($_POST['reelSliderProduct'])) {
            $reelSliderProduct = sanitize_text_field(wp_unslash($_POST['reelSliderProduct']));
            $reelSliderProduct = absint($reelSliderProduct);
            update_post_meta($post_id, 'reelSliderProduct', $reelSliderProduct);
        }

        if (isset($_POST['productsData']) && is_array($_POST['productsData'])) {
            $productIds = array_map('absint', $_POST['productsData']);
            update_post_meta($post_id, 'productsData', maybe_serialize($productIds));
        }   

		if (isset($_POST['right_video'])) {
            $right_video = sanitize_text_field(wp_unslash($_POST['right_video']));
            $right_video = esc_url_raw($right_video);
            update_post_meta($post_id, 'right_video', $right_video);
        }
    }

    add_action('save_post', 'broodle_sr_save_postdata');
}
if(!function_exists('broodle_sr_reel_slider_shortcode_func')){
	function broodle_sr_reel_slider_shortcode_func($atts){

		$post_ids = isset($atts[0]) ? explode(',', $atts[0]) : [];
		$html = ''; 
		
		$html .= '
		<div class="reelUpSlider">';

		if (empty($post_ids)) {
			$args = array(
				'post_type'=> 'broodle_sr_reels',
				'orderby'    => 'ID',
				'post_status' => 'publish',
				'order'    => 'DESC',
				'posts_per_page' => -1
			);
		}else{
			$args = array(
				'post_type'=> 'broodle_sr_reels',
				'post__in' => $post_ids,
				'orderby'    => 'post__in',
				'post_status' => 'publish',
				'order'    => 'DESC',
				'posts_per_page' => -1
			);
		}
		$result = new WP_Query( $args );
		$i = 1;
		if ($result->have_posts()) :
			while ($result->have_posts()) : $result->the_post();
				$data_product_id = get_post_meta(get_the_ID(), 'reelSliderProduct', true);
				if($data_product_id){	
						$videoData = get_post_meta( get_the_ID(),'medium_video'); 
						$reels_view = get_post_meta( get_the_ID(),'reels_view', true); 		
					$product = wc_get_product( $data_product_id );
					if (!$product || !$product->exists()) {
						continue;
					}
					
					// Handle different product types (simple, variable, etc.)
					if ($product->is_type('variable')) {
						$variations = $product->get_available_variations();
						if (!empty($variations)) {
							$first_variation = wc_get_product($variations[0]['variation_id']);
							$regular_price = $first_variation->get_regular_price();
							$sale_price = $first_variation->get_sale_price();
							$current_price = $first_variation->get_price();
						} else {
							$regular_price = $product->get_variation_regular_price('min');
							$sale_price = $product->get_variation_sale_price('min');
							$current_price = $product->get_variation_price('min');
						}
					} else {
						$regular_price = $product->get_regular_price();
						$sale_price = $product->get_sale_price();
						$current_price = $product->get_price();
					}
					
					$discountPer = 0;
					if (!empty($regular_price) && !empty($sale_price) && $sale_price > 0 && $sale_price < $regular_price) {
						$discountPer = round((($regular_price - $sale_price) / $regular_price) * 100);
					}
					
					// Build price display HTML
					$regular_price = floatval( $regular_price );
					$sale_price    = floatval( $sale_price );
					$has_price = ( $regular_price > 0 || $sale_price > 0 );
					$price_html = '';
					
					if ( $has_price ) {
						if ( $sale_price > 0 && $regular_price > 0 && $sale_price < $regular_price ) {
							$price_html = '<div class="selling_price">₹'. number_format($sale_price, 2) .'</div>';
							$price_html .= '<div class="regular_price">₹<del>'. number_format($regular_price, 2) .'</del></div>';
						} elseif ( $regular_price > 0 ) {
							$price_html = '<div class="selling_price">₹'. number_format($regular_price, 2) .'</div>';
						}
					}
					
					$html .= '<div>
						<div class="reel_product " data-product_id = "'. $data_product_id.'" data-reel_id = "'. get_the_ID().'">
							<div class="reel_product_image" >
								<div class="off_views">
									<span class="off" style="'. ($discountPer > 0 ? 'visibility: visible;' : 'visibility: hidden;') .'">'. ($discountPer > 0 ? $discountPer : '0') .'% off</span><span class="view"><i class="fa-solid fa-eye"></i>&nbsp;'. $reels_view .'k</span>
								</div>
								<div class="reel-video-container" data-video-src="'. esc_url($videoData[0]) .'" data-lazy="true">
									<div class="reel-video-placeholder">
										<div class="reel-video-spinner">
											<div class="spinner"></div>
										</div>
									</div>							
								</div>
							</div>
							<div class="reel_product_info">
								<div class="slide_product_image">
									'.wp_get_attachment_image( $product->get_image_id()).'
								</div>
								<div class="reel_product_text">
									<div class="product_name">
										<h5>'. esc_html($product->get_title()).'</h5>
									</div>
									'. ( $has_price ? '<div class="sel_org_price">' . $price_html . '</div>' : '' ) .'
								</div>
							</div>
						</div>					
					</div>';
						$i++;
				}
			endwhile;
		else :
			$html .= '<p>Reels Not Found.</p>';
		endif;
		wp_reset_postdata();
		$html .= '</div>';

		// Flag that shared modal + lazy-loader must be output in footer
		broodle_sr_flag_footer_assets();

		return $html;
	}
	add_shortcode('broodle_reel_slider', 'broodle_sr_reel_slider_shortcode_func');
}

// ─── Category-based shortcode ───
if ( ! function_exists( 'broodle_sr_reel_cat_shortcode_func' ) ) {
	function broodle_sr_reel_cat_shortcode_func( $atts ) {
		$atts = shortcode_atts( array(
			'category' => '',
			'slug'     => '',
			'limit'    => -1,
		), $atts, 'broodle_reel_category' );

		$tax_query = array();
		if ( ! empty( $atts['slug'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'broodle_sr_reels_cat',
				'field'    => 'slug',
				'terms'    => array_map( 'trim', explode( ',', $atts['slug'] ) ),
			);
		} elseif ( ! empty( $atts['category'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'broodle_sr_reels_cat',
				'field'    => 'term_id',
				'terms'    => array_map( 'absint', explode( ',', $atts['category'] ) ),
			);
		}

		if ( empty( $tax_query ) ) {
			return '<p>Please specify a category or slug. Usage: [broodle_reel_category slug="my-category"]</p>';
		}

		$args = array(
			'post_type'      => 'broodle_sr_reels',
			'orderby'        => 'ID',
			'post_status'    => 'publish',
			'order'          => 'DESC',
			'posts_per_page' => intval( $atts['limit'] ),
			'tax_query'      => $tax_query,
		);

		$result = new WP_Query( $args );
		$html = '<div class="reelUpSlider">';

		if ( $result->have_posts() ) :
			while ( $result->have_posts() ) : $result->the_post();
				$data_product_id = get_post_meta( get_the_ID(), 'reelSliderProduct', true );
				if ( ! $data_product_id ) { continue; }

				$videoData   = get_post_meta( get_the_ID(), 'medium_video' );
				$reels_view  = get_post_meta( get_the_ID(), 'reels_view', true );
				$product     = wc_get_product( $data_product_id );
				if ( ! $product || ! $product->exists() ) { continue; }

				if ( $product->is_type( 'variable' ) ) {
					$variations = $product->get_available_variations();
					if ( ! empty( $variations ) ) {
						$first_var     = wc_get_product( $variations[0]['variation_id'] );
						$regular_price = $first_var->get_regular_price();
						$sale_price    = $first_var->get_sale_price();
					} else {
						$regular_price = $product->get_variation_regular_price( 'min' );
						$sale_price    = $product->get_variation_sale_price( 'min' );
					}
				} else {
					$regular_price = $product->get_regular_price();
					$sale_price    = $product->get_sale_price();
				}

				$discountPer = 0;
				if ( ! empty( $regular_price ) && ! empty( $sale_price ) && $sale_price > 0 && $sale_price < $regular_price ) {
					$discountPer = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
				}

				$regular_price = floatval( $regular_price );
				$sale_price    = floatval( $sale_price );
				$has_price = ( $regular_price > 0 || $sale_price > 0 );
				$price_html = '';
				if ( $has_price ) {
					if ( $sale_price > 0 && $regular_price > 0 && $sale_price < $regular_price ) {
						$price_html  = '<div class="selling_price">₹' . number_format( $sale_price, 2 ) . '</div>';
						$price_html .= '<div class="regular_price">₹<del>' . number_format( $regular_price, 2 ) . '</del></div>';
					} elseif ( $regular_price > 0 ) {
						$price_html = '<div class="selling_price">₹' . number_format( $regular_price, 2 ) . '</div>';
					}
				}

				$html .= '<div>
					<div class="reel_product" data-product_id="' . esc_attr( $data_product_id ) . '" data-reel_id="' . esc_attr( get_the_ID() ) . '">
						<div class="reel_product_image">
							<div class="off_views">
								<span class="off" style="' . ( $discountPer > 0 ? 'visibility:visible;' : 'visibility:hidden;' ) . '">' . ( $discountPer > 0 ? $discountPer : '0' ) . '% off</span><span class="view"><i class="fa-solid fa-eye"></i>&nbsp;' . esc_html( $reels_view ) . 'k</span>
							</div>
							<div class="reel-video-container" data-video-src="' . esc_url( $videoData[0] ) . '" data-lazy="true">
								<div class="reel-video-placeholder">
									<div class="reel-video-spinner"><div class="spinner"></div></div>
								</div>
							</div>
						</div>
						<div class="reel_product_info">
							<div class="slide_product_image">' . wp_get_attachment_image( $product->get_image_id() ) . '</div>
							<div class="reel_product_text">
								<div class="product_name"><h5>' . esc_html( $product->get_title() ) . '</h5></div>
								' . ( $has_price ? '<div class="sel_org_price">' . $price_html . '</div>' : '' ) . '
							</div>
						</div>
					</div>
				</div>';
			endwhile;
		else :
			$html .= '<p>No reels found in this category.</p>';
		endif;
		wp_reset_postdata();
		$html .= '</div>';

		// Flag that shared modal + lazy-loader must be output in footer
		broodle_sr_flag_footer_assets();

		return $html;
	}
	add_shortcode( 'broodle_reel_category', 'broodle_sr_reel_cat_shortcode_func' );
}

// ─── Shared flag + footer output for modal & lazy-loader ───
function broodle_sr_flag_footer_assets() {
	static $hooked = false;
	if ( ! $hooked ) {
		$hooked = true;
		add_action( 'wp_footer', 'broodle_sr_render_shared_footer', 50 );
	}
}

function broodle_sr_render_shared_footer() {
	$slider_popup_design = get_option('slider_popup_design');
	$model_width = '800px';
	if ( $slider_popup_design == 'reels_product' ) {
		$model_width = '800px';
	}
	if ( $slider_popup_design == 'reels' ) {
		$model_width = '350px';
	}
	if ( $slider_popup_design == 'product' ) {
		$model_width = '470px';
	}
	?>
	<!-- Shoppable Reels Modal -->
	<div class="modal fade" id="productDetail_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
		aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:<?php echo esc_attr($model_width);?>">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body p-0">
					<div class="loader">
						<div class="load"></div>
					</div>
					<div class="productData_modal">
						<?php
							if ( $slider_popup_design != 'reels_product' ) {
								$column = "100%";
							} else {
								$column = "";
							}
							if ( $slider_popup_design == 'reels_product' || $slider_popup_design == 'reels' ) {
						?>
								<div class="reel" style="width:<?php echo esc_attr($column);?>">
								</div>
						<?php }
							if ( $slider_popup_design == 'reels_product' || $slider_popup_design == 'product' ) {
						?>
							<div class="productDataSide" style="width:<?php echo esc_attr($column);?>">
								<div style="padding: 10px 20px;">
									<div class="product_name">
									</div>
									<div class="sel_org_price">
									</div>
									<div class="size">
									</div>
									<div class="addtocart_moreinfo">
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		/* ── Smart preloading lazy-loader for reel carousels ── */
		var loadQueue = [];
		var isLoading = false;

		function unloadVideo(container) {
			var video = container.find('video');
			if (video.length) {
				video.get(0).pause();
				video.get(0).src = '';
				video.get(0).load();
				video.remove();
			}
			container.removeClass('video-loaded loaded loading');
			container.find('.reel-video-placeholder').removeClass('hidden').show();
		}

		function loadVideo(container, callback) {
			var videoSrc = container.attr('data-video-src');
			if (!videoSrc || container.hasClass('video-loaded')) {
				if (callback) callback();
				return;
			}
			container.addClass('video-loaded loading');
			var video = document.createElement('video');
			video.setAttribute('autoplay', '');
			video.setAttribute('muted', '');
			video.setAttribute('loop', '');
			video.setAttribute('playsinline', '');
			video.muted = true;
			video.volume = 0;
			video.preload = 'auto';

			var source = document.createElement('source');
			source.src = videoSrc;
			source.type = 'video/mp4';
			video.appendChild(source);

			var done = false;
			function finish() {
				if (done) return;
				done = true;
				container.addClass('loaded').removeClass('loading');
				container.find('.reel-video-placeholder').addClass('hidden').hide();
				video.muted = true;
				video.volume = 0;
				var p = video.play();
				if (p !== undefined) {
					p.catch(function() { video.style.opacity = '1'; });
				}
				if (callback) callback();
			}

			video.addEventListener('canplay', finish);
			video.addEventListener('playing', function() {
				video.muted = true; video.volume = 0; video.style.opacity = '1';
			});
			video.addEventListener('error', function() {
				container.removeClass('loading');
				container.find('.reel-video-placeholder').hide();
				done = true;
				if (callback) callback();
			});
			video.addEventListener('volumechange', function() {
				if (!video.muted || video.volume > 0) { video.muted = true; video.volume = 0; }
			});

			container.append(video);
			video.load();
		}

		function processQueue() {
			if (isLoading || loadQueue.length === 0) return;
			isLoading = true;
			var container = loadQueue.shift();
			if (!container.length || container.hasClass('loaded')) {
				isLoading = false;
				processQueue();
				return;
			}
			loadVideo(container, function() {
				isLoading = false;
				processQueue();
			});
		}

		function enqueueSlide(slider, idx) {
			var slideEl = slider.find('.slick-slide[data-slick-index="' + idx + '"]');
			if (!slideEl.length) return;
			var vc = slideEl.find('.reel-video-container[data-lazy="true"]:not(.video-loaded)');
			if (vc.length) {
				vc.addClass('loading');
				var dominated = false;
				for (var q = 0; q < loadQueue.length; q++) {
					if (loadQueue[q].is(vc)) { dominated = true; break; }
				}
				if (!dominated) loadQueue.push(vc);
			}
		}

		function getVisibleCount() {
			return window.innerWidth > 768 ? 3 : 2;
		}

		function cleanupFarSlides(slider, currentSlide) {
			var visible = getVisibleCount();
			var keepFrom = currentSlide - visible;
			var keepTo = currentSlide + visible * 3;
			slider.find('.slick-slide:not(.slick-cloned)').each(function() {
				var idx = parseInt($(this).attr('data-slick-index'), 10);
				if (isNaN(idx)) return;
				if (idx < keepFrom || idx > keepTo) {
					var vc = $(this).find('.reel-video-container.video-loaded');
					if (vc.length) unloadVideo(vc);
				}
			});
		}

		function initSliderVideos() {
			$('.reelUpSlider, .singlePagereelUpSlider').each(function() {
				var slider = $(this);
				if (!slider.hasClass('slick-initialized')) return;
				var visible = getVisibleCount();
				var total = slider.find('.slick-slide:not(.slick-cloned)').length;
				var loadTo = Math.min(visible * 2, total);
				for (var i = 0; i < loadTo; i++) {
					enqueueSlide(slider, i);
				}
				processQueue();
			});
		}

		var initAttempts = 0;
		function tryInit() {
			var anyInit = false;
			$('.reelUpSlider, .singlePagereelUpSlider').each(function() {
				if ($(this).hasClass('slick-initialized')) anyInit = true;
			});
			if (anyInit || initAttempts > 20) {
				initSliderVideos();
			} else {
				initAttempts++;
				setTimeout(tryInit, 150);
			}
		}
		tryInit();

		$(document).on('beforeChange', '.reelUpSlider, .singlePagereelUpSlider', function(event, slick, currentSlide, nextSlide) {
			var slider = $(this);
			var visible = getVisibleCount();
			var total = slider.find('.slick-slide:not(.slick-cloned)').length;
			var loadFrom = Math.max(0, nextSlide);
			var loadTo = Math.min(total, nextSlide + visible + visible);
			for (var i = loadFrom; i < loadTo; i++) {
				enqueueSlide(slider, i);
			}
			var loadBack = Math.max(0, nextSlide - 1);
			enqueueSlide(slider, loadBack);
			processQueue();
		});

		$(document).on('afterChange', '.reelUpSlider, .singlePagereelUpSlider', function(event, slick, currentSlide) {
			var slider = $(this);
			cleanupFarSlides(slider, currentSlide);
		});

		var resizeTimer;
		$(window).on('resize', function() {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(initSliderVideos, 200);
		});

		setTimeout(function() {
			$('.reel-video-container[data-lazy="true"]:not(.video-loaded)').each(function() {
				var c = $(this);
				if (!c.closest('.reelUpSlider, .singlePagereelUpSlider').length ||
					!c.closest('.reelUpSlider, .singlePagereelUpSlider').hasClass('slick-initialized')) {
					c.addClass('loading');
					loadQueue.push(c);
				}
			});
			processQueue();
		}, 3000);
	});
	</script>
	<?php
}

if(!function_exists('broodle_sr_create_user_form_ajax')){

	add_action('wp_ajax_nopriv_broodle_sr_create_user_form_ajax', 'broodle_sr_create_user_form_ajax'); 
	add_action('wp_ajax_broodle_sr_create_user_form_ajax', 'broodle_sr_create_user_form_ajax');	

	function broodle_sr_create_user_form_ajax() {
		if (!isset($_POST['broodle_sr_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['broodle_sr_nonce'])), 'broodle_sr_nonce')) {
            wp_send_json_error(['message' => 'Nonce verification failed']);
            wp_die();
        }

		$data = [];
		$product_id = isset($_POST['product_id']) ? sanitize_text_field(wp_unslash($_POST['product_id'])) : '';
		$reel_id = isset($_POST['reel_id']) ? sanitize_text_field(wp_unslash($_POST['reel_id'])) : '';

		$product_id = sanitize_text_field($product_id); 
		$reel_id = sanitize_text_field($reel_id);

		$product = wc_get_product($product_id);

		if ( ! $product || ! $product->exists() ) {
			wp_send_json_error( [ 'message' => 'Product not found' ] );
			wp_die();
		}

		// Get price information
		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
		$current_price = $product->get_price();
		$has_price = ( floatval( $regular_price ) > 0 || floatval( $sale_price ) > 0 || floatval( $current_price ) > 0 );
		
		$data['product'] = [
			"product_id" => $product_id,
			"product_image_url" => wp_get_attachment_url($product->get_image_id()),
			"product_name" => $product->get_name(),
			"selling_price" => !empty($sale_price) && $sale_price > 0 && $sale_price < $regular_price ? $sale_price : $current_price,
			"original_price" => $regular_price,
			"has_price" => $has_price,
			"product_description" => $product->get_description(),
			"product_url" => get_permalink($product_id),
			"add_to_cart" => esc_url(wc_get_cart_url() . '?add-to-cart=' . $product_id),
			"product_images" => '',
			"product_attributes" => []
		];

		if ($product->has_attributes()) {
			$variations = $product->get_attributes();
			foreach ($variations as $variation) {
				$attribute_name = $variation->get_name();
				$attribute_value = $variation->get_options();
				$data['product']['product_attributes'][$attribute_name] = $attribute_value; 
			}	
		}

		$gallery_image_ids = $product->get_gallery_image_ids();
		$product_image_slider = '<div class="product_slider" >';
		foreach ($gallery_image_ids as $image_id) {
			$product_image_slider .= '<div>' . wp_get_attachment_image($image_id, 'full', false, ['class' => 'product_image']) . '</div>';
		}
		$product_image_slider .= '</div>';
		$data['product']['product_images'] = $product_image_slider;
		$reel = '<video loop controls autoplay class="reel_video"><source src="'.esc_url(get_post_meta( $reel_id,'medium_video')[0]).'" type="video/mp4"></video>';
		$data['reel_data'] = $reel;

		$args_reel = array(
			'post_type'=> 'broodle_sr_reels',
			'orderby'    => 'ID',
			'post_status' => 'publish',
			'order'    => 'DESC',
			'posts_per_page' => -1
			);
		$result_reels = new WP_Query( $args_reel );	
		$related_product_arr = [];
		$getProductDataByReelId = get_post_meta( $reel_id, 'productsData', true );
		$getProductDataByReelId_decode = maybe_unserialize( $getProductDataByReelId );
		
		if ( is_array( $getProductDataByReelId_decode ) ) {
			foreach ($getProductDataByReelId_decode as $key => $productId) {
				$productData = wc_get_product($productId);
				if ( ! $productData || ! $productData->exists() ) {
					continue;
				}
				if($result_reels->have_posts()){
						$get_the_reelProductID = get_post_meta( get_the_ID(), "reelSliderProduct", true);
						if($productId != $get_the_reelProductID  || $productId != $product_id  ){
							$related_product_arr[] = [
								"product_id" =>$productId,
								"reel_id"=> get_the_ID(),
								"product_image_url" => wp_get_attachment_url($productData->get_image_id()),
								"product_name" => $productData->get_name(),
								"selling_price" => $productData->get_price(),
								"original_price" => $productData->get_regular_price(),	
							];				
						}	
				}
			}
		}
		$data['related_product'] = $related_product_arr;
		wp_send_json($data);
	} 	
}

if(!function_exists('broodle_sr_single_product_footer')){
	add_action('wp_footer', 'broodle_sr_single_product_footer');
	function broodle_sr_single_product_footer() {
		if (is_product()) {
			?>
			<!-- Modal -->
			<div class="modal fade" id="productDetail_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
				aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" style="max-width:350px;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body p-0">
							<div class="loader">
								<div class="load"></div>
							</div>
							<div class="productData_modal">
								<div class="reel" style="width:100%;">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
				$right_video = get_post_meta( get_the_ID(),'right_video'); 
				if($right_video){
			?>
					<div id="draggableDiv" class="draggable ui-draggable">
						<div class="content_wrap">
							<div class="videoBox">
								<video playsinline="playsinline" autoplay="autoplay" loop="loop" muted="muted" class="video ui-draggable-handle" width="200px" height="300px" id="pdp_video" preload="metadata" poster="">
									<source src="<?php echo esc_attr($right_video[0]);?>" type="video/mp4">								
								</video>
							</div>
							<div class="controls">
								<button class="pause_play">
									<span class="icon pause">
										<svg class="icon icon-pause" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false" aria-hidden="true">
											<path d="M1.2 0.75C0.813401 0.75 0.5 0.985051 0.5 1.275V10.725C0.5 11.0149 0.813401 11.25 1.2 11.25C1.5866 11.25 1.9 11.0149 1.9 10.725V1.275C1.9 0.985051 1.5866 0.75 1.2 0.75Z" fill="currentColor"></path>
											<path d="M6.8 0.75C6.4134 0.75 6.1 0.985051 6.1 1.275V10.725C6.1 11.0149 6.4134 11.25 6.8 11.25C7.1866 11.25 7.5 11.0149 7.5 10.725V1.275C7.5 0.985051 7.1866 0.75 6.8 0.75Z" fill="currentColor"></path>
										</svg>
									</span>
									<span class="icon play hide">
										<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" class="icon icon-play" fill="none" viewBox="0 0 10 14">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M1.48177 0.814643C0.81532 0.448245 0 0.930414 0 1.69094V12.2081C0 12.991 0.858787 13.4702 1.52503 13.0592L10.5398 7.49813C11.1918 7.09588 11.1679 6.13985 10.4965 5.77075L1.48177 0.814643Z" fill="currentColor"></path>
										</svg>
									</span>
								</button>
								<button class="video-close">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-close-bold" viewBox="0 0 24 24" fill="none">
										<line x1="18" y1="6" x2="6" y2="18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></line>
										<line x1="6" y1="6" x2="18" y2="18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></line>
									</svg>
								</button>
							</div>
						</div>
					</div>			
					<div class="modal fade" id="VideoModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-backdrop="static" data-keyboard="false" aria-modal="true" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content bg-transparent">								
								<div class="custom-model-wrap">
									<div class="pop-up-content-wrap">
										<div class="popup-video active">
											<video playsinline="playsinline" autoplay="autoplay" loop="loop" class="video popup_video" preload="metadata">
												<source src="<?php echo esc_attr($right_video[0]);?>" type="video/mp4">
											</video>
											<div class="popup-video-controls">
												<div class="controls">
													<button class="volume_btn">
														<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" stroke-width="3" stroke="#ffffff" fill="none">
															<g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M34.12,49.2,20.41,39.32H12V24h7.54l14.58-9.2a.39.39,0,0,1,.59.33V48.88A.39.39,0,0,1,34.12,49.2Z" stroke-linecap="square"></path><path d="M39.63,24.29a8,8,0,0,1,.16,15.37"></path><path d="M42.23,18.91A13.66,13.66,0,0,1,42.5,45"></path></g>
														</svg>
													</button>
													<button class="close-btn_mobile">
														<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-close-bold" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
															<line x1="18" y1="6" x2="6" y2="18"></line>
															<line x1="6" y1="6" x2="18" y2="18"></line>
														</svg>
													</button>
												</div>
											</div>
											<div class="paused_icon"></div>
											<div class="popup_content recommended-product-wrapper"></div> 
										</div>
									</div>
								</div>
							</div>
							<div class="bg-overlay"></div>
						</div>
					</div>			
			<?php
				}
		}
	}
}

$product_page_reels = get_option('product_page_reels');
if($product_page_reels == 1){
											
	if ( ! function_exists( 'broodle_sr_single_post_content' ) ) {
		add_action( 'woocommerce_after_add_to_cart_button', 'broodle_sr_single_post_content' );
		
		function broodle_sr_single_post_content() {
			?>
				<div class="" style="max-width: 330px;margin:20px 0;">
					<div class="singlePagereelUpSlider">
						<?php 
						$product_id = get_the_ID();
						$args = array(
							'post_type'      => 'broodle_sr_reels',
							'orderby'        => 'ID',
							'post_status'    => 'publish',
							'order'          => 'DESC',
							'posts_per_page' => -1,
							'meta_query'     => array(
								array(
									'key'     => 'reelSliderProduct',
									'value'   => $product_id,
									'compare' => '='
								)
							)
						);
						$result = new WP_Query( $args );
						$i = 1;
						if ($result->have_posts()) :
							while ($result->have_posts()) : $result->the_post();
								$data_product_id = get_post_meta(get_the_ID(), 'reelSliderProduct', true);				
								if($data_product_id){			
									$videoData = get_post_meta( get_the_ID(),'medium_video'); 
									$reels_view = get_post_meta( get_the_ID(),'reels_view'); 	
									$product = wc_get_product( $data_product_id );
									$regular_price = $product->get_regular_price();
									$sale_price = $product->get_sale_price();
									$discountPer = 0;
									if (!empty($regular_price) && !empty($sale_price) && $sale_price > 0 && $sale_price < $regular_price) {
										$discountPer = round((($regular_price - $sale_price) / $regular_price) * 100);
									}
						?>
						<div>
							<div class="reel_product " data-product_id="<?php echo esc_attr($data_product_id);?>" data-reel_id="<?php echo esc_attr(get_the_ID());?>">
								<div class="reel_product_image">
									<div class="off_views">
										<span class="off" style="<?php echo ($discountPer > 0 ? 'visibility: visible;' : 'visibility: hidden;'); ?>"><?php echo esc_html($discountPer > 0 ? $discountPer : '0');?>% off</span><span class="view"><i class="fa-solid fa-eye"></i>&nbsp;<?php echo esc_html($reels_view[0]);?>k</span>
									</div>
									<div class="videoDiv reel-video-container" data-video-src="<?php echo esc_url($videoData[0]); ?>" data-lazy="true" style="max-width:200px;max-height:270px;">
										<div class="reel-video-placeholder">
											<div class="reel-video-spinner">
												<div class="spinner"></div>
											</div>
										</div>								
									</div>								
								</div>
							</div>
						</div>
						<?php 
									$i++;
									}
								endwhile;
							endif;
							wp_reset_postdata();
						?>					
					</div>	
				</div>	
			<?php
		}
	}
}

if ( ! function_exists( 'broodle_sr_settings_page' ) ) {
	function broodle_sr_settings_page() {
		add_submenu_page(
			'edit.php?post_type=broodle_sr_reels',
			'Shoppable Reels Settings',
			'Settings',
			'manage_options',
			'broodle-sr-reels-settings',
			'broodle_sr_settings_page_callback'
		);
	}
	add_action('admin_menu', 'broodle_sr_settings_page');

	function broodle_sr_settings_page_callback() {

		$slider_popup_design = get_option('slider_popup_design');
		$related_product = get_option('related_product',0);
		$product_page_reels = get_option('product_page_reels', 0);
		$product_page_video = get_option('product_page_video', 0);

		?>

		<div class="wrap broodle-sr-setting">
			<h1>Shoppable Reels Settings</h1>
			<p class="broodle-sr-subtitle">Configure how reels display on your store.</p>
			<form method="post" action="options.php">
				<?php
				settings_fields('broodle_sr_reels_settings_group');
				do_settings_sections('broodle-reels-settings');?>
				<table class="form-table">
			        <tr valign="top">
						<th scope="row">Shortcodes</th>
						<td>
							<div class="broodle-sr-shortcode-card" style="margin-bottom:8px;">
								<code>[broodle_reel_slider]</code>
								<span>Display all published reels</span>
							</div><br>
							<div class="broodle-sr-shortcode-card" style="margin-bottom:8px;">
								<code>[broodle_reel_slider 1,2,3]</code>
								<span>Specific reels by post ID</span>
							</div><br>
							<div class="broodle-sr-shortcode-card" style="margin-bottom:8px;">
								<code>[broodle_reel_category slug="my-category"]</code>
								<span>Reels by category slug</span>
							</div><br>
							<div class="broodle-sr-shortcode-card">
								<code>[broodle_reel_category category="5,8" limit="6"]</code>
								<span>Reels by category ID with limit</span>
							</div>
						</td>
			        </tr>
			        
					<tr valign="top">
						<th scope="row">Slider Popup Design</th>
						<td class="checkbox">
							<select name="slider_popup_design" class="slider_popup_design" id="slider_popup_design">
								<option value="reels_product" <?php echo ($slider_popup_design == 'reels_product') ? 'selected' : '';?>>Reels And Product</option>
								<option value="reels" <?php echo ($slider_popup_design == 'reels') ? 'selected' : '';?>>Reels</option>
								<option value="product" <?php echo ($slider_popup_design == 'product') ? 'selected' : '';?>>Product</option>
							</select>
						</td>
			        </tr>	
			        <tr valign="top">
						<th scope="row">Related Product</th>
						<td class="checkbox">
							<input type="checkbox" name="related_product" id="related_product" class="related_product" value="1" <?php checked($related_product, 1); ?>/>
							<label for="related_product">Hide/Show</label>
						</td>
			        </tr>
					<tr valign="top">
						<th scope="row">Single Product Page Reels</th>
						<td class="checkbox">
							<input type="checkbox" name="product_page_reels" id="product_page_reels" class="product_page_reels" value="1"  <?php checked($product_page_reels, 1); ?>/>
							<label for="product_page_reels">Hide/Show</label>
						</td>
			        </tr>
					<tr valign="top">
						<th scope="row">Loading Background Image</th>
						<td>
							<?php $loading_bg_image = get_option( 'broodle_sr_loading_bg_image', '' ); ?>
							<input type="text" name="broodle_sr_loading_bg_image" id="broodle_sr_loading_bg_image" value="<?php echo esc_url( $loading_bg_image ); ?>" class="regular-text" placeholder="Enter image URL or use media picker" />
							<button type="button" class="button broodle-sr-upload-bg" id="broodle_sr_upload_bg_btn">Select Image</button>
							<?php if ( ! empty( $loading_bg_image ) ) : ?>
								<br><img src="<?php echo esc_url( $loading_bg_image ); ?>" style="max-width:300px;max-height:150px;margin-top:10px;border-radius:6px;" id="broodle_sr_bg_preview" />
							<?php else : ?>
								<br><img src="" style="max-width:300px;max-height:150px;margin-top:10px;border-radius:6px;display:none;" id="broodle_sr_bg_preview" />
							<?php endif; ?>
							<p class="description">This image is shown as the background while reel videos are loading. Leave empty for a plain dark background.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Google Font</th>
						<td>
							<?php $broodle_sr_font = get_option( 'broodle_sr_google_font', '' ); ?>
							<select name="broodle_sr_google_font" id="broodle_sr_google_font" class="regular-text">
								<option value="">— Default (inherit from theme) —</option>
								<?php
								$google_fonts = array(
									'Inter', 'Poppins', 'Roboto', 'Open Sans', 'Montserrat', 'Lato', 'Nunito',
									'Raleway', 'Oswald', 'Playfair Display', 'Merriweather', 'Source Sans 3',
									'DM Sans', 'Outfit', 'Manrope', 'Space Grotesk', 'Plus Jakarta Sans',
									'Figtree', 'Sora', 'Urbanist', 'Lexend', 'Rubik', 'Work Sans',
									'Quicksand', 'Mulish', 'Barlow', 'Josefin Sans', 'Cabin', 'Karla',
									'Libre Franklin', 'Noto Sans', 'PT Sans', 'Ubuntu', 'Titillium Web',
								);
								sort( $google_fonts );
								foreach ( $google_fonts as $font ) {
									$selected = ( $broodle_sr_font === $font ) ? ' selected' : '';
									echo '<option value="' . esc_attr( $font ) . '"' . $selected . '>' . esc_html( $font ) . '</option>';
								}
								?>
							</select>
							<p class="description">Choose a Google Font for product titles, prices, and buttons displayed by this plugin.</p>
						</td>
					</tr>
			    </table>
				<?php
				submit_button();
				?>
			</form>
			<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#broodle_sr_upload_bg_btn').on('click', function(e) {
					e.preventDefault();
					var mediaUploader = wp.media({
						title: 'Select Loading Background Image',
						button: { text: 'Use This Image' },
						multiple: false,
						library: { type: 'image' }
					});
					mediaUploader.on('select', function() {
						var attachment = mediaUploader.state().get('selection').first().toJSON();
						$('#broodle_sr_loading_bg_image').val(attachment.url);
						$('#broodle_sr_bg_preview').attr('src', attachment.url).show();
					});
					mediaUploader.open();
				});
			});
			</script>
			<?php
			wp_enqueue_media();
		?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'broodle_sr_register_reels_settings' ) ) {
    function broodle_sr_register_reels_settings() {
        register_setting( 'broodle_sr_reels_settings_group', 'slider_popup_design', 'sanitize_text_field' );
        register_setting( 'broodle_sr_reels_settings_group', 'related_product', 'absint' );
        register_setting( 'broodle_sr_reels_settings_group', 'product_page_reels', 'absint' );
        register_setting( 'broodle_sr_reels_settings_group', 'product_page_video', 'absint' );
        register_setting( 'broodle_sr_reels_settings_group', 'broodle_sr_loading_bg_image', 'esc_url_raw' );
        register_setting( 'broodle_sr_reels_settings_group', 'broodle_sr_google_font', 'sanitize_text_field' );
    }
    add_action('admin_init', 'broodle_sr_register_reels_settings');
}
