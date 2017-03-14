<?php
if ( defined('ABSPATH') ) {
	die;
}
   
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

/** Set up WordPress environment */
require_once( $path_to_wp.'/wp-load.php' );
   
@ini_set('max_execution_time', 0);
@set_time_limit(0); // infinte


/**
 * aaWoozoneKeys
 * http://www.aa-team.name
 * =======================
 *
 * @author       AA-Team
 */
if (!class_exists('aaWoozoneKeys')) { class aaWoozoneKeys {
	
	// plugin global object
	private $the_plugin = null;
	private $amzHelper = null;
	private $P = array();
	private $keysObj = null;
	
	private $used_keys = array();
	private $key_update_last = true;


	/**
	 * Constructor
	 */
	public function __construct() {
		//die('gimi');
		
		global $WooZone;
		$this->the_plugin = $WooZone;
		$this->amzHelper = $this->the_plugin->amzHelper;
		$this->P = $_POST;

		// aateam keys lib
		require_once( $this->the_plugin->cfg['paths']['plugin_dir_path'] . '_keys/keys.php' );
		$this->keysObj = new aaWoozoneKeysLib( $this->the_plugin, $this->P );

		$this->get_request();
	}
	
	
	private function get_request() {
		$req = array(
			'action'			=> isset($_REQUEST['action']) ? $_REQUEST['action'] : 'amazon_request',
			'what_func'			=> isset($_REQUEST['what_func']) ? $_REQUEST['what_func'] : '',
		);
		extract($req);
		
		$this->print_headers();
   
		if ( !in_array($action, array('amazon_request', 'reset_blocked_keys')) ) {
			$this->print_response( array(
				'msg'		=> 'Invalid action!',
			));
		}
		if ( ('amazon_request' == $action)
			&& !in_array($what_func, array('api_search_bypages', 'api_search_byasin', 'api_make_request')) ) {

			$this->print_response( array(
				'msg'		=> 'Invalid what func!',
			));
		}
   
		if ( 'amazon_request' == $action ) {
			$max_allowed_req = 5;
			$current_req = 0;
			do {
				$response = $this->amazon_request( $this->P );
				$current_req++;
			}
			while( ('invalid' == $response['status']) && !in_array($response['code'], array(-2, 3)) && ($current_req < $max_allowed_req) );
			// 3 	= Sorry, your search did not return any results.
			// -2 = Invalid parameters for this remote request
		}
		else if ( 'reset_blocked_keys' == $action ) {
			$response = $this->reset_blocked_keys();
		}

		$this->print_response( $response );
	}
	
	private function print_headers() {
		$content_type = 'text/xml';
	
		// export headers
		//header("Content-Description: File Transfer");           
		header("Content-Type: ".$content_type."; charset=utf-8"); //application/force-download
		//header("Content-Disposition: attachment; filename=$filename.$file_ext");
		// Disable caching
		//header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		//header("Cache-Control: private", false);
		//header("Pragma: no-cache"); // HTTP 1.0
		//header("Expires: 0"); // Proxies
	}
	
	private function print_response( $response=array() ) {
		$response = array_merge(array(
			'status'			=> 'invalid',
			'msg'			=> '',
			'code'			=> -1,
			'body'			=> '',
		), $response);
		extract($response);
		
		$body = serialize($body);

		$print = array();
		$print[] = '<?xml version="1.0" encoding="UTF-8"?><response>';
		$print[] = 		'<status>' . $status . '</status>';
		$print[] = 		'<msg><![CDATA[' . $msg . ']]></msg>';
		$print[] = 		'<code><![CDATA[' . $code . ']]></code>';
		$print[] = 		'<body><![CDATA[' . $body . ']]></body>';
		$print[] = '</response>';
		$print = implode(PHP_EOL, $print);

		echo $print;
		die;
	}

	private function amazon_request( $pms=array() ) {
		$ret = array(
			'status'		=> 'invalid',
			'msg'			=> '',
			'code'			=> -1,
			'body'			=> '',
		);
		$unlockStat = '';

		$what_func = isset($_REQUEST['what_func']) ? $_REQUEST['what_func'] : '';
		if (empty($what_func) || !method_exists($this->amzHelper, $what_func)) {
			return array_merge($ret, array('msg' => 'Invalid what func!', 'code' => -2));
		}
   
		$setupPms = array();

		// get available amazon keys
		$access_keys = $this->keysObj->get_available_access_key( $this->used_keys );
		if ( empty($access_keys) || !isset($access_keys['id']) ) {
			return array_merge($ret, array('msg' => 'No access key available!', 'code' => -2));
		}
		$setupPms['AccessKeyID'] = $access_keys['access_key'];
		$setupPms['SecretAccessKey'] = $access_keys['secret_key'];
		$this->used_keys[] = $access_keys['id'];
		
		// lock current amazon key
		//$this->keysObj->lock_current_access_key( $access_keys['id'] );

		// reinit the amazon object with new (country, access keys pair)
		if ( isset($this->P['__request'], $this->P['__request']['country']) ) {
			$setupPms['country'] = $this->P['__request']['country'];
		}
		$this->amzHelper->init_settings( $setupPms, true );
   
		try {
			$response = $this->amzHelper->$what_func(array_merge($this->P, array('keys_id' => $access_keys['id'])));
			$response__ = $response;
			$response = isset($response['response']) ? $response['response'] : '';
			if (empty($response)) {
				// save amazon request
				$request_insert_id = $this->keysObj->save_amazon_request(array(
					'id_amz_keys'			=> $access_keys['id'],
					'status'						=> 'invalid',
					'status_msg'				=> isset($response__['msg']) ? $response__['msg'] : 'amazon: empty response!',
				));

				// unlock current amazon key
				if ( $this->key_update_last ) {
				$unlockStat = $this->keysObj->unlock_current_access_key( $access_keys['id'], array(
					'last_request_id'				=> $request_insert_id,
					'last_request_time'			=> true,
					'nb_requests'					=> true
				));
				}
				return array_merge($ret, array('msg' => 'Something is wrong with response content!' . (' ' . $unlockStat)));
			}

			//var_dump('<pre>', $this->amzHelper->aaAmazonWS->responseConfig, '</pre>');
			//unset($this->P['amz_settings']);
			//var_dump('<pre>', 'aateam-amazon-response', $response, '</pre>');
			//var_dump('<pre>', 'aateam-params', $this->P, '</pre>');
			//die('debug...');
			
			// response status
			if ( isset($response__['status'], $response__['msg']) ) {
				$request_status = array(
					'status'				=> $response__['status'],
					'msg'				=> $response__['msg'],
					'code'				=> $response__['code'],
				);
			} else {
				if ( 'api_make_request' == $what_func ) {
	
					$method = isset($this->P['method']) ? $this->P['method'] : '';
	
					$request_status = $this->amzHelper->is_amazon_valid_response( 'cartThem' == $method ? $this->amzHelper->aaAmazonWS->get_lastCart() : $response, $method );
				}
				else {
					$request_status = $this->amzHelper->is_amazon_valid_response( $response );
				}
			}

			// save amazon request
			$request_insert_id = $this->keysObj->save_amazon_request(array(
				'id_amz_keys'			=> $access_keys['id'],
				'status'						=> isset($request_status['status']) ? $request_status['status'] : 'valid',
				'status_msg'				=> serialize( $response ), //isset($request_status['msg']) ? $request_status['msg'] : '',
			));

			// unlock current amazon key
			if ( $this->key_update_last ) {
			$unlockStat = $this->keysObj->unlock_current_access_key( $access_keys['id'], array(
				'last_request_id'				=> $request_insert_id,
				'last_request_time'			=> true,
				'nb_requests'					=> true
			));
			}

			return array_merge($ret, array(
				'status'			=> isset($request_status['status']) && ('valid' == $request_status['status']) ? 'valid' : 'invalid',
				'msg'			=> isset($request_status['msg']) ? $request_status['msg'] . (' ' . $unlockStat) : '' . (' ' . $unlockStat),
				'code'			=> isset($request_status['code']) ? $request_status['code'] : -1,
				'body'			=> $response
			));

		} catch (Exception $e) {
			// Check 
			if (isset($e->faultcode)) { // error occured!

				//var_dump('<pre>', 'aateam-amazon-response (Exception):', $e, '</pre>'); die('debug...');
				$__msg = $e->faultcode .  ' : ' . (isset($e->faultstring) ? $e->faultstring : $e->getMessage());

				// save amazon request
				$request_insert_id = $this->keysObj->save_amazon_request(array(
					'id_amz_keys'			=> $access_keys['id'],
					'status'						=> 'invalid',
					'status_msg'				=> $__msg,
				));

				// unlock current amazon key
				if ( $this->key_update_last ) {
				$unlockStat = $this->keysObj->unlock_current_access_key( $access_keys['id'], array(
					'last_request_id'				=> $request_insert_id,
					'last_request_time'			=> true,
					'nb_requests'					=> true
				));
				}

				return array_merge($ret, array('msg' => $__msg + (' ' . $unlockStat)));
			}
		}

		return array_merge($ret, array('msg' => 'unknown error'));
	}

	private function reset_blocked_keys( $interval_sec=45 ) {
		return $this->keysObj->reset_blocked_keys();
	}
	
} }
$woozone_keys = new aaWoozoneKeys();