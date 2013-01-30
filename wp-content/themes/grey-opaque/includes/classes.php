<?php
/**
 * Arraybehaviour.
 *
 * @author ppfeufer
 * @since Grey Opaque 1.0.3.5
 */
class greyopaque_array {
	function get($array, $key, $default = null) {
		return (isset($array[$key])) ? $array[$key] : $default;
	}
}

/**
 * Browser and Systemdetection.
 *
 * @author ppfeufer
 * @since Grey Opaque 1.0.3.5
 */
class greyopaque_browser {
	public static $ua = false;
	public static $browser = false;
	public static $engine = false;
	public static $version = false;
	public static $platform = false;

	function name($ua = null) {
		self::detect($ua);

		return self::$browser;
	}

	function engine($ua = null) {
		self::detect($ua);

		return self::$engine;
	}

	function version($ua = null) {
		self::detect($ua);

		return self::$version;
	}

	function platform($ua = null) {
		self::detect($ua);

		return self::$platform;
	}

	function mobile($ua = null) {
		self::detect($ua);

		return (self::$platform == 'mobile') ? true : false;
	}

	function iphone($ua = null) {
		self::detect($ua);

		return (in_array(self::$platform, array(
			'ipod',
			'iphone'
		))) ? true : false;
	}

	function ios($ua = null) {
		self::detect($ua);

		return (in_array(self::$platform, array(
			'ipod',
			'iphone',
			'ipad'
		))) ? true : false;
	}

	function css($ua = null, $array = false) {
		self::detect($ua);

		$css[] = self::$engine;
		$css[] = self::$browser;

		if(self::$version) {
			$css[] = self::$browser . str_replace('.', '_', self::$version);
		}

		$css[] = self::$platform;

		return ($array) ? $css : implode(' ', $css);
	}

	function detect($ua = null) {
//		$ua = ($ua) ? mb_strtolower($ua, 'utf-8') : mb_strtolower(greyopaque_server::get('http_user_agent'), 'utf-8');
		$ua = ($ua) ? greyopaque_string::string_to_lower($ua) : greyopaque_string::string_to_lower(greyopaque_server::get('http_user_agent'));

		// don't do the detection twice
		if(self::$ua == $ua) return array(
			'browser' => self::$browser,
			'engine' => self::$engine,
			'version' => self::$version,
			'platform' => self::$platform
		);

		self::$ua = $ua;
		self::$browser = false;
		self::$engine = false;
		self::$version = false;
		self::$platform = false;

		// browser
		if(!preg_match('/opera|webtv/i', self::$ua) && preg_match('/msie\s(\d)/', self::$ua, $array)) {
			self::$version = $array[1];
			self::$browser = 'ie';
			self::$engine = 'trident';
		} else if(strstr(self::$ua, 'firefox/4')) {
			self::$version = 4;
			self::$browser = 'ff';
			self::$engine = 'gecko';
		} else if(strstr(self::$ua, 'firefox/3.6')) {
			self::$version = 3.6;
			self::$browser = 'ff';
			self::$engine = 'gecko';
		} else if(strstr(self::$ua, 'firefox/3.5')) {
			self::$version = 3.5;
			self::$browser = 'ff';
			self::$engine = 'gecko';
		} else if(preg_match('/firefox\/(\d+)/i', self::$ua, $array)) {
			self::$version = $array[1];
			self::$browser = 'ff';
			self::$engine = 'gecko';
		} else if(preg_match('/opera(\s|\/)(\d+)/', self::$ua, $array)) {
			self::$engine = 'presto';
			self::$browser = 'opera';
			self::$version = $array[2];
		} else if(strstr(self::$ua, 'konqueror')) {
			self::$browser = 'konqueror';
			self::$engine = 'webkit';
		} else if(strstr(self::$ua, 'iron')) {
			self::$browser = 'iron';
			self::$engine = 'webkit';
		} else if(strstr(self::$ua, 'chrome')) {
			self::$browser = 'chrome';
			self::$engine = 'webkit';

			if(preg_match('/chrome\/(\d+)/i', self::$ua, $array)) {
				self::$version = $array[1];
			}
		} else if(strstr(self::$ua, 'applewebkit/')) {
			self::$browser = 'safari';
			self::$engine = 'webkit';

			if(preg_match('/version\/(\d+)/i', self::$ua, $array)) {
				self::$version = $array[1];
			}
		} else if(strstr(self::$ua, 'mozilla/')) {
			self::$engine = 'gecko';
			self::$browser = 'mozilla';
		}

		// platform
		if(strstr(self::$ua, 'j2me')) {
			self::$platform = 'mobile';
		} else if(strstr(self::$ua, 'iphone')) {
			self::$platform = 'iphone';
		} else if(strstr(self::$ua, 'ipod')) {
			self::$platform = 'ipod';
		} else if(strstr(self::$ua, 'ipad')) {
			self::$platform = 'ipad';
		} else if(strstr(self::$ua, 'mac')) {
			self::$platform = 'mac';
		} else if(strstr(self::$ua, 'darwin')) {
			self::$platform = 'mac';
		} else if(strstr(self::$ua, 'webtv')) {
			self::$platform = 'webtv';
		} else if(strstr(self::$ua, 'win')) {
			self::$platform = 'win';
		} else if(strstr(self::$ua, 'freebsd')) {
			self::$platform = 'freebsd';
		} else if(strstr(self::$ua, 'x11') || strstr(self::$ua, 'linux')) {
			self::$platform = 'linux';
		}

		return array(
			'browser' => self::$browser,
			'engine' => self::$engine,
			'version' => self::$version,
			'platform' => self::$platform
		);
	}
}

/**
 * Working with $_SERVER.
 *
 * @author ppfeufer
 * @since Grey Opaque 1.0.3.5
 */
class greyopaque_server {
	function get($key, $default = null) {
		if(empty($key)) {
			return $_SERVER;
		}

//		return greyopaque_array::get($_SERVER, mb_strtoupper($key, 'utf-8'), $default);
		return greyopaque_array::get($_SERVER, greyopaque_string::string_to_upper($key), $default);
	}
}

/**
 * Stringbehaviour.
 *
 * @author ppfeufer
 * @since Grey Opaque 1.1.0
 */
class greyopaque_string {
	function string_to_lower($var_sString = '') {
		if(!function_exists('mb_strtolower')) {
			$var_sLowerString = mb_strtolower($var_sString, 'utf-8');
		} else {
			$var_sLowerString = strtolower($var_sString);
		}

		return $var_sLowerString;
	}

	function string_to_upper($var_sString = '') {
		if(!function_exists('mb_strtoupper')) {
			$var_sUpperString = mb_strtoupper($var_sString, 'utf-8');
		} else {
			$var_sUpperString = strtoupper($var_sString);
		}

		return $var_sUpperString;
	}
}
?>