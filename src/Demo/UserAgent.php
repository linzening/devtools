<?php
// +--------------------------------
// | User-Agent解析composer插件
// +--------------------------------
// | author:linzening
// +--------------------------------
// | date:2019-05-09
// +--------------------------------
namespace linzening\devtools\Demo;

class UserAgent {

	// ----------------- 平台：系统版本
	var $platforms = [
		'windows nt 6.3'	=> 'Win8.1',
		'windows nt 6.2'	=> 'Win8',
		'windows nt 6.1'	=> 'Win7',
		'windows nt 6.0'	=> 'Win Longhorn',
		'windows nt 5.2'	=> 'Win2003',
		'windows nt 5.0'	=> 'Win2000',
		'windows nt 5.1'	=> 'WinXP',
		'windows nt 4.0'	=> 'Windows NT 4.0',
		'windows nt 10.0'	=> 'Windows NT 10.0',
		'winnt4.0'			=> 'Windows NT 4.0',
		'winnt10.0'			=> 'Windows NT 10.0',
		'winnt 4.0'			=> 'Windows NT',
		'winnt'				=> 'Windows NT',
		'windows 98'		=> 'Win98',
		'win98'				=> 'Win98',
		'windows 95'		=> 'Win95',
		'win95'				=> 'Win95',
		'windows'			=> 'Unknown Windows OS',
		'iPhone OS 12_0'	=> 'iPhone12_0',
		'iPhone OS 12'		=> 'iPhone12',
		'iPhone OS 11_4'	=> 'iPhone11_4',
		'iPhone OS 11'		=> 'iPhone11',
		'iPhone OS 10_3'	=> 'iPhone10_3',
		'iPhone OS 10_2'	=> 'iPhone10_2',
		'iPhone OS 10'		=> 'iPhone10',
		'iPhone OS 9_3'		=> 'iPhone9_3',
		'iPhone OS 9_1'		=> 'iPhone9_1',
		'iPhone OS 9'		=> 'iPhone9',
		'iPhone OS 8_4'		=> 'iPhone8_4',
		'iPhone OS 8_3'		=> 'iPhone8_3',
		'iPhone OS 8'		=> 'iPhone8',
		'iPhone OS 7'		=> 'iPhone7',
		'iPhone OS 6_0'		=> 'iPhone6_0',
		'iPhone OS 6'		=> 'iPhone6',
		'iPhone'			=> 'iPhone',
		'Android 8.0'		=> 'Android 8.0',
		'Android 8.1'		=> 'Android 8.1',
		'Android 7.0'		=> 'Android 7.0',
		'Android 7.1'		=> 'Android 7.1',
		'Android 6.0'		=> 'Android 6.0',
		'Android 6.1'		=> 'Android 6.1',
		'Android 5.0'		=> 'Android 5.0',
		'Android 5.1'		=> 'Android 5.1',
		'Android 4.4'		=> 'Android 4.4',
		'Android 4.0'		=> 'Android 4.0',
		'Android 2'			=> 'Android 2',
		'Android 3'			=> 'Android 3',
		'Android 4'			=> 'Android 4',
		'Android 5'			=> 'Android 5',
		'Android 6'			=> 'Android 6',
		'Android 7'			=> 'Android 7',
		'Android 8'			=> 'Android 8',
		'Android 9'			=> 'Android 9',
		'Android'			=> 'Android',
		'os x'				=> 'MacOS X',
		'ppc mac'			=> 'Power PC Mac',
		'freebsd'			=> 'FreeBSD',
		'ppc'				=> 'Macintosh',
		'linux'				=> 'Linux',
		'debian'			=> 'Debian',
		'sunos'				=> 'Sun Solaris',
		'beos'				=> 'BeOS',
		'apachebench'		=> 'ApacheBench',
		'aix'				=> 'AIX',
		'irix'				=> 'Irix',
		'osf'				=> 'DEC OSF',
		'hp-ux'				=> 'HP-UX',
		'netbsd'			=> 'NetBSD',
		'bsdi'				=> 'BSDi',
		'openbsd'			=> 'OpenBSD',
		'gnu'				=> 'GNU/Linux',
		'unix'				=> 'Unknown Unix OS'
	];


