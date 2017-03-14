<?php
/*
* Define class WooZoneFrontend
* Make sure you skip down to the end of this file, as there are a few
* lines of code that are very important.
*/
!defined('ABSPATH') and exit;
   
if (class_exists('WooZoneFrontend') != true) {
    class WooZoneFrontend
    {
        /*
        * Some required plugin information
        */
        const VERSION = '1.0';
		
        /*
        * Store some helpers config
        */
        public $the_plugin 		= null;
		public $amzHelper 	= null;
		public $is_admin		= null;

		public $amz_settings = array();
		public $p_type = null;

        static protected $_instance;

		public $alias;
		public $localizationName;

		private $current_theme = null;
		

        /*
        * Required __construct() function that initalizes the AA-Team Framework
        */
        public function __construct( $parent )
        {
            $this->the_plugin = $parent;
			$this->amzHelper = $this->the_plugin->amzHelper;
			$this->is_admin = $this->the_plugin->is_admin;
			
			$this->amz_settings = $this->the_plugin->amz_settings;
			$this->p_type = isset($this->amz_settings['onsite_cart']) && $this->amz_settings['onsite_cart'] == "no" ? 'external' : 'simple';

			$this->alias = $this->the_plugin->alias;
			$this->localizationName = $this->the_plugin->localizationName;
			
			$this->current_theme = wp_get_theme(); //get_current_theme() - deprecated notice!
			//var_dump('<pre>',$this->current_theme,'</pre>');  

			// wp actions - frontend
			if ( ! $this->is_admin ) {
				add_action( 'init' , array( $this, 'init' ) );
			}
			// executed only on frontend
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

			// wp ajax actions
			add_action('wp_ajax_WooZone_frontend', array( $this, 'ajax_requests') );
			add_action('wp_ajax_nopriv_WooZone_frontend', array( $this, 'ajax_requests') );

			// checkout email: wp ajax actions
			if ( $this->p_type == 'simple' ) {
				if ( isset($this->amz_settings['checkout_email']) && $this->amz_settings['checkout_email'] == 'yes' ) {
					add_action( 'wp_ajax_WooZone_before_user_checkout', array( $this, 'woocommerce_ajax_before_user_checkout') );
					add_action( 'wp_ajax_nopriv_WooZone_before_user_checkout', array( $this, 'woocommerce_ajax_before_user_checkout') );
				}
			}
        }
        
        /**
        * Singleton pattern
        *
        * @return Singleton instance
        */
        static public function getInstance( $parent )
        {
            if (!self::$_instance) {
                self::$_instance = new self($parent);
            }
            
            return self::$_instance;
        }
	

		/**
		 * Inits...
		 */
		// wp enqueue scripts & stypes
		public function wp_enqueue_scripts() {

			if( !wp_style_is($this->alias . '-frontend-style') ) {
				wp_enqueue_style( $this->alias . '-frontend-style', $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'lib/frontend/css/frontend.css' );
			}
			
			if( !wp_script_is($this->alias . '-frontend-script') ) {
				wp_enqueue_script( $this->alias . '-frontend-script' , $this->the_plugin->cfg['paths']['plugin_dir_url'] . 'lib/frontend/js/frontend.js', array( 'jquery' ) );

				$_checkout_url = wc_get_checkout_url();
				$_checkout_url = is_string($_checkout_url) ? esc_url( $_checkout_url ) : '';

				$vars = array(
					'ajax_url'				=> admin_url('admin-ajax.php'),
					'checkout_url' 		=> $_checkout_url,
					'lang' 					=> array(
						'loading'								=> __('Loading...', $this->localizationName),
						'closing'                   				=> __('Closing...', $this->localizationName),
						'saving'                   				=> __('Saving...', $this->localizationName),
						'amzcart_checkout'       		=> __('checkout done', $this->localizationName),
						'amzcart_cancel' 					=> __('canceled', $this->localizationName),
						'amzcart_checkout_msg'		=> __('all good.', $this->localizationName),
						'amzcart_cancel_msg'			=> __('You must check or cancel all amazon shops!', $this->localizationName),
						'available_yes'						=> __('available', $this->localizationName),
						'available_no' 						=> __('not available', $this->localizationName),
					),
				);
				wp_localize_script( 'WooZone-frontend-script', 'woozone_vars', $vars );
			}
		}

		// wp 'init' hook
		public function init() {
			add_action( 'wp_footer', array( $this, 'wp_footer' ), 1 );
			
			//::::::::::::::::::::::::::::::::::::
			// start box with product country check
			$is_country_check = ( ! isset($this->amz_settings['product_countries'])
				|| 'yes' == $this->amz_settings['product_countries'] ? true : false );
			if ( $is_country_check ) {

				// single product page
				$box_countries_pos = isset($this->amz_settings['product_countries_main_position'])
					? $this->amz_settings['product_countries_main_position'] : 'before_add_to_cart';
				/**
				 * woocommerce_single_product_summary hook
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 */
				switch ($box_countries_pos) {
					case 'before_add_to_cart':
						add_action( 'woocommerce_single_product_summary', array($this, 'woocommerce_single_product_summary'), 21 );
						if ( 'Kingdom - Woocommerce Amazon Affiliates Theme' == $this->current_theme ) {
							add_action( 'WooZone_frontend_footer', array( $this, '__before_add_to_cart' ), 1 );
						}
						break;
					
					case 'before_title_and_thumb':
						add_action( 'WooZone_frontend_footer', array( $this, '__before_title_and_thumb' ), 1 );
						break;

					case 'before_woocommerce_tabs':
						add_action( 'WooZone_frontend_footer', array( $this, '__before_woocommerce_tabs' ), 1 );
						break;
						
					case 'as_woocommerce_tab':
						add_action('woocommerce_product_tabs', array($this, 'woocommerce_product_tabs'), 0);
						break;		
				}

				//$where_country_check = isset($this->amz_settings['product_countries_where'])
				//	? (array) $this->amz_settings['product_countries_where'] : array(); //'maincart', 'minicart'
				$product_countries_maincart = ( ! isset($this->amz_settings['product_countries_maincart'])
				|| 'yes' == $this->amz_settings['product_countries_maincart'] ? true : false );
				$where_country_check = $product_countries_maincart ? array('maincart') : array();

				// view main cart
				if ( in_array('maincart', $where_country_check) )
					add_filter( 'woocommerce_cart_item_quantity', array($this, 'woocommerce_cart_item_quantity'), 10, 3 );

				// view mini cart
				if ( in_array('minicart', $where_country_check) ) {
					add_filter( 'woocommerce_widget_cart_item_quantity', array($this, 'woocommerce_widget_cart_item_quantity'), 10, 3 );
					if ( 'Kingdom - Woocommerce Amazon Affiliates Theme' == $this->current_theme ) {
						add_action( 'WooZone_frontend_footer', array( $this, '__widget_cart_item_quantity' ), 1 );
					}
				}
				
				// cart page
				//add_action( 'woocommerce_after_cart_table', array($this, 'woocommerce_after_cart') ); // don't work - already have a form
				add_action( 'woocommerce_after_cart', array($this, 'woocommerce_after_cart') );
			}
			// end box with product country check
			//::::::::::::::::::::::::::::::::::::
			
			$redirect_cart = (isset($_REQUEST['redirectCart']) && $_REQUEST['redirectCart']) != '' ? $_REQUEST['redirectCart'] : '';
			if( isset($redirect_cart) && $redirect_cart == 'true' ) {
				if ( ! $this->the_plugin->disable_amazon_checkout )
					$this->redirect_cart();
			}

			add_action( 'woocommerce_after_add_to_cart_button', array($this, 'woocommerce_external_add_to_cart'), 10 );

			// non-external products pages
			if ( $this->p_type == 'simple' ) {
				// cart checkout
				if ( ! $this->the_plugin->disable_amazon_checkout ) {
					add_action( 'woocommerce_checkout_init', array($this, 'woocommerce_external_checkout'), 10 );
				}

				// checkout email
				if( isset($this->amz_settings['checkout_email']) && $this->amz_settings['checkout_email'] == 'yes' ) {
					add_filter( 'woocommerce_before_cart_totals', array($this, 'woocommerce_before_checkout'), 10 );
				}
			}
		}

		// 'wp_footer' hook
		public function wp_footer() {
			global $wp_query;
			
			echo PHP_EOL . "<!-- start/ " . ($this->the_plugin->alias) . " wp_footer hook -->" . PHP_EOL;
			
			if ( ! has_action('WooZone_frontend_footer') )
				return true;
			
			do_action( 'WooZone_frontend_footer' );
			
			echo "<!-- end/ " . ($this->the_plugin->alias) . " wp_footer hook -->" . PHP_EOL.PHP_EOL;
			
			return true;
		}
		
		
		/**
		 * Hooks functions
		 */
		// wp 'hooks' functions
		// amazon shops checkout on cart page
		public function woocommerce_after_cart() {
			//$is_cart_page = is_cart();
			//if ( ! $is_cart_page ) return ;
   
			$box = $this->box_amazon_shops_checkout();
			if ( !empty($box) )
				echo $box;
		}

		// product country on product details page
		public function woocommerce_single_product_summary() {
			global $product;
   
			$box = $this->box_country_check_details( $product );
			if ( !empty($box) )
				echo $box;
		}
		
		// product country on main cart
		public function woocommerce_cart_item_quantity($product_quantity, $cart_item_key, $cart_item=null) {
			$str = $product_quantity;

			// theme: kingdom
			if ( empty($cart_item) ) {
				$cart_items_nb = (int) WC()->cart->get_cart_contents_count();
				if ( $cart_items_nb )
					$cart_item = WC()->cart->get_cart_item( $cart_item_key);
			}

			$box = $this->box_country_check_small( isset($cart_item['product_id']) ? $cart_item['product_id'] : 0 );
			if ( !empty($box) ) {
				//$str .= $box;
				$str = str_replace('</div>', $box . '</div>', $str);
			}
			echo $str;
		}
		
		// product country on mini cart
		public function woocommerce_widget_cart_item_quantity($product_quantity, $cart_item, $cart_item_key) {
			$str = $product_quantity;
			$box = $this->box_country_check_small( isset($cart_item['product_id']) ? $cart_item['product_id'] : 0 );
			if ( !empty($box) ) {
				//$str .= $box;
				$str = str_replace('</span></span>', '</span></span>' . $box, $str);
			}
			echo $str;
		}
		public function __widget_cart_item_quantity() {
			$pms = array('box_position' => 'minicart');
			$box = $this->box_country_check_minicart( $pms );
			if ( !empty($box) )
				echo $box;
		}
		
		// main box as woocommerce tab
		public function woocommerce_product_tabs( $tabs ) {
			$tabs['woozone_tab_countries_availability'] = array(
				'title'				=> __( 'Countries availability', $this->localizationName ),
				'priority'		=> 15,
				'callback'		=> array($this, '__woo_tab_countries_availability')
			);

			return $tabs;
		}
		public function __woo_tab_countries_availability( $tab ) {
			global $product;

			$box = $this->box_country_check_details( $product );
			if ( !empty($box) )
				echo $box;
		}

		// main box positioning
		public function __single_product_summary( $pms=array() ) {
			$is_product_page = is_product();
			if ( !$is_product_page ) return;

			global $product;

			$box = $this->box_country_check_details( $product, $pms );
			if ( !empty($box) )
				echo $box;
		}
		public function __before_add_to_cart() {
			$this->__single_product_summary( array('box_position' => 'before_add_to_cart') );
		}
		public function __before_title_and_thumb() {
			$this->__single_product_summary( array('box_position' => 'before_title_and_thumb') );
		}
		public function __before_woocommerce_tabs() {
			$this->__single_product_summary( array('box_position' => 'before_woocommerce_tabs') );
		}
		
		
		/**
		 * box: product country check
		 */
		// build minicart box with product country check
		private function box_country_check_minicart( $pms=array() ) {
			// parameters
			$pms = array_merge(array(
				'with_wrapper'			=> true,
				'box_position'			=> false,
			), $pms);
			extract($pms);
			
			// theme: kingdom
			$cart_items_nb = (int) WC()->cart->get_cart_contents_count();
			if ( !$cart_items_nb )
				return false;

			$minicart_items = array();

			$cart_items = WC()->cart->get_cart();
			foreach ( $cart_items as $key => $value ) {

				//$prod_id = isset($value['variation_id']) && (int)$value['variation_id'] > 0 ? $value['variation_id'] : $value['product_id'];
				$product_id = $value['product_id'];

				$asin = get_post_meta( $product_id, '_amzASIN', true );
				if ( empty($asin) ) continue 1;

				$product_country = $this->get_product_country_current( $product_id );
				$product_country__ = $product_country;
				if ( !empty($product_country) && isset($product_country['website']) ) {
					$product_country = substr($product_country['website'], 1);
				}
				
				$country_name = $product_country__['name'];
				
				$country_status = $product_country__['available'];
				$country_status_css = 'available-todo'; $country_status_text = __('not verified yet', $this->localizationName);
				switch ($country_status) {
					case 1:
						$country_status_css = 'available-yes';
						$country_status_text = __('is available', $this->localizationName);
						break;
						
					case 0:
						$country_status_css = 'available-no';
						$country_status_text = __('not available', $this->localizationName);
						break;
				}
				
				$minicart_items[] = array(
					'cart_item_key'				=> $key,
					'product_id'					=> $product_id,
					'asin'								=> $asin,
					'product_country'			=> $product_country,
					'country_name'				=> $country_name,
					'country_status_css'		=> $country_status_css,
					'country_status_text'	=> $country_status_text,
				);
			}

			ob_start();
		?>

<div class="WooZone-cc-small-cached" style="display: none;"><?php echo json_encode( $minicart_items ); ?></div>
<script type="text/template" id="WooZone-cc-small-template">
	<span class="WooZone-country-check-small WooZone-cc-custom">
		
		<span>
			<span class="WooZone-cc_domain"></span>
			<span class="WooZone-cc_status"></span>
		</span>

	</span>
</script>

		<?php
			$contents = ob_get_clean();
			return $contents;
		}

		// build small box with product country check
		private function box_country_check_small( $product, $pms=array() ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) && isset($product->id) ) {
				$product_id = $product->id;
			}
			if ( empty($product_id) ) return false;

			// parameters
			$pms = array_merge(array(
				'with_wrapper'			=> true,
				'box_position'			=> false,
			), $pms);
			extract($pms);

			// get asin meta key
			$asin = get_post_meta($product_id, '_amzASIN', true);
			if ( empty($asin) ) return false; // verify to be amazon product!
			//$asin = 'B000P0ZSHK'; // DEBUG
			//var_dump('<pre>',$asin,'</pre>');

			$product_country = $this->get_product_country_current( $product_id );
			$product_country__ = $product_country;
			//var_dump('<pre>', $product_id, $product_country, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( !empty($product_country) && isset($product_country['website']) ) {
				$product_country = substr($product_country['website'], 1);
			}
			
			//$all_countries_affid = $this->amzHelper->get_countries('main_aff_id');
			//$country_affid = $product_country__['key'];
			//$country_name = isset($all_countries_affid["$country_affid"]) ? $all_countries_affid["$country_affid"] : 'missing country name';
			$country_name = $product_country__['name'];

			$country_status = $product_country__['available'];
			$country_status_css = 'available-todo'; $country_status_text = __('not verified yet', $this->localizationName);
			switch ($country_status) {
				case 1:
					$country_status_css = 'available-yes';
					$country_status_text = __('is available', $this->localizationName);
					break;
					
				case 0:
					$country_status_css = 'available-no';
					$country_status_text = __('not available', $this->localizationName);
					break;
			}

			ob_start();
		?>

