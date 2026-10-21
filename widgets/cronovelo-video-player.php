<?php
if (!defined('ABSPATH')) exit;
class Cronovelo_Video_Player extends \Elementor\Widget_Base {
 public function get_name(){return 'cronovelo_video_player';}
 public function get_title(){return 'Cronovelo Video Player';}
 public function get_icon(){return 'eicon-play';}
 public function get_categories(){return ['cronovelo-widgets'];}
 protected function register_controls(){
  $this->start_controls_section('source',['label'=>'Video']);
  $this->add_control('video',['label'=>'WordPress video','type'=>\Elementor\Controls_Manager::MEDIA,'media_types'=>['video']]);
  $this->add_control('url',['label'=>'Direkte video URL (overstyrer upload)','type'=>\Elementor\Controls_Manager::URL]);
  $this->add_control('poster',['label'=>'Coverbillede','type'=>\Elementor\Controls_Manager::MEDIA]);
  $this->add_control('start',['label'=>'Starttid (sekunder)','type'=>\Elementor\Controls_Manager::NUMBER,'min'=>0,'step'=>0.1,'default'=>0]);
  $this->add_control('end',['label'=>'Sluttid (sekunder, 0 = hele videoen)','type'=>\Elementor\Controls_Manager::NUMBER,'min'=>0,'step'=>0.1,'default'=>0]);
  $this->end_controls_section();
  $this->start_controls_section('behaviour',['label'=>'Afspilning pr. enhed']);
  foreach(['desktop'=>'Computer','tablet'=>'Tablet','mobile'=>'Mobil'] as $device=>$label){
   $this->add_control('head_'.$device,['label'=>$label,'type'=>\Elementor\Controls_Manager::HEADING,'separator'=>'before']);
   foreach(['autoplay'=>'Autoplay','mute'=>'Tvungen mute','loop'=>'Loop','play'=>'Play/pause-knap','sound'=>'Lydknap','fullscreen'=>'Fullscreen-knap'] as $key=>$title){
    $yes=in_array($key,['mute','play','sound','fullscreen'],true)||($key==='autoplay'&&$device==='desktop');
    $this->add_control($device.'_'.$key,['label'=>$title,'type'=>\Elementor\Controls_Manager::SWITCHER,'return_value'=>'yes','default'=>$yes?'yes':'']);
   }
  }
  $this->end_controls_section();
  $this->start_controls_section('dimensions',['label'=>'Størrelse','tab'=>\Elementor\Controls_Manager::TAB_STYLE]);
  $this->add_responsive_control('width',['label'=>'Bredde','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['%','px','vw'],'range'=>['%'=>['min'=>1,'max'=>100],'px'=>['min'=>100,'max'=>2000],'vw'=>['min'=>1,'max'=>100]],'default'=>['unit'=>'%','size'=>100],'selectors'=>['{{WRAPPER}} .cv-player'=>'width:{{SIZE}}{{UNIT}};']]);
  $this->add_responsive_control('height',['label'=>'Højde','type'=>\Elementor\Controls_Manager::SLIDER,'size_units'=>['px','vh'],'range'=>['px'=>['min'=>100,'max'=>1200],'vh'=>['min'=>10,'max'=>100]],'default'=>['unit'=>'px','size'=>600],'tablet_default'=>['unit'=>'px','size'=>400],'mobile_default'=>['unit'=>'px','size'=>260],'selectors'=>['{{WRAPPER}} .cv-player'=>'height:{{SIZE}}{{UNIT}};']]);
  $this->add_control('fit',['label'=>'Tilpasning','type'=>\Elementor\Controls_Manager::SELECT,'options'=>['cover'=>'Beskær / udfyld','contain'=>'Vis hele videoen'],'default'=>'cover','selectors'=>['{{WRAPPER}} .cv-player video'=>'object-fit:{{VALUE}};']]);
  $this->end_controls_section();
 }
 protected function render(){
  $s=$this->get_settings_for_display();
  $url=!empty($s['url']['url'])?$s['url']['url']:($s['video']['url']??'');
  if(!$url){echo '<p>Vælg en video.</p>';return;}
  $config=['start'=>max(0,(float)$s['start']),'end'=>max(0,(float)$s['end'])];
  foreach(['desktop','tablet','mobile'] as $d){foreach(['autoplay','mute','loop','play','sound','fullscreen'] as $k){$config[$d][$k]=($s[$d.'_'.$k]??'')==='yes';}}
  // Server-rendered responsive visibility: works in Elementor preview even before JS initializes.
  $uid='cvp-'.esc_attr($this->get_id());
  $visibility='';
  foreach (['desktop'=>'(min-width:1025px)','tablet'=>'(min-width:768px) and (max-width:1024px)','mobile'=>'(max-width:767px)'] as $device=>$media) {
    foreach (['play','sound','fullscreen'] as $action) {
      if (empty($config[$device][$action])) {
        $visibility.='@media '.$media.'{#'.$uid.' [data-cv-action="'.$action.'"]{display:none!important;}}';
      }
    }
  }
  echo '<style>'.$visibility.'</style>';
  echo '<div id="'.$uid.'" class="cv-player" data-cv="'.esc_attr(wp_json_encode($config)).'"><video playsinline preload="metadata" src="'.esc_url($url).'"'.(!empty($s['poster']['url'])?' poster="'.esc_url($s['poster']['url']).'"':'').'></video><div class="cv-buttons"><button type="button" data-cv-action="play" aria-label="Afspil eller pause">▶</button><button type="button" data-cv-action="sound" aria-label="Slå lyd til eller fra">🔇</button><button type="button" data-cv-action="fullscreen" aria-label="Fuld skærm">⛶</button></div></div>';
 }
}
\Elementor\Plugin::instance()->widgets_manager->register(new Cronovelo_Video_Player());
