<?php

namespace HttpServer\protocol\QD\ActCommentServer\CommentObj\classes;

class CommonInParam extends \TARS_Struct {
	const APPID = 0;
	const AREAID = 1;
	const USERID = 2;
	const USERIP = 3;
	const SERVERIP = 4;


	public $appId; 
	public $areaId; 
	public $userId; 
	public $userIp; 
	public $serverIp; 


	protected static $_fields = array(
		self::APPID => array(
			'name'=>'appId',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::AREAID => array(
			'name'=>'areaId',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::USERID => array(
			'name'=>'userId',
			'required'=>false,
			'type'=>\TARS::INT64,
			),
		self::USERIP => array(
			'name'=>'userIp',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::SERVERIP => array(
			'name'=>'serverIp',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
	);

	public function __construct() {
		parent::__construct('QD_ActCommentServer_CommentObj_CommonInParam', self::$_fields);
	}
}
