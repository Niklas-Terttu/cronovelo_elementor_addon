<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cronovelo_About_Split_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'cronovelo_about_split';
	}

	public function get_title() {
		return esc_html__( 'Cronovelo Om Os Split', 'cronovelo-addons' );
	}

	public function get_icon() {
		return 'eicon-person';
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
			'main_image',
			[
				'label'   => esc_html__( 'Venstre billede', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'main_image_alt',
			[
				'label'       => esc_html__( 'Billede alt tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Munkhauge toemrer i arbejde', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'eyebrow',
			[
				'label'       => esc_html__( 'Overlinje', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'VELKOMMEN TIL MUNKHAUGE TOEMRER', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'headline',
			[
				'label'       => esc_html__( 'Overskrift', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => esc_html__( 'Solidt haandvaerk & aftaler der holder', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'lead_text',
			[
				'label'       => esc_html__( 'Brødtekst 1', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => esc_html__( 'Munkhauge Toemrer er et lille, enkeltmandsfirma med fokus paa det gode haandvaerk, den direkte dialog og aftaler, der holder.', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'body_text',
			[
				'label'       => esc_html__( 'Brødtekst 2', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => esc_html__( 'Bag firmaet staar jeg selv - og for mig er ingen opgave for lille, uanset om det gaelder renovering, vedligeholdelse eller nye projekter paa din bolig.', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'card_icon',
			[
				'label'       => esc_html__( 'Ikon (emoji eller tekst)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '🏡',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'card_title',
			[
				'label'       => esc_html__( 'Kort titel', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Til private', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'card_text',
			[
				'label'       => esc_html__( 'Kort tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => esc_html__( 'Jeg hjaelper dig sikkert i maal med dine boligprojekter med fokus paa kvalitet til tiden.', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'info_cards',
			[
				'label'       => esc_html__( 'Info kort', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ card_title }}}',
				'default'     => [
					[
						'card_icon'  => '🏡',
						'card_title' => esc_html__( 'Til private', 'cronovelo-addons' ),
						'card_text'  => esc_html__( 'Jeg hjaelper dig sikkert i maal med dine boligprojekter med fokus paa kvalitet til tiden.', 'cronovelo-addons' ),
					],
					[
						'card_icon'  => '🔨',
						'card_title' => esc_html__( 'Til erhverv & sjak', 'cronovelo-addons' ),
						'card_text'  => esc_html__( 'Fleksibel samarbejdspartner, der lejer sig ud som ekstern toemrer til stoerre projekter.', 'cronovelo-addons' ),
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_style',
			[
				'label' => esc_html__( 'Layout', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'section_bg',
			[
				'label'     => esc_html__( 'Sektion baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f3f5f7',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_padding',
			[
				'label'      => esc_html__( 'Sektion padding', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 18,
					'right'    => 18,
					'bottom'   => 18,
					'left'     => 18,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-about-split' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_max_width',
			[
				'label'      => esc_html__( 'Maks bredde', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 680, 'max' => 1700, 'step' => 10 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 1450,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-about-split__inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'grid_gap',
			[
				'label'      => esc_html__( 'Kolonne afstand', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 80, 'step' => 1 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 52,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-about-split__inner' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_style',
			[
				'label' => esc_html__( 'Tekst', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eyebrow_color',
			[
				'label'     => esc_html__( 'Overlinje farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1f4ca9',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split__eyebrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'eyebrow_typography',
				'selector' => '{{WRAPPER}} .cronovelo-about-split__eyebrow',
			]
		);

		$this->add_control(
			'headline_color',
			[
				'label'     => esc_html__( 'Overskrift farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#15284a',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split__headline' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'headline_typography',
				'selector' => '{{WRAPPER}} .cronovelo-about-split__headline',
			]
		);

		$this->add_control(
			'body_color',
			[
				'label'     => esc_html__( 'Brødtekst farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1d2d47',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split__lead, {{WRAPPER}} .cronovelo-about-split__body' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'body_typography',
				'selector' => '{{WRAPPER}} .cronovelo-about-split__lead, {{WRAPPER}} .cronovelo-about-split__body',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_style',
			[
				'label' => esc_html__( 'Info kort', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#edf2f7',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split__card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_border_color',
			[
				'label'     => esc_html__( 'Kant farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#d4dee9',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split__card' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Padding', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 28,
					'right'    => 28,
					'bottom'   => 28,
					'left'     => 28,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-about-split__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_radius',
			[
				'label'      => esc_html__( 'Hjørner', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-about-split__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'card_icon_color',
			[
				'label'     => esc_html__( 'Ikon farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1f4ca9',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split__card-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_title_color',
			[
				'label'     => esc_html__( 'Kort titel farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#0f2444',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split__card-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'card_title_typography',
				'selector' => '{{WRAPPER}} .cronovelo-about-split__card-title',
			]
		);

		$this->add_control(
			'card_text_color',
			[
				'label'     => esc_html__( 'Kort tekst farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#2f4460',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-about-split__card-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'card_text_typography',
				'selector' => '{{WRAPPER}} .cronovelo-about-split__card-text',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$cards    = ! empty( $settings['info_cards'] ) && is_array( $settings['info_cards'] ) ? $settings['info_cards'] : [];
		?>
		<section class="cronovelo-about-split">
			<div class="cronovelo-about-split__inner">
				<div class="cronovelo-about-split__media">
					<?php if ( ! empty( $settings['main_image']['url'] ) ) : ?>
						<img src="<?php echo esc_url( $settings['main_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['main_image_alt'] ); ?>" class="cronovelo-about-split__image">
					<?php endif; ?>
				</div>

				<div class="cronovelo-about-split__content">
					<?php if ( ! empty( $settings['eyebrow'] ) ) : ?>
						<div class="cronovelo-about-split__eyebrow"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
					<?php endif; ?>

					<?php if ( ! empty( $settings['headline'] ) ) : ?>
						<h2 class="cronovelo-about-split__headline"><?php echo esc_html( $settings['headline'] ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $settings['lead_text'] ) ) : ?>
						<p class="cronovelo-about-split__lead"><?php echo esc_html( $settings['lead_text'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $settings['body_text'] ) ) : ?>
						<p class="cronovelo-about-split__body"><?php echo esc_html( $settings['body_text'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $cards ) ) : ?>
					<div class="cronovelo-about-split__cards">
						<?php foreach ( $cards as $card ) : ?>
							<article class="cronovelo-about-split__card">
								<?php if ( ! empty( $card['card_icon'] ) ) : ?>
									<div class="cronovelo-about-split__card-icon" aria-hidden="true"><?php echo esc_html( $card['card_icon'] ); ?></div>
								<?php endif; ?>

								<?php if ( ! empty( $card['card_title'] ) ) : ?>
									<h3 class="cronovelo-about-split__card-title"><?php echo esc_html( $card['card_title'] ); ?></h3>
								<?php endif; ?>

								<?php if ( ! empty( $card['card_text'] ) ) : ?>
									<p class="cronovelo-about-split__card-text"><?php echo esc_html( $card['card_text'] ); ?></p>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<style>
			.cronovelo-about-split {
				background: #f3f5f7;
				padding: 18px;
			}
			.cronovelo-about-split__inner {
				max-width: 1450px;
				margin: 0 auto;
				display: grid;
				grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.15fr) minmax(0, 0.95fr);
				gap: 52px;
				align-items: start;
			}
			.cronovelo-about-split__media,
			.cronovelo-about-split__image {
				width: 100%;
			}
			.cronovelo-about-split__image {
				display: block;
				height: auto;
				object-fit: cover;
			}
			.cronovelo-about-split__eyebrow {
				margin: 10px 0 18px;
				color: #1f4ca9;
				font-size: 27px;
				line-height: 1.1;
				font-weight: 700;
				letter-spacing: 0.06em;
				text-transform: uppercase;
			}
			.cronovelo-about-split__headline {
				margin: 0 0 24px;
				font-size: 72px;
				line-height: 1.06;
				font-weight: 700;
				color: #15284a;
			}
			.cronovelo-about-split__lead,
			.cronovelo-about-split__body {
				margin: 0 0 22px;
				font-size: 45px;
				line-height: 1.42;
				color: #1d2d47;
			}
			.cronovelo-about-split__body {
				margin-bottom: 0;
				color: #314b67;
			}
			.cronovelo-about-split__cards {
				display: grid;
				gap: 20px;
			}
			.cronovelo-about-split__card {
				background: #edf2f7;
				border: 1px solid #d4dee9;
				border-radius: 10px;
				padding: 28px;
			}
			.cronovelo-about-split__card-icon {
				font-size: 34px;
				line-height: 1;
				margin-bottom: 14px;
				color: #1f4ca9;
			}
			.cronovelo-about-split__card-title {
				margin: 0 0 12px;
				font-size: 51px;
				line-height: 1.2;
				font-weight: 700;
				color: #0f2444;
			}
			.cronovelo-about-split__card-text {
				margin: 0;
				font-size: 38px;
				line-height: 1.46;
				color: #2f4460;
			}
			@media (max-width: 1250px) {
				.cronovelo-about-split__inner {
					grid-template-columns: minmax(0, 0.9fr) minmax(0, 1fr);
				}
				.cronovelo-about-split__cards {
					grid-column: 1 / -1;
					grid-template-columns: repeat(2, minmax(0, 1fr));
				}
				.cronovelo-about-split__headline {
					font-size: 52px;
				}
				.cronovelo-about-split__lead,
				.cronovelo-about-split__body {
					font-size: 30px;
				}
				.cronovelo-about-split__card-title {
					font-size: 34px;
				}
				.cronovelo-about-split__card-text {
					font-size: 24px;
				}
			}
			@media (max-width: 767px) {
				.cronovelo-about-split {
					padding: 14px;
				}
				.cronovelo-about-split__inner {
					grid-template-columns: 1fr;
					gap: 24px;
				}
				.cronovelo-about-split__cards {
					grid-template-columns: 1fr;
				}
				.cronovelo-about-split__eyebrow {
					font-size: 14px;
					margin: 0 0 10px;
				}
				.cronovelo-about-split__headline {
					font-size: 34px;
					margin-bottom: 14px;
				}
				.cronovelo-about-split__lead,
				.cronovelo-about-split__body {
					font-size: 18px;
					margin-bottom: 14px;
				}
				.cronovelo-about-split__card {
					padding: 20px;
				}
				.cronovelo-about-split__card-title {
					font-size: 24px;
				}
				.cronovelo-about-split__card-text {
					font-size: 18px;
				}
			}
		</style>
		<?php
	}
}

function cronovelo_register_about_split_widget( $widgets_manager ) {
	$widget = new \Cronovelo_About_Split_Widget();

	if ( method_exists( $widgets_manager, 'register' ) ) {
		$widgets_manager->register( $widget );
	} else {
		$widgets_manager->register_widget_type( $widget );
	}
}
add_action( 'elementor/widgets/register', 'cronovelo_register_about_split_widget' );