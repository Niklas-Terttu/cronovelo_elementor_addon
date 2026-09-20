<?php
/**
 * Plugin Name: Cronovelo Addons
 * Description: Skræddersyede avancerede Elementor widgets fra Cronovelo.
 * Version:     1.0.11
 * Author:      Cronovelo
 * Text Domain: cronovelo-addons
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Afvis direkte adgang
}

// Sæt disse i wp-config.php for at aktivere auto-update via fx GitHub releases.
if ( ! defined( 'CRONOVELO_ADDONS_UPDATE_URL' ) ) {
    define( 'CRONOVELO_ADDONS_UPDATE_URL', 'https://github.com/Niklas-Terttu/cronovelo_elementor_addon/' );
}

if ( ! defined( 'CRONOVELO_ADDONS_UPDATE_BRANCH' ) ) {
    define( 'CRONOVELO_ADDONS_UPDATE_BRANCH', 'main' );
}

if ( ! defined( 'CRONOVELO_ADDONS_UPDATE_TOKEN' ) ) {
    define( 'CRONOVELO_ADDONS_UPDATE_TOKEN', '' );
}

function cronovelo_addons_init_update_checker() {
    if ( empty( CRONOVELO_ADDONS_UPDATE_URL ) ) {
        return;
    }

    $autoload_file = __DIR__ . '/vendor/autoload.php';
    if ( file_exists( $autoload_file ) ) {
        require_once $autoload_file;
    }

    // Fallback hvis autoload ikke er tilgængelig, eller pakken er bundlet manuelt.
    if ( ! class_exists( '\\YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
        $possible_puc_files = [
            __DIR__ . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php',
            __DIR__ . '/vendor/plugin-update-checker/plugin-update-checker.php',
            __DIR__ . '/vendor/plugin-update-checker-5.7/plugin-update-checker.php',
        ];

        foreach ( $possible_puc_files as $puc_file ) {
            if ( file_exists( $puc_file ) ) {
                require_once $puc_file;
                break;
            }
        }
    }

    if ( ! class_exists( '\\YahnisElsts\\PluginUpdateChecker\\v5\\PucFactory' ) ) {
        return;
    }

    $update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
        CRONOVELO_ADDONS_UPDATE_URL,
        __FILE__,
        'cronovelo-addons'
    );

    if ( ! empty( CRONOVELO_ADDONS_UPDATE_BRANCH ) ) {
        $update_checker->setBranch( CRONOVELO_ADDONS_UPDATE_BRANCH );
    }

    if ( ! empty( CRONOVELO_ADDONS_UPDATE_TOKEN ) ) {
        $update_checker->setAuthentication( CRONOVELO_ADDONS_UPDATE_TOKEN );
    }
}
add_action( 'plugins_loaded', 'cronovelo_addons_init_update_checker' );

function cronovelo_elementor_init() {
    // Tjek om Elementor overhovedet er installeret og aktivt
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Registrer Cronovelo widget-kategori i Elementor
    \Elementor\Plugin::$instance->elements_manager->add_category(
        'cronovelo-widgets',
        [
            'title' => esc_html__( 'Cronovelo Elementer', 'cronovelo-addons' ),
            'icon'  => 'fa fa-clock-o',
        ]
    );

    // AUTOMATISK INDLÆSNING:
    // Looper igennem alle .php filer i /widgets/ mappen og inkluderer dem
    $widgets_dir = __DIR__ . '/widgets/';
    if ( is_dir( $widgets_dir ) ) {
        foreach ( glob( $widgets_dir . '*.php' ) as $file ) {
            require_once $file;
        }
    }
}
add_action( 'elementor/init', 'cronovelo_elementor_init' );