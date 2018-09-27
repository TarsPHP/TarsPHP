<?php

namespace HttpServer\protocol\QD\UserService\UserObj\classes;

class User extends \TARS_Struct {
	const USERID = 0;
	const NICKNAME = 1;
	const AVATAR = 2;
	const CREATETIME = 4;


	public $userId; 
	public $nickname; 
	public $avatar; 
	public $createTime; 


	protected static $_fields = array(
		self::USERID => array(
			'name'=>'userId',
			'required'=>false,
			'type'=>\TARS::INT64,
			),
		self::NICKNAME => array(
			'name'=>'nickname',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::AVATAR => array(
			'name'=>'avatar',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::CREATETIME => array(
			'name'=>'createTime',
			'required'=>false,
			'type'=>\TARS::INT64,
			),
	);

	public function __construct() {
		parent::__construct('QD_UserService_UserObj_User', self::$_fields);
	}
}
