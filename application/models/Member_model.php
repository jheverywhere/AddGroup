<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'member';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mem_id'; // 사용되는 테이블의 프라이머리키

	public $allow_search_field = array('mem_username','mem_phone','mem_address1','mem_address2','mem_address3','mem_address4');
	public $search_sfield = 'mem_username';

	private $teamname_list = array(
		array("name" => "영업담당", "level" => "20"),
		array("name" => "개장팀장", "level" => "10"),
		array("name" => "상조팀장", "level" => "10"),
		array("name" => "중간관리자", "level" => "40"),
		array("name" => "최고관리자", "level" => "50"),
	);

	function __construct()
	{
		parent::__construct();
	}

	public function get_teamname_list()
	{
		return $this->teamname_list;
	}

	public function get_teamlevel($teamname)
	{
		foreach ($this->teamname_list as $i => $row) {
			if (element('name', $row) == $teamname) {
				return element('level', $row);
			}
		}
		return 2;
	}

	public function get_by_memid($memid = 0, $select = '')
	{
		$memid = (int) $memid;
		if (empty($memid) OR $memid < 1) {
			return false;
		}
		$where = array('mem_id' => $memid);
		return $this->get_one('', $select, $where);
	}


	public function get_by_userid($userid = '', $select = '')
	{
		if (empty($userid)) {
			return false;
		}
		$where = array('mem_userid' => $userid);
		return $this->get_one('', $select, $where);
	}


	public function get_by_email($email = '', $select = '')
	{
		if (empty($email)) {
			return false;
		}
		$where = array('mem_email' => $email);
		return $this->get_one('', $select, $where);
	}


	public function get_by_both($str = '', $select = '')
	{
		if (empty($str)) {
			return false;
		}
		if ($select) {
			$this->db->select($select);
		}
		$this->db->from($this->_table);
		$this->db->where('mem_userid', $str);
		$this->db->or_where('mem_email', $str);
		$result = $this->db->get();
		return $result->row_array();
	}


	public function get_superadmin_list($select='')
	{
		$where = array(
			'mem_is_admin' => 1,
			'mem_denied' => 0,
		);
		$result = $this->get('', $select, $where);

		return $result;
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$join = array();
		if (isset($where['mgr_id'])) {
			$select = 'member.*';
			$join[] = array('table' => 'member_group_member', 'on' => 'member.mem_id = member_group_member.mem_id', 'type' => 'left');
		}
		$result = $this->_get_list_common($select = '', $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_register_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(mem_register_datetime, ' . $left . ') as day ', false);
		$this->db->where('left(mem_register_datetime, 10) >=', $start_date);
		$this->db->where('left(mem_register_datetime, 10) <=', $end_date);
		$this->db->where('mem_denied', 0);
		$this->db->group_by('day');
		$this->db->order_by('mem_register_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}

	public function del_member($mem_id)
	{
		if (is_array($mem_id)) {
			$this->db->where_in('mem_id', $mem_id);
		} else if (is_string($mem_id)) {
			$this->db->where('mem_id', $mem_id);
		}

		$result = $this->db->delete($this->_table);
		// debug_last_query();
		return $result;
	}

	public function get_saleman_list($offset=0, $limit='', $where='', $like='')
	{
		$result = array();
		$this->db->select("*");
		$this->db->from($this->_table);

		$this->db->where_in("mem_level", 20);
		if ($where) {
			$this->db->where($where);
		}
		if ($like) {
			$this->db->like($like);
		}

		$result['total_rows'] = $this->db->count_all_results('', false);
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by("mem_username", "ASC");
		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function get_member_list($offset=0, $limit='', $where='', $like='')
	{
		$result = array();
		$this->db->select("*");
		$this->db->from($this->_table);

		$this->db->where("mem_level <= ", 50);
		if ($where) { $this->db->group_start()->where($where)->group_end(); }
		if ($like) { $this->db->group_start()->or_like($like)->group_end(); }

		$result['total_rows'] = $this->db->count_all_results('', false);
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by("mem_username", "ASC");
		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}
}
