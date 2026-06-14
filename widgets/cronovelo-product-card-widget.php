<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Afvis direkte adgang
}

class Cronovelo_Product_Card_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'cronovelo_product_card';
	}

	public function get_title() {
		return esc_html__( 'Cronovelo Produktkort Grid', 'cronovelo-addons' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'cronovelo-widgets' ];
	}

	private function get_woo_category_options() {
		$options = [];

		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return $options;
		}

		$terms = get_terms(
			[
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
			]
		);

		if ( is_wp_error( $terms ) ) {
			return $options;
		}

		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}

		return $options;
	}

	protected function register_controls() {
		$this->start_controls_section(
			'data_source_section',
			[
				'label' => esc_html__( 'Datakilde', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'data_source',
			[
				'label'   => esc_html__( 'Hent kort fra', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => [
					'manual'         => esc_html__( 'Manuel liste', 'cronovelo-addons' ),
					'woo_products'   => esc_html__( 'WooCommerce produkter', 'cronovelo-addons' ),
					'post_categories' => esc_html__( 'WordPress kategorier', 'cronovelo-addons' ),
				],
			]
		);

		$this->add_control(
			'dynamic_limit',
			[
				'label'     => esc_html__( 'Antal dynamiske kort', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 12,
				'min'       => 1,
				'max'       => 60,
				'condition' => [
					'data_source!' => 'manual',
				],
			]
		);

		$this->add_control(
			'woo_categories',
			[
				'label'       => esc_html__( 'Woo kategorier (valgfrit)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_woo_category_options(),
				'condition'   => [
					'data_source' => 'woo_products',
				],
			]
		);

		$this->add_control(
			'dynamic_action',
			[
				'label'     => esc_html__( 'Knap handling (dynamiske kort)', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'link',
				'options'   => [
					'link'  => esc_html__( 'Ga til link', 'cronovelo-addons' ),
					'modal' => esc_html__( 'Aabn info boks', 'cronovelo-addons' ),
					'email' => esc_html__( 'Send mail', 'cronovelo-addons' ),
				],
				'condition' => [
					'data_source!' => 'manual',
				],
			]
		);

		$this->add_control(
			'dynamic_button_text',
			[
				'label'     => esc_html__( 'Knap tekst (dynamiske kort)', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Laes mere', 'cronovelo-addons' ),
				'condition' => [
					'data_source!' => 'manual',
				],
			]
		);

		$this->add_control(
			'dynamic_email',
			[
				'label'       => esc_html__( 'Email (dynamiske kort)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'info@ditfirma.dk',
				'condition'   => [
					'data_source!'   => 'manual',
					'dynamic_action' => 'email',
				],
			]
		);

		$this->add_control(
			'dynamic_modal_cta_text',
			[
				'label'     => esc_html__( 'Info boks knap tekst (dynamisk)', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Kontakt os', 'cronovelo-addons' ),
				'condition' => [
					'data_source!'   => 'manual',
					'dynamic_action' => 'modal',
				],
			]
		);

		$this->add_control(
			'dynamic_modal_cta_link',
			[
				'label'       => esc_html__( 'Info boks knap link (dynamisk)', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://',
				'default'     => [ 'url' => '#' ],
				'condition'   => [
					'data_source!'   => 'manual',
					'dynamic_action' => 'modal',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cards_section',
			[
				'label' => esc_html__( 'Manuelle kort', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'card_type',
			[
				'label'   => esc_html__( 'Korttype', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'product',
				'options' => [
					'product'  => esc_html__( 'Produkt kort', 'cronovelo-addons' ),
					'category' => esc_html__( 'Kategori kort', 'cronovelo-addons' ),
				],
			]
		);

		$repeater->add_control(
			'card_image',
			[
				'label'   => esc_html__( 'Billede', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'card_title',
			[
				'label'       => esc_html__( 'Titel', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'MULTIONE 5.2K', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'card_price',
			[
				'label'       => esc_html__( 'Pris tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Ring for pris', 'cronovelo-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Knap tekst', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Laes mere', 'cronovelo-addons' ),
			]
		);

		$repeater->add_control(
			'button_action',
			[
				'label'   => esc_html__( 'Knap handling', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'modal',
				'options' => [
					'modal' => esc_html__( 'Aabn info boks', 'cronovelo-addons' ),
					'link'  => esc_html__( 'Ga til link', 'cronovelo-addons' ),
					'email' => esc_html__( 'Send mail', 'cronovelo-addons' ),
				],
			]
		);

		$repeater->add_control(
			'button_link',
			[
				'label'       => esc_html__( 'Knap link', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://',
				'condition'   => [
					'button_action' => 'link',
				],
			]
		);

		$repeater->add_control(
			'button_email',
			[
				'label'       => esc_html__( 'Email adresse', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'info@ditfirma.dk',
				'condition'   => [
					'button_action' => 'email',
				],
			]
		);

		$repeater->add_control(
			'modal_title',
			[
				'label'       => esc_html__( 'Info boks titel', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'MULTIONE 5.2K', 'cronovelo-addons' ),
				'label_block' => true,
				'condition'   => [
					'button_action' => 'modal',
				],
			]
		);

		$repeater->add_control(
			'modal_price',
			[
				'label'       => esc_html__( 'Info boks pris tekst', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Ring for pris', 'cronovelo-addons' ),
				'label_block' => true,
				'condition'   => [
					'button_action' => 'modal',
				],
			]
		);

		$repeater->add_control(
			'modal_description',
			[
				'label'     => esc_html__( 'Info boks beskrivelse', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXTAREA,
				'rows'      => 8,
				'default'   => esc_html__( 'Skriv en kort produktbeskrivelse her.', 'cronovelo-addons' ),
				'condition' => [
					'button_action' => 'modal',
				],
			]
		);

		$repeater->add_control(
			'modal_cta_text',
			[
				'label'     => esc_html__( 'Info boks knap tekst', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Kontakt os om denne maskine', 'cronovelo-addons' ),
				'condition' => [
					'button_action' => 'modal',
				],
			]
		);

		$repeater->add_control(
			'modal_cta_link',
			[
				'label'       => esc_html__( 'Info boks knap link', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://',
				'default'     => [
					'url' => '#',
				],
				'condition'   => [
					'button_action' => 'modal',
				],
			]
		);

		$this->add_control(
			'cards',
			[
				'label'       => esc_html__( 'Kort liste', 'cronovelo-addons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ card_title }}}',
				'default'     => [
					[
						'card_type'     => 'product',
						'card_title'    => esc_html__( 'MULTIONE 2.3', 'cronovelo-addons' ),
						'card_price'    => esc_html__( 'Ring for pris', 'cronovelo-addons' ),
						'button_text'   => esc_html__( 'Laes mere', 'cronovelo-addons' ),
						'button_action' => 'modal',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_section',
			[
				'label' => esc_html__( 'Layout', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'visible_type',
			[
				'label'   => esc_html__( 'Vis korttype', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all'      => esc_html__( 'Alle kort', 'cronovelo-addons' ),
					'product'  => esc_html__( 'Kun produkt kort', 'cronovelo-addons' ),
					'category' => esc_html__( 'Kun kategori kort', 'cronovelo-addons' ),
				],
			]
		);

		$this->add_control(
			'enable_frontend_filter',
			[
				'label'        => esc_html__( 'Vis filter knapper pa frontend', 'cronovelo-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Ja', 'cronovelo-addons' ),
				'label_off'    => esc_html__( 'Nej', 'cronovelo-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_type_badge',
			[
				'label'        => esc_html__( 'Vis type label pa kort', 'cronovelo-addons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Ja', 'cronovelo-addons' ),
				'label_off'    => esc_html__( 'Nej', 'cronovelo-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'columns_desktop',
			[
				'label'   => esc_html__( 'Kolonner desktop', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 6,
			]
		);

		$this->add_control(
			'rows_desktop',
			[
				'label'   => esc_html__( 'Raekker desktop', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 20,
			]
		);

		$this->add_control(
			'columns_tablet',
			[
				'label'   => esc_html__( 'Kolonner tablet', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 6,
			]
		);

		$this->add_control(
			'rows_tablet',
			[
				'label'   => esc_html__( 'Raekker tablet', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 20,
			]
		);

		$this->add_control(
			'columns_mobile',
			[
				'label'   => esc_html__( 'Kolonner mobil', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 3,
			]
		);

		$this->add_control(
			'rows_mobile',
			[
				'label'   => esc_html__( 'Raekker mobil', 'cronovelo-addons' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 20,
			]
		);

		$this->add_control(
			'grid_gap',
			[
				'label'      => esc_html__( 'Afstand mellem kort', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 60, 'step' => 1 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_style_section',
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
					'{{WRAPPER}} .cronovelo-product-card' => 'background-color: {{VALUE}};',
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
					'top'      => 20,
					'right'    => 20,
					'bottom'   => 18,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_radius',
			[
				'label'      => esc_html__( 'Hjorner', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 4,
					'right'    => 4,
					'bottom'   => 4,
					'left'     => 4,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-product-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .cronovelo-product-card',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .cronovelo-product-card',
			]
		);

		$this->add_responsive_control(
			'card_align',
			[
				'label'     => esc_html__( 'Indhold justering', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
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
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-product-card' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'image_style_section',
			[
				'label' => esc_html__( 'Billede', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'Hojde', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [ 'min' => 120, 'max' => 520, 'step' => 5 ],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 170,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-product-card__image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_radius',
			[
				'label'      => esc_html__( 'Hjorner', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-product-card__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'type_badge_style_section',
			[
				'label' => esc_html__( 'Type label', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'type_badge_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#eef2f5',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-product-card__type' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'type_badge_color',
			[
				'label'     => esc_html__( 'Tekstfarve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1f2a33',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-product-card__type' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style_section',
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
				'default'   => '#00131f',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-product-card__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .cronovelo-product-card__title',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'price_style_section',
			[
				'label' => esc_html__( 'Pris tekst', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Farve', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f47d20',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-product-card__price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .cronovelo-product-card__price',
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
			'button_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'cronovelo-addons' ),
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#001826',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-product-card__button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .cronovelo-product-card__button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'cronovelo-addons' ),
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label'     => esc_html__( 'Baggrund (Hover)', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f47d20',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-product-card__button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => esc_html__( 'Tekst (Hover)', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-product-card__button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .cronovelo-product-card__button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'top'      => 12,
					'right'    => 16,
					'bottom'   => 12,
					'left'     => 16,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-product-card__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label'      => esc_html__( 'Hjorner', 'cronovelo-addons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'      => 3,
					'right'    => 3,
					'bottom'   => 3,
					'left'     => 3,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cronovelo-product-card__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'filter_style_section',
			[
				'label' => esc_html__( 'Filter knapper', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'filter_button_bg',
			[
				'label'     => esc_html__( 'Baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f0f3f5',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-filter-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_button_color',
			[
				'label'     => esc_html__( 'Tekst', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#1f2a33',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-filter-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_button_active_bg',
			[
				'label'     => esc_html__( 'Baggrund aktiv', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#001826',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-filter-btn.is-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_button_active_color',
			[
				'label'     => esc_html__( 'Tekst aktiv', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-filter-btn.is-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'modal_style_section',
			[
				'label' => esc_html__( 'Info boks', 'cronovelo-addons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'modal_overlay_bg',
			[
				'label'     => esc_html__( 'Overlay baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.45)',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-modal-overlay' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'modal_bg',
			[
				'label'     => esc_html__( 'Boks baggrund', 'cronovelo-addons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f2f2f2',
				'selectors' => [
					'{{WRAPPER}} .cronovelo-modal' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function build_manual_cards( $settings ) {
		$cards = [];

		if ( empty( $settings['cards'] ) || ! is_array( $settings['cards'] ) ) {
			return $cards;
		}

		foreach ( $settings['cards'] as $card ) {
			$cards[] = [
				'card_type'         => ! empty( $card['card_type'] ) ? $card['card_type'] : 'product',
				'image_url'         => ! empty( $card['card_image']['url'] ) ? $card['card_image']['url'] : '',
				'title'             => ! empty( $card['card_title'] ) ? $card['card_title'] : '',
				'price'             => ! empty( $card['card_price'] ) ? $card['card_price'] : '',
				'button_text'       => ! empty( $card['button_text'] ) ? $card['button_text'] : esc_html__( 'Laes mere', 'cronovelo-addons' ),
				'button_action'     => ! empty( $card['button_action'] ) ? $card['button_action'] : 'modal',
				'button_link'       => ! empty( $card['button_link'] ) ? $card['button_link'] : [ 'url' => '' ],
				'button_email'      => ! empty( $card['button_email'] ) ? $card['button_email'] : '',
				'modal_title'       => ! empty( $card['modal_title'] ) ? $card['modal_title'] : ( ! empty( $card['card_title'] ) ? $card['card_title'] : '' ),
				'modal_price'       => ! empty( $card['modal_price'] ) ? $card['modal_price'] : '',
				'modal_description' => ! empty( $card['modal_description'] ) ? $card['modal_description'] : '',
				'modal_cta_text'    => ! empty( $card['modal_cta_text'] ) ? $card['modal_cta_text'] : '',
				'modal_cta_link'    => ! empty( $card['modal_cta_link'] ) ? $card['modal_cta_link'] : [ 'url' => '' ],
			];
		}

		return $cards;
	}

	private function build_dynamic_cards( $settings ) {
		$cards = [];
		$source = ! empty( $settings['data_source'] ) ? $settings['data_source'] : 'manual';
		$limit = ! empty( $settings['dynamic_limit'] ) ? max( 1, (int) $settings['dynamic_limit'] ) : 12;
		$action = ! empty( $settings['dynamic_action'] ) ? $settings['dynamic_action'] : 'link';
		$button_text = ! empty( $settings['dynamic_button_text'] ) ? $settings['dynamic_button_text'] : esc_html__( 'Laes mere', 'cronovelo-addons' );
		$dynamic_email = ! empty( $settings['dynamic_email'] ) ? $settings['dynamic_email'] : get_option( 'admin_email' );
		$modal_cta_text = ! empty( $settings['dynamic_modal_cta_text'] ) ? $settings['dynamic_modal_cta_text'] : esc_html__( 'Kontakt os', 'cronovelo-addons' );
		$modal_cta_link = ! empty( $settings['dynamic_modal_cta_link'] ) ? $settings['dynamic_modal_cta_link'] : [ 'url' => '#' ];

		if ( 'woo_products' === $source && post_type_exists( 'product' ) ) {
			$query_args = [
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => $limit,
			];

			if ( ! empty( $settings['woo_categories'] ) && is_array( $settings['woo_categories'] ) ) {
				$query_args['tax_query'] = [
					[
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => array_map( 'intval', $settings['woo_categories'] ),
					],
				];
			}

			$query = new \WP_Query( $query_args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$price_text = esc_html__( 'Kontakt for pris', 'cronovelo-addons' );
					if ( function_exists( 'wc_get_product' ) ) {
						$product = wc_get_product( get_the_ID() );
						if ( $product ) {
							$price_value = wp_strip_all_tags( $product->get_price_html() );
							if ( ! empty( $price_value ) ) {
								$price_text = $price_value;
							}
						}
					}

					$cards[] = [
						'card_type'         => 'product',
						'image_url'         => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
						'title'             => get_the_title(),
						'price'             => $price_text,
						'button_text'       => $button_text,
						'button_action'     => $action,
						'button_link'       => [
							'url' => get_permalink(),
						],
						'button_email'      => $dynamic_email,
						'modal_title'       => get_the_title(),
						'modal_price'       => $price_text,
						'modal_description' => has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 70 ),
						'modal_cta_text'    => $modal_cta_text,
						'modal_cta_link'    => $modal_cta_link,
					];
				}
			}

			wp_reset_postdata();
		}

		if ( 'post_categories' === $source ) {
			$terms = get_terms(
				[
					'taxonomy'   => 'category',
					'hide_empty' => true,
					'number'     => $limit,
				]
			);

			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$cards[] = [
						'card_type'         => 'category',
						'image_url'         => '',
						'title'             => $term->name,
						'price'             => sprintf( esc_html__( '%d indlaeg', 'cronovelo-addons' ), (int) $term->count ),
						'button_text'       => $button_text,
						'button_action'     => $action,
						'button_link'       => [
							'url' => get_term_link( $term ),
						],
						'button_email'      => $dynamic_email,
						'modal_title'       => $term->name,
						'modal_price'       => sprintf( esc_html__( '%d indlaeg', 'cronovelo-addons' ), (int) $term->count ),
						'modal_description' => ! empty( $term->description ) ? $term->description : esc_html__( 'Ingen beskrivelse angivet for denne kategori.', 'cronovelo-addons' ),
						'modal_cta_text'    => $modal_cta_text,
						'modal_cta_link'    => $modal_cta_link,
					];
				}
			}
		}

		return $cards;
	}

	private function build_cards( $settings ) {
		$source = ! empty( $settings['data_source'] ) ? $settings['data_source'] : 'manual';

		if ( 'manual' === $source ) {
			return $this->build_manual_cards( $settings );
		}

		return $this->build_dynamic_cards( $settings );
	}

	private function has_multiple_types( $cards ) {
		$types = [];
		foreach ( $cards as $card ) {
			$types[] = ! empty( $card['card_type'] ) ? $card['card_type'] : 'product';
		}
		return count( array_unique( $types ) ) > 1;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$cards = $this->build_cards( $settings );

		if ( empty( $cards ) ) {
			return;
		}

		$visible_type = ! empty( $settings['visible_type'] ) ? $settings['visible_type'] : 'all';
		$show_type_badge = ( ! empty( $settings['show_type_badge'] ) && 'yes' === $settings['show_type_badge'] );
		$frontend_filter = ( ! empty( $settings['enable_frontend_filter'] ) && 'yes' === $settings['enable_frontend_filter'] );
		$has_multiple_types = $this->has_multiple_types( $cards );

		$columns_desktop = max( 1, (int) $settings['columns_desktop'] );
		$rows_desktop = max( 1, (int) $settings['rows_desktop'] );
		$columns_tablet = max( 1, (int) $settings['columns_tablet'] );
		$rows_tablet = max( 1, (int) $settings['rows_tablet'] );
		$columns_mobile = max( 1, (int) $settings['columns_mobile'] );
		$rows_mobile = max( 1, (int) $settings['rows_mobile'] );
		$grid_gap = ! empty( $settings['grid_gap']['size'] ) ? (int) $settings['grid_gap']['size'] : 20;

		$limits = [
			'desktop' => $columns_desktop * $rows_desktop,
			'tablet'  => $columns_tablet * $rows_tablet,
			'mobile'  => $columns_mobile * $rows_mobile,
		];

		$widget_id = 'cronovelo-grid-' . $this->get_id();
		$modal_count = 0;
		?>
		<div id="<?php echo esc_attr( $widget_id ); ?>" class="cronovelo-product-grid-wrapper" data-default-filter="<?php echo esc_attr( $visible_type ); ?>" data-limits="<?php echo esc_attr( wp_json_encode( $limits ) ); ?>">

			<?php if ( $frontend_filter && $has_multiple_types ) : ?>
				<div class="cronovelo-filter-bar" role="tablist" aria-label="<?php echo esc_attr__( 'Filtrer kort', 'cronovelo-addons' ); ?>">
					<button type="button" class="cronovelo-filter-btn" data-filter="all"><?php echo esc_html__( 'Alle', 'cronovelo-addons' ); ?></button>
					<button type="button" class="cronovelo-filter-btn" data-filter="product"><?php echo esc_html__( 'Produkter', 'cronovelo-addons' ); ?></button>
					<button type="button" class="cronovelo-filter-btn" data-filter="category"><?php echo esc_html__( 'Kategorier', 'cronovelo-addons' ); ?></button>
				</div>
			<?php endif; ?>

			<div class="cronovelo-product-grid">
				<?php foreach ( $cards as $index => $card ) : ?>
					<?php
					$card_type = ! empty( $card['card_type'] ) ? $card['card_type'] : 'product';
					$button_action = ! empty( $card['button_action'] ) ? $card['button_action'] : 'link';
					$button_text = ! empty( $card['button_text'] ) ? $card['button_text'] : esc_html__( 'Laes mere', 'cronovelo-addons' );
					$title = ! empty( $card['title'] ) ? $card['title'] : '';
					$price = ! empty( $card['price'] ) ? $card['price'] : '';
					$image_url = ! empty( $card['image_url'] ) ? $card['image_url'] : '';
					$modal_id = $widget_id . '-modal-' . $index;
					$button_key = 'card_btn_' . $this->get_id() . '_' . $index;
					?>
					<div class="cronovelo-grid__item" data-card-type="<?php echo esc_attr( $card_type ); ?>">
						<div class="cronovelo-product-card">
							<?php if ( ! empty( $image_url ) ) : ?>
								<img class="cronovelo-product-card__image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
							<?php endif; ?>

							<?php if ( $show_type_badge ) : ?>
								<span class="cronovelo-product-card__type">
									<?php echo esc_html( 'category' === $card_type ? __( 'Kategori', 'cronovelo-addons' ) : __( 'Produkt', 'cronovelo-addons' ) ); ?>
								</span>
							<?php endif; ?>

							<?php if ( ! empty( $title ) ) : ?>
								<h3 class="cronovelo-product-card__title"><?php echo esc_html( $title ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $price ) ) : ?>
								<p class="cronovelo-product-card__price"><?php echo esc_html( $price ); ?></p>
							<?php endif; ?>

							<?php if ( 'modal' === $button_action ) : ?>
								<?php $modal_count++; ?>
								<button type="button" class="cronovelo-product-card__button js-cronovelo-open-modal" data-modal-id="<?php echo esc_attr( $modal_id ); ?>">
									<?php echo esc_html( $button_text ); ?>
								</button>
							<?php elseif ( 'email' === $button_action ) : ?>
								<?php $email_href = 'mailto:' . sanitize_email( ! empty( $card['button_email'] ) ? $card['button_email'] : get_option( 'admin_email' ) ); ?>
								<a class="cronovelo-product-card__button" href="<?php echo esc_attr( $email_href ); ?>">
									<?php echo esc_html( $button_text ); ?>
								</a>
							<?php else : ?>
								<?php
								$this->add_render_attribute( $button_key, 'class', 'cronovelo-product-card__button' );

								if ( ! empty( $card['button_link']['url'] ) ) {
									$this->add_render_attribute( $button_key, 'href', $card['button_link']['url'] );
								}

								if ( ! empty( $card['button_link']['is_external'] ) ) {
									$this->add_render_attribute( $button_key, 'target', '_blank' );
								}

								if ( ! empty( $card['button_link']['nofollow'] ) ) {
									$this->add_render_attribute( $button_key, 'rel', 'nofollow' );
								}
								?>
								<a <?php $this->print_render_attribute_string( $button_key ); ?>>
									<?php echo esc_html( $button_text ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>

					<?php if ( 'modal' === $button_action ) : ?>
						<div class="cronovelo-modal-overlay" id="<?php echo esc_attr( $modal_id ); ?>" aria-hidden="true">
							<div class="cronovelo-modal" role="dialog" aria-modal="true">
								<button type="button" class="cronovelo-modal__close js-cronovelo-close-modal" aria-label="<?php echo esc_attr__( 'Luk', 'cronovelo-addons' ); ?>">&times;</button>

								<div class="cronovelo-modal__layout">
									<div class="cronovelo-modal__media">
										<?php if ( ! empty( $image_url ) ) : ?>
											<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
										<?php endif; ?>
									</div>

									<div class="cronovelo-modal__content">
										<h3 class="cronovelo-modal__title">
											<?php echo esc_html( ! empty( $card['modal_title'] ) ? $card['modal_title'] : $title ); ?>
										</h3>
										<?php if ( ! empty( $card['modal_price'] ) ) : ?>
											<p class="cronovelo-modal__price"><?php echo esc_html( $card['modal_price'] ); ?></p>
										<?php endif; ?>

										<?php if ( ! empty( $card['modal_description'] ) ) : ?>
											<div class="cronovelo-modal__description">
												<?php echo wpautop( wp_kses_post( $card['modal_description'] ) ); ?>
											</div>
										<?php endif; ?>

										<?php if ( ! empty( $card['modal_cta_text'] ) && ! empty( $card['modal_cta_link']['url'] ) ) : ?>
											<?php
											$modal_cta_attr = $button_key . '_modal';
											$this->add_render_attribute( $modal_cta_attr, 'class', 'cronovelo-modal__cta' );
											$this->add_render_attribute( $modal_cta_attr, 'href', $card['modal_cta_link']['url'] );

											if ( ! empty( $card['modal_cta_link']['is_external'] ) ) {
												$this->add_render_attribute( $modal_cta_attr, 'target', '_blank' );
											}

											if ( ! empty( $card['modal_cta_link']['nofollow'] ) ) {
												$this->add_render_attribute( $modal_cta_attr, 'rel', 'nofollow' );
											}
											?>
											<a <?php $this->print_render_attribute_string( $modal_cta_attr ); ?>>
												<?php echo esc_html( $card['modal_cta_text'] ); ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>

		<style>
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-filter-bar {
				display: flex;
				gap: 10px;
				flex-wrap: wrap;
				margin-bottom: 14px;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-filter-btn {
				border: 0;
				padding: 9px 14px;
				border-radius: 99px;
				font-size: 14px;
				font-weight: 600;
				cursor: pointer;
				transition: all 0.2s ease;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-grid {
				display: grid;
				grid-template-columns: repeat(<?php echo (int) $columns_desktop; ?>, minmax(0, 1fr));
				gap: <?php echo (int) $grid_gap; ?>px;
				align-items: stretch;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-grid__item {
				display: block;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card {
				display: block;
				height: 100%;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__image {
				display: block;
				width: 100%;
				object-fit: cover;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__type {
				display: inline-block;
				padding: 4px 12px;
				margin: 14px 0 0;
				font-size: 12px;
				font-weight: 700;
				letter-spacing: 0.05em;
				text-transform: uppercase;
				border-radius: 20px;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__title {
				margin: 20px 0 10px;
				font-size: 30px;
				line-height: 1.2;
				font-weight: 700;
				text-transform: uppercase;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__price {
				margin: 0 0 18px;
				font-size: 24px;
				font-weight: 700;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__button {
				display: inline-block;
				text-decoration: none;
				text-align: center;
				border: 0;
				cursor: pointer;
				font-size: 20px;
				line-height: 1;
				font-weight: 600;
				width: 100%;
				box-sizing: border-box;
				transition: all 0.2s ease;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__button:hover {
				transform: translateY(-1px);
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal-overlay {
				display: none;
				position: fixed;
				inset: 0;
				z-index: 9999;
				padding: 20px;
				overflow-y: auto;
				align-items: center;
				justify-content: center;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal-overlay.is-open {
				display: flex;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal {
				max-width: 1100px;
				width: 100%;
				margin: 0;
				border-radius: 12px;
				position: relative;
				padding: 32px;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__close {
				position: absolute;
				top: 12px;
				right: 12px;
				background: transparent;
				border: 0;
				font-size: 36px;
				line-height: 1;
				cursor: pointer;
				color: #8b8b8b;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__layout {
				display: grid;
				grid-template-columns: minmax(260px, 48%) minmax(260px, 1fr);
				gap: 32px;
				align-items: center;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__media img {
				display: block;
				width: 100%;
				border-radius: 8px;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__title {
				margin: 0 0 10px;
				font-size: 44px;
				font-weight: 700;
				line-height: 1.1;
				text-transform: uppercase;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__price {
				margin: 0 0 14px;
				font-size: 42px;
				font-weight: 700;
				line-height: 1.1;
				color: #f47d20;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__description {
				font-size: 18px;
				line-height: 1.7;
				margin-bottom: 18px;
			}
			#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__cta {
				display: inline-block;
				text-decoration: none;
				background: #ff7900;
				color: #ffffff;
				font-weight: 700;
				padding: 14px 24px;
				border-radius: 8px;
			}
			@media (max-width: 1024px) {
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-grid {
					grid-template-columns: repeat(<?php echo (int) $columns_tablet; ?>, minmax(0, 1fr));
				}
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal {
					padding: 20px;
				}
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__layout {
					grid-template-columns: 1fr;
				}
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__title {
					font-size: 34px;
				}
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__price {
					font-size: 30px;
				}
			}
			@media (max-width: 767px) {
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-grid {
					grid-template-columns: repeat(<?php echo (int) $columns_mobile; ?>, minmax(0, 1fr));
				}
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__title {
					font-size: 24px;
				}
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__price {
					font-size: 20px;
				}
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-product-card__button {
					font-size: 18px;
				}
				#<?php echo esc_attr( $widget_id ); ?> .cronovelo-modal__description {
					font-size: 16px;
				}
			}
		</style>

		<script>
			(function() {
				const root = document.getElementById('<?php echo esc_js( $widget_id ); ?>');
				if (!root) {
					return;
				}

				const gridItems = Array.from(root.querySelectorAll('.cronovelo-grid__item'));
				const filterButtons = Array.from(root.querySelectorAll('.cronovelo-filter-btn'));
				const limits = JSON.parse(root.getAttribute('data-limits') || '{}');
				let activeFilter = root.getAttribute('data-default-filter') || 'all';

				function currentBreakpoint() {
					const w = window.innerWidth;
					if (w <= 767) {
						return 'mobile';
					}
					if (w <= 1024) {
						return 'tablet';
					}
					return 'desktop';
				}

				function applyFilter() {
					const bp = currentBreakpoint();
					const limit = Number(limits[bp] || gridItems.length);
					let shown = 0;

					gridItems.forEach(function(item) {
						const cardType = item.getAttribute('data-card-type') || 'product';
						const match = activeFilter === 'all' || cardType === activeFilter;
						if (match && shown < limit) {
							item.style.display = 'block';
							shown += 1;
						} else {
							item.style.display = 'none';
						}
					});

					filterButtons.forEach(function(btn) {
						btn.classList.toggle('is-active', btn.getAttribute('data-filter') === activeFilter);
					});
				}

				filterButtons.forEach(function(button) {
					button.addEventListener('click', function() {
						activeFilter = button.getAttribute('data-filter') || 'all';
						applyFilter();
					});
				});

				const openButtons = root.querySelectorAll('.js-cronovelo-open-modal');
				const closeButtons = document.querySelectorAll('#<?php echo esc_js( $widget_id ); ?> .js-cronovelo-close-modal');

				function closeModal(modal) {
					if (!modal) {
						return;
					}
					modal.classList.remove('is-open');
					modal.setAttribute('aria-hidden', 'true');
					document.body.style.overflow = '';
				}

				function openModal(modal) {
					if (!modal) {
						return;
					}
					modal.classList.add('is-open');
					modal.setAttribute('aria-hidden', 'false');
					document.body.style.overflow = 'hidden';
				}

				openButtons.forEach(function(button) {
					button.addEventListener('click', function() {
						const modalId = button.getAttribute('data-modal-id');
						openModal(document.getElementById(modalId));
					});
				});

				closeButtons.forEach(function(button) {
					button.addEventListener('click', function() {
						closeModal(button.closest('.cronovelo-modal-overlay'));
					});
				});

				document.querySelectorAll('#<?php echo esc_js( $widget_id ); ?> .cronovelo-modal-overlay').forEach(function(overlay) {
					overlay.addEventListener('click', function(event) {
						if (event.target === overlay) {
							closeModal(overlay);
						}
					});
				});

				document.addEventListener('keydown', function(event) {
					if (event.key !== 'Escape') {
						return;
					}
					document.querySelectorAll('#<?php echo esc_js( $widget_id ); ?> .cronovelo-modal-overlay.is-open').forEach(function(modal) {
						closeModal(modal);
					});
				});

				window.addEventListener('resize', applyFilter);
				applyFilter();
			})();
		</script>
		<?php
	}
}

function cronovelo_register_product_card_widget( $widgets_manager ) {
	$widget = new \Cronovelo_Product_Card_Widget();

	if ( method_exists( $widgets_manager, 'register' ) ) {
		$widgets_manager->register( $widget );
	} else {
		$widgets_manager->register_widget_type( $widget );
	}
}
add_action( 'elementor/widgets/register', 'cronovelo_register_product_card_widget' );
