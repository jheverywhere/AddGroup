<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_Contract_model class
 */

class Mp_Contract_model extends CB_Model
{
	public $_table = 'cb_mp_contract';
	public $primary_key = 'ctr_no'; // 사용되는 테이블의 프라이머리키

	private $ctr_status_list = array(
		array("ctr_status" => "담당배정", "highlight" => "info"),
		array("ctr_status" => "1차상담",  "highlight" => "info"),
		array("ctr_status" => "2차상담",  "highlight" => "info"),
		array("ctr_status" => "방문답사", "highlight" => "info"),
		array("ctr_status" => "계약완료", "highlight" => "warning"),
		array("ctr_status" => "잔금완료", "highlight" => "success"),
		array("ctr_status" => "계약취소", "highlight" => "danger"),
		array("ctr_status" => "진행불발", "highlight" => "active"),
	);

	function __construct()
	{
		parent::__construct();
	}

	public function get_ctr_status_list()
	{
		return $this->ctr_status_list;
	}

	public function get_ctr_status_highlight($ctr_status)
	{
		foreach ($this->ctr_status_list as $i => $row) {
			if (element('ctr_status', $row) == $ctr_status) {
				return element('highlight', $row);
			}
		}
		return "default";
	}

	public function get_contract($ctr_no)
	{
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
			ctr_park.park_no          ctr_park_no,
			ctr_park.park_real_name   ctr_park_name,
			ctr_prod.park_prod_cd     ctr_prod_no,
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
			remark_reg_mem.mem_username ctr_remark_reg_mem_uesrname,
			remark_mod_mem.mem_username ctr_remark_mod_mem_uesrname
		");
		$this->db->from($this->_table);
		$this->db->join("cb_member", "cb_member.mem_id = cb_mp_contract.ctr_mem_id", "LEFT");
		$this->db->join("cb_member remark_reg_mem", "remark_reg_mem.mem_id = cb_mp_contract.ctr_remark_reg_mem_id", "LEFT");
		$this->db->join("cb_member remark_mod_mem", "remark_mod_mem.mem_id = cb_mp_contract.ctr_remark_mod_mem_id", "LEFT");
		$this->db->join("cb_mp_park    wish_park1", "wish_park1.park_no = cb_mp_contract.wish_park1", "LEFT");
		$this->db->join("cb_mp_park    wish_park2", "wish_park2.park_no = cb_mp_contract.wish_park2", "LEFT");
		$this->db->join("cb_mp_park    wish_park3", "wish_park3.park_no = cb_mp_contract.wish_park3", "LEFT");
		$this->db->join("cb_mp_product wish_prod1", "wish_prod1.park_prod_cd = cb_mp_contract.wish_prod1", "LEFT");
		$this->db->join("cb_mp_product wish_prod2", "wish_prod2.park_prod_cd = cb_mp_contract.wish_prod2", "LEFT");
		$this->db->join("cb_mp_product wish_prod3", "wish_prod3.park_prod_cd = cb_mp_contract.wish_prod3", "LEFT");
		$this->db->join("cb_mp_park    ctr_park", "ctr_park.park_no = cb_mp_contract.ctr_park_no", "LEFT");
		$this->db->join("cb_mp_product ctr_prod", "ctr_prod.park_prod_cd = cb_mp_contract.ctr_prod_cd", "LEFT");
		$this->db->join("cb_mp_code park_type_cd", "park_type_cd.cd_type = 'park_type_cd' AND SUBSTR(ctr_prod.park_prod_cd,1,2) = park_type_cd.cd_name", "LEFT");
		$this->db->join("cb_mp_code dan_type", "dan_type.cd_type = 'dan_type' AND SUBSTR(ctr_prod.park_prod_cd,7,2) = dan_type.cd_name", "LEFT");
		$this->db->join("cb_mp_code public_yn", "public_yn.cd_type = 'public_yn' AND SUBSTR(ctr_prod.park_prod_cd,7,2) = public_yn.cd_name", "LEFT");
		$this->db->join("cb_mp_code tree_type", "tree_type.cd_type = 'tree_type' AND SUBSTR(ctr_prod.park_prod_cd,9,2) = tree_type.cd_name", "LEFT");
		$this->db->where("ctr_no", $ctr_no);
		$qry = $this->db->get();
		// debug_last_query();
		$row = $qry->row_array();
		return $row;
	}