<?php if ($with_wrapper) { ?>
<span class="WooZone-country-check-small" data-prodid="<?php echo $product_id; ?>" data-asin="<?php echo $asin; ?>" data-prodcountry="<?php echo $product_country; ?>">
<?php } ?>

		<span>
			<span class="WooZone-cc_domain <?php echo str_replace('.', '-', $product_country); ?>" title="<?php echo $country_name; ?>"></span>
			<span class="WooZone-cc_status <?php echo $country_status_css; ?>" title="<?php echo $country_status_text; ?>"></span>
		</span>

<?php if ($with_wrapper) { ?>
</span>
<?php } ?>

		<?php
			$contents = ob_get_clean();
			return $contents;
		}

		// build main box with product country check
		private function box_country_check_details( $product, $pms=array() ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) && isset($product->id) ) {
				$product_id = $product->id;
			}
			if ( empty($product_id) ) return false;

			// parameters
			$pms = array_merge(array(
				'with_wrapper'			=> true,
				'box_position'			=> false,
			), $pms);
			extract($pms);
			
			// get asin meta key
			$asin = get_post_meta($product_id, '_amzASIN', true);
			if ( empty($asin) ) return false; // verify to be amazon product!
			//$asin = 'B000P0ZSHK'; // DEBUG
			//var_dump('<pre>',$asin,'</pre>');
			
			$available_countries = $this->get_product_countries_available( $product_id );
			//var_dump('<pre>', $product_id, $available_countries, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;   
			if ( empty($available_countries) ) return false;

			$product_country = $this->get_product_country_current( $product_id );
			//var_dump('<pre>', $product_id, $product_country, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			if ( !empty($product_country) && isset($product_country['website']) ) {
				$product_country = substr($product_country['website'], 1);
			}
			
			// aff ids
			$aff_ids = $this->the_plugin->get_aff_ids();

			ob_start();
		?>

