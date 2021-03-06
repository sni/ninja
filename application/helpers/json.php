<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Help class for handling JSON encode or decode
 * since we can't always know for sure that we have
 * the built-in PHP json_encode function and should
 * use ZEND_Json instead.
 */
class json_Core
{
	/**
	 * Kills the request after echoing a structured json response
	 *
	 * @param array $response = null
	 * @param int $http_status_code = 0
	 */
	private static function _send_response($response = null, $http_status_code = 200) {
		header('Content-Type: application/json');
		$exit = 0;
		if($http_status_code > 299) {
			$exit = 1;
			header("HTTP/1.0 $http_status_code");
		}
		echo self::encode($response);
		exit($exit);
	}

	/**
	 * Decode JSON data into PHP
	 *
	 * @param $var json-encoded string to decode
	 * @return false on error, json-decoded data on success
	 */
	public static function decode($var = false)
	{
		if (empty($var)) {
			return false;
		}
		if (function_exists('json_decode')) {
			return json_decode($var);
		}
		$json = zend::instance('json');
		return $json->decode($var);
	}

	/**
	 * Encode variable data into JSON
	 *
	 * @param $var Variable to encode
	 * @return false on error, json-encoded string on success.
	 */
	public static function encode($var = false)
	{
		if (empty($var) && !is_array($var)) {
			return false;
		}
		if (function_exists('json_encode')) {
			return json_encode($var);
		}

		$json = zend::instance('json');
		return $json->encode($var);
	}

	/**
	 * Give it anything, it will turn it into JSON
	 *
	 * @param $reason string
	 * @param $http_status_code int = 500
	 */
	public static function fail($reason = null, $http_status_code = 500) {
		return self::_send_response($reason, $http_status_code);
		//return self::_send_response(array('error' => $reason), 1);
	}

	/**
	 * Give it anything, it will turn it into JSON
	 *
	 * @param $result string
	 * @param $http_status_code int = 200
	 */
	public static function ok($result = null, $http_status_code = 200) {
		return self::_send_response($result, $http_status_code);
		//return self::_send_response(array('result' => $result));
	}
}
