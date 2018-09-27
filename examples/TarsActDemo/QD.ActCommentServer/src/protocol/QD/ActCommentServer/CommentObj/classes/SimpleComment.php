<?php

namespace Server\protocol\QD\ActCommentServer\CommentObj\classes;

class SimpleComment extends \TARS_Struct {
	const ID = 0;
	const ACTIVITYID = 1;
	const USERID = 2;
	const CONTENT = 3;
	const TITLE = 4;
	const EXT1 = 5;
	const CREATETIME = 6;


	public $id; 
	public $activityId; 
	public $userId; 
	public $content; 
	public $title; 
	public $ext1; 
	public $createTime; 


	protected static $_fields = array(
		self::ID => array(
			'name'=>'id',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::ACTIVITYID => array(
			'name'=>'activityId',
			'required'=>false,
			'type'=>\TARS::INT64,
			),
		self::USERID => array(
			'name'=>'userId',
			'required'=>false,
			'type'=>\TARS::INT64,
			),
		self::CONTENT => array(
			'name'=>'content',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::TITLE => array(
			'name'=>'title',
			'required'=>false,
			'type'=>\TARS::STRING,
			),
		self::EXT1 => array(
			'name'=>'ext1',
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
		parent::__construct('QD_ActCommentServer_CommentObj_SimpleComment', self::$_fields);
	}
}