	public function get_contract2($ctr_no)
	{
		$this->db->select("
			cb_member.mem_id,
			cb_member.mem_userid,
			cb_member.mem_username,
			cb_member.mem_phone,
			cb_mp_contract.*
		");
		$this->db->from($this->_table);
		$this->db->join("cb_member", "cb_member.mem_id = cb_mp_contract.ctr_mem_id", "INNER");
		$this->db->where("ctr_no", $ctr_no);
		$qry = $this->db->get();
		// debug_last_query();
		$row = $qry->row_array();
		return $row;
	}

	public function get_contract_by_cust($cust_name, $cust_phone)
	{
		$this->db->select("ctr_no, cust_name, cust_phone, accept_date");
		$this->db->from($this->_table);
		$this->db->where("cust_name", $cust_name);
		$this->db->where("cust_phone", $cust_phone);
		$qry = $this->db->get();
		// debug_last_query();
		$row = $qry->row_array();
		return $row;
	}

	public function get_contract_by_cust_name($cust_name)
	{
		$this->db->select("ctr_no, cust_name, cust_phone, accept_date");
		$this->db->from($this->_table);
		// $this->db->like("cust_name", $cust_name);
		$this->db->where("cust_name", $cust_name);
		$query_result = $this->db->get();
		// debug_last_query();
		return $query_result->result_array();
	}

	public function get_contract_by_cust_name_or_phone($stx)
	{
		$this->db->select("ctr_no, cust_name, cust_phone, accept_date");
		$this->db->from($this->_table);
		
		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}

		$this->db->group_start();
		$this->db->or_like("cust_name", $stx);
		$this->db->or_like("cust_phone", $stx);
		$this->db->group_end();
		$query_result = $this->db->get();
		// debug_last_query();
		return $query_result->result_array();
	}

