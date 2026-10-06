<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Terttus_Hero_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'terttus_hero'; }
    public function get_title() { return esc_html__( 'Terttus Hero', 'cronovelo-addons' ); }
    public function get_icon() { return 'eicon-banner'; }
    public function get_categories() { return [ 'terttus-widgets' ]; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => 'Indhold']);
        $this->add_control('title', ['label'=>'Overskrift','type'=>\Elementor\Controls_Manager::TEXTAREA,'default'=>'Gør dit hjem smartere med Terttus']);
        $this->add_control('text', ['label'=>'Tekst','type'=>\Elementor\Controls_Manager::TEXTAREA,'default'=>'IT, elektronik og smart home – udvalgt af en entusiast, til fornuftige priser og med ordentlig support.']);
        $this->add_control('button_text', ['label'=>'Knaptekst','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Se alle varer']);
        $this->add_control('button_link', ['label'=>'Knaplink','type'=>\Elementor\Controls_Manager::URL,'default'=>['url'=>'/shop/']]);
        $this->add_control('image', ['label'=>'Billede','type'=>\Elementor\Controls_Manager::MEDIA]);
        $this->end_controls_section();

        $this->start_controls_section('style', ['label'=>'Design','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('bg1', ['label'=>'Gradient start','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#0B5360']);
        $this->add_control('bg2', ['label'=>'Gradient slut','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#2BAFA7']);
        $this->add_control('accent', ['label'=>'Knapfarve','type'=>\Elementor\Controls_Manager::COLOR,'default'=>'#FF7B32']);
        $this->add_responsive_control('min_height', ['label'=>'Min. højde','type'=>\Elementor\Controls_Manager::SLIDER,'range'=>['px'=>['min'=>280,'max'=>800]],'default'=>['size'=>480,'unit'=>'px']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $style = sprintf('background:linear-gradient(100deg,%s,%s);min-height:%dpx;', esc_attr($s['bg1']), esc_attr($s['bg2']), intval($s['min_height']['size']));
        ?>
        <section class="terttus-hero" style="<?php echo esc_attr($style); ?>">
            <div class="terttus-hero__content">
                <h1><?php echo esc_html($s['title']); ?></h1>
                <p><?php echo esc_html($s['text']); ?></p>
                <?php if ( ! empty($s['button_text']) ) : ?>
                    <a class="terttus-hero__button" style="background:<?php echo esc_attr($s['accent']); ?>" href="<?php echo esc_url($s['button_link']['url']); ?>"><?php echo esc_html($s['button_text']); ?></a>
                <?php endif; ?>
            </div>
            <?php if ( ! empty($s['image']['url']) ) : ?><div class="terttus-hero__media"><img src="<?php echo esc_url($s['image']['url']); ?>" alt=""></div><?php endif; ?>
        </section>
        <style>
        .terttus-hero{display:grid;grid-template-columns:1.25fr .75fr;align-items:center;gap:40px;border-radius:18px;padding:42px;color:#fff;overflow:hidden}.terttus-hero__content{max-width:720px}.terttus-hero h1{font-size:clamp(40px,5vw,68px);line-height:1.04;margin:0 0 18px;color:#fff}.terttus-hero p{font-size:clamp(18px,2vw,24px);line-height:1.5;margin:0 0 28px}.terttus-hero__button{display:inline-block;color:#fff;text-decoration:none;font-weight:700;padding:14px 22px;border-radius:10px}.terttus-hero__media img{width:100%;max-height:360px;object-fit:contain}.terttus-hero__media{text-align:center}@media(max-width:767px){.terttus-hero{grid-template-columns:1fr;padding:28px}.terttus-hero__media{order:-1}.terttus-hero__media img{max-height:220px}}
        </style>
        <?php
    }
}
\Elementor\Plugin::instance()->widgets_manager->register( new Terttus_Hero_Widget() );