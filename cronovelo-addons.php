<?php
/**
 * Plugin Name: Cronovelo Addons
 * Description: Elementor widgets for Cronovelo and Terttus, including WooCommerce storefront components.
 * Version:     1.4.1
 * Author:      Cronovelo / Terttus
 * Text Domain: cronovelo-addons
 * Requires PHP: 7.4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'CRONOVELO_ADDONS_VERSION', '1.4.1' );
define( 'CRONOVELO_ADDONS_UPDATE_METADATA', 'https://raw.githubusercontent.com/Niklas-Terttu/cronovelo_elementor_addon/main/update.json' );

function cronovelo_addons_assets() {
    wp_register_style( 'terttus-design-system', plugins_url( 'assets/css/terttus-design-system.css', __FILE__ ), [], CRONOVELO_ADDONS_VERSION );
    wp_enqueue_style( 'terttus-design-system' );
}
add_action( 'wp_enqueue_scripts', 'cronovelo_addons_assets' );
function cronovelo_video_assets(){
 wp_enqueue_style('cronovelo-video-player',plugins_url('assets/css/cronovelo-video-player.css',__FILE__),[],CRONOVELO_ADDONS_VERSION);
 wp_enqueue_script('cronovelo-video-player',plugins_url('assets/js/cronovelo-video-player.js',__FILE__),[],CRONOVELO_ADDONS_VERSION,true);
}
add_action('wp_enqueue_scripts','cronovelo_video_assets');
add_action('elementor/editor/after_enqueue_scripts','cronovelo_video_assets');

function cronovelo_addons_get_update_metadata() {
    $r = wp_remote_get( CRONOVELO_ADDONS_UPDATE_METADATA, [ 'timeout' => 10, 'headers' => [ 'Accept' => 'application/json' ] ] );
    if ( is_wp_error( $r ) || 200 !== wp_remote_retrieve_response_code( $r ) ) { return null; }
    $d = json_decode( wp_remote_retrieve_body( $r ), true );
    return is_array( $d ) ? $d : null;
}
function cronovelo_addons_check_for_update( $t ) {
    if ( empty( $t->checked ) ) { return $t; }
    $d = cronovelo_addons_get_update_metadata();
    if ( empty( $d['version'] ) || empty( $d['download_url'] ) ) { return $t; }
    if ( version_compare( CRONOVELO_ADDONS_VERSION, $d['version'], '<' ) ) {
        $p = plugin_basename( __FILE__ );
        $t->response[$p] = (object) [
            'slug' => 'cronovelo-addons', 'plugin' => $p, 'new_version' => $d['version'],
            'url' => $d['details_url'] ?? '', 'package' => $d['download_url'],
            'tested' => $d['tested'] ?? '', 'requires_php' => $d['requires_php'] ?? '',
        ];
    }
    return $t;
}
add_filter( 'pre_set_site_transient_update_plugins', 'cronovelo_addons_check_for_update' );

function cronovelo_addons_plugin_information( $r, $a, $x ) {
    if ( 'plugin_information' !== $a || empty( $x->slug ) || 'cronovelo-addons' !== $x->slug ) { return $r; }
    $d = cronovelo_addons_get_update_metadata();
    if ( empty( $d['version'] ) ) { return $r; }
    return (object) [
        'name' => 'Cronovelo Addons', 'slug' => 'cronovelo-addons', 'version' => $d['version'],
        'author' => 'Cronovelo / Terttus', 'homepage' => $d['details_url'] ?? '',
        'download_link' => $d['download_url'] ?? '',
        'sections' => [ 'description' => $d['description'] ?? 'Elementor widgets for Cronovelo and Terttus.' ],
    ];
}
add_filter( 'plugins_api', 'cronovelo_addons_plugin_information', 20, 3 );

function cronovelo_addons_admin_notice() {
    if ( current_user_can( 'activate_plugins' ) && ! did_action( 'elementor/loaded' ) ) {
        echo '<div class="notice notice-warning"><p><strong>Cronovelo Addons:</strong> Elementor skal være aktivt, før widgets kan indlæses.</p></div>';
    }
}
add_action( 'admin_notices', 'cronovelo_addons_admin_notice' );

function cronovelo_addons_register_categories( $elements_manager ) {
    $elements_manager->add_category( 'cronovelo-widgets', [ 'title' => esc_html__( 'Cronovelo Elementer', 'cronovelo-addons' ), 'icon' => 'fa fa-clock-o' ] );
    $elements_manager->add_category( 'terttus-widgets', [ 'title' => esc_html__( 'Terttus Shop Elementer', 'cronovelo-addons' ), 'icon' => 'eicon-woocommerce' ] );
}
add_action( 'elementor/elements/categories_registered', 'cronovelo_addons_register_categories' );


/**
 * Load original Cronovelo widgets without changing their Elementor IDs.
 * Legacy files self-register on elementor/widgets/register.
 * During migration, the older plugin remains the provider if it is active.
 */
