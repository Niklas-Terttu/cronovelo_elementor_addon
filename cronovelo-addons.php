<?php
/**
 * Plugin Name: Cronovelo Addons
 * Description: Elementor widgets for Cronovelo and Terttus, including WooCommerce storefront components.
 * Version:     1.1.0
 * Author:      Cronovelo / Terttus
 * Text Domain: cronovelo-addons
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! defined( 'CRONOVELO_ADDONS_UPDATE_URL' ) ) {
    define( 'CRONOVELO_ADDONS_UPDATE_URL', 'https://github.com/Niklas-Terttu/cronovelo_elementor_addon/' );
}
if ( ! defined( 'CRONOVELO_ADDONS_UPDATE_BRANCH' ) ) {
    define( 'CRONOVELO_ADDONS_UPDATE_BRANCH', 'main' );
}

function cronovelo_addons_init_update_checker() {
    if ( empty( CRONOVELO_ADDONS_UPDATE_URL ) ) { return; }
    $autoload_file = __DIR__ . '/vendor/autoload.php';
    if ( file_exists( $autoload_file ) ) { require_once $autoload_file; }
    if ( ! class_exists( '\\YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
        foreach ( [
            __DIR__ . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php',
            __DIR__ . '/vendor/plugin-update-checker/plugin-update-checker.php',
            __DIR__ . '/vendor/plugin-update-checker-5.7/plugin-update-checker.php',
        ] as $puc_file ) {
            if ( file_exists( $puc_file ) ) { require_once $puc_file; break; }
        }
    }
    if ( ! class_exists( '\\YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) { return; }
    $update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
        CRONOVELO_ADDONS_UPDATE_URL,
        __FILE__,
        'cronovelo-addons'
    );
    if ( ! empty( CRONOVELO_ADDONS_UPDATE_BRANCH ) ) { $update_checker->setBranch( CRONOVELO_ADDONS_UPDATE_BRANCH ); }
}
add_action( 'plugins_loaded', 'cronovelo_addons_init_update_checker' );

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