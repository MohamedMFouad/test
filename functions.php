<?php //Start building your awesome child theme functions

add_action( 'wp_enqueue_scripts', 'shopkeeper_enqueue_styles', 99 );
function shopkeeper_enqueue_styles() {

    // enqueue parent styles
    wp_enqueue_style( 'shopkeeper-icon-font', get_template_directory_uri() . '/inc/fonts/shopkeeper-icon-font/style.css' );
	wp_enqueue_style( 'shopkeeper-styles', get_template_directory_uri() .'/css/styles.css' );
    wp_enqueue_style( 'shopkeeper-default-style', get_template_directory_uri() .'/style.css' );

    // enqueue child styles
    wp_enqueue_style( 'shopkeeper-child-style',
        get_stylesheet_directory_uri() . '/style.css');

	// enqueue RTL styles
    if( is_rtl() ) {
    	wp_enqueue_style( 'shopkeeper-child-rtl-styles',  get_template_directory_uri() . '/rtl.css', array( 'shopkeeper-styles' ), wp_get_theme()->get('Version') );
    }
}

add_filter( 'wpo_wcpdf_invoice_title', 'wpo_wcpdf_invoice_title', 10, 2 );
function wpo_wcpdf_invoice_title ( $title, $document ) {
    $title = 'Order';
    return $title;
}

// out of stock hide from related

function misha_hide_out_of_stock_option( $option ){
	return 'yes';
}
 
add_action( 'woocommerce_before_template_part', function( $template_name ) {
 
	if( $template_name !== "single-product/related.php" ) {
		return;
	}
 
	add_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'misha_hide_out_of_stock_option' );
 
} );
 
add_action( 'woocommerce_after_template_part', function( $template_name ) {
 
	if( $template_name !== "single-product/related.php" ) {
		return;
	}
 
	remove_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'misha_hide_out_of_stock_option' );
 
} );

// Limit Woocommerce phone field to 11 digits number
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');
  
function my_custom_checkout_field_process() {
    global $woocommerce;
  
    // Check if set, if its not set add an error. This one is only requite for companies
    if ( ! (preg_match('/^[0-9]{11}$/D', $_POST['billing_phone'] ))){
        wc_add_notice( "Incorrect Phone Number! Please enter valid 11 digits phone number"  ,'error' );
    }
}

/* ======================= edit sale price ============================================== */

add_filter( 'woocommerce_sale_flash', 'add_percentage_to_sale_badge', 20, 3 );
function add_percentage_to_sale_badge( $html, $post, $product ) {
    if( $product->is_type('variable')){
        $percentages = array();

        // Get all variation prices
        $prices = $product->get_variation_prices();

        // Loop through variation prices
        foreach( $prices['price'] as $key => $price ){
            // Only on sale variations
            if( $prices['regular_price'][$key] !== $price ){
                // Calculate and set in the array the percentage for each variation on sale
                $percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
            }
        }
        // We keep the highest value
        $percentage = max($percentages) . '%';
    } else {
        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();

        $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
    }
    return '<span class="onsale onsalePercentage">' .  $percentage . " Off" . '</span>';
}


/**
		 * Display SKU as item meta.
		 *
		 * @param WC_Order_Item_Product $item order item object.
		 * @param WC_Order              $order order object.
		 */
		function display_sku_as_meta_data( $item, $order ) {
			$product = BEWPI_WC_Order_Compatibility::get_product( $order, $item );
			$sku     = $product && BEWPI_WC_Product_Compatibility::get_prop( $product, 'sku' ) ? BEWPI_WC_Product_Compatibility::get_prop( $product, 'sku' ) : '-';
			?>
			<br>
			<ul>
				<li>
					<strong><?php esc_html_e( 'SKU:', 'woocommerce-pdf-invoices' ); ?></strong> <?php echo esc_html( $sku ); ?>
				</li>
			</ul>
			<?php
		}
add_action( 'wpi_order_item_meta_start', 'display_sku_as_meta_data', 10, 2 );

/**
 * @snippet Display 30% off on all website label @ Loop Pages - WooCommerce
 */
 
// add_action( 'woocommerce_before_shop_loop_item', 'bbloomer_show_sale_percentage_loop', 25 );
  
// function bbloomer_show_sale_percentage_loop() {
//    global $product;
	
