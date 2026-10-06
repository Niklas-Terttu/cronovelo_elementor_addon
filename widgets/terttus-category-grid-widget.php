<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
class Terttus_Category_Grid_Widget extends \Elementor\Widget_Base {
  public function get_name(){return 'terttus_category_grid';}
  public function get_title(){return 'Terttus Kategorier';}
  public function get_icon(){return 'eicon-gallery-grid';}
  public function get_categories(){return ['terttus-widgets'];}
  private function options(){ $o=[]; if(!taxonomy_exists('product_cat')) return $o; $terms=get_terms(['taxonomy'=>'product_cat','hide_empty'=>false]); if(is_wp_error($terms)) return $o; foreach($terms as $t){ $o[$t->term_id]=$t->name; } return $o; }
  protected function register_controls(){
    $this->start_controls_section('content',['label'=>'Indhold']);
    $this->add_control('categories',['label'=>'Kategorier','type'=>\Elementor\Controls_Manager::SELECT2,'options'=>$this->options(),'multiple'=>true,'label_block'=>true]);
    $this->add_control('limit',['label'=>'Antal','type'=>\Elementor\Controls_Manager::NUMBER,'default'=>6,'min'=>1,'max'=>24]);
    $this->add_control('show_count',['label'=>'Vis antal varer','type'=>\Elementor\Controls_Manager::SWITCHER,'default'=>'yes','return_value'=>'yes']);
    $this->end_controls_section();
  }
  protected function render(){ $s=$this->get_settings_for_display(); if(!taxonomy_exists('product_cat')){ echo '<p>WooCommerce er ikke aktiv.</p>'; return; }
    $args=['taxonomy'=>'product_cat','hide_empty'=>false,'number'=>intval($s['limit'])]; if(!empty($s['categories'])) $args['include']=array_map('intval',$s['categories']);
    $terms=get_terms($args); if(is_wp_error($terms)) return; ?>
    <div class="terttus-cat-grid"><?php foreach($terms as $term): $thumb=get_term_meta($term->term_id,'thumbnail_id',true); $img=$thumb?wp_get_attachment_image_url($thumb,'medium'):wc_placeholder_img_src(); ?>
      <a class="terttus-cat-card" href="<?php echo esc_url(get_term_link($term)); ?>"><img src="<?php echo esc_url($img); ?>" alt=""><div><strong><?php echo esc_html($term->name); ?></strong><?php if($s['show_count']==='yes'): ?><span><?php echo intval($term->count); ?> varer</span><?php endif; ?></div></a>
    <?php endforeach; ?></div>
    <style>.terttus-cat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.terttus-cat-card{display:flex;align-items:center;gap:16px;background:#fff;border:1px solid #e0e6ea;border-radius:14px;padding:16px;text-decoration:none;color:#10212d;transition:.18s ease}.terttus-cat-card:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(0,0,0,.06)}.terttus-cat-card img{width:76px;height:76px;object-fit:contain}.terttus-cat-card strong{display:block;font-size:18px}.terttus-cat-card span{display:block;color:#6a7781;margin-top:3px}@media(max-width:900px){.terttus-cat-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:520px){.terttus-cat-grid{grid-template-columns:1fr}}</style>
  <?php }
}
\Elementor\Plugin::instance()->widgets_manager->register(new Terttus_Category_Grid_Widget());