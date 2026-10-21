<?php
/**
 * Plugin Name: Cronovelo Addons
 * Description: Elementor widgets for Cronovelo and Terttus, including WooCommerce storefront components.
 * Version:     1.4.3
 * Author:      Cronovelo / Terttus
 * Text Domain: cronovelo-addons
 * Requires PHP: 7.4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'CRONOVELO_ADDONS_VERSION', '1.4.3' );
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

function cronovelo_addons_get_update_metadata($fresh = false) {
    $url = CRONOVELO_ADDONS_UPDATE_METADATA;
    if ($fresh) $url = add_query_arg('refresh', time(), $url);
    $r = wp_remote_get($url, ['timeout' => 15, 'headers' => ['Accept' => 'application/json', 'Cache-Control' => 'no-cache']]);
    if (is_wp_error($r)) return new WP_Error('cronovelo_network', $r->get_error_message());
    if (200 !== wp_remote_retrieve_response_code($r)) return new WP_Error('cronovelo_http', 'GitHub HTTP ' . wp_remote_retrieve_response_code($r));
    $d = json_decode(wp_remote_retrieve_body($r), true);
    if (!is_array($d) || empty($d['version']) || empty($d['download_url'])) return new WP_Error('cronovelo_metadata', 'Ugyldige versionsdata fra GitHub');
    return $d;
}
function cronovelo_addons_check_for_update($t) {
    if (!is_object($t) || empty($t->checked)) return $t;
    $d = cronovelo_addons_get_update_metadata();
    if (is_wp_error($d)) return $t;
    $p = plugin_basename(__FILE__);
    if (version_compare(CRONOVELO_ADDONS_VERSION, $d['version'], '<')) {
        $t->response[$p] = (object) [
            'slug' => 'cronovelo-addons', 'plugin' => $p, 'new_version' => $d['version'],
            'url' => $d['details_url'] ?? '', 'package' => $d['download_url'],
            'tested' => $d['tested'] ?? '', 'requires_php' => $d['requires_php'] ?? '',
        ];
        unset($t->no_update[$p]);
    } else {
        unset($t->response[$p]);
    }
    return $t;
}
add_filter('pre_set_site_transient_update_plugins', 'cronovelo_addons_check_for_update');
function cronovelo_addons_plugin_information( $r, $a, $x ) {
    if ( 'plugin_information' !== $a || empty( $x->slug ) || 'cronovelo-addons' !== $x->slug ) { return $r; }
    $d = cronovelo_addons_get_update_metadata();
    if ( is_wp_error($d) || empty( $d['version'] ) ) { return $r; }
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
 $d = cronovelo_addons_get_update_metadata(true);
 if (is_wp_error($d)) {
    set_transient('cronovelo_update_result_' . get_current_user_id(), ['error' => $d->get_error_message()], 120);
 } else {
    delete_site_transient('update_plugins');
    wp_update_plugins();
    set_transient('cronovelo_update_result_' . get_current_user_id(), ['latest' => sanitize_text_field($d['version'])], 120);
 }
 wp_safe_redirect(admin_url('plugins.php?cronovelo_update_checked=1'));
 exit;
}
add_action('admin_post_cronovelo_addons_check_updates', 'cronovelo_addons_manual_update_check');

function cronovelo_addons_update_check_notice() {
 if (!current_user_can('update_plugins') || !isset($_GET['cronovelo_update_checked'])) return;
 $key = 'cronovelo_update_result_' . get_current_user_id();
 $result = get_transient($key);
 delete_transient($key);
 if (!$result) return;
 if (isset($result['error'])) {
    $message = 'Cronovelo Addons: Opdateringstjek fejlede: ' . $result['error'];
    $type = 'error';
 } else {
    $latest = $result['latest'];
    $available = version_compare(CRONOVELO_ADDONS_VERSION, $latest, '<');
    $message = 'Cronovelo Addons: Installeret ' . CRONOVELO_ADDONS_VERSION . ' – GitHub ' . $latest . '. ' . ($available ? 'Ny version fundet. Opdater pluginet nedenfor.' : 'Du har den nyeste version.');
    $type = $available ? 'warning' : 'success';
 }
 echo '<div class="notice notice-' . esc_attr($type) . ' is-dismissible"><p>' . esc_html($message) . '</p></div>';
}
add_action('admin_notices', 'cronovelo_addons_update_check_notice');
