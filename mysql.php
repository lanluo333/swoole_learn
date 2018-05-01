<?php
/**
 * @Author: Rnb-3Ds
 * @Date:   2018-03-30 16:47:42
 * @Last Modified by:   Rnb-3Ds
 * @Last Modified time: 2018-03-30 21:49:00
 */

class AsyncMysql{
	public $db = '';
	public $dbConfig = array();

	public function __construct(){
		$this->db = new swoole_mysql;
		$this->dbConfig = array(
			'host' => '127.0.0.1',
		    'port' => 3306,
		    'user' => 'root',
		    'password' => '123456',
		    'database' => 'test',
		    'charset' => 'utf8', //指定字符集
		);
	}

	public function execute($id,$username){
		$this->db->connect($this->dbConfig,function($db,$result){
			if ($result == false) {
				var_dump($db->connect_error);
				die;
			}
			$sql = 'select * from posts where id = 1';
			$db->query($sql,function($db,$result){
				//select => result返回的就是结果集
				//add,update,delete =>result返回的就是布尔类型
				if ($result === false) {
					var_dump($db->error);
					exit('数据库错误');
				}elseif ($result === true) {
					var_dump($db->affected_rows);
					//add,update,delete =>result返回的就是布尔类型
				}else {
					print_r($result);
				}
				$db->close();
			});
		});
		return true;
	}

}

$obj = new AsyncMysql();
$obj->execute(1,'handsome');

