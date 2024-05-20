<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_settlement_model class
 */

class Mp_settlement_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cb_mp_settlement';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'settle_no'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}

	public function get_settlement($ctr_no)
	{
		$result = array();
		$this->db->select("
			cb_employee.mem_username,
			cb_employee.mem_phone,
			cb_employee.mem_commission_rate employee_commission_rate,
			cb_mp_settlement.settle_no,
			cb_mp_settlement.settle_park_commission_rate,
			cb_mp_settlement.settle_park_commission_amount,
			cb_mp_settlement.discount_rate,
			cb_mp_settlement.extra_deduction,
			cb_mp_settlement.sangjo_price,
			cb_mp_settlement.tombmig_price,
			cb_mp_settlement.income_amount,
			cb_mp_settlement.tax_type,
			cb_mp_settlement.mem_commission_rate,
			cb_mp_settlement.mem_commission_amount,
			cb_mp_settlement.mem_net_amount,
			cb_mp_settlement.settle_remark,
			cb_mp_park.park_real_name ctr_park_name,
			cb_mp_park.park_commission_rate,
			ctr_prod.park_prod_cd ctr_prod_cd,
			CASE WHEN ctr_prod.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', cb_park_type_cd.cd_value, ctr_prod.park_prod_name, CONCAT(ctr_prod.park_dan_cd, '단'), cb_dan_type.cd_value, CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', cb_park_type_cd.cd_value, ctr_prod.park_prod_name, cb_public_yn.cd_value, cb_tree_type.cd_value, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', cb_park_type_cd.cd_value, ctr_prod.park_prod_name, cb_public_yn.cd_value, cb_tree_type.cd_value, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN ctr_prod.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', cb_park_type_cd.cd_value, ctr_prod.park_prod_name, cb_public_yn.cd_value, cb_tree_type.cd_value, IF(SUBSTR(ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(ctr_prod.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(ctr_prod.park_prod_price / 10000, 0), '만원'))
			END ctr_prod_name,
			SUM(cb_mp_payment.payment_price) sum_payment_price,
			MAX(cb_mp_payment.payment_date) last_payment_date,
			SUM(cb_mp_payroll.payroll_amount) sum_payroll_amount,
			MAX(cb_mp_payroll.payroll_date) last_payroll_date,
			settle_complete_time,
			cb_mp_contract.*
		");
		$this->db->from("cb_mp_contract");
		$this->db->join("cb_mp_settlement", "cb_mp_settlement.ctr_no = cb_mp_contract.ctr_no", "LEFT");
		$this->db->join("cb_member cb_employee", "cb_employee.mem_id = cb_mp_contract.ctr_mem_id", "LEFT");
		$this->db->join("cb_mp_park", "cb_mp_park.park_no = cb_mp_contract.ctr_park_no", "LEFT");
		$this->db->join("cb_mp_product ctr_prod", "ctr_prod.park_prod_cd = cb_mp_contract.ctr_prod_cd", "LEFT");
		$this->db->join("cb_mp_code cb_park_type_cd", "cb_park_type_cd.cd_type = 'cb_park_type_cd' AND SUBSTR(ctr_prod.park_prod_cd,1,2) = cb_park_type_cd.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_dan_type", "cb_dan_type.cd_type = 'cb_dan_type' AND SUBSTR(ctr_prod.park_prod_cd,7,2) = cb_dan_type.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_public_yn", "cb_public_yn.cd_type = 'cb_public_yn' AND SUBSTR(ctr_prod.park_prod_cd,7,2) = cb_public_yn.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_tree_type", "cb_tree_type.cd_type = 'cb_tree_type' AND SUBSTR(ctr_prod.park_prod_cd,9,2) = cb_tree_type.cd_name", "LEFT");
		$this->db->join("cb_mp_payment", "cb_mp_payment.ctr_no = cb_mp_contract.ctr_no", "LEFT");
		$this->db->join("cb_mp_payroll", "cb_mp_payroll.ctr_no = cb_mp_contract.ctr_no", "LEFT");

		$this->db->where('cb_mp_contract.ctr_no', $ctr_no);

		$this->db->group_by("cb_mp_contract.ctr_no");

		$query_result = $this->db->get();
		// debug_last_query();
		return $query_result->row_array();
	}

	public function get_settlement_list($offset=0, $limit='', $where='', $like='', $where_in = null)
	{
		$result = array();
		$this->db->select("
			cb_employee.mem_username,
			cb_employee.mem_phone,
			cb_mp_settlement.settle_no,
			cb_mp_settlement.settle_park_commission_rate,
			cb_mp_settlement.settle_park_commission_amount,
			cb_mp_settlement.discount_rate,
			cb_mp_settlement.extra_deduction,
			cb_mp_settlement.sangjo_price,
			cb_mp_settlement.tombmig_price,
			cb_mp_settlement.income_amount,
			cb_mp_settlement.tax_type,
			cb_mp_settlement.mem_commission_rate,
			cb_mp_settlement.mem_commission_amount,
			cb_mp_settlement.mem_net_amount,
			cb_mp_settlement.settle_remark,
			cb_mp_park.park_real_name ctr_park_name,
			CASE WHEN cb_ctr_prod.park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', cb_park_type_cd.cd_value, cb_ctr_prod.park_prod_name, CONCAT(cb_ctr_prod.park_dan_cd, '단'), cb_dan_type.cd_value, CONCAT(FORMAT(cb_ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN cb_ctr_prod.park_type_cd = 'NB' THEN
				CONCAT_WS(' ', cb_park_type_cd.cd_value, cb_ctr_prod.park_prod_name, cb_public_yn.cd_value, cb_tree_type.cd_value, IF(SUBSTR(cb_ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN cb_ctr_prod.park_type_cd = 'CT' THEN
				CONCAT_WS(' ', cb_park_type_cd.cd_value, cb_ctr_prod.park_prod_name, cb_public_yn.cd_value, cb_tree_type.cd_value, IF(SUBSTR(cb_ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_ctr_prod.park_prod_cd,11,2), UNSIGNED), '구'), ''), , CONCAT(FORMAT(cb_ctr_prod.park_prod_price / 10000, 0), '만원'))
			WHEN cb_ctr_prod.park_type_cd = 'CF' THEN
				CONCAT_WS(' ', cb_park_type_cd.cd_value, cb_ctr_prod.park_prod_name, cb_public_yn.cd_value, cb_tree_type.cd_value, IF(SUBSTR(cb_ctr_prod.park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(cb_ctr_prod.park_prod_cd,11,2), UNSIGNED), '구좌'), ''), , CONCAT(FORMAT(cb_ctr_prod.park_prod_price / 10000, 0), '만원'))
			END ctr_prod_name,
			(SELECT SUM(cb_mp_payment.payment_price) FROM cb_mp_payment WHERE ctr_no=cb_mp_contract.ctr_no) sum_payment_price,
			(SELECT MAX(cb_mp_payment.payment_date) FROM cb_mp_payment WHERE ctr_no=cb_mp_contract.ctr_no) last_payment_date,
			(SELECT SUM(cb_mp_payroll.payroll_amount) FROM cb_mp_payroll WHERE ctr_no=cb_mp_contract.ctr_no) sum_payroll_amount,
			(SELECT MAX(cb_mp_payroll.payroll_date) FROM cb_mp_payroll WHERE ctr_no=cb_mp_contract.ctr_no) last_payroll_date,
			(cb_mp_settlement.mem_net_amount = (SELECT SUM(cb_mp_payroll.payroll_amount) FROM cb_mp_payroll WHERE ctr_no=cb_mp_contract.ctr_no)) is_settlement_complete,
			settle_complete_time,
			cb_mp_contract.*
		");
		$this->db->from("cb_mp_contract");
		$this->db->join("cb_mp_settlement", "cb_mp_settlement.ctr_no = cb_mp_contract.ctr_no", "LEFT");
		$this->db->join("cb_member cb_employee", "cb_employee.mem_id = cb_mp_contract.ctr_mem_id", "LEFT");
		$this->db->join("cb_mp_park", "cb_mp_park.park_no = cb_mp_contract.ctr_park_no", "LEFT");
		$this->db->join("cb_mp_product cb_ctr_prod", "cb_ctr_prod.park_prod_cd = cb_mp_contract.ctr_prod_cd", "LEFT");
		$this->db->join("cb_mp_code cb_park_type_cd", "cb_park_type_cd.cd_type = 'cb_park_type_cd' AND SUBSTR(cb_ctr_prod.park_prod_cd,1,2) = cb_park_type_cd.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_dan_type", "cb_dan_type.cd_type = 'cb_dan_type' AND SUBSTR(cb_ctr_prod.park_prod_cd,7,2) = cb_dan_type.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_public_yn", "cb_public_yn.cd_type = 'cb_public_yn' AND SUBSTR(cb_ctr_prod.park_prod_cd,7,2) = cb_public_yn.cd_name", "LEFT");
		$this->db->join("cb_mp_code cb_tree_type", "cb_tree_type.cd_type = 'cb_tree_type' AND SUBSTR(cb_ctr_prod.park_prod_cd,9,2) = cb_tree_type.cd_name", "LEFT");
		// $this->db->join("cb_mp_payment", "cb_mp_payment.ctr_no = cb_mp_contract.ctr_no", "LEFT");
		// $this->db->join("cb_mp_payroll", "cb_mp_payroll.ctr_no = cb_mp_contract.ctr_no", "LEFT");
		$this->db->join("cb_mp_history", "cb_mp_history.ctr_no = cb_mp_contract.ctr_no", "LEFT");


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

		$this->db->group_by("cb_mp_contract.ctr_no");

		$result['total_rows'] = $this->db->count_all_results('', false);
		$this->db->order_by("ctr_no", "DESC");
		// debug_var($limit, $offset);
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function delete_settlement($ctr_no)
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

	public function delete_payroll($ctr_no)
	{
		if (is_array($ctr_no)) {
			$this->db->where_in('ctr_no', $ctr_no);
		} else if (is_string($ctr_no)) {
			$this->db->where('ctr_no', $ctr_no);
		}

		$result = $this->db->delete("cb_mp_payroll");
		// debug_last_query();
		return $result;
	}

	public function get_payroll_list($ctr_no, $offset=0, $limit='', $where='', $like='')
	{
		$result = array();
		$this->db->select("
			*
		");
		$this->db->from("cb_mp_payroll");

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
		return $result;
	}

	public function insert_payroll($data)
	{
		if ( ! empty($data)) {
			$this->db->insert("cb_mp_payroll", $data);
			$insert_id = $this->db->insert_id();

			return $insert_id;
		} else {
			return false;
		}
	}

	public function update_payroll($payroll_no, $data)
	{
		if ($payroll_no < 0) {
			$ctr_payroll = $this->session->userdata("ctr_payroll");
			if (NULL != $ctr_payroll && $ctr_payroll[$payroll_no]) {
				$ctr_payroll[$payroll_no] = $data;
				$this->session->set_userdata("ctr_payroll", $ctr_payroll);
			}
			return -1;
		} else {
			if (is_array($payroll_no)) {
				$this->db->where_in('payroll_no', $payroll_no);
			} else if (is_string($payroll_no)) {
				$this->db->where('payroll_no', $payroll_no);
			}
			$this->db->set($data);
			$result = $this->db->update("cb_mp_payroll");
			// debug_last_query();
			return $result;
		}
	}

	public function remove_payroll($payroll_no)
	{
		$this->db->where("payroll_no", $payroll_no);
		$result = $this->db->delete("cb_mp_payroll");
		return $result;
	}

}
