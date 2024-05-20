<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_smssend_recv_model class
 */

class Mp_smssend_recv_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'mp_smssend_recv';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'smr_no'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();

		$this->load->library('session');
	}

	public function get_recv_list($offset=0, $limit='', $where='', $like='', $where_in='')
	{
		$result = array();

		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->join("cb_mp_smssend_log", "cb_mp_smssend_log.sml_no = cb_mp_smssend_recv.sml_no", "INNER");

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
		$this->db->order_by("smr_no", "ASC");
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}
		$paging_result = $this->db->get();
		debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function get_sms_recv_list($sml_no, $offset=0, $limit='', $where='', $like='')
	{
		$result = array();

		$this->db->select("
			*
		");
		$this->db->from($this->_table);

		$this->db->where('sml_no', $sml_no);
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

}
