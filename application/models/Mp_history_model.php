<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_history_model class
 */

class Mp_history_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cb_mp_history';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'history_no'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();

		$this->load->library('session');
	}

	public function get_history_list($offset=0, $limit='', $where='', $where_or='', $like='', $where_in)
	{
		$result = array();

		$this->db->select("
			cb_member.mem_id,
			cb_member.mem_userid,
			cb_member.mem_username,
			cb_member.mem_phone,
			wish_park1.park_no        wish_park1_no,
			wish_park1.park_real_name wish_park1_name,
			wish_park2.park_no        wish_park2_no,
			wish_park2.park_real_name wish_park2_name,
			wish_park3.park_no        wish_park3_no,
			wish_park3.park_real_name wish_park3_name,
			wish_prod1.park_prod_cd   wish_prod1_no,
			CASE WHEN wish_prod1.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', wish_prod1.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(wish_prod1.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod1.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', wish_prod1.park_prod_name, IF(SUBSTR(wish_prod1.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod1.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(wish_prod1.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod1.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', wish_prod1.park_prod_name, IF(SUBSTR(wish_prod1.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod1.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(wish_prod1.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod1.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', wish_prod1.park_prod_name, IF(SUBSTR(wish_prod1.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod1.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(wish_prod1.park_prod_price / 10000, 0), '만원'))
			END wish_prod1_name,
			wish_prod2.park_prod_cd   wish_prod2_no,
			CASE WHEN wish_prod2.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', wish_prod2.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(wish_prod2.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod2.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', wish_prod2.park_prod_name, IF(SUBSTR(wish_prod2.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod2.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(wish_prod2.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod2.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', wish_prod2.park_prod_name, IF(SUBSTR(wish_prod2.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod2.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(wish_prod2.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod2.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', wish_prod2.park_prod_name, IF(SUBSTR(wish_prod2.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod2.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(wish_prod2.park_prod_price / 10000, 0), '만원'))
			END wish_prod2_name,
			wish_prod3.park_prod_cd   wish_prod3_no,
			CASE WHEN wish_prod3.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', wish_prod3.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(wish_prod3.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod3.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', wish_prod3.park_prod_name, IF(SUBSTR(wish_prod3.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod3.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(wish_prod3.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod3.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', wish_prod3.park_prod_name, IF(SUBSTR(wish_prod3.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod3.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(wish_prod3.park_prod_price / 10000, 0), '만원'))
			WHEN wish_prod3.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', wish_prod3.park_prod_name, IF(SUBSTR(wish_prod3.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(wish_prod3.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(wish_prod3.park_prod_price / 10000, 0), '만원'))
			END wish_prod3_name,

			ctr_park.park_no        ctr_park_no,
			ctr_park.park_real_name ctr_park_name,
			ctr_prod.park_prod_cd   ctr_prod_no,
			CASE WHEN ctr_prod.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', ctr_prod.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', ctr_prod.park_prod_name, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', ctr_prod.park_prod_name, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', ctr_prod.park_prod_name, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			END ctr_prod_name,

			cb_mp_contract.*,
			cb_mp_history.*,
		");
		$this->db->from($this->_table);
		$this->db->join("cb_mp_contract", "cb_mp_contract.ctr_no = cb_mp_history.ctr_no", "INNER");
		$this->db->join("cb_member", "cb_member.mem_id = cb_mp_contract.ctr_mem_id", "INNER");
		$this->db->join("cb_mp_park    wish_park1", "wish_park1.park_no = cb_mp_contract.wish_park1", "LEFT");
		$this->db->join("cb_mp_park    wish_park2", "wish_park2.park_no = cb_mp_contract.wish_park2", "LEFT");
		$this->db->join("cb_mp_park    wish_park3", "wish_park3.park_no = cb_mp_contract.wish_park3", "LEFT");
		$this->db->join("cb_mp_product wish_prod1", "wish_prod1.park_prod_cd = cb_mp_contract.wish_prod1", "LEFT");
		$this->db->join("cb_mp_product wish_prod2", "wish_prod2.park_prod_cd = cb_mp_contract.wish_prod2", "LEFT");
		$this->db->join("cb_mp_product wish_prod3", "wish_prod3.park_prod_cd = cb_mp_contract.wish_prod3", "LEFT");
		$this->db->join("cb_mp_park    ctr_park", "ctr_park.park_no = cb_mp_contract.ctr_park_no", "LEFT");
		$this->db->join("cb_mp_product ctr_prod", "ctr_prod.park_prod_cd = cb_mp_contract.ctr_prod_cd", "LEFT");
		$this->db->join("cb_mp_code dan_type", "dan_type.cd_type = 'dan_type' AND SUBSTR(wish_prod1.park_prod_cd,7,2) = dan_type.cd_name", "LEFT");
		$this->db->join("cb_mp_settlement", "cb_mp_settlement.ctr_no = cb_mp_contract.ctr_no", "LEFT");

		if ($where) { $this->db->group_start()->where($where)->group_end(); }
		if ($like) { $this->db->group_start()->or_like($like)->group_end(); }
		if ($where_in) {
			foreach ($where_in as $fld_name => $in_list) {
				$this->db->where_in($fld_name, $in_list);
			}
		}

		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}

		$result['total_rows'] = $this->db->count_all_results('', false);
		$this->db->order_by("history_date", "ASC");
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}
		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function get_history_list_by_ctr_no($ctr_no, $offset=0, $limit='', $where='', $like='')
	{
		$result = array();

		if (!$ctr_no) {
			$result['list'] = $this->session->userdata("ctr_history") ? $this->session->userdata("ctr_history") : array();
			$result['total_rows'] = count($result['list']);
		} else {
			$this->db->select("
				*
			");
			$this->db->from($this->_table);

			$this->db->where('ctr_no', $ctr_no);
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
		}
		return $result;
	}

	public function save_history($ctr_no)
	{
		$result = NULL;
		$ctr_history = $this->session->userdata("ctr_history");
		if ($ctr_history) {
			foreach ($ctr_history as $i => $row) {
				unset($ctr_history[$i]['history_no']);
				$ctr_history[$i]['ctr_no'] = $ctr_no;
			}
			$result = $this->db->insert_batch($this->_table, $ctr_history);
			// debug_last_query();
		}
		return $result;
	}

	public function insert_history($data)
	{
		if (!element('ctr_no', $data)) {
			$ctr_history = $this->session->userdata("ctr_history") ? $this->session->userdata("ctr_history") : array();
			$idx = ($ctr_history) ? (min(array_keys($ctr_history)) - 1) : -1;
			$data['history_no'] = $idx;
			$ctr_history[$idx] = $data;
			$this->session->set_userdata("ctr_history", $ctr_history);
			return -1;
		} else {
			if ( ! empty($data)) {
				$this->db->insert($this->_table, $data);
				$insert_id = $this->db->insert_id();
	
				return $insert_id;
			} else {
				return false;
			}
		}
	}

	public function update_history($history_no, $data)
	{
		if ($history_no < 0) {
			$ctr_history = $this->session->userdata("ctr_history");
			if (NULL != $ctr_history && $ctr_history[$history_no]) {
				$ctr_history[$history_no] = $data;
				$this->session->set_userdata("ctr_history", $ctr_history);
			}
			return -1;
		} else {
			if (is_array($history_no)) {
				$this->db->where_in('history_no', $history_no);
			} else if (is_string($history_no)) {
				$this->db->where('history_no', $history_no);
			}
			$this->db->set($data);
			$result = $this->db->update($this->_table);
			// debug_last_query();
			return $result;
		}
	}

	public function delete_history($history_no)
	{
		if ($history_no < 0) {
			$ctr_history = $this->session->userdata("ctr_history");
			if (NULL != $ctr_history && $ctr_history[$history_no]) {
				unset($ctr_history[$history_no]);
				// foreach ($ctr_history as $key => $row) {
				// 	if ($row['history_no'] == $history_no) {
				// 		unset($ctr_history[$key]);
				// 		break;
				// 	}
				// }
				$this->session->set_userdata("ctr_history", $ctr_history);
			}
			return -1;
		} else {
			if (is_array($history_no)) {
				$this->db->where_in('history_no', $history_no);
			} else if (is_string($history_no)) {
				$this->db->where('history_no', $history_no);
			}

			$result = $this->db->delete($this->_table);
			// debug_last_query();
			return $result;
		}
	}

	public function clear_history($ctr_no='')
	{
		if (!$ctr_no) {
			$this->session->unset_userdata("ctr_history");
		} else {
			if (is_array($ctr_no)) {
				$this->db->where_in('ctr_no', $ctr_no);
			} else if (is_string($ctr_no)) {
				$this->db->where('ctr_no', $ctr_no);
			}

			$result = $this->db->delete($this->_table);
			// debug_last_query();
			return $result;
		}
	}

}
