<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Afvis direkte adgang
}

class Cronovelo_Card_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'cronovelo_hero_slider';
	}

	public function get_title() {
		return esc_html__( 'Cronovelo Hero Video/Billede Slider', 'cronovelo-addons' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'cronovelo-widgets' ];
	}

	// Vi beder Elementor om at sikre, at Swiper-scripts og stilarter er indlæst
	public function get_script_depends() {
		return [ 'swiper' ];
	}
	public function get_style_depends() {
		return [ 'swiper' ];
	}

	protected function register_controls() {

		// ==========================================
		// SEKTION 1: SLIDES INDHOLD (REPEATER)
		// ==========================================
		$this->start_controls_section(
			'slides_section',
			[
				'label' => esc_html__( 'Slides', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'slide_title',
			[
				'label'       => esc_html__( 'Slide Titel', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'OPLEV VALTRA SHOWROOM', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'slide_subtitle',
			[
				'label'       => esc_html__( 'Slide Undertekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'placeholder' => esc_html__( 'Skriv en kort undertekst til overskriften', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'media_type',
			[
				'label'   => esc_html__( 'Medietype', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'video',
				'options' => [
					'video' => esc_html__( 'Video (MP4)', 'cronovelo-addons' ),
					'youtube' => esc_html__( 'YouTube (Link)', 'cronovelo-addons' ),
					'image' => esc_html__( 'Billede', 'cronovelo-addons' ),
				],
			]
		);

		$repeater->add_control(
			'slide_image',
			[
				'label'     => esc_html__( 'Vælg Billede', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'image_link',
			[
				'label'       => esc_html__( 'Billedlink', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://',
				'condition'   => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_responsive_control(
			'slide_image_width',
			[
				'label'      => esc_html__( 'Billede Bredde', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'range'      => [
					'%'  => [ 'min' => 20, 'max' => 200, 'step' => 1 ],
					'px' => [ 'min' => 100, 'max' => 3000, 'step' => 10 ],
					'vw' => [ 'min' => 10, 'max' => 200, 'step' => 1 ],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'condition'  => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_responsive_control(
			'slide_image_height',
			[
				'label'      => esc_html__( 'Billede Højde', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vh' ],
				'range'      => [
					'%'  => [ 'min' => 20, 'max' => 200, 'step' => 1 ],
					'px' => [ 'min' => 100, 'max' => 3000, 'step' => 10 ],
					'vh' => [ 'min' => 10, 'max' => 200, 'step' => 1 ],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'condition'  => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'slide_video_url',
			[
				'label'       => esc_html__( 'Video URL (MP4)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'https://dit-site.dk/wp-content/...mp4',
				'label_block' => true,
				'condition'   => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'slide_youtube_url',
			[
				'label'       => esc_html__( 'YouTube URL', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'https://www.youtube.com/watch?v=...',
				'label_block' => true,
				'condition'   => [
					'media_type' => 'youtube',
				],
			]
		);

		// Individuel tid per slide
		$repeater->add_control(
			'slide_duration',
			[
				'label'       => esc_html__( 'Visningstid (i ms)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 5000,
				'description' => esc_html__( 'Hvor mange millisekunder dette slide skal vises (f.eks. 5000 = 5 sekunder).', 'cronovelo-addons' ),
			]
		);

		// Knap indstillinger
		$repeater->add_control(
			'btn_text',
			[
				'label'   => esc_html__( 'Knaptekst', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'SE MERE', 'cronovelo-addons' ),
			]
		);

		$repeater->add_control(
			'btn_link',
			[
				'label'       => esc_html__( 'Knap Link', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://',
				'default'     => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'slides',
			[
				'label'       => esc_html__( 'Slider Elementer', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'slide_title' => esc_html__( 'OPLEV VALTRA SHOWROOM', 'cronovelo-addons' ),
						'media_type'  => 'video',
					],
				],
				'title_field' => '{{{ slide_title }}}',
			]
		);

		$this->end_controls_section();


		// ==========================================
		// SEKTION 2: GENERELLE SLIDER INDSTILLINGER
		// ==========================================
		$this->start_controls_section(
			'slider_settings',
			[
				'label' => esc_html__( 'Slider Indstillinger', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'loop',
			[
				'label'        => esc_html__( 'Loop uendeligt', 'cronovelo-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Ja', 'cronovelo-addons' ),
				'label_off'    => esc_html__( 'Nej', 'cronovelo-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label'        => esc_html__( 'Vis Pile (Navigation)', 'cronovelo-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Ja', 'cronovelo-addons' ),
				'label_off'    => esc_html__( 'Nej', 'cronovelo-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_dots',
			[
				'label'        => esc_html__( 'Vis Prikker (Pagination)', 'cronovelo-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Ja', 'cronovelo-addons' ),
				'label_off'    => esc_html__( 'Nej', 'cronovelo-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB: HØJDE & MEDIE STYLING
		// ==========================================
		$this->start_controls_section(
			'style_general_section',
			[
				'label' => esc_html__( 'Generel Styling', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Slider Højde - Desktop
		$this->add_responsive_control(
			'slider_height',
			[
				'label'      => esc_html__( 'Slider Højde (px)', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [ 'min' => 200, 'max' => 1200, 'step' => 10 ],
					'vh' => [ 'min' => 20, 'max' => 100 ],
				],
				'default'    => [ 'unit' => 'px', 'size' => 700 ],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-video-hero' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Mørk toning (Overlay brightness)
		$this->add_control(
			'media_brightness',
			[
				'label'      => esc_html__( 'Medie Lysstyrke (Toning)', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [ 'min' => 10, 'max' => 100, 'step' => 5 ],
				],
				'default'    => [ 'unit' => '%', 'size' => 60 ],
				'selectors'  => [
					'{{WRAPPER}} .hero-media-file' => 'filter: brightness(calc({{SIZE}} / 100));',
				],
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB: TYPOGRAFI & TITEL
		// ==========================================
		$this->start_controls_section(
			'style_title_section',
			[
				'label' => esc_html__( 'Titel Styling', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .hero-overlay h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hero-overlay h2',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Undertekst Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .hero-overlay .hero-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .hero-overlay .hero-subtitle',
			]
		);

		$this->end_controls_section();


		// ==========================================
		// STYLE TAB: KNAP STYLING
		// ==========================================
		$this->start_controls_section(
			'style_button_section',
			[
				'label' => esc_html__( 'Knap Styling', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		// Normal Status
		$this->start_controls_tab(
			'btn_normal_tab',
			[ 'label' => esc_html__( 'Normal', 'cronovelo-addons' ) ]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label'     => esc_html__( 'Baggrundsfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#BD1B21', // Agrotek Rød
				'selectors' => [ '{{WRAPPER}} .hero-btn' => 'background-color: {{VALUE}};', ],
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'label'     => esc_html__( 'Tekstfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [ '{{WRAPPER}} .hero-btn' => 'color: {{VALUE}};', ],
			]
		);

		$this->end_controls_tab();

		// Hover Status
		$this->start_controls_tab(
			'btn_hover_tab',
			[ 'label' => esc_html__( 'Hover', 'cronovelo-addons' ) ]
		);

		$this->add_control(
			'btn_bg_color_hover',
			[
				'label'     => esc_html__( 'Baggrundsfarve (Hover)', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [ '{{WRAPPER}} .hero-btn:hover' => 'background-color: {{VALUE}};', ],
			]
		);

		$this->add_control(
			'btn_text_color_hover',
			[
				'label'     => esc_html__( 'Tekstfarve (Hover)', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#BD1B21',
				'selectors' => [ '{{WRAPPER}} .hero-btn:hover' => 'color: {{VALUE}};', ],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Knap Radius og Padding
		$this->add_responsive_control(
			'btn_radius',
			[
				'label'      => esc_html__( 'Knap Afrunding (Border Radius)', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [ '{{WRAPPER}} .hero-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', ],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_typography',
				'selector' => '{{WRAPPER}} .hero-btn',
			]
		);

		$this->end_controls_section();
	}

	private function extract_youtube_video_id( $url ) {
		if ( empty( $url ) ) {
			return '';
		}

		$parts = wp_parse_url( trim( $url ) );
		if ( empty( $parts['host'] ) ) {
			return '';
		}

		$host = strtolower( $parts['host'] );

		if ( false !== strpos( $host, 'youtu.be' ) ) {
			$path = isset( $parts['path'] ) ? trim( $parts['path'], '/' ) : '';
			return sanitize_text_field( $path );
		}

		if ( false !== strpos( $host, 'youtube.com' ) || false !== strpos( $host, 'youtube-nocookie.com' ) ) {
			if ( ! empty( $parts['query'] ) ) {
				parse_str( $parts['query'], $query_vars );
				if ( ! empty( $query_vars['v'] ) ) {
					return sanitize_text_field( $query_vars['v'] );
				}
			}

			$path = isset( $parts['path'] ) ? trim( $parts['path'], '/' ) : '';
			if ( ! empty( $path ) ) {
				$segments = explode( '/', $path );
				if ( isset( $segments[0] ) && 'embed' === $segments[0] && ! empty( $segments[1] ) ) {
					return sanitize_text_field( $segments[1] );
				}
				if ( isset( $segments[0] ) && 'shorts' === $segments[0] && ! empty( $segments[1] ) ) {
					return sanitize_text_field( $segments[1] );
				}
			}
		}

		return '';
	}

	private function get_youtube_embed_url( $url ) {
		$video_id = $this->extract_youtube_video_id( $url );
		if ( empty( $video_id ) ) {
			return '';
		}

		$params = [
			'autoplay'        => '1',
			'mute'            => '1',
			'loop'            => '1',
			'playlist'        => $video_id,
			'controls'        => '0',
			'modestbranding'  => '1',
			'rel'             => '0',
			'playsinline'     => '1',
			'enablejsapi'     => '1',
		];

		return add_query_arg( $params, 'https://www.youtube.com/embed/' . rawurlencode( $video_id ) );
	}

	// ==========================================
	// FRONTEND GENERERING (RENDER HTML + JS)
	// ==========================================
	protected function render() {
		$settings = $this->get_settings_for_display();
		$slides   = $settings['slides'];

		if ( empty( $slides ) ) {
			return;
		}

		// Giv hver instans et unikt ID så der kan være flere på samme side
		$unique_id = 'swiper-' . $this->get_id();

		// Forbered data-attributter til JavaScript
		$slider_options = wp_json_encode([
			'loop' => ( $settings['loop'] === 'yes' ),
			'show_arrows' => ( $settings['show_arrows'] === 'yes' ),
			'show_dots' => ( $settings['show_dots'] === 'yes' )
		]);
		?>

		<div class="cronovelo-slider-container" id="<?php echo esc_attr( $unique_id ); ?>" data-options="<?php echo esc_attr( $slider_options ); ?>">
			<div class="swiper-container unique-swiper-class">
				<div class="swiper-wrapper">

					<?php foreach ( $slides as $slide ) : ?>
						<div class="swiper-slide cronovelo-video-hero" data-swiper-autoplay="<?php echo esc_attr( $slide['slide_duration'] ); ?>">
							
							<?php if ( 'video' === $slide['media_type'] && ! empty( $slide['slide_video_url'] ) ) : ?>
								<video autoplay muted loop playsinline class="hero-video hero-media-file">
									<source src="<?php echo esc_url( $slide['slide_video_url'] ); ?>" type="video/mp4">
								</video>
							<?php elseif ( 'youtube' === $slide['media_type'] && ! empty( $slide['slide_youtube_url'] ) ) : ?>
								<?php $youtube_embed_url = $this->get_youtube_embed_url( $slide['slide_youtube_url'] ); ?>
								<?php if ( ! empty( $youtube_embed_url ) ) : ?>
									<iframe
										class="hero-youtube hero-media-file"
										src="<?php echo esc_url( $youtube_embed_url ); ?>"
										title="<?php echo esc_attr( $slide['slide_title'] ); ?>"
										frameborder="0"
										allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
										allowfullscreen
									></iframe>
								<?php endif; ?>
							<?php elseif ( 'image' === $slide['media_type'] && ! empty( $slide['slide_image']['url'] ) ) : ?>
								<?php
								$image_style = '';
								if ( ! empty( $slide['slide_image_width']['size'] ) && ! empty( $slide['slide_image_width']['unit'] ) ) {
									$image_style .= 'width:' . floatval( $slide['slide_image_width']['size'] ) . $slide['slide_image_width']['unit'] . ';';
								}
								if ( ! empty( $slide['slide_image_height']['size'] ) && ! empty( $slide['slide_image_height']['unit'] ) ) {
									$image_style .= 'height:' . floatval( $slide['slide_image_height']['size'] ) . $slide['slide_image_height']['unit'] . ';';
								}
								?>
								<?php if ( ! empty( $slide['image_link']['url'] ) ) : ?>
									<?php
									$this->add_render_attribute( 'image_link_attr_' . $slide['_id'], 'href', $slide['image_link']['url'] );
									$this->add_render_attribute( 'image_link_attr_' . $slide['_id'], 'class', 'hero-image-link' );
									if ( $slide['image_link']['is_external'] ) {
										$this->add_render_attribute( 'image_link_attr_' . $slide['_id'], 'target', '_blank' );
									}
									if ( $slide['image_link']['nofollow'] ) {
										$this->add_render_attribute( 'image_link_attr_' . $slide['_id'], 'rel', 'nofollow' );
									}
									?>
									<a <?php $this->print_render_attribute_string( 'image_link_attr_' . $slide['_id'] ); ?> aria-label="<?php echo esc_attr( $slide['slide_title'] ); ?>">
										<img src="<?php echo esc_url( $slide['slide_image']['url'] ); ?>" alt="<?php echo esc_attr( $slide['slide_title'] ); ?>" class="hero-image hero-media-file" style="<?php echo esc_attr( $image_style ); ?>">
									</a>
								<?php else : ?>
									<img src="<?php echo esc_url( $slide['slide_image']['url'] ); ?>" alt="<?php echo esc_attr( $slide['slide_title'] ); ?>" class="hero-image hero-media-file" style="<?php echo esc_attr( $image_style ); ?>">
								<?php endif; ?>
							<?php endif; ?>

							<div class="hero-overlay">
								<?php if ( ! empty( $slide['slide_title'] ) ) : ?>
									<h2><?php echo esc_html( $slide['slide_title'] ); ?></h2>
								<?php endif; ?>

								<?php if ( ! empty( $slide['slide_subtitle'] ) ) : ?>
									<p class="hero-subtitle"><?php echo esc_html( $slide['slide_subtitle'] ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $slide['btn_text'] ) && ! empty( $slide['btn_link']['url'] ) ) : ?>
									<?php
									$this->add_render_attribute( 'btn_attr_' . $slide['_id'], 'href', $slide['btn_link']['url'] );
									$this->add_render_attribute( 'btn_attr_' . $slide['_id'], 'class', 'hero-btn' );
									if ( $slide['btn_link']['is_external'] ) {
										$this->add_render_attribute( 'btn_attr_' . $slide['_id'], 'target', '_blank' );
									}
									if ( $slide['btn_link']['nofollow'] ) {
										$this->add_render_attribute( 'btn_attr_' . $slide['_id'], 'rel', 'nofollow' );
									}
									?>
									<a <?php $this->print_render_attribute_string( 'btn_attr_' . $slide['_id'] ); ?>>
										<?php echo esc_html( $slide['btn_text'] ); ?>
									</a>
								<?php endif; ?>
							</div>

						</div>
					<?php endforeach; ?>

				</div>

				<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_dots'] ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
			</div>
		</div>

		<style>
			.cronovelo-slider-container { position: relative; width: 100%; overflow: hidden; }
			.unique-swiper-class { width: 100%; height: 100%; }
			.cronovelo-video-hero {
				position: relative;
				width: 100%;
				background: #000;
				overflow: hidden;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.hero-media-file {
				position: absolute;
				top: 50%; left: 50%;
				min-width: 100%; min-height: 100%;
				width: auto; height: auto;
				transform: translate(-50%, -50%);
				object-fit: cover;
				z-index: 0;
			}
			.hero-image.hero-media-file {
				min-width: 0;
				min-height: 0;
				max-width: none;
				max-height: none;
			}
			.hero-youtube.hero-media-file {
				border: 0;
			}
			.hero-image-link {
				position: absolute;
				inset: 0;
				z-index: 1;
				display: block;
			}
			.hero-overlay { position: relative; z-index: 10; text-align: center; padding: 20px; width: 100%; }
			.hero-overlay h2 { font-size: 54px; font-weight: 800; text-transform: uppercase; margin-bottom: 20px; font-family: 'Roboto', sans-serif; letter-spacing: 1px; }
			.hero-overlay .hero-subtitle { font-size: 20px; line-height: 1.4; max-width: 920px; margin: 0 auto 20px; font-family: 'Roboto', sans-serif; }
			.hero-btn { display: inline-block; padding: 12px 35px; text-decoration: none; font-weight: 600; transition: 0.3s ease; }
			.hero-btn:hover { transform: scale(1.05); }
			
			/* Swiper-specifikke pil-farver tilpasses let */
			.swiper-button-next, .swiper-button-prev { color: #fff !important; }
			.swiper-pagination-bullet-active { background: #fff !important; }

			@media (max-width: 767px) {
				.hero-overlay h2 { font-size: 28px !important; }
				.hero-overlay .hero-subtitle { font-size: 16px !important; }
			}
		</style>

		<script>
			document.addEventListener("DOMContentLoaded", function() {
				const container = document.getElementById("<?php echo esc_attr( $unique_id ); ?>");
				if (!container) return;

				const options = JSON.parse(container.getAttribute("data-options"));
				const swiperElement = container.querySelector('.unique-swiper-class');

				function syncSlideMedia(swiper) {
					const activeSlide = swiper.slides[swiper.activeIndex];

					swiper.slides.forEach(function(slide) {
						const isActive = slide === activeSlide;

						slide.querySelectorAll('video.hero-video').forEach(function(videoEl) {
							try {
								videoEl.pause();
								videoEl.currentTime = 0;
								if (isActive) {
									const playPromise = videoEl.play();
									if (playPromise && typeof playPromise.catch === 'function') {
										playPromise.catch(function() {});
									}
								}
							} catch (e) {
								/* ignore media reset errors */
							}
						});

						slide.querySelectorAll('iframe.hero-youtube').forEach(function(iframeEl) {
							const savedSrc = iframeEl.getAttribute('data-embed-src') || iframeEl.getAttribute('src');
							if (!savedSrc) return;
							iframeEl.setAttribute('data-embed-src', savedSrc);

							if (isActive) {
								iframeEl.setAttribute('src', savedSrc);
							} else {
								iframeEl.setAttribute('src', '');
							}
						});
					});
				}

				function getSlideAutoplayDelay(slideEl) {
					if (!slideEl) {
						return 5000;
					}

					const rawDelay = Number(slideEl.dataset.swiperAutoplay);
					if (Number.isFinite(rawDelay) && rawDelay > 0) {
						return rawDelay;
					}

					return 5000;
				}

				function updateAutoplayDelay(swiper) {
					if (!swiper || !swiper.params || !swiper.params.autoplay) {
						return;
					}

					const activeSlide = swiper.slides[swiper.activeIndex];
					const nextDelay = getSlideAutoplayDelay(activeSlide);
					swiper.params.autoplay.delay = nextDelay;

					if (swiper.autoplay) {
						swiper.autoplay.stop();
						swiper.autoplay.start();
					}
				}

				const initialSlide = swiperElement.querySelector('.swiper-slide');
				const swiperConfig = {
					init: true,
					slidesPerView: 1,
					watchSlidesProgress: true,
					autoplay: {
						delay: getSlideAutoplayDelay(initialSlide),
						disableOnInteraction: false,
					},
					loop: options.loop,
					on: {
						init: function() {
							syncSlideMedia(this);
							updateAutoplayDelay(this);
						},
						slideChangeTransitionStart: function() {
							syncSlideMedia(this);
							updateAutoplayDelay(this);
						}
					}
				};

				if (options.show_arrows) {
					swiperConfig.navigation = {
						nextEl: container.querySelector('.swiper-button-next'),
						prevEl: container.querySelector('.swiper-button-prev'),
					};
				}

				if (options.show_dots) {
					swiperConfig.pagination = {
						el: container.querySelector('.swiper-pagination'),
						clickable: true,
					};
				}

				// Start Swiper
				if (typeof Swiper !== 'undefined') {
					new Swiper(swiperElement, swiperConfig);
				} else {
					// Fallback hvis Elementor loader det asynkront i editoren
					window.addEventListener('elementor/frontend/init', () => {
						new Swiper(swiperElement, swiperConfig);
					});
				}
			});
		</script>
		<?php
	}
}

function cronovelo_register_hero_slider_widget( $widgets_manager ) {
	$widget = new \Cronovelo_Card_Widget();

	if ( method_exists( $widgets_manager, 'register' ) ) {
		$widgets_manager->register( $widget );
	} else {
		$widgets_manager->register_widget_type( $widget );
	}
}
add_action( 'elementor/widgets/register', 'cronovelo_register_hero_slider_widget' );