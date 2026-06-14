<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cronovelo_Offer_Grid_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'cronovelo_offer_grid';
	}

	public function get_title() {
		return esc_html__( 'Cronovelo Tilbyder Grid', 'cronovelo-addons' );
	}

	public function get_icon() {
		return 'eicon-info-box';
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
			'section_title',
			[
				'label'       => esc_html__( 'Titel', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Vi tilbyder', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'item_icon',
			[
				'label'       => esc_html__( 'Ikon (emoji eller tekst)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '⚠️',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_text',
			[
				'label'       => esc_html__( 'Tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Autohjælp i hele DK/EU', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'offer_items',
			[
				'label'       => esc_html__( 'Kort', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_text }}}',
				'default'     => [
					[
						'item_icon' => '⚠️',
						'item_text' => esc_html__( 'Autohjælp i hele DK/EU', 'cronovelo-addons' ),
					],
					[
						'item_icon' => '💳',
						'item_text' => esc_html__( 'Kontokort og billån', 'cronovelo-addons' ),
					],
					[
						'item_icon' => '30',
						'item_text' => esc_html__( '3 års garanti på reservedele', 'cronovelo-addons' ),
					],
					[
						'item_icon' => '30',
						'item_text' => esc_html__( 'Dæk med 3 års garanti', 'cronovelo-addons' ),
					],
					[
						'item_icon' => '🚗',
						'item_text' => esc_html__( 'Forsikringsskader', 'cronovelo-addons' ),
					],
					[
						'item_icon' => '🤝',
						'item_text' => esc_html__( 'AutoMester Serviceaftale', 'cronovelo-addons' ),
					],
				],
			]
		);

		$this->add_control(
			'columns_desktop',
			[
				'label'   => esc_html__( 'Kolonner desktop', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 4,
			]
		);

		$this->add_control(
			'columns_tablet',
			[
				'label'   => esc_html__( 'Kolonner tablet', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 3,
			]
		);

		$this->add_control(
			'columns_mobile',
			[
				'label'   => esc_html__( 'Kolonner mobil', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 2,
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
			'section_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ededef',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-offer-grid' => 'background-color: {{VALUE}};',
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
					'top'      => 54,
					'right'    => 24,
					'bottom'   => 54,
					'left'     => 24,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-offer-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'px' => [ 'min' => 680, 'max' => 1800, 'step' => 10 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 1320,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-offer-grid__inner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'layout_gap',
			[
				'label'      => esc_html__( 'Afstand mellem venstre og højre', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 140, 'step' => 1 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 42,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-offer-grid__inner' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cards_gap',
			[
				'label'      => esc_html__( 'Afstand mellem kort', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 60, 'step' => 1 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-offer-grid__cards' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Titel', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1c2f4b',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-offer-grid__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .cronovelo-offer-grid__title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_style',
			[
				'label' => esc_html__( 'Kort', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f8f8f9',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-offer-grid__card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_border_color',
			[
				'label'     => esc_html__( 'Kant farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e5e5e8',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-offer-grid__card' => 'border-color: {{VALUE}};',
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
					'top'      => 26,
					'right'    => 16,
					'bottom'   => 26,
					'left'     => 16,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-offer-grid__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top'      => 12,
					'right'    => 12,
					'bottom'   => 12,
					'left'     => 12,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-offer-grid__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Ikon farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ef7f1f',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-offer-grid__icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_typography',
				'selector' => '{{WRAPPER}} .cronovelo-offer-grid__icon',
			]
		);

		$this->add_control(
			'card_text_color',
			[
				'label'     => esc_html__( 'Tekst farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#182a45',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-offer-grid__text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'card_text_typography',
				'selector' => '{{WRAPPER}} .cronovelo-offer-grid__text',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = ! empty( $settings['offer_items'] ) && is_array( $settings['offer_items'] ) ? $settings['offer_items'] : [];
		$has_title = ! empty( $settings['section_title'] );

		if ( empty( $items ) ) {
			return;
		}

		$columns_desktop = ! empty( $settings['columns_desktop'] ) ? max( 1, (int) $settings['columns_desktop'] ) : 3;
		$columns_tablet  = ! empty( $settings['columns_tablet'] ) ? max( 1, (int) $settings['columns_tablet'] ) : 2;
		$columns_mobile  = ! empty( $settings['columns_mobile'] ) ? max( 1, (int) $settings['columns_mobile'] ) : 1;
		$inner_class     = 'cronovelo-offer-grid__inner' . ( $has_title ? '' : ' cronovelo-offer-grid__inner--no-title' );
		?>
		<section class="cronovelo-offer-grid">
			<div class="<?php echo esc_attr( $inner_class ); ?>">
				<?php if ( $has_title ) : ?>
					<div class="cronovelo-offer-grid__left">
						<h2 class="cronovelo-offer-grid__title"><?php echo esc_html( $settings['section_title'] ); ?></h2>
					</div>
				<?php endif; ?>

				<div class="cronovelo-offer-grid__right">
					<div class="cronovelo-offer-grid__cards" style="--offer-cols-desktop: <?php echo (int) $columns_desktop; ?>; --offer-cols-tablet: <?php echo (int) $columns_tablet; ?>; --offer-cols-mobile: <?php echo (int) $columns_mobile; ?>;">
						<?php foreach ( $items as $item ) : ?>
							<article class="cronovelo-offer-grid__card">
								<?php if ( ! empty( $item['item_icon'] ) ) : ?>
									<div class="cronovelo-offer-grid__icon" aria-hidden="true"><?php echo esc_html( $item['item_icon'] ); ?></div>
								<?php endif; ?>
								<?php if ( ! empty( $item['item_text'] ) ) : ?>
									<div class="cronovelo-offer-grid__text"><?php echo esc_html( $item['item_text'] ); ?></div>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>

		<style>
			.cronovelo-offer-grid {
				background: #ededef;
				padding: 54px 24px;
			}
			.cronovelo-offer-grid__inner {
				max-width: 1320px;
				margin: 0 auto;
				display: grid;
				grid-template-columns: minmax(200px, 270px) minmax(0, 1fr);
				column-gap: 42px;
				align-items: start;
			}
			.cronovelo-offer-grid__inner--no-title {
				grid-template-columns: minmax(0, 1fr);
				column-gap: 0;
			}
			.cronovelo-offer-grid__title {
				margin: 0;
				font-size: 58px;
				line-height: 1.1;
				font-weight: 700;
				color: #1c2f4b;
			}
			.cronovelo-offer-grid__cards {
				display: grid;
				grid-template-columns: repeat(var(--offer-cols-desktop), minmax(0, 1fr));
				gap: 14px;
			}
			.cronovelo-offer-grid__card {
				background: #f8f8f9;
				border: 1px solid #e5e5e8;
				border-radius: 12px;
				padding: 26px 16px;
				min-height: 160px;
				display: flex;
				flex-direction: column;
				justify-content: center;
				align-items: center;
				text-align: center;
			}
			.cronovelo-offer-grid__icon {
				font-size: 58px;
				line-height: 1;
				color: #ef7f1f;
				margin-bottom: 18px;
			}
			.cronovelo-offer-grid__text {
				font-size: 27px;
				line-height: 1.35;
				font-weight: 600;
				color: #182a45;
			}
			@media (max-width: 1200px) {
				.cronovelo-offer-grid__title {
					font-size: 46px;
				}
				.cronovelo-offer-grid__icon {
					font-size: 50px;
				}
				.cronovelo-offer-grid__text {
					font-size: 22px;
				}
			}
			@media (max-width: 991px) {
				.cronovelo-offer-grid__inner {
					grid-template-columns: 1fr;
					row-gap: 20px;
				}
				.cronovelo-offer-grid__cards {
					grid-template-columns: repeat(var(--offer-cols-tablet), minmax(0, 1fr));
				}
			}
			@media (max-width: 767px) {
				.cronovelo-offer-grid {
					padding: 34px 14px;
				}
				.cronovelo-offer-grid__title {
					font-size: 36px;
				}
				.cronovelo-offer-grid__cards {
					grid-template-columns: repeat(var(--offer-cols-mobile), minmax(0, 1fr));
				}
				.cronovelo-offer-grid__card {
					min-height: 130px;
					padding: 20px 12px;
				}
				.cronovelo-offer-grid__icon {
					font-size: 42px;
					margin-bottom: 12px;
				}
				.cronovelo-offer-grid__text {
					font-size: 17px;
				}
			}
		</style>
		<?php
	}
}

function cronovelo_register_offer_grid_widget( $widgets_manager ) {
	$widget = new \Cronovelo_Offer_Grid_Widget();

	if ( method_exists( $widgets_manager, 'register' ) ) {
		$widgets_manager->register( $widget );
	} else {
		$widgets_manager->register_widget_type( $widget );
	}
}
add_action( 'elementor/widgets/register', 'cronovelo_register_offer_grid_widget' );