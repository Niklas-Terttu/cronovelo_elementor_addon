<?php
/**
 * Plugin Name: Cronovelo Addons
 * Description: Elementor widgets for Cronovelo and Terttus, including WooCommerce storefront components.
 * Version:     1.3.0
 * Author:      Cronovelo / Terttus
 * Text Domain: cronovelo-addons
 */
if(!defined('ABSPATH'))exit;
define('CRONOVELO_ADDONS_VERSION','1.3.0');
define('CRONOVELO_ADDONS_UPDATE_METADATA','https://raw.githubusercontent.com/Niklas-Terttu/cronovelo_elementor_addon/main/update.json');
function cronovelo_addons_assets(){wp_register_style('terttus-design-system',plugins_url('assets/css/terttus-design-system.css',__FILE__),[],CRONOVELO_ADDONS_VERSION);wp_enqueue_style('terttus-design-system');}
add_action('wp_enqueue_scripts','cronovelo_addons_assets');
function cronovelo_addons_get_update_metadata(){$r=wp_remote_get(CRONOVELO_ADDONS_UPDATE_METADATA,['timeout'=>10,'headers'=>['Accept'=>'application/json']]);if(is_wp_error($r)||200!==wp_remote_retrieve_response_code($r))return null;$d=json_decode(wp_remote_retrieve_body($r),true);return is_array($d)?$d:null;}
function cronovelo_addons_check_for_update($t){if(empty($t->checked))return $t;$d=cronovelo_addons_get_update_metadata();if(empty($d['version'])||empty($d['download_url']))return $t;if(version_compare(CRONOVELO_ADDONS_VERSION,$d['version'],'<')){$p=plugin_basename(__FILE__);$t->response[$p]=(object)['slug'=>'cronovelo-addons','plugin'=>$p,'new_version'=>$d['version'],'url'=>$d['details_url']??'https://github.com/Niklas-Terttu/cronovelo_elementor_addon','package'=>$d['download_url'],'tested'=>$d['tested']??'','requires_php'=>$d['requires_php']??''];}return $t;} add_filter('pre_set_site_transient_update_plugins','cronovelo_addons_check_for_update');
function cronovelo_addons_plugin_information($r,$a,$x){if('plugin_information'!==$a||empty($x->slug)||'cronovelo-addons'!==$x->slug)return $r;$d=cronovelo_addons_get_update_metadata();if(empty($d['version']))return $r;return(object)['name'=>'Cronovelo Addons','slug'=>'cronovelo-addons','version'=>$d['version'],'author'=>'Cronovelo / Terttus','homepage'=>$d['details_url']??'','download_link'=>$d['download_url']??'','sections'=>['description'=>$d['description']??'Elementor widgets for Cronovelo and Terttus.']];} add_filter('plugins_api','cronovelo_addons_plugin_information',20,3);
function cronovelo_elementor_init(){if(!did_action('elementor/loaded'))return;\Elementor\Plugin::$instance->elements_manager->add_category('cronovelo-widgets',['title'=>esc_html__('Cronovelo Elementer','cronovelo-addons'),'icon'=>'fa fa-clock-o']);\Elementor\Plugin::$instance->elements_manager->add_category('terttus-widgets',['title'=>esc_html__('Terttus Shop Elementer','cronovelo-addons'),'icon'=>'eicon-woocommerce']);$d=__DIR__.'/widgets/';if(is_dir($d))foreach(glob($d.'*.php') as $f)require_once $f;} add_action('elementor/init','cronovelo_elementor_init');
