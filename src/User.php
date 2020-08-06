<?php
namespace linzening\devtools;
/**
 * 一级类
 */
use linzening\devtools\Demo\GetLage;
class User
{
	protected $id = 0;
	protected $user = [];
	protected $options = [];
	
	private function __construct($options) {
		$this->options = $options;
	}
	
    // test v1
    public function setId($id = 0){
		if($id){
			$this->user = ['id'=> $id, 'name'=>'aaa'];
		}
		return $this;
    }
	
	public function getUser(){
		return $this->user;
	}
}
