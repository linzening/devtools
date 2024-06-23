<?php
// +------------------------------
// | 数据请求类
// +------------------------------
namespace linzening\devtools;
class Request
{
	/**
     * GET方法请求
     * @access public
     * @param  string     $url 需要请求的URL地址
     * @return string
     */
	public static function get($url){
		return file_get_contents($url);
	}

	/**
     * Content方法POST
     * @access public
     * @param  string     $url 需要请求的URL地址
     * @param  array     $post_data 发送的信息
     * @return array
     */
	public static function http_contents($url,$post_data){
		ini_set('user_agent', 'php_get');
		$postdata = http_build_query($post_data);
		$options = ['http' => [
			'method' => 'POST',
			'header' => 'Content-type:application/x-www-form-urlencoded',
			'content' => $postdata,
			'timeout' => 15 * 60 ]];
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return json_decode($result,true);//直接将json字符串转换为数组返回
	}

	/**
	 * CURL方法POST
	 * @access public
	 * @param  string    $url 需要请求的URL地址
	 * @param  array     $data 发送的信息
	 * @param  string    $proxy 代理URL地址
	 * @return array
	 */
	public function http_curl($url, $data, $proxy = '') {
		$data=json_encode($data,true);//数组转换成json字符串
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if(!empty($proxy)){
			$proxy_demo = "http://183.251.xxx.65:566"; // 设置代理
			curl_setopt ($ch, CURLOPT_PROXY, $proxy);
		}

		// 如果是HTTPS，需要加这两行
		if( substr($url,0,5) == 'https'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json',
            'Content-Length: '.strlen($data),'user_agent:php_curl']);
        $result = curl_exec($ch);
        return json_decode($result,true);//直接将json字符串转换为数组返回
	}

	/**
	 * socket
	 * @access public
	 * @param  string    $remote_server 需要请求的域名
	 * @param  string    $remote_path   URI地址
	 * @param  string    $post_string   请求的数据
	 * @param  int       $port          请求端口
	 * @param  int       $timeout       超时时间
	 * @return array
	 */
	public function socket($remote_server,$remote_path,$post_string,$port=80,$timeout=30){
		$socket = fsockopen($remote_server, $port, $errno, $errstr, $timeout);
		if (!$socket) die("$errstr($errno)");
		echo "s:$remote_server;p:$remote_path;t:$post_string<br>";
		fwrite($socket, "POST $remote_path HTTP/1.0");
		fwrite($socket, "User-Agent: Socket Example");
		fwrite($socket, "HOST: $remote_server");
		fwrite($socket, "Content-type: application/x-www-form-urlencoded");
		fwrite($socket, "Content-length: " .(strlen($post_string) + 8). "");
		fwrite($socket, "Accept:*/*");
		fwrite($socket, "");
		fwrite($socket, "mypost=$post_string");
		fwrite($socket, "");
		$header = "";
		while ($str = trim(fgets($socket, 4096))) {
			$header .= $str;
		}
		$data = "";
		while (!feof($socket)) {
			$data .= fgets($socket, 4096);
		}
		return $data;
	}

	/**
	 * [get_client_ip 获取用户IP（ThinkPHP自带的函数有问题，所以自己写一个）20240622]
	 * @access public
	 * @param  integer   $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @param  boolean   $adv 是否进行高级模式获取（有可能被伪装）
	 * @param  string    $httpAgentIpHeader 获取代理IP的头部
	 * @return [string]      [获取到的IP地址]
	 */
	public function get_client_ip($type = 0, $adv = true,$httpAgentIpHeader = ''){
		$type      = $type ? 1 : 0;
		static $ip = null;

		if (null !== $ip) {
			return $ip[$type];
		}

		$httpAgentIp = $httpAgentIpHeader ?: config('app.http_agent_ip');
		
		$server = $_SERVER;
	
		if ($adv) {
			if ($httpAgentIp && isset($server[$httpAgentIp])) {
				$ip = $server[$httpAgentIp];
			}elseif (isset($server['HTTP_X_FORWARDED_FOR'])) {
				$arr = explode(',', $server['HTTP_X_FORWARDED_FOR']);
				$pos = array_search('unknown', $arr);
				if (false !== $pos) {
					unset($arr[$pos]);
				}
				$ip = trim(current($arr));
			} elseif (isset($server['HTTP_CLIENT_IP'])) {
				$ip = $server['HTTP_CLIENT_IP'];
			} elseif (isset($server['REMOTE_ADDR'])) {
				$ip = $server['REMOTE_ADDR'];
			}
		} elseif (isset($server['REMOTE_ADDR'])) {
			$ip = $server['REMOTE_ADDR'];
		}

		// IP地址类型
		$ip_mode = (strpos($ip, ':') === false) ? 'ipv4' : 'ipv6';

		// IP地址合法验证
		if (filter_var($ip, FILTER_VALIDATE_IP) !== $ip) {
			$ip = ('ipv4' === $ip_mode) ? '0.0.0.0' : '::';
		}

		// 如果是ipv4地址，则直接使用ip2long返回int类型ip；如果是ipv6地址，暂时不支持，直接返回0
		$long_ip = ('ipv4' === $ip_mode) ? sprintf("%u", ip2long($ip)) : 0;

		$ip = [$ip, $long_ip];

		return $ip[$type];
	}
}
