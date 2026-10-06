<?php
/**
 * Plugin Name: Cronovelo Addons
 * Description: Elementor widgets for Cronovelo and Terttus, including WooCommerce storefront components.
 * Version:     1.2.7
 * Author:      Cronovelo / Terttus
 * Text Domain: cronovelo-addons
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'CRONOVELO_ADDONS_VERSION', '1.2.7' );

// Lightweight updater: reads metadata from a raw JSON file in this repository.
// This intentionally avoids Plugin Update Checker and the GitHub API.
define( 'CRONOVELO_ADDONS_UPDATE_METADATA', 'https://raw.githubusercontent.com/Niklas-Terttu/cronovelo_elementor_addon/main/update.json' );

function cronovelo_addons_get_update_metadata() {
    $response = wp_remote_get( CRONOVELO_ADDONS_UPDATE_METADATA, [
        'timeout' => 10,
        'headers' => [ 'Accept' => 'application/json' ],
    ] );
    if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
        return null;
    }
    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    return is_array( $data ) ? $data : null;
}

function cronovelo_addons_check_for_update( $transient ) {
    if ( empty( $transient->checked ) ) { return $transient; }
    $data = cronovelo_addons_get_update_metadata();
    if ( empty( $data['version'] ) || empty( $data['download_url'] ) ) { return $transient; }

    if ( version_compare( CRONOVELO_ADDONS_VERSION, $data['version'], '<' ) ) {
        $plugin_file = plugin_basename( __FILE__ );
        $transient->response[ $plugin_file ] = (object) [
            'slug'        => 'cronovelo-addons',
            'plugin'      => $plugin_file,
            'new_version' => $data['version'],
            'url'         => $data['details_url'] ?? 'https://github.com/Niklas-Terttu/cronovelo_elementor_addon',
            'package'     => $data['download_url'],
            'tested'      => $data['tested'] ?? '',
            'requires_php'=> $data['requires_php'] ?? '',
        ];
    }
    return $transient;
}
add_filter( 'pre_set_site_transient_update_plugins', 'cronovelo_addons_check_for_update' );

function cronovelo_addons_plugin_information( $result, $action, $args ) {
    if ( 'plugin_information' !== $action || empty( $args->slug ) || 'cronovelo-addons' !== $args->slug ) {
        return $result;
    }
    $data = cronovelo_addons_get_update_metadata();
    if ( empty( $data['version'] ) ) { return $result; }
    return (object) [
        'name'          => 'Cronovelo Addons',
        'slug'          => 'cronovelo-addons',
        'version'       => $data['version'],
        'author'        => '<a href="https://github.com/Niklas-Terttu">Cronovelo / Terttus</a>',
        'homepage'      => $data['details_url'] ?? 'https://github.com/Niklas-Terttu/cronovelo_elementor_addon',
        'download_link' => $data['download_url'] ?? '',
        'sections'      => [ 'description' => $data['description'] ?? 'Elementor widgets for Cronovelo and Terttus.' ],
    ];
}
add_filter( 'plugins_api', 'cronovelo_addons_plugin_information', 20, 3 );

function cronovelo_elementor_init() {
    if ( ! did_action( 'elementor/loaded' ) ) { return; }

    \Elementor\Plugin::$instance->elements_manager->add_category(
        'cronovelo-widgets',
        [ 'title' => esc_html__( 'Cronovelo Elementer', 'cronovelo-addons' ), 'icon' => 'fa fa-clock-o' ]
    );
    \Elementor\Plugin::$instance->elements_manager->add_category(
        'terttus-widgets',
        [ 'title' => esc_html__( 'Terttus Shop Elementer', 'cronovelo-addons' ), 'icon' => 'eicon-woocommerce' ]
    );

    $widgets_dir = __DIR__ . '/widgets/';
    if ( is_dir( $widgets_dir ) ) {
        foreach ( glob( $widgets_dir . '*.php' ) as $file ) { require_once $file; }
    }
}
add_action( 'elementor/init', 'cronovelo_elementor_init' );