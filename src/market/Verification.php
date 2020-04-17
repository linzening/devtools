<?php
// +----------------------------------------------
// | 该类是自己写的类，主要用于管理员/用户的密码加密等
// +----------------------------------------------
namespace linzening\devtools\market;
class Verification {
    // salt盐值
    protected $salt = [
        'admin' =>  "00000",
        'user'  =>  "00000"
    ];

    /**
     * 初始化秘钥
     * param array
     */
    public function __construct($data=[]){
        if(!empty($data)){
            $this->salt = $data;
        }
    }

    /**
     * 管理员密码加密
     * param string $string 管理员原密码
     * return string 管理员加密密码
     */
    public function xmd5($string) {
        return md5(substr(md5($string),8,16).$this->salt['admin']);
    }

    /**
     * 用户密码加密
     * param string $string 用户原密码
     * return string 用户加密密码
     */
    public function umd5($string){
        return substr(md5(md5($string).$this->salt['user']),8,16);
    }

    /**
     * 随机产生16位MD5
     * return string
     */
    public function rmd5() {
    	//substr(string,start,length)
        return substr(md5(microtime().mt_rand(100,999)), 8, 16);
    }
}
