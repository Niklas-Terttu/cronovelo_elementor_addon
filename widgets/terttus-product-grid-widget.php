<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
class Terttus_Product_Grid_Widget extends \Elementor\Widget_Base {
  public function get_name(){return 'terttus_product_grid';}
  public function get_title(){return 'Terttus Produkt Grid';}
  public function get_icon(){return 'eicon-products';}
  public function get_categories(){return ['terttus-widgets'];}
  private function cat_options(){ $o=[]; if(!taxonomy_exists('product_cat')) return $o; $terms=get_terms(['taxonomy'=>'product_cat','hide_empty'=>false]); if(is_wp_error($terms))return $o; foreach($terms as $t){$o[$t->term_id]=$t->name;} return $o; }
  protected function register_controls(){
    $this->start_controls_section('query',['label'=>'Produkter']);
    $this->add_control('source',['label'=>'Vis','type'=>\Elementor\Controls_Manager::SELECT,'default'=>'latest','options'=>['latest'=>'Nyeste','featured'=>'Udvalgte','sale'=>'Tilbud','best_selling'=>'Bedst sælgende','category'=>'Kategori']]);
    $this->add_control('category',['label'=>'Kategori','type'=>\Elementor\Controls_Manager::SELECT,'options'=>$this->cat_options(),'condition'=>['source'=>'category']]);
    $this->add_control('limit',['label'=>'Antal','type'=>\Elementor\Controls_Manager::NUMBER,'default'=>8,'min'=>1,'max'=>32]);
    $this->add_control('columns',['label'=>'Kolonner','type'=>\Elementor\Controls_Manager::SELECT,'default'=>'4','options'=>['2'=>'2','3'=>'3','4'=>'4','5'=>'5']]);
    $this->add_control('show_rating',['label'=>'Vis rating','type'=>\Elementor\Controls_Manager::SWITCHER,'default'=>'yes','return_value'=>'yes']);
    $this->add_control('show_stock',['label'=>'Vis lagerstatus','type'=>\Elementor\Controls_Manager::SWITCHER,'default'=>'yes','return_value'=>'yes']);
    $this->add_control('button_text',['label'=>'Knaptekst','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Læg i kurv']);
    $this->end_controls_section();
  }
  protected function render(){ if(!class_exists('WooCommerce')){echo '<p>WooCommerce er ikke aktiv.</p>'; return;} $s=$this->get_settings_for_display();
    $args=['status'=>'publish','limit'=>intval($s['limit'])];
    if($s['source']==='featured') $args['featured']=true;
    elseif($s['source']==='sale') $args['include']=wc_get_product_ids_on_sale();
    elseif($s['source']==='best_selling'){$args['orderby']='meta_value_num';$args['meta_key']='total_sales';$args['order']='DESC';}
    elseif($s['source']==='category' && !empty($s['category'])){ $term=get_term(intval($s['category']),'product_cat'); if($term && !is_wp_error($term)) $args['category']=[$term->slug]; }
    else {$args['orderby']='date';$args['order']='DESC';}
    $products=wc_get_products($args); $cols=intval($s['columns']); ?>
    <div class="terttus-products" style="--terttus-cols:<?php echo $cols; ?>"><?php foreach($products as $product): ?>
      <article class="terttus-product-card">
        <a class="terttus-product-image" href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo $product->get_image('woocommerce_thumbnail'); ?></a>
        <div class="terttus-product-body">
          <a class="terttus-product-title" href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
          <?php if($s['show_rating']==='yes' && wc_review_ratings_enabled()): ?><div class="terttus-product-rating"><?php echo wc_get_rating_html($product->get_average_rating()); ?></div><?php endif; ?>
          <div class="terttus-product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
          <?php if($s['show_stock']==='yes'): ?><div class="terttus-stock <?php echo $product->is_in_stock()?'is-in':'is-out'; ?>"><?php echo esc_html($product->is_in_stock()?'På lager':'Ikke på lager'); ?></div><?php endif; ?>
          <a class="terttus-cart-btn" href="<?php echo esc_url($product->add_to_cart_url()); ?>" data-quantity="1" data-product_id="<?php echo intval($product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" rel="nofollow"><?php echo esc_html($s['button_text']); ?></a>
        </div>
      </article><?php endforeach; ?></div>
    <style>.terttus-products{display:grid;grid-template-columns:repeat(var(--terttus-cols),minmax(0,1fr));gap:20px}.terttus-product-card{background:#fff;border:1px solid #e0e6ea;border-radius:14px;overflow:hidden;display:flex;flex-direction:column}.terttus-product-image{display:block;padding:18px;background:#fff}.terttus-product-image img{width:100%;aspect-ratio:1/1;object-fit:contain}.terttus-product-body{padding:16px;display:flex;flex-direction:column;gap:10px;flex:1}.terttus-product-title{font-weight:700;text-decoration:none;color:#10212d;line-height:1.35}.terttus-product-price{font-size:20px;font-weight:800;color:#0b5360}.terttus-product-price del{opacity:.5;font-weight:500}.terttus-product-price ins{text-decoration:none}.terttus-stock{font-size:14px}.terttus-stock.is-in{color:#198754}.terttus-stock.is-out{color:#b42318}.terttus-cart-btn{margin-top:auto;text-align:center;background:#ff7b32;color:#fff!important;text-decoration:none;font-weight:700;border-radius:9px;padding:11px 14px}.terttus-product-rating .star-rating{float:none;margin:0}@media(max-width:1024px){.terttus-products{grid-template-columns:repeat(3,1fr)}}@media(max-width:767px){.terttus-products{grid-template-columns:repeat(2,1fr);gap:12px}}@media(max-width:430px){.terttus-products{grid-template-columns:1fr}}</style>
  <?php }
}
\Elementor\Plugin::instance()->widgets_manager->register(new Terttus_Product_Grid_Widget());