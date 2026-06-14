<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Afvis direkte adgang
}

class Cronovelo_Event_Promo_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'cronovelo_event_promo';
	}

	public function get_title() {
		return esc_html__( 'Cronovelo Event Promo', 'cronovelo-addons' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	public function get_categories() {
		return [ 'cronovelo-widgets' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Indhold', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'banner_media_type',
			[
				'label'   => esc_html__( 'Banner type', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Billede', 'cronovelo-addons' ),
					'video' => esc_html__( 'Video (autospiller i loop)', 'cronovelo-addons' ),
				],
			]
		);

		$this->add_control(
			'banner_image',
			[
				'label'     => esc_html__( 'Banner billede', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'default'   => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'banner_media_type' => 'image',
				],
			]
		);

		$this->add_control(
			'banner_alt',
			[
				'label'       => esc_html__( 'Billede alt tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Event banner', 'cronovelo-addons' ),
				'condition'   => [
					'banner_media_type' => 'image',
				],
			]
		);

		$this->add_control(
			'banner_video_url',
			[
				'label'       => esc_html__( 'Video URL (MP4)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://dit-site.dk/video.mp4',
				'label_block' => true,
				'description' => esc_html__( 'Link til MP4-fil. Videoen spiller automatisk, lydlost og i loop.', 'cronovelo-addons' ),
				'condition'   => [
					'banner_media_type' => 'video',
				],
			]
		);

		$this->add_control(
			'banner_video_poster',
			[
				'label'       => esc_html__( 'Video fallback-billede', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'description' => esc_html__( 'Vises mens videoen loader (valgfrit).', 'cronovelo-addons' ),
				'condition'   => [
					'banner_media_type' => 'video',
				],
			]
		);

		$this->add_responsive_control(
			'banner_video_height',
			[
				'label'      => esc_html__( 'Video hojde', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [ 'min' => 100, 'max' => 900, 'step' => 10 ],
					'vh' => [ 'min' => 10, 'max' => 100 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 380,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-event__banner video' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'banner_media_type' => 'video',
				],
			]
		);

		$this->add_control(
			'badge_text',
			[
				'label'       => esc_html__( 'Badge tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Torsdag d. 28. Maj', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Titel', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Maskinernes Dag', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label'       => esc_html__( 'Undertekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Hos Agrotek i Bronderslev!', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => esc_html__( 'Beskrivelse', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Saet et stort kryds i kalenderen den 28. maj kl. 10-20. Vi viser maskinparken frem med grill, hygge og gode tilbud.', 'cronovelo-addons' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'highlight_icon',
			[
				'label'       => esc_html__( 'Ikon/emoji', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 'i',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'highlight_text',
			[
				'label'       => esc_html__( 'Highlight tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Kl. 10:00 - 20:00', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'highlights',
			[
				'label'       => esc_html__( 'Highlights', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ highlight_text }}}',
				'default'     => [
					[
						'highlight_icon' => 'TID',
						'highlight_text' => esc_html__( 'Kl. 10:00 - 20:00', 'cronovelo-addons' ),
					],
					[
						'highlight_icon' => 'MAD',
						'highlight_text' => esc_html__( 'Grillen er taendt', 'cronovelo-addons' ),
					],
					[
						'highlight_icon' => 'STED',
						'highlight_text' => esc_html__( 'Oestergade 130, Bronderslev', 'cronovelo-addons' ),
					],
				],
			]
		);

		$this->add_control(
			'cta_text',
			[
				'label'       => esc_html__( 'Knap tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Find vej til Bronderslev', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'cta_link',
			[
				'label'       => esc_html__( 'Knap link', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://',
				'default'     => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_style_section',
			[
				'label' => esc_html__( 'Layout', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'container_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label'      => esc_html__( 'Maks bredde', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [ 'min' => 320, 'max' => 1400, 'step' => 10 ],
					'%'  => [ 'min' => 30, 'max' => 100, 'step' => 1 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 800,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-event' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_radius',
			[
				'label'      => esc_html__( 'Hjorner', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 12,
					'right'    => 12,
					'bottom'   => 12,
					'left'     => 12,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-event' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .cronovelo-event',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'container_shadow',
				'selector' => '{{WRAPPER}} .cronovelo-event',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'badge_style_section',
			[
				'label' => esc_html__( 'Badge', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'badge_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e30613',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label'     => esc_html__( 'Tekstfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .cronovelo-event__badge',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_style_section',
			[
				'label' => esc_html__( 'Indhold', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 40,
					'right'    => 35,
					'bottom'   => 40,
					'left'     => 35,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-event__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_align',
			[
				'label'   => esc_html__( 'Justering', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Venstre', 'cronovelo-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cronovelo-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Hojre', 'cronovelo-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle'  => true,
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Titel farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1a252c',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .cronovelo-event__title',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Undertekst farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e30613',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .cronovelo-event__subtitle',
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'     => esc_html__( 'Divider farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e30613',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__divider' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Brdtekst farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#4a5568',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .cronovelo-event__text',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'highlights_style_section',
			[
				'label' => esc_html__( 'Highlights', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'highlights_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f8fafc',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__highlights' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'highlights_border_color',
			[
				'label'     => esc_html__( 'Venstre kant farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1a252c',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__highlights' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'highlights_columns',
			[
				'label'      => esc_html__( 'Kolonner', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '' ],
				'range'      => [
					'' => [ 'min' => 1, 'max' => 6, 'step' => 1 ],
				],
				'default'    => [
					'unit' => '',
					'size' => 3,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-event__highlights' => 'grid-template-columns: repeat({{SIZE}}, 1fr);',
				],
			]
		);

		$this->add_control(
			'highlight_text_color',
			[
				'label'     => esc_html__( 'Tekstfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#2d3748',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__highlight-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'highlight_icon_color',
			[
				'label'     => esc_html__( 'Ikonfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1a252c',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__highlight-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_style_section',
			[
				'label' => esc_html__( 'Knap', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'cronovelo-addons' ),
			]
		);

		$this->add_control(
			'button_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1a252c',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => esc_html__( 'Tekstfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'cronovelo-addons' ),
			]
		);

		$this->add_control(
			'button_bg_hover',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e30613',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label'     => esc_html__( 'Tekstfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-event__btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .cronovelo-event__btn',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 15,
					'right'    => 35,
					'bottom'   => 15,
					'left'     => 35,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-event__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$highlights = ! empty( $settings['highlights'] ) ? $settings['highlights'] : [];

		$this->add_render_attribute( 'cta_btn', 'class', 'cronovelo-event__btn' );
		if ( ! empty( $settings['cta_link']['url'] ) ) {
			$this->add_render_attribute( 'cta_btn', 'href', $settings['cta_link']['url'] );
		}
		if ( ! empty( $settings['cta_link']['is_external'] ) ) {
			$this->add_render_attribute( 'cta_btn', 'target', '_blank' );
		}
		if ( ! empty( $settings['cta_link']['nofollow'] ) ) {
			$this->add_render_attribute( 'cta_btn', 'rel', 'nofollow' );
		}
		?>
		<div class="cronovelo-event">
			<div class="cronovelo-event__banner">
				<?php if ( ! empty( $settings['badge_text'] ) ) : ?>
					<div class="cronovelo-event__badge"><?php echo esc_html( $settings['badge_text'] ); ?></div>
				<?php endif; ?>

				<?php if ( 'video' === $settings['banner_media_type'] && ! empty( $settings['banner_video_url']['url'] ) ) : ?>
					<video
						class="cronovelo-event__video"
						autoplay
						muted
						loop
						playsinline
						<?php if ( ! empty( $settings['banner_video_poster']['url'] ) ) : ?>poster="<?php echo esc_url( $settings['banner_video_poster']['url'] ); ?>"<?php endif; ?>
					>
						<source src="<?php echo esc_url( $settings['banner_video_url']['url'] ); ?>" type="video/mp4">
					</video>
				<?php elseif ( ! empty( $settings['banner_image']['url'] ) ) : ?>
					<img src="<?php echo esc_url( $settings['banner_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['banner_alt'] ); ?>">
				<?php endif; ?>
			</div>

			<div class="cronovelo-event__content">
				<?php if ( ! empty( $settings['title'] ) ) : ?>
					<h2 class="cronovelo-event__title"><?php echo esc_html( $settings['title'] ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $settings['subtitle'] ) ) : ?>
					<div class="cronovelo-event__subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></div>
				<?php endif; ?>

				<div class="cronovelo-event__divider"></div>

				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<div class="cronovelo-event__text">
						<?php echo wp_kses_post( $settings['description'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $highlights ) ) : ?>
					<div class="cronovelo-event__highlights">
						<?php foreach ( $highlights as $item ) : ?>
							<div class="cronovelo-event__highlight-item">
								<span class="cronovelo-event__highlight-icon"><?php echo esc_html( $item['highlight_icon'] ); ?></span>
								<span class="cronovelo-event__highlight-text"><?php echo esc_html( $item['highlight_text'] ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $settings['cta_text'] ) ) : ?>
					<a <?php $this->print_render_attribute_string( 'cta_btn' ); ?>>
						<?php echo esc_html( $settings['cta_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<style>
			.cronovelo-event {
				font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
				width: 100%;
				margin: 20px auto;
				overflow: hidden;
			}
			.cronovelo-event__banner {
				position: relative;
				width: 100%;
				display: block;
			}
			.cronovelo-event__banner img,
			.cronovelo-event__banner video {
				width: 100%;
				display: block;
			}
			.cronovelo-event__banner img {
				height: auto;
			}
			.cronovelo-event__video {
				object-fit: cover;
			}
			.cronovelo-event__badge {
				position: absolute;
				top: 20px;
				right: 20px;
				padding: 10px 20px;
				border-radius: 30px;
				font-weight: 700;
				text-transform: uppercase;
				font-size: 0.9rem;
				letter-spacing: 1px;
				box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
				z-index: 5;
			}
			.cronovelo-event__title {
				font-size: 2.2rem;
				margin: 0 0 5px;
				font-weight: 800;
				text-transform: uppercase;
				letter-spacing: 0.5px;
			}
			.cronovelo-event__subtitle {
				font-size: 1.2rem;
				font-weight: 600;
				margin-bottom: 25px;
			}
			.cronovelo-event__divider {
				height: 3px;
				width: 60px;
				margin: 0 auto 25px;
				border-radius: 2px;
			}
			.cronovelo-event__text {
				font-size: 1.1rem;
				line-height: 1.7;
				margin-bottom: 30px;
			}
			.cronovelo-event__highlights {
				display: grid;
				gap: 15px;
				margin-bottom: 35px;
				padding: 20px;
				border-radius: 8px;
				border-left: 4px solid #1a252c;
			}
			.cronovelo-event__highlight-item {
				display: flex;
				flex-direction: column;
				align-items: center;
				font-size: 0.95rem;
				font-weight: 600;
				gap: 6px;
			}
			.cronovelo-event__highlight-icon {
				font-size: 1.1rem;
			}
			.cronovelo-event__btn {
				display: inline-block;
				text-decoration: none;
				border-radius: 6px;
				font-weight: 700;
				font-size: 1.05rem;
				transition: all 0.3s ease;
				box-shadow: 0 4px 12px rgba(26, 37, 44, 0.2);
			}
			.cronovelo-event__btn:hover {
				transform: translateY(-2px);
			}
			@media (max-width: 600px) {
				.cronovelo-event__title {
					font-size: 1.7rem;
				}
				.cronovelo-event__highlights {
					grid-template-columns: 1fr !important;
					gap: 20px;
				}
				.cronovelo-event__highlight-item {
					flex-direction: row;
					justify-content: flex-start;
					text-align: left;
					gap: 12px;
				}
				.cronovelo-event__badge {
					position: static;
					display: inline-block;
					margin: 20px 20px 0;
				}
			}
		</style>
		<?php
	}
}

function cronovelo_register_event_promo_widget( $widgets_manager ) {
	$widget = new \Cronovelo_Event_Promo_Widget();

	if ( method_exists( $widgets_manager, 'register' ) ) {
		$widgets_manager->register( $widget );
	} else {
		$widgets_manager->register_widget_type( $widget );
	}
}
add_action( 'elementor/widgets/register', 'cronovelo_register_event_promo_widget' );
