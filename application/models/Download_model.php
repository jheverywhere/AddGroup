<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Download_model class
 */

class Download_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cb_download';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();

		$this->load->library('session');
	}

}