function cronovelo_addons_load_legacy_widgets() {
    $legacy_active = false;
    $active_plugins = (array) get_option('active_plugins', []);
    if (is_multisite()) {
        $active_plugins = array_merge($active_plugins, array_keys((array) get_site_option('active_sitewide_plugins', [])));
    }
    foreach ($active_plugins as $plugin_file) {
        if ($plugin_file === plugin_basename(__FILE__)) continue;
        if (strtolower(basename($plugin_file)) === 'cronovelo-addons.php') {
            $legacy_active = true;
            break;
        }
    }
    if ($legacy_active) return;
    $files = [
        'cronovelo-offer-grid-widget.php',
        'cronovelo-hero-slider-widget.php',
        'cronovelo-about-split-widget.php',
        'cronovelo-cta-banner-widget.php',
        'cronovelo-product-card-widget.php',
        'cronovelo-event-promo-widget.php',
        'cronovelo-services-grid-widget.php',
    ];
    foreach ($files as $file) {
        $path = __DIR__ . '/widgets/' . $file;
        if (is_readable($path)) require_once $path;
    }
}
add_action('elementor/init', 'cronovelo_addons_load_legacy_widgets', 20);

function cronovelo_addons_register_widgets( $widgets_manager ) {
    $files = [
        'cronovelo-video-player.php',
        'terttus-header-widget.php',
        'terttus-hero-widget.php',
        'terttus-product-grid-widget.php',
        'terttus-trust-bar-widget.php',
    ];
    foreach ( $files as $file ) {
        $path = __DIR__ . '/widgets/' . $file;
        if ( is_readable( $path ) ) { require_once $path; }
    }
}
add_action( 'elementor/widgets/register', 'cronovelo_addons_register_widgets' );

/**
 * Manual update check from Plugins screen.
 * Uses the standard WordPress plugin-update transient and a fresh GitHub metadata request.
 */
function cronovelo_addons_update_action_link($links, $file) {
 if ($file === plugin_basename(__FILE__) && current_user_can('update_plugins')) {
  $url = wp_nonce_url(admin_url('admin-post.php?action=cronovelo_addons_check_updates'), 'cronovelo_addons_check_updates');
  $links[] = '<a href="' . esc_url($url) . '">Søg efter opdateringer</a>';
 }
 return $links;
}
add_filter('plugin_action_links', 'cronovelo_addons_update_action_link', 10, 2);

function cronovelo_addons_manual_update_check() {
 if (!current_user_can('update_plugins')) wp_die('Ingen adgang.');
 check_admin_referer('cronovelo_addons_check_updates');
 delete_site_transient('update_plugins');
 $metadata = cronovelo_addons_get_update_metadata();
 if (!$metadata || empty($metadata['version'])) {
  $status = 'error';
 } else {
  wp_update_plugins();
  $status = version_compare(CRONOVELO_ADDONS_VERSION, $metadata['version'], '<') ? 'available' : 'current';
 }
 wp_safe_redirect(add_query_arg('cronovelo_update_status', $status, admin_url('plugins.php')));
 exit;
}
add_action('admin_post_cronovelo_addons_check_updates', 'cronovelo_addons_manual_update_check');

function cronovelo_addons_update_check_notice() {
 if (!current_user_can('update_plugins') || !isset($_GET['cronovelo_update_status'])) return;
 $status = sanitize_key(wp_unslash($_GET['cronovelo_update_status']));
 $messages = [
  'available' => 'Cronovelo Addons: Der er en ny opdatering klar. Opdater pluginet nedenfor.',
  'current' => 'Cronovelo Addons: Du har allerede den nyeste version.',
  'error' => 'Cronovelo Addons: Kunne ikke hente versionsinformation fra GitHub. Prøv igen senere.'
 ];
 if (!isset($messages[$status])) return;
 echo '<div class="notice notice-' . ($status === 'error' ? 'error' : 'success') . ' is-dismissible"><p>' . esc_html($messages[$status]) . '</p></div>';
}
add_action('admin_notices', 'cronovelo_addons_update_check_notice');
