<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
class Terttus_Trust_Bar_Widget extends \Elementor\Widget_Base {
    public function get_name(){ return 'terttus_trust_bar'; }
    public function get_title(){ return 'Terttus Trust Bar'; }
    public function get_icon(){ return 'eicon-info-box'; }
    public function get_categories(){ return ['terttus-widgets']; }
    protected function register_controls(){
        $this->start_controls_section('content',['label'=>'Punkter']);
        $r = new \Elementor\Repeater();
        $r->add_control('icon',['label'=>'Ikon','type'=>\Elementor\Controls_Manager::ICONS]);
        $r->add_control('title',['label'=>'Titel','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Hurtig levering']);
        $r->add_control('text',['label'=>'Tekst','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Afsendt samme dag før kl. 14']);
        $this->add_control('items',['type'=>\Elementor\Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'title_field'=>'{{{ title }}}','default'=>[
            ['title'=>'Hurtig levering','text'=>'Afsendt samme dag før kl. 14'],['title'=>'Sikker betaling','text'=>'MobilePay, kort og faktura'],['title'=>'14 dages fortrydelse','text'=>'Nem returnering'],['title'=>'Dansk support','text'=>'Hjælp til opsætning']]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); ?>
      <div class="terttus-trust-grid">
        <?php foreach($s['items'] as $item): ?><div class="terttus-trust-card">
          <?php if(!empty($item['icon']['value'])){ \Elementor\Icons_Manager::render_icon($item['icon'],['aria-hidden'=>'true']); } ?>
          <div><strong><?php echo esc_html($item['title']); ?></strong><span><?php echo esc_html($item['text']); ?></span></div>
        </div><?php endforeach; ?>
      </div>
      <style>.terttus-trust-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}.terttus-trust-card{display:flex;gap:12px;align-items:flex-start;background:#fff;border:1px solid #dfe5e8;border-radius:14px;padding:18px}.terttus-trust-card i,.terttus-trust-card svg{font-size:20px;width:22px}.terttus-trust-card strong{display:block;font-size:17px;color:#0c2030}.terttus-trust-card span{display:block;margin-top:4px;color:#586673}@media(max-width:900px){.terttus-trust-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:520px){.terttus-trust-grid{grid-template-columns:1fr}}</style>
    <?php }
}
\Elementor\Plugin::instance()->widgets_manager->register(new Terttus_Trust_Bar_Widget());