	// ----------------- 浏览器名称
	var $browsers = [
		'Flock'				=> 'Flock',
		'Chimera'			=> 'Chimera',
		'UCBrowser'			=> 'UC',
		'Netscape'			=> 'Netscape',
		'MicroMessenger'	=> 'Wechart',
		'MQQBrowser'		=> 'MQQBrowser',
		'Opera'				=> 'Opera',
		'MSIE 10.0'			=> 'IE10',
		'MSIE 9.0'			=> 'IE9',
		'MSIE 8.0'			=> 'IE8',
		'MSIE 7.0'			=> 'IE7',
		'MSIE 6.0'			=> 'IE6',
		'MSIE'				=> 'IE',
		'Edge'				=> 'Edge',
		'Internet Explorer'	=> 'IE',
		'AppleWebKit'	=> 'AppleWebKit',
		'Safari'			=> 'Safari',
		'QQ'				=> 'QQ',
		'Shiira'			=> 'Shiira',
		'Firefox'			=> 'Firefox',
		'Chrome'			=> 'Chrome',
		'Phoenix'			=> 'Phoenix',
		'Firebird'			=> 'Firebird',
		'Camino'			=> 'Camino',
		'OmniWeb'			=> 'OmniWeb',
		'Konqueror'			=> 'Konqueror',
		'icab'				=> 'iCab',
		'Lynx'				=> 'Lynx',
		'Links'				=> 'Links',
		'hotjava'			=> 'HotJava',
		'amaya'				=> 'Amaya',
		'IBrowse'			=> 'IBrowse',
		'Trident'				=> 'IE',
		'Mozilla'			=> 'Mozilla',
	];
	// ----------------- 移动设备
	var $mobiles = [
		// legacy array, old values commented out
		'mobileexplorer'	=> 'Mobile Explorer',
		//					'openwave'			=> 'Open Wave',
		//					'opera mini'		=> 'Opera Mini',
		//					'operamini'			=> 'Opera Mini',
		//					'elaine'			=> 'Palm',
		'palmsource'		=> 'Palm',
		//					'digital paths'		=> 'Palm',
		//					'avantgo'			=> 'Avantgo',
		//					'xiino'				=> 'Xiino',
		'palmscape'			=> 'Palmscape',
		//					'nokia'				=> 'Nokia',
		//					'ericsson'			=> 'Ericsson',
		//					'blackberry'		=> 'BlackBerry',
		//					'motorola'			=> 'Motorola'

		// Phones and Manufacturers
		'motorola'			=> "Motorola",
		'nokia'				=> "Nokia",
		'palm'				=> "Palm",
		'iphone'			=> "Apple iPhone",
		'ipad'				=> "iPad",
		'ipod'				=> "Apple iPod Touch",
		'sony'				=> "Sony Ericsson",
		'ericsson'			=> "Sony Ericsson",
		'blackberry'		=> "BlackBerry",
		'cocoon'			=> "O2 Cocoon",
		'blazer'			=> "Treo",
		'lg'				=> "LG",
		'amoi'				=> "Amoi",
		'xda'				=> "XDA",
		'mda'				=> "MDA",
		'vario'				=> "Vario",
		'htc'				=> "HTC",
		'samsung'			=> "Samsung",
		'sharp'				=> "Sharp",
		'sie-'				=> "Siemens",
		'alcatel'			=> "Alcatel",
		'benq'				=> "BenQ",
		'ipaq'				=> "HP iPaq",
		'mot-'				=> "Motorola",
		'playstation portable'	=> "PlayStation Portable",
		'hiptop'			=> "Danger Hiptop",
		'nec-'				=> "NEC",
		'panasonic'			=> "Panasonic",
		'philips'			=> "Philips",
		'sagem'				=> "Sagem",
		'sanyo'				=> "Sanyo",
		'spv'				=> "SPV",
		'zte'				=> "ZTE",
		'sendo'				=> "Sendo",

		// Operating Systems
		'symbian'				=> "Symbian",
		'SymbianOS'				=> "SymbianOS",
		'elaine'				=> "Palm",
		'palm'					=> "Palm",
		'series60'				=> "Symbian S60",
		'windows ce'			=> "Windows CE",

		// Browsers
		'obigo'					=> "Obigo",
		'netfront'				=> "Netfront Browser",
		'openwave'				=> "Openwave Browser",
		'mobilexplorer'			=> "Mobile Explorer",
		'operamini'				=> "Opera Mini",
		'opera mini'			=> "Opera Mini",

		// Other
		'digital paths'			=> "Digital Paths",
		'avantgo'				=> "AvantGo",
		'xiino'					=> "Xiino",
		'novarra'				=> "Novarra Transcoder",
		'vodafone'				=> "Vodafone",
		'docomo'				=> "NTT DoCoMo",
		'o2'					=> "O2",

		// Fallback
		'mobile'				=> "Generic Mobile",
		'wireless'				=> "Generic Mobile",
		'j2me'					=> "Generic Mobile",
		'midp'					=> "Generic Mobile",
		'cldc'					=> "Generic Mobile",
		'up.link'				=> "Generic Mobile",
		'up.browser'			=> "Generic Mobile",
		'smartphone'			=> "Generic Mobile",
		'cellphone'				=> "Generic Mobile"
	];

