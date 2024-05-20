<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_payroll_model class
 */

class Mp_payroll_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cb_mp_payroll';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'payroll_no'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}

	public function get_payroll_list($ctr_no, $offset=0, $limit='', $where='', $like='')
	{
		$result = array();
		$this->db->select("*");
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
		return $result;
	}

	public function delete_payroll($ctr_no)
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

}
