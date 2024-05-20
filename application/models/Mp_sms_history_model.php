<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_sms_history_model class
 */

class Mp_sms_history_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cb_mp_sms_history';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'sms_no'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();

		$this->load->library('session');
	}

	public function get_sms_list($offset=0, $limit='', $where='', $like='', $where_in='')
	{
		$result = array();

		$this->db->select("
			cb_wish_park1.park_no        wish_park1_no,
			cb_wish_park1.park_real_name wish_park1_name,
			cb_wish_park2.park_no        wish_park2_no,
			cb_wish_park2.park_real_name wish_park2_name,
			cb_wish_park3.park_no        wish_park3_no,
			cb_wish_park3.park_real_name wish_park3_name,
			cb_wish_prod1.park_prod_cd   wish_prod1_no,
			CASE WHEN cb_wish_prod1.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', cb_wish_prod1.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(cb_wish_prod1.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod1.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', cb_wish_prod1.park_prod_name, IF(SUBSTR(cb_wish_prod1.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod1.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_wish_prod1.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod1.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', cb_wish_prod1.park_prod_name, IF(SUBSTR(cb_wish_prod1.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod1.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_wish_prod1.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod1.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', cb_wish_prod1.park_prod_name, IF(SUBSTR(cb_wish_prod1.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod1.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(cb_wish_prod1.park_prod_price / 10000, 0), '만원'))
			END wish_prod1_name,
			cb_wish_prod2.park_prod_cd   wish_prod2_no,
			CASE WHEN cb_wish_prod2.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', cb_wish_prod2.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(cb_wish_prod2.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod2.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', cb_wish_prod2.park_prod_name, IF(SUBSTR(cb_wish_prod2.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod2.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_wish_prod2.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod2.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', cb_wish_prod2.park_prod_name, IF(SUBSTR(cb_wish_prod2.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod2.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_wish_prod2.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod2.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', cb_wish_prod2.park_prod_name, IF(SUBSTR(cb_wish_prod2.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod2.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(cb_wish_prod2.park_prod_price / 10000, 0), '만원'))
			END wish_prod2_name,
			cb_wish_prod3.park_prod_cd   wish_prod3_no,
			CASE WHEN cb_wish_prod3.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', cb_wish_prod3.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(cb_wish_prod3.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod3.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', cb_wish_prod3.park_prod_name, IF(SUBSTR(cb_wish_prod3.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod3.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_wish_prod3.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod3.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', cb_wish_prod3.park_prod_name, IF(SUBSTR(cb_wish_prod3.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod3.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_wish_prod3.park_prod_price / 10000, 0), '만원'))
			WHEN cb_wish_prod3.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', cb_wish_prod3.park_prod_name, IF(SUBSTR(cb_wish_prod3.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_wish_prod3.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(cb_wish_prod3.park_prod_price / 10000, 0), '만원'))
			END wish_prod3_name,
			cb_ctr_park.park_no          ctr_park_no,
			cb_ctr_park.park_real_name   ctr_park_name,
			cb_ctr_prod.park_prod_cd     ctr_prod_no,
			CASE WHEN cb_ctr_prod.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', cb_ctr_prod.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(cb_ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN cb_ctr_prod.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', cb_ctr_prod.park_prod_name, IF(SUBSTR(cb_ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN cb_ctr_prod.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', cb_ctr_prod.park_prod_name, IF(SUBSTR(cb_ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN cb_ctr_prod.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', cb_ctr_prod.park_prod_name, IF(SUBSTR(cb_ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_ctr_prod.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(cb_ctr_prod.park_prod_price / 10000, 0), '만원'))
			END ctr_prod_name,
			COUNT(cb_mp_sms_recv.sms_no) sent_count,
			cb_mp_sms_history.*
		");
		$this->db->from("cb_mp_sms_history");
		// $this->db->join("cb_mp_sms_recv", "cb_mp_sms_recv.sms_no = cb_mp_sms_history.sms_no", "LEFT");
		$this->db->join("cb_mp_contract", "cb_mp_contract.ctr_no = cb_mp_sms_history.sms_ctr_no", "INNER");
		$this->db->join("cb_mp_sms_recv", "cb_mp_sms_recv.sms_no = cb_mp_sms_history.sms_no", "INNER");
		$this->db->join("cb_mp_park    cb_wish_park1", "cb_wish_park1.park_no = cb_mp_contract.wish_park1", "LEFT");
		$this->db->join("cb_mp_park    cb_wish_park2", "cb_wish_park2.park_no = cb_mp_contract.wish_park2", "LEFT");
		$this->db->join("cb_mp_park    cb_wish_park3", "cb_wish_park3.park_no = cb_mp_contract.wish_park3", "LEFT");
		$this->db->join("cb_mp_product cb_wish_prod1", "cb_wish_prod1.park_prod_cd = cb_mp_contract.wish_prod1", "LEFT");
		$this->db->join("cb_mp_product cb_wish_prod2", "cb_wish_prod2.park_prod_cd = cb_mp_contract.wish_prod2", "LEFT");
		$this->db->join("cb_mp_product cb_wish_prod3", "cb_wish_prod3.park_prod_cd = cb_mp_contract.wish_prod3", "LEFT");
		$this->db->join("cb_mp_park    cb_ctr_park", "cb_ctr_park.park_no = cb_mp_contract.ctr_park_no", "LEFT");
		$this->db->join("cb_mp_product cb_ctr_prod", "cb_ctr_prod.park_prod_cd = cb_mp_contract.ctr_prod_cd", "LEFT");
		$this->db->join("cb_mp_code cb_park_type_cd", "cb_park_type_cd.cd_type = 'park_type_cd' AND SUBSTR(cb_ctr_prod.park_prod_cd,1,2) = cb_park_type_cd.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_dan_type", "cb_dan_type.cd_type = 'dan_type' AND SUBSTR(cb_ctr_prod.park_prod_cd,7,2) = cb_dan_type.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_public_yn", "cb_public_yn.cd_type = 'public_yn' AND SUBSTR(cb_ctr_prod.park_prod_cd,7,2) = cb_public_yn.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_tree_type", "cb_tree_type.cd_type = 'tree_type' AND SUBSTR(cb_ctr_prod.park_prod_cd,9,2) = cb_tree_type.cd_name", "LEFT");

		if ($where) { $this->db->group_start()->where($where)->group_end(); }
		if ($like) { $this->db->group_start()->or_like($like)->group_end(); }
		if ($where_in && is_array($where_in)) {
			foreach ($where_in as $fld_name => $in_list) {
				$this->db->where_in($fld_name, $in_list);
			}
		}

		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("sms_mem_id", $this->member->is_member());
		}

		$this->db->group_by("cb_mp_sms_history.sms_no");

		$result['total_rows'] = $this->db->count_all_results('', false);
		$this->db->order_by("sms_no", "DESC");
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
		$this->db->from("cb_mp_sms_history");
		$this->db->join("cb_mp_sms_recv", "cb_mp_sms_recv.sms_no = cb_mp_sms_history.sms_no", "LEFT");

		$this->db->where('cb_mp_sms_history.sms_ctr_no', $ctr_no);
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
	// 	$this->db->from("cb_mp_sms_history");
	// 	$this->db->join("cb_mp_sms_recv", "cb_mp_sms_recv.sms_no = cb_mp_sms_history.sms_no", "INNER");

	// 	$this->db->where('cb_mp_sms_history.sms_ctr_no', $ctr_no);
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
