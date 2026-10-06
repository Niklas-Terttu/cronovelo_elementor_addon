<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
class Terttus_Product_Search_Widget extends \Elementor\Widget_Base {
  public function get_name(){return 'terttus_product_search';}
  public function get_title(){return 'Terttus Produktsøgning';}
  public function get_icon(){return 'eicon-search';}
  public function get_categories(){return ['terttus-widgets'];}
  protected function register_controls(){
    $this->start_controls_section('content',['label'=>'Indhold']);
    $this->add_control('placeholder',['label'=>'Placeholder','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Søg efter produkter...']);
    $this->add_control('button',['label'=>'Knaptekst','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Søg']);
    $this->end_controls_section();
  }
  protected function render(){ $s=$this->get_settings_for_display(); ?>
    <form class="terttus-product-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"><input type="search" name="s" placeholder="<?php echo esc_attr($s['placeholder']); ?>"><input type="hidden" name="post_type" value="product"><button type="submit"><?php echo esc_html($s['button']); ?></button></form>
    <style>.terttus-product-search{display:flex;width:100%;border:1px solid #d8e0e5;border-radius:12px;overflow:hidden;background:#fff}.terttus-product-search input{flex:1;border:0!important;box-shadow:none!important;padding:14px 16px;font-size:17px;min-width:0}.terttus-product-search button{border:0;background:#148b93;color:#fff;font-weight:700;padding:0 22px;cursor:pointer}</style>
  <?php }
}
\Elementor\Plugin::instance()->widgets_manager->register(new Terttus_Product_Search_Widget());