	// ----------------- 机器人
	var $robots = [
		'googlebot'			=> 'Googlebot',
		'msnbot'			=> 'MSNBot',
		'slurp'				=> 'Inktomi Slurp',
		'yahoo'				=> 'Yahoo',
		'askjeeves'			=> 'AskJeeves',
		'fastcrawler'		=> 'FastCrawler',
		'infoseek'			=> 'InfoSeek Robot 1.0',
		'lycos'				=> 'Lycos'
	];


	public $agent		= NULL;

	public $is_browser	= FALSE;
	public $is_robot	= FALSE;
	public $is_mobile	= FALSE;

	public $languages	= [];
	public $charsets	= [];

	public $platform	= '';
	public $browser	= '';
	public $version	= '';
	public $mode		= 'Y'; // 浏览器模式：Y极速模式、N兼容模式
	public $mobile		= '';
	public $robot		= '';
	public $NetType	= '';//网络类型：移动端的WIFI或者4G，P：PC端

	/**
	 * 构造函数
	 *
	 * Sets the User Agent and runs the compilation routine
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct($agent = '')
	{
		if ($agent != '')
		{
			$this->agent = $agent;
		}
		else if (isset($_SERVER['HTTP_USER_AGENT']))
		{
			$this->agent = trim($_SERVER['HTTP_USER_AGENT']);
		}

		if ( ! is_null($this->agent))
		{
			$this->_compile_data();
		}

		//log_message('debug', "User Agent Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Compile the User Agent Data
	 * 处理UA数据
	 * @access	private
	 * @return	bool
	 */
	private function _compile_data()
	{
		$this->_set_platform();
		// 遍历执行私有方法
		foreach (['_set_robot', '_set_browser', '_set_mobile'] as $function)
		{
			if ($this->$function() === TRUE)
			{
				break;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set the Platform
	 * 获取用户平台
	 * @access	private
	 * @return	mixed
	 */
	private function _set_platform()
	{
		if (is_array($this->platforms) AND count($this->platforms) > 0)
		{
			foreach ($this->platforms as $key => $val)
			{
				if (preg_match("/".preg_quote($key)."/i", $this->agent))
				{
					$this->platform = $val;
					//### Android和iPhone下，获取详细版本号 ###
					if(strstr($key,"Android")||strstr($key,"iPhone")){
						if (preg_match("/Android ([\d.]+)/i", $this->agent ,$match)){
							if(!empty($match[1])){
								$this->platform = "Android ".$match[1];
							}
						}
						if (preg_match("/iPhone OS ([\d_]+)/i", $this->agent ,$match)){
							if(!empty($match[1])){
								$this->platform = "iPhone ".$match[1];
							}
						}
					}
					return TRUE;
				}
			}
		}
		$this->platform = 'Unknown Platform';
	}

	// --------------------------------------------------------------------

	/**
	 * Set the Browser
	 * 获取浏览器
	 * @access	private
	 * @return	bool
	 */
	private function _set_browser()
	{
		if (is_array($this->browsers) AND count($this->browsers) > 0)
		{
			foreach ($this->browsers as $key => $val)
			{
				if (preg_match("/".preg_quote($key).".*?([0-9\.]+)/i", $this->agent, $match))
				{
					$this->is_browser = TRUE;
					$this->version = $match[1];
					$this->browser = $val;
					$this->_set_mobile();
					// 获取浏览器模式：急速模式/兼容模式
					if (preg_match("/(MSIE|compatible|Trident)/i", $this->agent, $match10)){
						$this->mode = "N";
					}
					// 获取网络类型
					if (preg_match("/NetType\/(4G|WIFI)/i", $this->agent, $match11)){
						$this->NetType = $match11[1];
					}else{
						$this->NetType = "P"; // P表示PC端
					}
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Set the Robot
	 * 获取机器人名称
	 * @access	private
	 * @return	bool
	 */
	private function _set_robot()
	{
		if (is_array($this->robots) AND count($this->robots) > 0)
		{
			foreach ($this->robots as $key => $val)
			{
				if (preg_match("|".preg_quote($key)."|i", $this->agent))
				{
					$this->is_robot = TRUE;
					$this->robot = $val;
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Set the Mobile Device
	 * 获取移动设备
	 * @access	private
	 * @return	bool
	 */
	private function _set_mobile()
	{
		if (is_array($this->mobiles) AND count($this->mobiles) > 0)
		{
			foreach ($this->mobiles as $key => $val)
			{
				if (FALSE !== (strpos(strtolower($this->agent), $key)))
				{
					$this->is_mobile = TRUE;
					$this->mobile = $val;
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	/**
	 * Set the accepted languages
	 * 获取用户可接受语言类型
	 * @access	private
	 * @return	void
	 */
	private function _set_languages()
	{
		if ((count($this->languages) == 0) AND isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '')
		{
			$languages = preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));

			$this->languages = explode(',', $languages);
		}

		if (count($this->languages) == 0)
		{
			$this->languages = array('Undefined');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set the accepted character sets
	 * 字符类型
	 * @access	private
	 * @return	void
	 */
	private function _set_charsets()
	{
		if ((count($this->charsets) == 0) AND isset($_SERVER['HTTP_ACCEPT_CHARSET']) AND $_SERVER['HTTP_ACCEPT_CHARSET'] != '')
		{
			$charsets = preg_replace('/(;q=.+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])));

			$this->charsets = explode(',', $charsets);
		}

		if (count($this->charsets) == 0)
		{
			$this->charsets = array('Undefined');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Is Browser
	 * 是否浏览器
	 * @access	public
	 * @return	bool
	 */
	public function is_browser($key = NULL)
	{
		if ( ! $this->is_browser)
		{
			return FALSE;
		}

		// No need to be specific, it's a browser
		if ($key === NULL)
		{
			return TRUE;
		}

		// Check for a specific browser
		return array_key_exists($key, $this->browsers) AND $this->browser === $this->browsers[$key];
	}

	// --------------------------------------------------------------------

	/**
	 * Is Robot
	 * 是否机器人
	 * @access	public
	 * @return	bool
	 */
	public function is_robot($key = NULL)
	{
		if ( ! $this->is_robot)
		{
			return FALSE;
		}

		// No need to be specific, it's a robot
		if ($key === NULL)
		{
			return TRUE;
		}

		// Check for a specific robot
		return array_key_exists($key, $this->robots) AND $this->robot === $this->robots[$key];
	}

	// --------------------------------------------------------------------

	/**
	 * Is Mobile
	 * 是否移动终端
	 * @access	public
	 * @return	bool
	 */
	public function is_mobile($key = NULL)
	{
		if ( ! $this->is_mobile)
		{
			return FALSE;
		}

		// No need to be specific, it's a mobile
		if ($key === NULL)
		{
			return TRUE;
		}

		// Check for a specific robot
		return array_key_exists($key, $this->mobiles) AND $this->mobile === $this->mobiles[$key];
	}

	// --------------------------------------------------------------------

	/**
	 * Is this a referral from another site?
	 *
	 * @access	public
	 * @return	bool
	 */
	public function is_referral()
	{
		if ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '')
		{
			return FALSE;
		}
		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Agent String
	 * 返回全部UA
	 * @access	public
	 * @return	string
	 */
	public function agent_string()
	{
		return $this->agent;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Platform
	 * 获取平台
	 * @access	public
	 * @return	string
	 */
	public function platform()
	{
		return $this->platform;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Browser Name
	 * 返回浏览器名称
	 * @access	public
	 * @return	string
	 */
	public function browser()
	{
		return $this->browser;
	}

	// --------------------------------------------------------------------

	/**
	 * Get the Browser Version
	 * 返回浏览器版本
	 * @access	public
	 * @return	string
	 */
	public function version()
	{
		return $this->version;
	}

	// --------------------------------------------------------------------

	/**
	 * Get The Robot Name
	 * 返回机器名称
	 * @access	public
	 * @return	string
	 */
	public function robot()
	{
		return $this->robot;
	}
	// --------------------------------------------------------------------

	/**
	 * Get the Mobile Device
	 * 返回移动设备名称
	 * @access	public
	 * @return	string
	 */
	public function mobile()
	{
		return $this->mobile;
	}

	// --------------------------------------------------------------------

	/**
	 * Get the referrer
	 * 返回UA referer
	 * @access	public
	 * @return	bool
	 */
	public function referrer()
	{
		return ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
	}

	// --------------------------------------------------------------------

	/**
	 * Get the accepted languages
	 *
	 * @access	public
	 * @return	array
	 */
	public function languages()
	{
		if (count($this->languages) == 0)
		{
			$this->_set_languages();
		}

		return $this->languages;
	}

	// --------------------------------------------------------------------

	/**
	 * Get the accepted Character Sets
	 *
	 * @access	public
	 * @return	array
	 */
	public function charsets()
	{
		if (count($this->charsets) == 0)
		{
			$this->_set_charsets();
		}

		return $this->charsets;
	}

	// --------------------------------------------------------------------

	/**
	 * Test for a particular language
	 *
	 * @access	public
	 * @return	bool
	 */
	public function accept_lang($lang = 'en')
	{
		return (in_array(strtolower($lang), $this->languages(), TRUE));
	}

	// --------------------------------------------------------------------

	/**
	 * Test for a particular character set
	 *
	 * @access	public
	 * @return	bool
	 */
	public function accept_charset($charset = 'utf-8')
	{
		return (in_array(strtolower($charset), $this->charsets(), TRUE));
	}

	public function mode(){
		return $this->mode;
	}

	/**
	 * Test for a useragent string
	 * 获取分析的UA
	 * @access	public
	 * @return	bool
	 */
	public function uaname(){
		// 格式：设备类型-浏览器名称-浏览器模式-手机网络类型
		return str_replace(' ','',$this->platform."-".$this->browser."-".$this->NetType);
		// return str_replace(' ','',$this->platform."-".$this->browser."-".$this->NetType."-".$this->mode);
	}

	// ----------------- 静态方法
	public static function ua(){
		return (new self())->uaname();
	}
}