<?php if ($with_wrapper) { ?>
<ul class="WooZone-country-check" data-prodid="<?php echo $product_id; ?>" data-asin="<?php echo $asin; ?>" data-prodcountry="<?php echo $product_country; ?>" data-boxpos="<?php echo $box_position; ?>" <?php echo !empty($box_position) ? 'style="display: none;"' : ''; ?>>
<?php } ?>

	<div class="WooZone-country-cached" style="display: none;"><?php echo json_encode( $available_countries ); ?></div>
	<div class="WooZone-country-affid" style="display: none;"><?php echo json_encode( $aff_ids ); ?></div>
	<div class="WooZone-country-loader">
		<div>
			<div id="floatingBarsG">
				<div class="blockG" id="rotateG_01"></div>
				<div class="blockG" id="rotateG_02"></div>
				<div class="blockG" id="rotateG_03"></div>
				<div class="blockG" id="rotateG_04"></div>
				<div class="blockG" id="rotateG_05"></div>
				<div class="blockG" id="rotateG_06"></div>
				<div class="blockG" id="rotateG_07"></div>
				<div class="blockG" id="rotateG_08"></div>
			</div>
			<div class="WooZone-country-loader-text"></div>
		</div>
	</div>
	<div class="WooZone-country-loader bottom">
		<div>
			<div id="floatingBarsG">
				<div class="blockG" id="rotateG_01"></div>
				<div class="blockG" id="rotateG_02"></div>
				<div class="blockG" id="rotateG_03"></div>
				<div class="blockG" id="rotateG_04"></div>
				<div class="blockG" id="rotateG_05"></div>
				<div class="blockG" id="rotateG_06"></div>
				<div class="blockG" id="rotateG_07"></div>
				<div class="blockG" id="rotateG_08"></div>
			</div>
			<div class="WooZone-country-loader-text"></div>
		</div>
	</div>
	<div style="display: none;" id="WooZone-cc-template">
		<li>
			<?php if ( 'external' != $this->p_type ) { ?>
			<span class="WooZone-cc_checkbox">
				<input type="radio" name="WooZone-cc-choose[<?php echo $asin; ?>]" />
			</span>
			<?php } ?>
			<span class="WooZone-cc_domain"></span>
			<span class="WooZone-cc_name"><a href="#" target="_blank"></a></span>
			-
			<span class="WooZone-cc-status">
				<span class="WooZone-cc-loader">
					<span class="WooZone-cc-bounce1"></span>
					<span class="WooZone-cc-bounce2"></span>
					<span class="WooZone-cc-bounce3"></span>
				</span>
			</span>
		</li>
	</div>

