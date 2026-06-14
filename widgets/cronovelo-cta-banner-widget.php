<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cronovelo_CTA_Banner_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'cronovelo_cta_banner';
	}

	public function get_title() {
		return esc_html__( 'Cronovelo CTA Banner', 'cronovelo-addons' );
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
			'headline',
			[
				'label'       => esc_html__( 'Overskrift', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Skal vi samarbejde om dit næste projekt?', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description',
			[
				'label'       => esc_html__( 'Brødtekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => esc_html__( 'Uanset om du mangler en tømrer hjemme hos dig selv eller en professionel håndværker til et større sjak, sidder jeg klar til at tage en uforpligtende snak.', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Knap tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Få et uforpligtende tilbud', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'button_link',
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

		$this->add_responsive_control(
			'content_max_width',
			[
				'label'      => esc_html__( 'Indhold max bredde', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 320,
						'max'  => 1200,
						'step' => 10,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 760,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-cta__content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Sektion', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'section_background',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#081639',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-cta' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_padding',
			[
				'label'      => esc_html__( 'Padding', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 56,
					'right'    => 24,
					'bottom'   => 56,
					'left'     => 24,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_radius',
			[
				'label'      => esc_html__( 'Hjørner', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 8,
					'right'    => 8,
					'bottom'   => 8,
					'left'     => 8,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Overskrift', 'cronovelo-addons' ),
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
					'{{WRAPPER}} .cronovelo-cta__headline' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .cronovelo-cta__headline',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'description_style',
			[
				'label' => esc_html__( 'Brødtekst', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-cta__description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .cronovelo-cta__description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_style',
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
			'button_bg_color',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1d45ad',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-cta__button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Tekst', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-cta__button' => 'color: {{VALUE}};',
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
			'button_bg_color_hover',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#2a56c6',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-cta__button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => esc_html__( 'Tekst', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-cta__button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .cronovelo-cta__button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 18,
					'right'    => 36,
					'bottom'   => 18,
					'left'     => 36,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-cta__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label'      => esc_html__( 'Hjørner', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 8,
					'right'    => 8,
					'bottom'   => 8,
					'left'     => 8,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-cta__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'cta_button', 'class', 'cronovelo-cta__button' );

		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_render_attribute( 'cta_button', 'href', $settings['button_link']['url'] );
		} else {
			$this->add_render_attribute( 'cta_button', 'href', '#' );
		}

		if ( ! empty( $settings['button_link']['is_external'] ) ) {
			$this->add_render_attribute( 'cta_button', 'target', '_blank' );
		}

		if ( ! empty( $settings['button_link']['nofollow'] ) ) {
			$this->add_render_attribute( 'cta_button', 'rel', 'nofollow' );
		}
		?>
		<section class="cronovelo-cta">
			<div class="cronovelo-cta__content">
				<?php if ( ! empty( $settings['headline'] ) ) : ?>
					<h2 class="cronovelo-cta__headline"><?php echo esc_html( $settings['headline'] ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<p class="cronovelo-cta__description"><?php echo esc_html( $settings['description'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $settings['button_text'] ) ) : ?>
					<a <?php $this->print_render_attribute_string( 'cta_button' ); ?>>
						<?php echo esc_html( $settings['button_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</section>

		<style>
			.cronovelo-cta {
				background: #081639;
				padding: 56px 24px;
				border-radius: 8px;
			}
			.cronovelo-cta__content {
				max-width: 760px;
				margin: 0 auto;
				text-align: center;
			}
			.cronovelo-cta__headline {
				margin: 0;
				font-size: 44px;
				line-height: 1.2;
				font-weight: 700;
				color: #ffffff;
			}
			.cronovelo-cta__description {
				margin: 20px auto 32px;
				font-size: 20px;
				line-height: 1.5;
				color: #ffffff;
			}
			.cronovelo-cta__button {
				display: inline-block;
				text-decoration: none;
				font-size: 19px;
				font-weight: 600;
				line-height: 1;
				padding: 18px 36px;
				border-radius: 8px;
				background: #1d45ad;
				color: #ffffff;
				transition: all 0.2s ease;
			}
			.cronovelo-cta__button:hover {
				background: #2a56c6;
				transform: translateY(-1px);
			}
			@media (max-width: 767px) {
				.cronovelo-cta {
					padding: 36px 16px;
				}
				.cronovelo-cta__headline {
					font-size: 30px;
				}
				.cronovelo-cta__description {
					font-size: 17px;
					margin-bottom: 24px;
				}
				.cronovelo-cta__button {
					font-size: 17px;
					padding: 16px 24px;
				}
			}
		</style>
		<?php
	}
}

function cronovelo_register_cta_banner_widget( $widgets_manager ) {
	$widget = new \Cronovelo_CTA_Banner_Widget();

	if ( method_exists( $widgets_manager, 'register' ) ) {
		$widgets_manager->register( $widget );
	} else {
		$widgets_manager->register_widget_type( $widget );
	}
}
add_action( 'elementor/widgets/register', 'cronovelo_register_cta_banner_widget' );