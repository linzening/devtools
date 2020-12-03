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
}
