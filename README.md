# tocc

1. 直接在htdoc中建立tocc資料夾
2. 下composer install指令
3. 創建db名稱為tocc，找installation下的sql資料夾匯入tocc.sql
4. core/config/database.php 改成自己的帳號密碼
5. 匯入postman的collection和env
6. app/views為開發的html

core/config/database.php
```
<?php
if ( ! defined('PPP')) exit('非法入口');
if(DEBUG) {
	return array(
		'database_type' => 'mysql',
		'database_name' => 'tocc',
		'server' => 'localhost',
		'username' => 'root',
		'password' => 'root',
		'charset' => 'utf8',
		'option' => [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			//千萬不能開啟，會造成ACID失敗
			// PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
		]
	);
} else {
	return array(
		'database_type' => 'mysql',
		'database_name' => 'tocc',
		'server' => 'localhost',
		'username' => 'root',
		'password' => 'root',
		'charset' => 'utf8',
		'option' => [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			//千萬不能開啟，會造成ACID失敗
			// PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
		]
	);
}

```
