<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cronovelo_Services_Grid_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'cronovelo_services_grid';
	}

	public function get_title() {
		return esc_html__( 'Cronovelo Ydelser Grid', 'cronovelo-addons' );
	}

	public function get_icon() {
		return 'eicon-icon-box';
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
				'default'     => esc_html__( 'Hvad kan Munkhauge Tømrer hjælpe dig med?', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'section_description',
			[
				'label'       => esc_html__( 'Beskrivelse', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => esc_html__( 'Vi leverer solidt tømrerarbejde i høj kvalitet, uanset om det gælder mindre reparationer eller store byggeprojekter. Se et udvalg af vores ydelser herunder.', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'card_icon',
			[
				'label'       => esc_html__( 'Ikon (emoji eller tekst)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '🏠',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'card_title',
			[
				'label'       => esc_html__( 'Kort titel', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Renovering & Modernisering', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'card_text',
			[
				'label'       => esc_html__( 'Kort tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => esc_html__( 'Trænger huset til en kærlig hånd? Vi fornyer ældre boliger med respekt for den eksisterende stil, uanset om det gælder efterisolering, nye gipsvægge eller totalrenovering.', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'service_cards',
			[
				'label'       => esc_html__( 'Ydelseskort', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ card_title }}}',
				'default'     => [
					[
						'card_icon'  => '🏠',
						'card_title' => esc_html__( 'Renovering & Modernisering', 'cronovelo-addons' ),
						'card_text'  => esc_html__( 'Trænger huset til en kærlig hånd? We make it shine. Vi fornyer ældre boliger med respekt for den eksisterende stil, uanset om det gælder efterisolering, nye gipsvægge eller totalrenovering.', 'cronovelo-addons' ),
					],
					[
						'card_icon'  => '📐',
						'card_title' => esc_html__( 'Tag & Facade', 'cronovelo-addons' ),
						'card_text'  => esc_html__( 'Et tæt og flot tag beskytter dit hjem mod vind og vejr. Vi lægger nyt tag, reparerer det gamle og monterer vedligeholdelsesvenlige facadebeklædninger.', 'cronovelo-addons' ),
					],
					[
						'card_icon'  => '🪟',
						'card_title' => esc_html__( 'Udskiftning af Døre & Vinduer', 'cronovelo-addons' ),
						'card_text'  => esc_html__( 'Sænk varmeregningen og giv boligen et visuelt løft. Vi opmåler og monterer energivenlige døre og vinduer i højeste kvalitet.', 'cronovelo-addons' ),
					],
					[
						'card_icon'  => '🪵',
						'card_title' => esc_html__( 'Træterrasser & Udemiljø', 'cronovelo-addons' ),
						'card_text'  => esc_html__( 'Få mest muligt ud af sommeren med en skræddersyet træterrasse. Vi designer og bygger terrasser, pergolaer og skure, der harmonerer med haven og husets arkitektur.', 'cronovelo-addons' ),
					],
					[
						'card_icon'  => '🔎',
						'card_title' => esc_html__( 'Køkken & Inventar', 'cronovelo-addons' ),
						'card_text'  => esc_html__( 'Køkkenet er husets hjerte. Vi står for professionel montering af dit nye elementkøkken og hjælper med specialtilpassede løsninger, der optimerer din plads.', 'cronovelo-addons' ),
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
			'section_background',
			[
				'label'     => esc_html__( 'Baggrundsfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f4f5f7',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-services' => 'background-color: {{VALUE}};',
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
					'top'      => 64,
					'right'    => 24,
					'bottom'   => 64,
					'left'     => 24,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-services' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'px' => [
						'min'  => 480,
						'max'  => 1600,
						'step' => 10,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 1240,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-services__inner' => 'max-width: {{SIZE}}{{UNIT}};',
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
				'default'   => '#13233f',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-services__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .cronovelo-services__title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'description_style',
			[
				'label' => esc_html__( 'Beskrivelse', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#5f708c',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-services__description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .cronovelo-services__description',
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
			'card_background',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-services__card' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .cronovelo-services__card',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .cronovelo-services__card',
			]
		);

		$this->add_responsive_control(
			'card_radius',
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
					'{{WRAPPER}} .cronovelo-services__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top'      => 24,
					'right'    => 24,
					'bottom'   => 24,
					'left'     => 24,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-services__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_icon_style',
			[
				'label' => esc_html__( 'Ikon', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_icon_color',
			[
				'label'     => esc_html__( 'Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f0b83a',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-services__icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'card_icon_typography',
				'selector' => '{{WRAPPER}} .cronovelo-services__icon',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_title_style',
			[
				'label' => esc_html__( 'Kort titel', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_title_color',
			[
				'label'     => esc_html__( 'Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#13233f',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-services__card-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'card_title_typography',
				'selector' => '{{WRAPPER}} .cronovelo-services__card-title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_text_style',
			[
				'label' => esc_html__( 'Kort tekst', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_text_color',
			[
				'label'     => esc_html__( 'Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#3c4d68',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-services__card-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'card_text_typography',
				'selector' => '{{WRAPPER}} .cronovelo-services__card-text',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$cards    = ! empty( $settings['service_cards'] ) && is_array( $settings['service_cards'] ) ? $settings['service_cards'] : [];

		if ( empty( $cards ) ) {
			return;
		}

		$columns_desktop = ! empty( $settings['columns_desktop'] ) ? max( 1, (int) $settings['columns_desktop'] ) : 3;
		$columns_tablet  = ! empty( $settings['columns_tablet'] ) ? max( 1, (int) $settings['columns_tablet'] ) : 2;
		$columns_mobile  = ! empty( $settings['columns_mobile'] ) ? max( 1, (int) $settings['columns_mobile'] ) : 1;

		$grid_vars = sprintf(
			'--cronovelo-cols-desktop:%1$d;--cronovelo-cols-tablet:%2$d;--cronovelo-cols-mobile:%3$d;',
			$columns_desktop,
			$columns_tablet,
			$columns_mobile
		);
		?>
		<section class="cronovelo-services">
			<div class="cronovelo-services__inner">
				<?php if ( ! empty( $settings['section_title'] ) ) : ?>
					<h2 class="cronovelo-services__title"><?php echo esc_html( $settings['section_title'] ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $settings['section_description'] ) ) : ?>
					<p class="cronovelo-services__description"><?php echo esc_html( $settings['section_description'] ); ?></p>
				<?php endif; ?>

				<div class="cronovelo-services__grid" style="<?php echo esc_attr( $grid_vars ); ?>">
					<?php foreach ( $cards as $card ) : ?>
						<article class="cronovelo-services__card">
							<?php if ( ! empty( $card['card_icon'] ) ) : ?>
								<div class="cronovelo-services__icon" aria-hidden="true"><?php echo esc_html( $card['card_icon'] ); ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $card['card_title'] ) ) : ?>
								<h3 class="cronovelo-services__card-title"><?php echo esc_html( $card['card_title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $card['card_text'] ) ) : ?>
								<p class="cronovelo-services__card-text"><?php echo esc_html( $card['card_text'] ); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<style>
			.cronovelo-services {
				padding: 64px 24px;
				background: #f4f5f7;
			}
			.cronovelo-services__inner {
				max-width: 1240px;
				margin: 0 auto;
			}
			.cronovelo-services__title {
				margin: 0;
				text-align: center;
				font-size: 52px;
				line-height: 1.14;
				font-weight: 700;
				color: #13233f;
			}
			.cronovelo-services__description {
				margin: 18px auto 40px;
				max-width: 760px;
				text-align: center;
				font-size: 22px;
				line-height: 1.45;
				color: #5f708c;
			}
			.cronovelo-services__grid {
				display: grid;
				grid-template-columns: repeat(var(--cronovelo-cols-desktop), minmax(0, 1fr));
				gap: 24px;
			}
			.cronovelo-services__card {
				background: #ffffff;
				border: 1px solid #d9e1ea;
				border-radius: 8px;
				padding: 24px;
			}
			.cronovelo-services__icon {
				margin-bottom: 14px;
				font-size: 34px;
				line-height: 1;
				color: #f0b83a;
			}
			.cronovelo-services__card-title {
				margin: 0 0 14px;
				font-size: 39px;
				line-height: 1.2;
				font-weight: 700;
				color: #13233f;
			}
			.cronovelo-services__card-text {
				margin: 0;
				font-size: 31px;
				line-height: 1.52;
				color: #3c4d68;
			}
			@media (max-width: 1024px) {
				.cronovelo-services__title {
					font-size: 38px;
				}
				.cronovelo-services__description {
					font-size: 18px;
				}
				.cronovelo-services__grid {
					grid-template-columns: repeat(var(--cronovelo-cols-tablet), minmax(0, 1fr));
				}
				.cronovelo-services__card-title {
					font-size: 30px;
				}
				.cronovelo-services__card-text {
					font-size: 23px;
				}
			}
			@media (max-width: 767px) {
				.cronovelo-services {
					padding: 40px 16px;
				}
				.cronovelo-services__title {
					font-size: 30px;
				}
				.cronovelo-services__description {
					font-size: 16px;
					margin-bottom: 24px;
				}
				.cronovelo-services__grid {
					grid-template-columns: repeat(var(--cronovelo-cols-mobile), minmax(0, 1fr));
					gap: 16px;
				}
				.cronovelo-services__card {
					padding: 18px;
				}
				.cronovelo-services__card-title {
					font-size: 24px;
				}
				.cronovelo-services__card-text {
					font-size: 18px;
				}
			}
		</style>
		<?php
	}
}

function cronovelo_register_services_grid_widget( $widgets_manager ) {
	$widget = new \Cronovelo_Services_Grid_Widget();

	if ( method_exists( $widgets_manager, 'register' ) ) {
		$widgets_manager->register( $widget );
	} else {
		$widgets_manager->register_widget_type( $widget );
	}
}
add_action( 'elementor/widgets/register', 'cronovelo_register_services_grid_widget' );
