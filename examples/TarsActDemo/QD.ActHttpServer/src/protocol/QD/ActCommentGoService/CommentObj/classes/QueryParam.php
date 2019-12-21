<?php

namespace HttpServer\protocol\QD\ActCommentGoService\CommentObj\classes;

class QueryParam extends \TARS_Struct {
	const ACTIVITYID = 0;
	const PAGE = 1;
	const SIZE = 2;
	const ORDERTYPE = 3;


	public $activityId; 
	public $page; 
	public $size; 
	public $orderType; 


	protected static $_fields = array(
		self::ACTIVITYID => array(
			'name'=>'activityId',
			'required'=>false,
			'type'=>\TARS::INT64,
			),
		self::PAGE => array(
			'name'=>'page',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::SIZE => array(
			'name'=>'size',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
		self::ORDERTYPE => array(
			'name'=>'orderType',
			'required'=>false,
			'type'=>\TARS::INT32,
			),
	);

	public function __construct() {
		parent::__construct('QD_ActCommentServer_CommentObj_QueryParam', self::$_fields);
	}
}
