<?php
// +----------------------------------------
// | 这是一个类文件，要写类的话可以参考这个写
// +----------------------------------------

namespace linzening\devtools;
class Dclass {
	//用于保存实例化对象
	private static $instance;
	//用于保存数据库句柄
	private $db = null;

	//禁止直接实例化，负责数据库连接，将数据库连接句柄保存至私有变量$db
	private function __construct($options) {
		$this->db = mysqli_connect($options['db_host'], $options['db_user'], $options['db_password'], $options['db_database']);
	}

	//负责实例化数据库类，返回实例化后的对象
	public static function getInstance($options) {
		if (!(self::$instance instanceof self)) {
			self::$instance = new self($options);
		}
		return self::$instance;
	}

	//获取数据库连接句柄
	public function db() {
		return $this->db;
	}

	//禁止克隆
	private function __clone() {
		// TODO: Implement __clone() method.
	}

	//禁止重构
	private function __wakeup() {
		// TODO: Implement __wakeup() method.
	}
}