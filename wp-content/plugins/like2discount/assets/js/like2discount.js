/**
 *	Like 2 Discount plugin
 *
 *	Created by: Laborator - www.laborator.co
 */

// @codekit-prepend "cookies.js";

;( function(  $, window, undefined ) {
	"use strict";

	$( document ).ready( function() {
		
		var $overlay      	 = $( '.l2d-overlay' ),
			$body            = $( '.l2d-body' ),

			$both            = $overlay.add( $body ),

			$le_env          = $body.find( '.like-and-email' ),
			$cc_wrapper      = $body.find( '.cc-wrapper' ),
			$email_input     = $body.find( '.email-box input' ),

			$errors			 = $body.find( '.errors-container' ),
			$successmsg	 	 = $body.find( '.successmsg-container' ),

			_error_timeout	 = 0,
			_success_timeout = 0,

			is_liked	 	 = false,

			entrance_class   = $body.data( 'enter' ),
			exit_class       = $body.data( 'exit' ),

			requested_coupon = '',

			modal_is_opened	 = false,

			is_share_button  = false,
			is_send_button   = false,
			
			// added in v1.1
			require_confirmation = $body.data( 'require-confirmation' );



		// Show Modal
		var l2dShow = function() {
			$both.addClass( 'visible' );
			$body.addClass( 'animated ' + entrance_class );

			if ( $body.css( 'position' ) == 'absolute' ) {
				$body.css({
					top: $( window ).scrollTop() + 100
				});
			}

			if ( ! requested_coupon || is_share_button ) {
				requestNewCouponCode();
			}

			modal_is_opened = true;
		};


		// Hide Modal
		var l2dHide = function() {
			$body.removeClass( entrance_class ).addClass( exit_class );
			$overlay.addClass( 'animated fadeOut' );

			setTimeout( function() {
				$overlay.attr( 'class', 'l2d-overlay' );
				$body.attr( 'class', 'l2d-body' );
			}, 500 );

			modal_is_opened = false;
		};


		// Show Modal for the First time
		var showModalFirstTime = function() {
			if ( Cookies.get( 'l2d_modal_is_shown' ) != 1 ) {
				Cookies.set( 'l2d_modal_is_shown', 1 );

				l2dShow();
			}
		}


		// Show Modal After adding to cart
		var showModalAfterAddingToCart = function() {
			if ( Cookies.get( 'l2d_modal_addtocart_is_shown' ) != 1 ) {
				Cookies.set( 'l2d_modal_addtocart_is_shown', 1 );

				l2dShow();
			}
		}


		// Make these functions public
		window.l2dShow = l2dShow;
		window.l2dHide = l2dHide;
		window.showModalAfterAddingToCart = showModalAfterAddingToCart;
		//window.showModalFirstTime = showModalFirstTime;


		// Show Coupon
		function showCoupon( coupon_code ) {
			if ( coupon_code.length < 2 ) {
				coupon_code = '...';
			}
			
			$cc_wrapper.removeClass( 'closed' );
			$cc_wrapper.find( 'input' ).val( coupon_code );
		}


		// Hide Coupon
		function hideCoupon() {
			$cc_wrapper.addClass( 'closed' );
		}
		
		
		// On Share Event
		if ( $le_env.hasClass( 'l2d-display-share' ) ) {
			is_share_button = true;
		}
		
		
		// On Send Event
		if ( $le_env.hasClass( 'l2d-display-send' ) ) {
			is_send_button = true;
		}


		// On Like Event
		function facebookPageIsLiked( url, ui ) {

			var $fb_iframe_widget = $( '.fb_iframe_widget' );
			$fb_iframe_widget.height( $fb_iframe_widget.outerHeight() );

			$le_env.addClass( 'step-1-finished' );
			//$email_input.focus();


			if ( require_confirmation == 0 ) {
				// verify the coupon
				checkSubmittedEmail( true );
			} else {
				showCoupon( requested_coupon );
				showSuccess( $body.find( '.like-box' ).data( 'enter-email' ), 4000 );

				$body.find( '.like-btn' ).addClass( 'loaded' );
			}

			is_liked = true;

			Cookies.set( 'l2d_page_is_liked', 1, { expire: 3600 * 24 * 7 } );
		}


		// On Unlike Event
		function facebookPageIsUnliked( url, ui ) {
			var $fb_iframe_widget = $( '.fb_iframe_widget' );
			$fb_iframe_widget.height( $fb_iframe_widget.outerHeight() );

			$le_env.removeClass( 'step-1-finished' );
			hideCoupon();

			is_liked = false;

			Cookies.set( 'l2d_page_is_liked', 0, { expire: 0 } );
		}
		
		
		// Send Link
		function facebookLinkSent( response ) {
			
			$le_env.addClass( 'message-send' );
			facebookPageIsLiked();
		}


		// Request new coupon Code
		function requestNewCouponCode() {
			var _data = {
				action: 'laborator_l2d_request_new_coupon_code'
			};

			$.post( ajax_url, _data, function( resp ) {
				requested_coupon = resp;
				
				// Delayed post
				var $coupon_input = $cc_wrapper.find( 'input' );
				
				if ( $coupon_input.val() == '...' ) {
					$coupon_input.val( requested_coupon );
				}
			}, 'json');
		}


		// Show Error
		function showError( msg, timeout ) {
			$errors.html( msg );
			$errors.slideDown( 'normal' );

			if ( timeout ) {
				window.clearTimeout( _error_timeout );

				_error_timeout = setTimeout( function() {
					hideError();

				}, timeout );
			}
		}

		function hideError() {
			$errors.slideUp( 'fast', function() {
				$errors.html( '' );
			} );
		}


		// Show Success
		function showSuccess( msg, timeout ) {
			$successmsg.html( msg );
			$successmsg.slideDown( 'normal' );

			if ( timeout ) {
				window.clearTimeout();

				_success_timeout = setTimeout( function() {
					hideSuccess();

				}, timeout );
			}
		}

		function hideSuccess() {
			$successmsg.slideUp( 'fast', function() {
				$successmsg.html( '' );
			} );
		}


		// Check Submitted Email
		function checkSubmittedEmail( no_confirmation ) {
			// Check if form is filled already
			if ( $email_input.data( 'done' ) ) {
				return false;
			}

			// Check if page is liked
			if ( ! no_confirmation && ! is_liked && ! is_share_button ) {
				showError( $body.find( '.like-box' ).data( 'likemsg' ), 3000 );
				return false;
			}
			
			// Email Validation
			var re = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i;
			
			if ( ! no_confirmation && ! re.test( $email_input.val() ) ) {
				showError( $email_input.data( 'invalid' ), 3000 );
				return false;
			}
			
			if ( is_share_button ) {
				showCoupon( requested_coupon );
			}

			// Submit Email and Pass to the next validation phase
			var _data = {
				action: 'laborator_l2d_submit_coupon_email',
				email: $email_input.val(),
				requestedCoupon: requested_coupon
			};

			if ( ! no_confirmation ) {
				$email_input.parent().addClass( 'is-loading' );
			} else {
				$body.find( '.like-btn' ).addClass( 'is-loading' );
			}

			$.post( ajax_url, _data, function( resp ) {
				if ( ! no_confirmation ) {
					$email_input.parent().removeClass( 'is-loading' );
				} else {
					$body.find( '.like-btn' ).removeClass( 'is-loading' );

					if ( no_confirmation ) {
						$body.find( '.like-btn' ).addClass( 'loaded' );
					}
				}


				if ( resp.success ) {
					$email_input
						.attr( 'readonly', true )
							.data( 'done', true );

					if ( no_confirmation ) {
						showCoupon( requested_coupon );
						showSuccess( $body.find( '.like-box' ).data( 'coupon-confirmed' ) );
					} else if ( resp.message ) {
						showSuccess( resp.message );
					}
				} else {
					$email_input.attr( 'readonly', false );

					if ( resp.errmsg ) {
						showError( resp.errmsg, 10000 );
					}
				}

			}, 'json' );
		}


		// Coupon Wrapper Input Select
		$body.find( '.cc-wrapper input' ).on('click', function( ev ) {
			$(this).select();
		});


		// Close Event
		$overlay.on( 'click', l2dHide );

		$body.on( 'click', '.close', function( ev ) {
			ev.preventDefault();

			l2dHide();
		} );


		// Like Event
		var eventsAttached = false;
		
		window.fbAsyncInit = function() {
			if ( eventsAttached ) {
				return;
			}
			
			FB.Event.subscribe( 'edge.create', facebookPageIsLiked );
			FB.Event.subscribe( 'edge.remove', facebookPageIsUnliked );
				
			if ( is_send_button ) {
				FB.Event.subscribe( 'message.send', facebookLinkSent );
			}
			
			eventsAttached = true;
		};
		
		$( window ).load( function() {
			
			if ( eventsAttached == false ) {
				
				FB.Event.subscribe( 'edge.create', facebookPageIsLiked );
				FB.Event.subscribe( 'edge.remove', facebookPageIsUnliked );
				
				if ( is_send_button ) {
					FB.Event.subscribe( 'message.send', facebookLinkSent );
				}
				
				eventsAttached = true;
			}
		} );


		// Email Submit Event
		$email_input.on( 'keydown', function( ev ) {
			if ( ev.keyCode == 13 ) {
				checkSubmittedEmail();
			}
		} );
		
		$( '.email-box .email-submit' ).on( 'click', function( ev ) {
			
			ev.preventDefault();
			checkSubmittedEmail();
		} );


		// Close Modal if opened
		$( window ).on( 'keyup', function( ev ) {
			if ( modal_is_opened && ev.keyCode == 27 ) {
				l2dHide();
			}
		} );


		// Show Success Modal
		if ( $body.hasClass( 'is-success' ) ) {
			requested_coupon = 'null';
			l2dShow();

			Cookies.set( 'l2d_coupon_gained', 1 );
		}

		// When refreshing the page


		// Show Modal for the First time
		if ( $( 'body' ).hasClass( 'l2d-modal' ) ) {
			var delay_time = $( 'body' ).attr( 'class' ).match(/l2d-show-delay-([0-9]+)/);

			if ( delay_time ) {
				delay_time = parseInt( delay_time[1], 10 );

				setTimeout( showModalFirstTime, delay_time * 1000 );
			}
		}


		// Show Modal after adding to cart
		if ( $( 'body' ).hasClass( 'l2d-after-add-to-cart' ) ) {
			if ( typeof wc_add_to_cart_params != 'undefined' ) {
				// Ajax mode
				$( document ).on( 'added_to_cart', function( fragments, cart_hash ) {
					setTimeout( showModalAfterAddingToCart, 1000 );
				} );
			}
		}


		// Show Modal Link
		$( 'body' ).on( 'click', '.l2d-show-modal', function( ev ) {
			ev.preventDefault();

			l2dShow();
		});


		// Center Sidebanner
		var $sb = $( '.l2d-side-banner' );

		if ( $sb.length ) {
			var sb_img = $sb.find( 'img' ).attr( 'src' ),
				sb_load = new Image();

			sb_load.src = sb_img;
			sb_load.onload = function() {
				$sb.css( {
					marginTop: -this.height / 2,
					display: 'block'
				} );
			}
		}
	 } );

} )( jQuery, window );