<?php if ($with_wrapper) { ?>
</ul>
<?php } ?>

		<?php
			$contents = ob_get_clean();
			return $contents;
		}


		/**
		 * box: amazon shops checkout on cart page
		 */
		public function box_amazon_shops_checkout() {
			$shops = $this->woo_cart_get_amazon_prods_bycountry();
			if ( empty($shops) ) return false;
			
			$is_multiple = $this->woo_cart_is_amazon_multiple( $shops );
			if ( empty($is_multiple) || $is_multiple <= 1 ) return false;
			
			ob_start();
		?>

<div class="WooZone-cart-checkout">
	<ul class="WooZone-cart-shops">
	<?php
	foreach ($shops as $key => $value) {
		if ( empty($value) ) continue 1;

		//$country_name = array_shift(array_slice($array, 0, 1)); // get first element from array if a array "copy" is needed
		$domain = $value['domain'];
		$affID = $value['affID'];
		$country_name = $value['name'];

		$products = $value['products'];
		$nb_products = count($products);
		
		$prods_available = array();
		foreach ($products as $pkey => $pvalue)
			if ( $pvalue['available'] == 1 ) $prods_available[] = $pkey;
		$nb_available = count($prods_available);
	?>
		<li data-domain="<?php echo $domain; ?>">
			<span class="WooZone-cc_domain <?php echo str_replace('.', '-', $domain); ?>"></span>
			<span class="WooZone-cc_name"><?php echo $country_name; ?></span>
			<span class="WooZone-cc_count"><?php echo sprintf( _n('(%s available from %s product)', '(%s available from %s products)', $nb_available, $nb_products, $this->localizationName),  $nb_available, $nb_products ); ?></span>
			<span class="WooZone-cc_checkout">

				<form target="_blank" method="GET" action="//www.amazon.<?php echo $domain; ?>/gp/aws/cart/add.html">
					<input type="hidden" name="AssociateTag" value="<?php echo $affID; ?>"/>
					<?php /*<input type="hidden" name="SubscriptionId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>*/ ?>
					<input type="hidden" name="AWSAccessKeyId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>
					<?php 
					$cc = 1; 
					foreach ($products as $pkey => $pvalue){
					?>      
						<input type="hidden" name="ASIN.<?php echo $cc;?>" value="<?php echo $pvalue['asin'];?>"/>
						<input type="hidden" name="Quantity.<?php echo $cc;?>" value="<?php echo $pvalue['quantity'];?>"/>
					<?php
						$cc++;
					} // end foreach
					$redirect_in = isset($this->amz_settings['redirect_time']) && (int) $this->amz_settings['redirect_time'] > 0 ? ( (int) $this->amz_settings['redirect_time'] * 1000 ) : 1;
					?>
					<input type="submit" value="<?php _e('Proceed to Amazon Checkout', $this->localizationName); ?>" class="WooZone-button">
					<input type="button" value="<?php _e('Cancel', $this->localizationName); ?>" class="WooZone-button cancel">
				</form>

			</span>
			<span class="WooZone-cc_status"></span>
		</li>
	<?php
	} // end foreach
	?>
	</ul>
	<div class="WooZone-cart-msg"></div>
</div>

		<?php
			$contents = ob_get_clean();
			return $contents;
		}


		/**
		 * Cart related
		 */
		public function woocommerce_external_add_to_cart() { 
			echo '<script>jQuery(".single_add_to_cart_button").attr("target", "_blank");</script>'; 
		}

		public function woocommerce_external_checkout() {
			if( is_checkout() == true ){
				$this->redirect_cart();
			}
		}
		
		public function redirect_cart() {
			//global $woocommerce;

			$shops = $this->woo_cart_get_amazon_prods_bycountry();

			$is_multiple = $this->woo_cart_is_amazon_multiple( $shops );
			if ( empty($is_multiple) ) return true;
    
			// more than 1 amazon shops: product belonging to different amazon shops
			if ( $is_multiple > 1 ) {
				$this->woo_cart_update_meta_amazon_prods();
				$this->woo_cart_delete_amazon_prods();
				//echo '<script>setTimeout(function() { window.location.reload(true); }, 1);</script>'; 
				return true;
			}

			// single amazon shops: all products from cart will go to single amazon shop at checkout
			foreach ($shops as $key => $value) {
				if ( empty($value) ) continue 1;

				$domain = $value['domain'];
				$affID = $value['affID'];
				$country_name = $value['name'];
				$products = $value['products'];
				$nb_products = count($products);
			}
			//var_dump('<pre>', $domain, $affID, $country_name, $nb_products, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			if ( ! $nb_products ) return true;

			$html = array();
			if ( isset($this->amz_settings["redirect_checkout_msg"]) && trim($this->amz_settings["redirect_checkout_msg"]) != "" ) {
				$html[] = '<img src="' . ( $this->the_plugin->cfg['paths']['freamwork_dir_url'] . 'images/checkout_loading.gif'  ) . '" style="margin: 10px auto;">';
				$html[] = "<h3>" . ( str_replace( '{amazon_website}', 'www.amazon.' . $domain, $this->amz_settings["redirect_checkout_msg"]) ) . "</h3>";
			}
    	
			//$checkout_type =  isset($this->amz_settings['checkout_type']) && $this->amz_settings['checkout_type'] == '_blank' ? '_blank' : '_self';
			$checkout_type = '_self';
			
			ob_start();
			?>

			<form target="<?php echo $checkout_type;?>" id="amzRedirect" method="GET" action="//www.amazon.<?php echo $domain; ?>/gp/aws/cart/add.html">
				<input type="hidden" name="AssociateTag" value="<?php echo $affID;?>"/>
				<?php /*<input type="hidden" name="SubscriptionId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>*/ ?>
				<input type="hidden" name="AWSAccessKeyId" value="<?php echo $this->amz_settings['AccessKeyID'];?>"/>
				<?php 
					$cc = 1; 
					foreach ($products as $key => $value){
				?>      
						<input type="hidden" name="ASIN.<?php echo $cc;?>" value="<?php echo $value['asin'];?>"/>
						<input type="hidden" name="Quantity.<?php echo $cc;?>" value="<?php echo $value['quantity'];?>"/>
				<?php
						$cc++;
					} // end foreach

   					$redirect_in = isset($this->amz_settings['redirect_time']) && (int) $this->amz_settings['redirect_time'] > 0 ? ( (int) $this->amz_settings['redirect_time'] * 1000 ) : 1;
				?>
			</form>
			<?php ///* debug by uncomment ?>
			<script type="text/javascript">
				setTimeout(function() {
					document.getElementById("amzRedirect").submit();
					<?php 
						//if( (int)$woocommerce->cart->cart_contents_count > 0 && $checkout_type == '_blank' ){
						if ( $nb_products && $checkout_type == '_blank' ) {
					?>
					setTimeout(function() { window.location.reload(true); }, 1);
					<?php
						}
					?>
				}, <?php echo $redirect_in;?>);
			</script>

			<?php 
			$html[] = ob_get_contents(); //ob_clean();
			echo implode(PHP_EOL, $html);

			$this->woo_cart_update_meta_amazon_prods();
			$this->woo_cart_delete_amazon_prods();
			exit();
			return true;
		}


		/**
		 * Ajax request
		 */
		public function ajax_requests()
		{
			$action = isset($_REQUEST['sub_action']) ? $_REQUEST['sub_action'] : 'none';
    
			$allowed_action = array( 'save_countries', 'save_product_country' );

			if( !in_array($action, $allowed_action) ){
				die(json_encode(array(
					'status'		=> 'invalid',
					'html'		=> 'Invalid action!'
				)));
			}

			if ( 'save_countries' == $action ) {
				$req = array(
					'product_id'			=> isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0,
					'product_country'	=> isset($_REQUEST['product_country']) ? trim( $_REQUEST['product_country'] ) : 0,
					'countries'				=> isset($_REQUEST['countries']) ? stripslashes(trim( $_REQUEST['countries'] )) : '',
				);
				extract($req);
				//var_dump('<pre>', $req, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				
				$countries = json_decode( $countries, true );
				if ( $countries ) {
					foreach ($countries as $key => $val) {
						unset($countries["$key"]['name']);
					}
				}
				//var_dump('<pre>', $countries, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				
				// save it
				if ( $product_id && $countries ) {
					$meta_value = array(
						'countries'							=> $countries,
						'countries_cache_time'		=> time(),
					);
					update_post_meta( $product_id, '_amzaff_frontend', $meta_value );
				}
				
				// get asin meta key
				$asin = get_post_meta($product_id, '_amzASIN', true);
				//var_dump('<pre>',$asin,'</pre>');
				
				// save product country
				$_SESSION['WooZone']['product_country']["$asin"] = $product_country;

				die(json_encode(array(
					'status'		=> 'valid',
					'html'		=> 'ok'
				)));
			}

			if ( 'save_product_country' == $action ) {
				$req = array(
					'product_id'			=> isset($_REQUEST['product_id']) ? (int) $_REQUEST['product_id'] : 0,
					'product_country'	=> isset($_REQUEST['product_country']) ? trim( $_REQUEST['product_country'] ) : 0,
				);
				extract($req);
				//var_dump('<pre>', $req, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
				
				// get asin meta key
				$asin = get_post_meta($product_id, '_amzASIN', true);
				//var_dump('<pre>',$asin,'</pre>');
				
				// save product country
				$_SESSION['WooZone']['product_country']["$asin"] = $product_country;

				die(json_encode(array(
					'status'		=> 'valid',
					'html'		=> 'ok'
				)));
			}

			die(json_encode(array(
				'status' 		=> 'invalid',
				'html'		=> 'Invalid action!'
			)));
		}


		/**
		 * checkout email
		 */
		public function woocommerce_before_checkout()
		{
			$return = '<div class="woozone_email_wrapper">';
			$return .= '<label for="woozone_checkout_user_email">E-mail:</label>';
			if( isset($this->amz_settings['checkout_email_mandatory']) && $this->amz_settings['checkout_email_mandatory'] == 'yes' ) {
				$return .= '<input type="hidden" id="woozone_checkout_email_required" name="woozone_checkout_email_required" value="1"/>';
			}
			$return .= '<input type="hidden" id="woozone_checkout_email_nonce" name="woozone_checkout_email_nonce" value="' . ( wp_create_nonce('woozone_checkout_email_nonce') ) . '"/>';
			$return .= '<input type="text" id="woozone_checkout_email" name="woozone_checkout_email" placeholder="email@example.com"/>';
			$return .= '</div>';

			echo $return;
		}
		
		public function woocommerce_ajax_before_user_checkout()
		{
			if( ! wp_verify_nonce( $_REQUEST['_nonce'], 'woozone_checkout_email_nonce')) die ('Busted!');
			unset($_REQUEST['_nonce']);
			
			$email = sanitize_email( $_REQUEST['email'] );
			$users_email = array();
			$users_email = get_option('WooZone_clients_email');
			
			if( is_email($email) ) {
				if( in_array($email, $users_email) ) {
					echo 'email_exists';
					die;
				}
				$users_email[] = $email;
				update_option('WooZone_clients_email', $users_email);
				echo 'success';
			}else{
				echo 'invalid_email';
			}
			
			die;
		}
	

		/**
		 * product country check
		 */
		// get product available amazon countries shops
		public function get_product_countries_available( $product ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) && isset($product->id) ) {
				$product_id = $product->id;
			}
			if ( empty($product_id) ) return false;

			// amazon location & main affiliate ids
			$affIds = (array) isset($this->amz_settings['AffiliateID']) ? $this->amz_settings['AffiliateID'] : array();
			if ( empty($affIds) ) return false;

			$main_aff_id = $this->the_plugin->main_aff_id();
			$main_aff_site = $this->the_plugin->main_aff_site();

			// countries
			$all_countries = $this->amzHelper->get_countries('country');
			$all_countries_affid = $this->amzHelper->get_countries('main_aff_id');

			// loop through setted affiliate ids from amazon config
			$available = array(); $cc = 0;
			foreach ($affIds as $key => $val) {
				if ( empty($val) ) continue 1;

				$convertCountry = $this->the_plugin->discount_convert_country2country();
				$domain = isset($convertCountry['amzwebsite']["$key"]) ? $convertCountry['amzwebsite']["$key"] : '';
				if ( empty($domain) ) continue 1;

				$available[$cc] = array(
					'domain'	=> $domain,
					'name'		=> isset($all_countries_affid["$key"]) ? $all_countries_affid["$key"] : 'missing country name',
				);
				$cc++;
			}
			if ( empty($available) ) return false;

			// verify affiliate ids based on product cached/saved available countries
			$meta_frontend = get_post_meta($product_id, '_amzaff_frontend', true);
			$cache_countries = isset($meta_frontend['countries']) && is_array($meta_frontend['countries']) ? $meta_frontend['countries'] : array();
			$cache_time = isset($meta_frontend['countries_cache_time']) ? $meta_frontend['countries_cache_time'] : 0;

			$cache_need_refresh = empty($cache_countries)
				|| !$cache_time
				|| ( ($cache_time + $this->the_plugin->ss['countries_cache_time']) < time() );

			// product amazon countries availability needs refresh (mandatory)
			if ( $cache_need_refresh ) return $available;

			// may need refresh if one country availability verification is missing!
			// verification for refresh is done in javascript/json based on 'available' key
			foreach ($available as $key => $val) {
				foreach ($cache_countries as $key2 => $val2) {
					// country founded
					if ( isset($val2['domain'], $val2['available']) && ($val['domain'] == $val2['domain']) ) {
						$available["$key"]['available'] = $val2['available'];
						break 1;
					}
				}
			}

			return $available;
		}

		// get product default country when added to cart (based on client country and main affiliate id)
		public function get_product_country_default( $product, $find_client_country=true ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) && isset($product->id) ) {
				$product_id = $product->id;
			}
			if ( empty($product_id) ) return false;

			// client country
			$client_country = false;
			if ( $find_client_country ) {
				$client_country = $this->the_plugin->get_country_perip_external();
			}
			//var_dump('<pre>', $client_country, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
			// return is of type:
			//array(3) {
  			//	["key"]			=> string(3) "com"
  			//	["website"]	=> string(4) ".com"
  			//	["affID"]		=> string(8) "jimmy-us"
			//}

			// product available countries
			$available_countries = $this->get_product_countries_available( $product_id );
			$found = false; $first = false; $first_available = false;
			//var_dump('<pre>', $product_id, $available_countries, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			if ( !empty($available_countries) ) {
				foreach ($available_countries as $key => $val) {

					if ( empty($first) )
						$first = $val['domain'];

					if ( isset($val['available']) ) {
						if ( empty($first) )
							$first = $val['domain'];
						if ( empty($first_available) && $val['available'] )
							$first_available = $val['domain'];
					}
  
					if ( ! empty($client_country) && isset($client_country['website'])
						&& substr($client_country['website'], 1) == $val['domain'] ) {
						$found = $val['domain'];
					}
				}
			}
			//var_dump('<pre>',$found, $first, $first_available,'</pre>');  

			// default country based on: first from all valid countries, first available country or found client country
			$the_country = false;
			if ( !empty($first) ) 
				$the_country = $first;
			if ( !empty($first_available) ) 
				$the_country = $first_available;
			if ( !empty($found) ) 
				$the_country = $found;

			$country = $this->the_plugin->domain2amzForUser( $the_country );
			if ( !empty($available_countries) ) {
				foreach ($available_countries as $key => $val) {
					if ( substr($country['website'], 1) == $val['domain'] ) {
						$country = array_merge($country, array(
							'name'			=> $val['name'],
							'available'		=> isset($val['available']) ? $val['available'] : -1,
						));
					}
				}
			}
			return $country;
		}

		// get product current country when added to cart (based on default country and if client choose a country by himself)
		public function get_product_country_current( $product, $find_client_country=true ) {
			// get product id
			$product_id = $product;
			if ( is_object($product) && isset($product->id) ) {
				$product_id = $product->id;
			}
			if ( empty($product_id) ) return false;
   
			$the_country = $this->get_product_country_default( $product_id, $find_client_country );
			$country = $the_country;
			
			// get asin meta key
			$asin = get_post_meta($product_id, '_amzASIN', true);
			//var_dump('<pre>',$asin,'</pre>');

			//unset($_SESSION['WooZone']);
			//var_dump('<pre>', $the_country, $_SESSION, '</pre>');

			if ( !empty($asin)
				 && isset(
					$_SESSION['WooZone'],
					$_SESSION['WooZone']['product_country'],
					$_SESSION['WooZone']['product_country']["$asin"]
				 )
				 && !empty($_SESSION['WooZone']['product_country']["$asin"])
			) {
				$sess_country = $_SESSION['WooZone']['product_country']["$asin"];

				// product available countries
				$available_countries = $this->get_product_countries_available( $product_id );

				if ( !empty($available_countries) ) {
					foreach ($available_countries as $key => $val) {

						if ( $sess_country == $val['domain'] ) {
							$the_country = $sess_country;
							$country = $this->the_plugin->domain2amzForUser( $the_country );
							$country = array_merge($country, array(
								'name'			=> $val['name'],
								'available'		=> isset($val['available']) ? $val['available'] : -1,
							));
						}
					}
				}
			}

			return $country;
		}
		
		// get amazon products from cart
		public function woo_cart_get_amazon_prods() {
			//global $woocommerce;

			$amz_products = array();

			$cart_items_nb = (int) WC()->cart->get_cart_contents_count();
			if ( ! $cart_items_nb ) return false;

			$cart_items = WC()->cart->get_cart();
			//var_dump('<pre>', $cart_items, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			foreach ($cart_items as $key => $value) {

				$prod_id = isset($value['variation_id']) && (int)$value['variation_id'] > 0 ? $value['variation_id'] : $value['product_id'];
				$amzASIN = $prod_id ? get_post_meta( $prod_id, '_amzASIN', true ) : '';
				
				$parent_id = isset($value['variation_id']) && (int)$value['variation_id'] > 0 ? $value['product_id'] : 0;
				$parent_amzASIN = $parent_id ? get_post_meta( $parent_id, '_amzASIN', true ) : '';
				
				//if ( empty($amzASIN) || strlen($amzASIN) != 10 )
				if ( empty($amzASIN) ) continue 1;

				$amz_products["$key"] = array(
					'cart_item_key'				=> $key,
					'product_id'					=> $prod_id,
					'asin'								=> $amzASIN,
					'parent_id'						=> $parent_id,
					'parent_asin' 				=> $parent_amzASIN,
					'quantity'						=> $value['quantity'],
				);
			} // end foreach
    
			return $amz_products;
		}
		
		// get amazon products from cart by country availability
		public function woo_cart_get_amazon_prods_bycountry() {
			$prods = $this->woo_cart_get_amazon_prods();
			//var_dump('<pre>', $prods, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			if ( empty($prods) ) return false;

			foreach ($prods as $key => $value) {
				$prod_id = $value['parent_id'] ? $value['parent_id'] : $value['product_id'];
				$product_country = $this->get_product_country_current( $prod_id );

				$prods["$key"] = array_merge($prods["$key"], $product_country);
			} // end foreach
			//var_dump('<pre>', $prods, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;

			$bycountry = array();
			foreach ($prods as $key => $value) {
				$domain = substr($value['website'], 1);

				if ( ! isset($bycountry["$domain"]) ) {
					$bycountry["$domain"] = array(
						'domain'			=> $domain,
						'affID'				=> $value['affID'],
						'name'				=> $value['name'],
						'products'			=> array(),
					);
				}
				$bycountry["$domain"]["products"]["$key"] = $value;
			} // end foreach
			//var_dump('<pre>', $bycountry, '</pre>');    

			return $bycountry;
		}

		// woocommerce cart contains multiple amazon shops
		public function woo_cart_is_amazon_multiple( $shops=array() ) {
			if ( empty($shops) )
				$shops = $this->woo_cart_get_amazon_prods_bycountry();
			if ( empty($shops) ) return false;

			$domains = array();
			foreach ($shops as $key => $value) {
				if ( empty($value) ) continue 1;
				
				$domain = $value['domain'];
				if ( ! in_array($domain, $domains) )
					$domains[] = $domain;
			}
			return count($domains);
		}
		
		// update meta (redirect to amazon) for amazon products from cart
		public function woo_cart_update_meta_amazon_prods() {
			$prods = $this->woo_cart_get_amazon_prods();
			//var_dump('<pre>', $prods, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			if ( empty($prods) ) return false;
   
			foreach ($prods as $key => $value) {
				if ( ! isset($value['asin']) || trim($value['asin']) == '' ) continue 1;

				$post_id = $this->the_plugin->get_post_id_by_meta_key_and_value('_amzASIN', $value['asin']);

				$redirect_to_amz = (int) get_post_meta($post_id, '_amzaff_redirect_to_amazon', true);
				update_post_meta($post_id, '_amzaff_redirect_to_amazon', (int)($redirect_to_amz+1));

				$redirect_to_amz2 = (int) get_post_meta($post_id, '_amzaff_redirect_to_amazon_prev', true);
				update_post_meta($post_id, '_amzaff_redirect_to_amazon_prev', (int)($redirect_to_amz2+1));
			} // end foreach
		}
		
		// delete amazon products from cart
		public function woo_cart_delete_amazon_prods() {
			$prods = $this->woo_cart_get_amazon_prods();
			//var_dump('<pre>', $prods, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;    
			if ( empty($prods) ) return false;

			foreach ($prods as $key => $value) {
				if ( ! isset($value['asin']) || trim($value['asin']) == '' ) continue 1;

				//var_dump('<pre>', $key, $value,'</pre>');

				// Remove it from the cart
				//WC()->cart->set_quantity( $value['key'], 0 );
                WC()->cart->remove_cart_item($key);

				//$cart_item = WC()->cart->get_cart_item( $value['key'] );
				//var_dump('<pre>','after delete:', $cart_item,'</pre>');
			} // end foreach

			$cart_items_nb = (int) WC()->cart->get_cart_contents_count();
			//var_dump('<pre>', $cart_items_nb, '</pre>'); echo __FILE__ . ":" . __LINE__;die . PHP_EOL;
		}
	}
}