//    if (has_term( 'Sale', 'product_cat', $product_id )
// 	   || has_term( 'Women Sale', 'product_cat', $product_id ) 
// 	   || has_term( 'Men Sale', 'product_cat', $product_id ) ) {
// 	   echo "<span class='sale-perc'>" . "Sale" . "</span>";
	   
// 	   return;
//    }
// }



/* ===================== Custom Add To Cart Messages ========================== */

// add_filter ( 'wc_add_to_cart_message_html', 'wc_add_to_cart_message_html_filter', 10, 2 );
// function wc_add_to_cart_message_html_filter( $message, $products ) {

//     foreach( $products as $product_id => $quantity ){

//         // (If needed) get the WC_Product object
//         $product = wc_get_product( $product_id );
//         // The product title
//         $product_title = $product->get_title();

//         // Set Here a product category Id, name or slug (for example, if needed)
//         $sale_category = "Sale";
//         if( has_term( 'Sale', 'product_cat', $product_id )  
//         || has_term( 'Women Sale', 'product_cat', $product_id ) 
//         || has_term( 'Men Sale', 'product_cat', $product_id )){
			
// 			/* ================================= */
// 			$cat_in_cart = false;
// 			$counter = 0;
// 			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

// 				if (has_term( 'Sale', 'product_cat', $product_id )  
// 		        || has_term( 'Women Sale', 'product_cat', $product_id ) 
// 		        || has_term( 'Men Sale', 'product_cat', $product_id ) ) {
// 					$cat_in_cart = true;
// 					$counter++;
// 				}
// 			}
   
// 			// Do something if the category is in the Cart      
// 			if ( $cat_in_cart && ($counter == 1) ) {
// 				return __("Item added to cart. Your bag now qualifies for 30% off
// 			<br /> <a href='https://www.magmasportswear.com/cart/' class='button card-notif-btn'>VIEW BAG </a>
// 			 <a href='https://www.magmasportswear.com/product-category/sale/' class='button card-notif-btn'>CONTINUE SHOPPING </a>
// 			", 
// 						  "woocommerce");
// 			}
			
// 		if ( $cat_in_cart && ($counter == 2) ) {
// 				return __("Item added to cart. Your bag now qualifies for 40% off
// 			<br /> <a href='https://www.magmasportswear.com/cart/' class='button card-notif-btn'>VIEW BAG </a>
// 			 <a href='https://www.magmasportswear.com/product-category/sale/' class='button card-notif-btn'>CONTINUE SHOPPING </a>
// 			", 
// 						  "woocommerce");
// 			}
			
// 			if ( $cat_in_cart && ($counter == 3) ) {
// 				return __("Item added to cart. Your bag now qualifies for 50% off
// 			<br /> <a href='https://www.magmasportswear.com/cart/' class='button card-notif-btn'>VIEW BAG </a>
// 			 <a href='https://www.magmasportswear.com/product-category/sale/' class='button card-notif-btn'>CONTINUE SHOPPING </a>
// 			", 
// 						  "woocommerce");
// 			}
			
// 			/* ================================= */
//         }
//     }
//     return $message;
// }



/* prevent to manually add out of stock product to order */
add_filter( 'woocommerce_ajax_add_order_item_validation', 'rmg_woocommerce_ajax_add_order_item_validation', 10, 4 );
function rmg_woocommerce_ajax_add_order_item_validation( $validation_error, $product, $order, $qty ) {
    if ( $validation_error && !$product->is_in_stock() ) {
        $validation_error->add( 'product-out-of-stock', __('Product Out of Stock', 'woocommerce') );
    }
    if ( $validation_error && ( $product->get_stock_quantity() < $qty ) ) {
        $validation_error->add( 'product-low-stock', __('Product low of Stock', 'woocommerce') );
    }
    return $validation_error;
}


/**
 * When a order is failed, restore stock.
 * @param int $order_id Order ID.
 */

add_action( 'woocommerce_order_status_failed', 'wc_maybe_increase_stock_levels' );


/* ================================= enqueue custom script ==================================== */
function enqueue_custom_script() {
    wp_enqueue_script("custom-script", get_stylesheet_directory_uri() . "/customScript.js", array(), "", true);
}

add_action("wp_enqueue_scripts", "enqueue_custom_script");



function woocommerce_product_custom_fields ()
{
    global $woocommerce, $post;
    echo '<div class=" product_custom_field ">';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id'          => '_custom_product_upc',
            'label'       => __( 'UPC Code', 'woocommerce' ),
            'placeholder' => 'UPC barcode Field',
            'desc_tip'    => 'true'
        )

    );


