<?php
/**
 * Plugin Name: Cronovelo Addons
 * Description: Elementor widgets for Cronovelo and Terttus, including WooCommerce storefront components.
 * Version:     1.3.2
 * Author:      Cronovelo / Terttus
 * Text Domain: cronovelo-addons
 * Requires PHP: 7.4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'CRONOVELO_ADDONS_VERSION', '1.3.2' );
define( 'CRONOVELO_ADDONS_UPDATE_METADATA', 'https://raw.githubusercontent.com/Niklas-Terttu/cronovelo_elementor_addon/main/update.json' );

function cronovelo_addons_assets() {
    wp_register_style( 'terttus-design-system', plugins_url( 'assets/css/terttus-design-system.css', __FILE__ ), [], CRONOVELO_ADDONS_VERSION );
    wp_enqueue_style( 'terttus-design-system' );
}
add_action( 'wp_enqueue_scripts', 'cronovelo_addons_assets' );

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

function cronovelo_addons_register_widgets( $widgets_manager ) {
    $files = [
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
