<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Terttus_Product_Grid_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'terttus_product_grid'; }
    public function get_title() { return 'Terttus Produkt Grid'; }
    public function get_icon() { return 'eicon-products'; }
    public function get_categories() { return [ 'terttus-widgets' ]; }

    private function cat_options() {
        $o = [];
        if ( ! taxonomy_exists( 'product_cat' ) ) { return $o; }
        $ts = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => false ] );
        if ( is_wp_error( $ts ) ) { return $o; }
        foreach ( $ts as $t ) { $o[ $t->term_id ] = $t->name; }
        return $o;
    }

    protected function register_controls() {
        $this->start_controls_section( 'query', [ 'label' => 'Produkter' ] );
        $this->add_control( 'heading', [ 'label' => 'Sektionsoverskrift', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Udvalgte produkter' ] );
        $this->add_control( 'source', [ 'label' => 'Vis', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'latest', 'options' => [ 'latest' => 'Nyeste', 'featured' => 'Udvalgte', 'sale' => 'Tilbud', 'best_selling' => 'Bedst sælgende', 'category' => 'Kategori' ] ] );
        $this->add_control( 'category', [ 'label' => 'Kategori', 'type' => \Elementor\Controls_Manager::SELECT, 'options' => $this->cat_options(), 'condition' => [ 'source' => 'category' ] ] );
        $this->add_control( 'limit', [ 'label' => 'Antal', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 8, 'min' => 1, 'max' => 32 ] );
        $this->add_control( 'columns', [ 'label' => 'Kolonner', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4', 'options' => [ '2'=>'2','3'=>'3','4'=>'4','5'=>'5' ] ] );
        $this->add_control( 'show_rating', [ 'label' => 'Vis rating', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ] );
        $this->add_control( 'show_stock', [ 'label' => 'Vis lagerstatus', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ] );
        $this->add_control( 'button_text', [ 'label' => 'Knaptekst', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Læg i kurv' ] );
        $this->end_controls_section();
    }

    protected function render() {
        if ( ! function_exists( 'wc_get_products' ) ) {
            echo '<p>WooCommerce er ikke aktiv.</p>';
            return;
        }
        $s = $this->get_settings_for_display();
        $a = [ 'status' => 'publish', 'limit' => max( 1, (int) ( $s['limit'] ?? 8 ) ) ];
        $source = $s['source'] ?? 'latest';
        if ( 'featured' === $source ) {
            $a['featured'] = true;
        } elseif ( 'sale' === $source ) {
            $a['include'] = wc_get_product_ids_on_sale();
        } elseif ( 'best_selling' === $source ) {
            $a['orderby'] = 'meta_value_num'; $a['meta_key'] = 'total_sales'; $a['order'] = 'DESC';
        } elseif ( 'category' === $source && ! empty( $s['category'] ) ) {
            $t = get_term( (int) $s['category'], 'product_cat' );
            if ( $t && ! is_wp_error( $t ) ) { $a['category'] = [ $t->slug ]; }
        } else {
            $a['orderby'] = 'date'; $a['order'] = 'DESC';
        }
        $ps = wc_get_products( $a );
        $cols = max( 2, min( 5, (int) ( $s['columns'] ?? 4 ) ) );

        echo '<section class="terttus-product-section terttus-widget">';
        if ( ! empty( $s['heading'] ) ) {
            echo '<div class="terttus-section-head"><h2>' . esc_html( $s['heading'] ) . '</h2><span></span></div>';
        }
        echo '<div class="terttus-products" style="--terttus-cols:' . esc_attr( $cols ) . '">';
        foreach ( $ps as $p ) {
            if ( ! is_a( $p, 'WC_Product' ) ) { continue; }
            echo '<article class="terttus-product-card tt-card"><a class="terttus-product-image" href="' . esc_url( $p->get_permalink() ) . '">';
            if ( $p->is_on_sale() ) { echo '<span class="terttus-sale">Tilbud</span>'; }
            echo wp_kses_post( $p->get_image( 'woocommerce_thumbnail' ) ) . '</a><div class="terttus-product-body">';
            echo '<a class="terttus-product-title" href="' . esc_url( $p->get_permalink() ) . '">' . esc_html( $p->get_name() ) . '</a>';
            if ( ( $s['show_rating'] ?? '' ) === 'yes' && wc_review_ratings_enabled() ) {
                echo '<div class="terttus-product-rating">' . wp_kses_post( wc_get_rating_html( $p->get_average_rating() ) ) . '</div>';
            }
            echo '<div class="terttus-product-price">' . wp_kses_post( $p->get_price_html() ) . '</div>';
            if ( ( $s['show_stock'] ?? '' ) === 'yes' ) {
                $in = $p->is_in_stock();
                echo '<div class="terttus-stock ' . esc_attr( $in ? 'is-in' : 'is-out' ) . '"><i></i>' . esc_html( $in ? 'På lager' : 'Ikke på lager' ) . '</div>';
            }
            $classes = $p->supports( 'ajax_add_to_cart' ) ? ' ajax_add_to_cart add_to_cart_button' : '';
            echo '<a class="terttus-cart-btn tt-btn tt-btn--accent' . esc_attr( $classes ) . '" href="' . esc_url( $p->add_to_cart_url() ) . '" data-quantity="1" data-product_id="' . (int) $p->get_id() . '" data-product_sku="' . esc_attr( $p->get_sku() ) . '" rel="nofollow">' . esc_html( $s['button_text'] ?? 'Læg i kurv' ) . '</a>';
            echo '</div></article>';
        }
        echo '</div></section>';
        ?>
        <style>
        .terttus-section-head{display:flex;align-items:center;gap:16px;margin-bottom:20px}.terttus-section-head h2{margin:0;color:var(--tt-navy);font-size:clamp(25px,3vw,34px);letter-spacing:-.025em}.terttus-section-head span{height:2px;flex:1;background:linear-gradient(90deg,var(--tt-teal),transparent)}.terttus-products{display:grid;grid-template-columns:repeat(var(--terttus-cols),minmax(0,1fr));gap:20px}.terttus-product-card{overflow:hidden;display:flex;flex-direction:column}.terttus-product-image{display:block;position:relative;padding:20px;background:linear-gradient(180deg,#fff,#f8fafb)}.terttus-product-image img{width:100%;aspect-ratio:1/1;object-fit:contain;transition:transform .22s}.terttus-product-card:hover .terttus-product-image img{transform:scale(1.035)}.terttus-sale{position:absolute;z-index:2;left:12px;top:12px;background:var(--tt-orange);color:#fff;font-size:12px;font-weight:900;padding:6px 9px;border-radius:999px}.terttus-product-body{padding:17px;display:flex;flex-direction:column;gap:10px;flex:1}.terttus-product-title{font-weight:800;text-decoration:none;color:var(--tt-navy);line-height:1.35}.terttus-product-title:hover{color:var(--tt-teal)}.terttus-product-price{font-size:21px;font-weight:900;color:var(--tt-petrol)}.terttus-product-price del{opacity:.45;font-weight:500;font-size:14px}.terttus-product-price ins{text-decoration:none}.terttus-stock{font-size:13px;display:flex;align-items:center;gap:6px}.terttus-stock i{width:7px;height:7px;border-radius:50%;background:currentColor}.terttus-stock.is-in{color:var(--tt-success)}.terttus-stock.is-out{color:var(--tt-danger)}.terttus-cart-btn{margin-top:auto;padding:11px 14px}.terttus-product-rating .star-rating{float:none;margin:0}@media(max-width:1024px){.terttus-products{grid-template-columns:repeat(3,1fr)}}@media(max-width:767px){.terttus-products{grid-template-columns:repeat(2,1fr);gap:12px}}@media(max-width:430px){.terttus-products{grid-template-columns:1fr}}
        </style>
        <?php
    }
}

if ( isset( $widgets_manager ) && is_object( $widgets_manager ) ) {
    $widgets_manager->register( new Terttus_Product_Grid_Widget() );
}
