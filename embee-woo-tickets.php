<?php
/**
 * Plugin Name: WooTickets by Marcus Battle
 * Plugin URI: http://marcusbattle.com/plugins/embee-woo-tickets
 * Description: Seamlessly integrate ticket sales into WooCommerce.
 * Version: 0.1.0
 * Author: Marcus Battle
 * Author URI: http://marcusbattle.com
 * Text Domain: embee-woo-tickets
 * License: GPL2
 */


/**
 * Embee_Woo_Tickets class
 */
class Embee_Woo_Tickets {

	/**
	 * Hook in methods
	 */
 	public function __construct() { 
 		
 		add_action( 'init', array( $this, 'register_ticket_taxonomy' ) );
 		add_filter( 'product_type_selector', array( $this, 'filter_product_type_selector' ), 10, 2 );
 		add_filter( 'woocommerce_product_data_tabs', array( $this, 'woocommerce_product_data_tabs' ), 10, 2 );
 		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'woocommerce_product_options_general_product_data' ), 10 );

 	}

 	/**
 	 * Registers the 'Ticket' product_type
 	 *
 	 * @since 0.1.0
 	 */
 	public function register_ticket_taxonomy() {
 		
 		$term = term_exists( 'tickets', 'product_type' );

 		if ( ! $term ) {

 			wp_insert_term(
				'ticket', // the term 
				'product_type', // the taxonomy
				array(
					'slug' => 'ticket',
				)
			);

 		}

 	}

 	/**
 	 * Adds the 'Ticket' product_type to the product type selector 
 	 *
 	 * @since 0.1.0
 	 *
	 * @param array $product_type_selector Options for product data, product type selector
	 * @param string $product_type 
 	 *
	 * @return array $product_type_selector
 	 */
 	public function filter_product_type_selector( $product_type_selector, $product_type ) {

 		$ticket_option = array(
 			'ticket' => __( 'Ticket', 'embee-woo-tickets' )
 		);

 		$product_type_selector = wp_parse_args( $ticket_option, $product_type_selector );
 		
 		return $product_type_selector;

 	}

 	/**
 	 * Modify the product data tabs to add special fields for 'Tickets'
 	 *
 	 * @since 0.1.0
 	 *
 	 * @param array $product_data_tabs
 	 *
 	 * @return array $product_data_tabs 
 	 */
 	public function woocommerce_product_data_tabs( $product_data_tabs = array() ) {

 		// Show inventory if user selects 'Ticket'
 		$product_data_tabs['inventory']['class'][] = 'show_if_ticket';

 		// Hide shipping if user select 'Ticket' 
		$product_data_tabs['shipping']['class'][] = 'hide_if_ticket'; 		

 		return $product_data_tabs;

 	}

 	/**
 	 * Adds the 'Regular Price' field to the 'General' product tab when 'Ticket' is selected
 	 */
 	public function woocommerce_product_options_general_product_data() {

 		// Display price field
 		echo '<div class="options_group pricing show_if_ticket">';
			woocommerce_wp_text_input( array( 'id' => '_regular_price', 'label' => __( 'Regular Price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')', 'data_type' => 'price' ) );
		echo '</div>';

 	}

}

$embee_woo_tickets = new Embee_Woo_Tickets();