	public function get_contract_list($limit = '', $offset = '', $where = '', $where_or = '', $like = '', $where_in = null, $where_not_in = null, $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$this->db->select("
			cb_member.mem_id,
			cb_member.mem_userid,
			cb_member.mem_username,
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
			ctr_park.park_no          ctr_park_park_no,
			ctr_park.park_real_name   ctr_park_name,
			ctr_prod.park_prod_cd     ctr_prod_no,
			CASE WHEN ctr_prod.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', ctr_prod.park_prod_name, dan_type.cd_value, CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', ctr_prod.park_prod_name, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', ctr_prod.park_prod_name, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', ctr_prod.park_prod_name, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			END ctr_prod_name,
			cb_mp_payroll.payroll_amount payroll_amount,
			cb_mp_settlement.settle_no,
			cb_mp_settlement.mem_net_amount,
			SUM(cb_mp_payroll.payroll_amount) sum_payroll_amount,
			(cb_mp_settlement.mem_net_amount = SUM(cb_mp_payroll.payroll_amount)) is_settlement_complete,
			settle_complete_time,
			cb_mp_contract.*
		");
		// (cb_mp_settlement.mem_net_amount IS NOT NULL) is_settlement_complete,
		// CONCAT_WS(' ', park_type_cd.cd_value, ctr_prod.park_prod_name, CONCAT(ctr_prod.park_dan_cd, '단'), dan_type.cd_value, CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
		// CONCAT_WS(' ', park_type_cd.cd_value, ctr_prod.park_prod_name, public_yn.cd_value, tree_type.cd_value, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))

		$this->db->from($this->_table);
		$this->db->join("cb_member", "cb_member.mem_id = cb_mp_contract.ctr_mem_id", "LEFT");
		$this->db->join("cb_mp_park    wish_park1", "wish_park1.park_no = cb_mp_contract.wish_park1", "LEFT");
		$this->db->join("cb_mp_park    wish_park2", "wish_park2.park_no = cb_mp_contract.wish_park2", "LEFT");
		$this->db->join("cb_mp_park    wish_park3", "wish_park3.park_no = cb_mp_contract.wish_park3", "LEFT");
		$this->db->join("cb_mp_product wish_prod1", "wish_prod1.park_prod_cd = cb_mp_contract.wish_prod1", "LEFT");
		$this->db->join("cb_mp_product wish_prod2", "wish_prod2.park_prod_cd = cb_mp_contract.wish_prod2", "LEFT");
		$this->db->join("cb_mp_product wish_prod3", "wish_prod3.park_prod_cd = cb_mp_contract.wish_prod3", "LEFT");
		$this->db->join("cb_mp_park    ctr_park", "ctr_park.park_no = cb_mp_contract.ctr_park_no", "LEFT");
		$this->db->join("cb_mp_product ctr_prod", "ctr_prod.park_prod_cd = cb_mp_contract.ctr_prod_cd", "LEFT");
		$this->db->join("cb_mp_code park_type_cd", "park_type_cd.cd_type = 'park_type_cd' AND SUBSTR(ctr_prod.park_prod_cd,1,2) = park_type_cd.cd_name", "LEFT");
		$this->db->join("cb_mp_code dan_type", "dan_type.cd_type = 'dan_type' AND SUBSTR(ctr_prod.park_prod_cd,7,2) = dan_type.cd_name", "LEFT");
		$this->db->join("cb_mp_code public_yn", "public_yn.cd_type = 'public_yn' AND SUBSTR(ctr_prod.park_prod_cd,7,2) = public_yn.cd_name", "LEFT");
		$this->db->join("cb_mp_code tree_type", "tree_type.cd_type = 'tree_type' AND SUBSTR(ctr_prod.park_prod_cd,9,2) = tree_type.cd_name", "LEFT");
		$this->db->join("cb_mp_settlement", "cb_mp_settlement.ctr_no = cb_mp_contract.ctr_no", "LEFT");
		$this->db->join("cb_mp_payroll", "cb_mp_payroll.ctr_no = cb_mp_contract.ctr_no", "LEFT");
		$this->db->join("cb_mp_history", "cb_mp_history.ctr_no = cb_mp_contract.ctr_no", "LEFT");

		if ($where_or) { $this->db->group_start()->or_where($where_or)->group_end(); }
		if ($where) { $this->db->group_start()->where($where)->group_end(); }
		if ($like) { $this->db->group_start()->or_like($like)->group_end(); }

		if ($where_in) {
			foreach ($where_in as $fld_name => $in_list) {
				$this->db->where_in($fld_name, $in_list);
			}
		}
		if ($where_not_in) {
			foreach ($where_not_in as $fld_name => $in_list) {
				$this->db->where_not_in($fld_name, $in_list);
			}
		}

		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}

		$paging_result = array();
		$paging_result['total_rows'] = $this->db->count_all_results('', FALSE);
		$this->db->group_by("cb_mp_contract.ctr_no");

		$subquery = $this->db->get_compiled_select();

		$this->db->reset_query();
		$old_dbprefix = $this->db->dbprefix;
		$this->db->set_dbprefix('');
		$this->db->select("*");
		$this->db->from("({$subquery}) as T");
		$this->db->order_by($findex, $forder);
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}

