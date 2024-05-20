<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth model class
 */

class Auth_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cb_member_userid';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mem_userid'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}
}
