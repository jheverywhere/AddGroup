<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_smssend_log_model class
 */

class Mp_smssend_log_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'mp_smssend_log';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'sml_no'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();

		$this->load->library('session');
	}

	public function get_smssend_log($offset=0, $limit='', $where='', $like='', $where_in='')
	{
		$result = array();

		$this->db->select("*");
		$this->db->from($this->_table);

		if ($where) { $this->db->group_start()->where($where)->group_end(); }
		if ($like) { $this->db->group_start()->or_like($like)->group_end(); }
		if ($where_in && is_array($where_in)) {
			foreach ($where_in as $fld_name => $in_list) {
				$this->db->where_in($fld_name, $in_list);
			}
		}

		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("sml_mem_id", $this->member->is_member());
		}

		$result['total_rows'] = $this->db->count_all_results('', false);
		$this->db->order_by("sml_no", "DESC");
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}
		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function get_sms_list_by_ctr_no($ctr_no, $where='', $like='')
	{
		$result = array();

		$this->db->select("
			*
		");
		$this->db->from("cb_mp_smssend_log");
		$this->db->join("cb_mp_sms_recv", "cb_mp_sms_recv.sms_no = cb_mp_smssend_log.sms_no", "LEFT");

		$this->db->where('cb_mp_smssend_log.sms_ctr_no', $ctr_no);
		if ($where) {
			$this->db->where($where);
		}
		if ($like) {
			$this->db->where($like);
		}

		$this->db->order_by("sms_recv_park_type_cd", "ASC");
		$this->db->order_by("sms_recv_park_name", "ASC");

		$query_result = $this->db->get();
		// debug_last_query();
		$result = array();
		$list = $query_result->result_array();
		foreach ($list as $i => $row) {
			if (!element(element('sms_no', $row), $result)) {
				$result[element('sms_no', $row)] = array(
					"sms_no"           => element('sms_no', $row),
					"sms_ctr_no"       => element('sms_ctr_no', $row),
					"sms_mem_id"       => element('sms_mem_id', $row),
					"sms_mem_username" => element('sms_mem_username', $row),
					"sms_mem_phone"    => element('sms_mem_phone', $row),
					"sms_cust_name"    => element('sms_cust_name', $row),
					"sms_cust_phone"   => element('sms_cust_phone', $row),
					"sms_datetime"     => element('sms_datetime', $row),
					"sms_hq_content"   => element('sms_hq_content', $row),
				);
			}
			$result[element('sms_no', $row)]['recv_list'][] = array(
				"sms_recv_no"           => element('sms_recv_no', $row),
				"sms_recv_park_no"      => element('sms_recv_park_no', $row),
				"sms_recv_park_type_cd" => element('sms_recv_park_type_cd', $row),
				"sms_recv_park_name"    => element('sms_recv_park_name', $row),
				"sms_recv_name"         => element('sms_recv_name', $row),
				"sms_recv_phone"        => element('sms_recv_phone', $row),
				"sms_recv_content"      => element('sms_recv_content', $row),
			);
		}
		return $result;
	}

	// public function get_sms_recv_list_by_ctr_no($ctr_no, $where='', $like='')
	// {
	// 	$result = array();

	// 	$this->db->select("
	// 		cb_mp_sms_recv.*
	// 	");
	// 	$this->db->from("cb_mp_smssend_log");
	// 	$this->db->join("cb_mp_sms_recv", "cb_mp_sms_recv.sms_no = cb_mp_smssend_log.sms_no", "INNER");

	// 	$this->db->where('cb_mp_smssend_log.sms_ctr_no', $ctr_no);
	// 	if ($where) {
	// 		$this->db->where($where);
	// 	}
	// 	if ($like) {
	// 		$this->db->where($like);
	// 	}

	// 	$query_result = $this->db->get();
	// 	// debug_last_query();
	// 	$result = $query_result->result_array();
	// 	return $result;
	// }

	public function get_sms_recv_list($sms_no, $offset=0, $limit='', $where='', $like='')
	{
		$result = array();

		$this->db->select("
			*
		");
		$this->db->from("cb_mp_sms_recv");

		$this->db->where('sms_no', $sms_no);
		if ($where) {
			$this->db->where($where);
		}
		if ($like) {
			$this->db->where($like);
		}

		$result['total_rows'] = $this->db->count_all_results('', false);
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}
		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function insert_sms($data)
	{
		if ( ! empty($data)) {
			$this->db->insert($this->_table, $data);
			$insert_id = $this->db->insert_id();

			return $insert_id;
		} else {
			return false;
		}
	}

	public function update_sms($sms_no, $data)
	{
		if (is_array($sms_no)) {
			$this->db->where_in('sms_no', $sms_no);
		} else {
			$this->db->where('sms_no', $sms_no);
		}
		$this->db->set($data);
		$result = $this->db->update($this->_table);
		// debug_last_query();
		return $result;
	}

	public function delete_sms($sms_no)
	{
		if (is_array($sms_no)) {
			$this->db->where_in('sms_no', $sms_no);
		} else {
			$this->db->where('sms_no', $sms_no);
		}

		$result = $this->db->delete($this->_table);
		// debug_last_query();
		return $result;
	}

	public function insert_recv($data)
	{
		if ( ! empty($data)) {
			$this->db->insert("cb_mp_sms_recv", $data);
			$insert_id = $this->db->insert_id();

			return $insert_id;
		} else {
			return false;
		}
	}

	public function update_recv($sms_no, $data)
	{
		if (is_array($sms_no)) {
			$this->db->where_in('sms_no', $sms_no);
		} else {
			$this->db->where('sms_no', $sms_no);
		}
		$this->db->set($data);
		$result = $this->db->update("cb_mp_sms_recv");
		// debug_last_query();
		return $result;
	}

	public function delete_recv($sms_no)
	{
		if (is_array($sms_no)) {
			$this->db->where_in('sms_no', $sms_no);
		} else {
			$this->db->where('sms_no', $sms_no);
		}

		$result = $this->db->delete("cb_mp_sms_recv");
		// debug_last_query();
		return $result;
	}

}