		$query_result = $this->db->get();
		// debug_last_query();
		$paging_result['list'] = $query_result->result_array();
		$this->db->set_dbprefix($old_dbprefix);
		return $paging_result;
	}

	public function update_contract($ctr_no, $updatedata, $where = '')
	{
		if ( ! empty($updatedata)) {
			if ( ! empty($ctr_no)) {
				$this->db->where($this->primary_key, $ctr_no);
			}
			if ( ! empty($where)) {
				$this->db->where($where);
			}
			$this->db->set($updatedata);
			$result = $this->db->update($this->_table);

			return $result;
		} else {
			return false;
		}
	}

	public function del_contract($ctr_no)
	{
		if (is_array($ctr_no)) {
			$this->db->where_in('ctr_no', $ctr_no);
		} else if (is_string($ctr_no)) {
			$this->db->where('ctr_no', $ctr_no);
		}

		$result = $this->db->delete($this->_table);
		// debug_last_query();
		return $result;
	}

	public function get_summary() {
		$summary = array();

		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}
		$summary['total'] = $this->db->count_all_results($this->_table);
		// debug_last_query();

		$this->db->where_in('ctr_status', array('담당배정','1차상담','2차상담','방문답사'));
		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}
		$summary['continue'] = $this->db->count_all_results($this->_table);
		// debug_last_query();

		$this->db->where_in('ctr_status', array('계약완료'));
		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}
		$summary['hold'] = $this->db->count_all_results($this->_table);
		// debug_last_query();

		$this->db->where_in('ctr_status', array('잔금완료'));
		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}
		$summary['complete'] = $this->db->count_all_results($this->_table);
		// debug_last_query();

		$this->db->where_in('ctr_status', array('계약취소'));
		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}
		$summary['removal'] = $this->db->count_all_results($this->_table);
		// debug_last_query();

		$this->db->where_in('ctr_status', array('진행불발'));
		if ($this->member->item('mem_level') <= 20) { // 영업담당 이하
			$this->db->where("cb_mp_contract.ctr_mem_id", $this->member->is_member());
		}
		$summary['misfire'] = $this->db->count_all_results($this->_table);
		// debug_last_query();

		return $summary;
	}

	public function get_user_list($limit = '', $offset = '', $s1 = '')
	{
		$this->db->select("*");
		$this->db->from("vtrack.cb_member");

		if ($s1) {
			$this->db->like('mem_username', $s1);
		}

		$paging_result = array();
		$paging_result['total_rows'] = $this->db->count_all_results('', FALSE);
		$this->db->order_by("mem_id", "DESC");
		if ($limit && $offset) {
			$this->db->limit($limit, $offset);
		}
		$query_result = $this->db->get();
		$paging_result['list'] = $query_result->result_array();
		// debug_last_query();
		return $paging_result;
	}

	public function get_member_group_member($mem_id)
	{
		$this->db->select("*");
		$this->db->from("vtrack.cb_member_group_member");

		$this->db->where("mem_id", $mem_id);

		$this->db->order_by("mgr_id", "ASC");

		$query_result = $this->db->get();
		return $query_result->result_array();
	}

	public function get_member_all_group()
	{
		$cache_name = 'mp_contract/Mp-Contract-model-get';
		$cache_time = 86400; // 캐시 저장시간
	
		$cachename = $cache_name;
		if ( ! $result = $this->cache->get($cachename)) {
			$this->db->select("*");
			$this->db->from("vtrack.cb_member_group_member");
			$this->db->join("vtrack.cb_member_group", "vtrack.cb_member_group_member.mgr_id=vtrack.cb_member_group.mgr_id", "LEFT");
	
			$this->db->order_by("vtrack.cb_member_group_member.mgr_id", "ASC");
	
			$query_result = $this->db->get();
			$result_list = $query_result->result_array();
			$result = array();
			foreach ($result_list as $key => $val) {
				$result[$val['mgr_id']] = $val;
			}
			$this->cache->save($cachename, $result, $cache_time);
		}
		return $result;
	}

	public function get_member_item($groupid = 0)
	{
		$groupid = (int) $groupid;
		if (empty($groupid) OR $groupid < 1) {
			return false;
		}

		$data = $this->get_member_all_group();
		$result = isset($data[ $groupid ]) ? $data[ $groupid ] : false;

		return $result;
	}

	public function get_cust_list($limit = '', $offset = '', $ctr_no_list = array())
	{
		$this->db->select("ctr_no, cust_name, cust_phone");
		$this->db->from($this->_table);

		if ($ctr_no_list) {
			$this->db->where_in('ctr_no', $ctr_no_list);
		}

		$paging_result = array();
		$paging_result['total_rows'] = $this->db->count_all_results('', FALSE);
		$this->db->order_by("ctr_no", "ASC");
		if ($limit && $offset) {
			$this->db->limit($limit, $offset);
		}
		$query_result = $this->db->get();
		$paging_result['list'] = $query_result->result_array();
		// debug_last_query();
		return $paging_result;
	}


}