// This function has the logic of creating custom field
//  This function includes input text field, Text area and number field
    echo '</div>';
}

function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_product_text_field = $_POST['_custom_product_upc'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_custom_product_upc', esc_attr($woocommerce_custom_product_text_field));

}

// The code for displaying WooCommerce Product Custom Fields
add_action( 'woocommerce_product_options_inventory_product_data', 'woocommerce_product_custom_fields' );

// Following code Saves  WooCommerce Product Custom Fields
add_action( 'woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save' );


/**
 * Adding custom field of UPC to product variations
 */















add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );

// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

/**
 * Create new fields for variations
 *
 */
function variation_settings_fields( $loop, $variation_data, $variation ) {

    // Text Field
    woocommerce_wp_text_input(
        array(
            'id'          => '_upc_variation_code[' . $variation->ID . ']',
            'label'       => __( 'UPC Barcode', 'woocommerce' ),
            'placeholder' => '',
            'desc_tip'    => 'true',
            'description' => __( 'UPC barcode for Variant.', 'woocommerce' ),
            'value'       => get_post_meta( $variation->ID, '_upc_variation_code', true )
        )
    );



}

/**
 * Save new fields for variations
 *
 */
function save_variation_settings_fields( $post_id ) {

    // Text Field
    $text_field = $_POST['_upc_variation_code'][ $post_id ];
    if( ! empty( $text_field ) ) {
        update_post_meta( $post_id, '_upc_variation_code', esc_attr( $text_field ) );
    }

    // Number Field
    $number_field = $_POST['_number_field'][ $post_id ];
    if( ! empty( $number_field ) ) {
        update_post_meta( $post_id, '_number_field', esc_attr( $number_field ) );
    }

    // Textarea
    $textarea = $_POST['_textarea'][ $post_id ];
    if( ! empty( $textarea ) ) {
        update_post_meta( $post_id, '_textarea', esc_attr( $textarea ) );
    }

    // Select
    $select = $_POST['_select'][ $post_id ];
    if( ! empty( $select ) ) {
        update_post_meta( $post_id, '_select', esc_attr( $select ) );
    }

    // Checkbox
    $checkbox = isset( $_POST['_checkbox'][ $post_id ] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_checkbox', $checkbox );

    // Hidden field
    $hidden = $_POST['_hidden_field'][ $post_id ];
    if( ! empty( $hidden ) ) {
        update_post_meta( $post_id, '_hidden_field', esc_attr( $hidden ) );
    }

}

















//add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
add_action( 'woocommerce_variation_options', 'variable_fields', 10, 1);

//add_action( 'woocommerce_save_product_variation', 'save_variable_fields', 10, 1 );

function variation_settings_fields1($loop, $variation_data  ) {
   // global $woocommerce, $post;
    woocommerce_wp_text_input(
        array(
            'id'          => '_custom_product_upc_[' . $variation->ID . ']',
            'label'       => __( 'UPC label', 'woocommerce' ),
            'placeholder' => '',
            'desc_tip'    => 'true',
            'description' => __( 'UPC barcode Field', 'woocommerce' ),
            'value'       => get_post_meta( $variation->ID, '_custom_product_upc_', true )
        )
    );
}

function save_variation_settings_fields1( $post_id ) {
    $text_field = $_POST['_custom_product_upc_'][ $post_id ];
    if( ! empty( $text_field ) ) {
        update_post_meta( $post_id, '_custom_product_upc_', esc_attr( $text_field ));
    }
}


function save_variable_fields( $post_id ) {
    if (isset( $_POST['variable_sku'] ) ) :

        $variable_sku          = $_POST['variable_sku'];
        $variable_post_id      = $_POST['variable_post_id'];

// Textarea
        $_custom_product_upc_ = $_POST['_custom_product_upc_'];
        for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
            $variation_id = (int) $variable_post_id[$i];
            if ( isset( $_custom_product_upc_[$i] ) ) {
                update_post_meta( $variation_id, '_custom_product_upc_', stripslashes( $_custom_product_upc_[$i] ) );
            }
        endfor;


    endif;
}


//add_filter( 'woocommerce_available_variation', 'load_variation_settings_fields' );

function load_variation_settings_fields( $variations ) {

    $variations['_custom_product_upc_'] = get_post_meta( $variations[ 'variation_id' ], '_custom_product_upc_', true );

    return $variations;
}

