<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_park_model class
 */

class Mp_park_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'mp_park';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'park_no'; // 사용되는 테이블의 프라이머리키
	public $allow_search_field = "park_type";

	function __construct()
	{
		parent::__construct();
	}

	public function get_select_park_list($offset=0, $limit='', $where='', $like='')
	{
		$result = array();
		$this->db->select("*");
		$this->db->from($this->_table);
		$this->db->where("park_use", 1);

		if ($where) { $this->db->group_start()->where($where)->group_end(); }
		if ($like) { $this->db->group_start()->or_like($like)->group_end(); }

		$result['total_rows'] = $this->db->count_all_results('', false);
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by("park_order", "DESC");
		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function get_park_list($offset=0, $limit='', $where='', $like='')
	{
		$result = array();
		$this->db->select("*");
		$this->db->from($this->_table);

		if ($where) { $this->db->group_start()->where($where)->group_end(); }
		if ($like) { $this->db->group_start()->or_like($like)->group_end(); }

		$result['total_rows'] = $this->db->count_all_results('', false);
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}
		$this->db->order_by("park_order", "DESC");
		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function get_park_list_by_region($search_addr='')
	{
		$result = array();
		$this->db->select("*");
		$this->db->from($this->_table);

		if ($search_addr) {
			$this->db->group_start()
				->or_like("park_addr1", $search_addr)
				->or_like("park_addr2", $search_addr)
				->or_like("park_addr3", $search_addr)
				->group_end();
		}

		$this->db->order_by("park_order", "DESC");
		$query_result = $this->db->get();
		// debug_last_query();
		$list = $query_result->result_array();
		foreach ($list as $i => $row) {
			$result[element('park_type_cd', $row)][] = element('park_no', $row);
		}
		return $result;
	}

	public function get_sms_receiver_list($where_in)
	{
		$result = array();
		$this->db->select("park_no, park_real_name, park_name, park_type_cd, park_manager_name, park_manager_phone, park_manager_phone_masked");
		$this->db->from($this->_table);

		$this->db->where_in('park_no', $where_in);

		$query_result = $this->db->get();
		// debug_last_query();
		$result = $query_result->result_array();
		return $result;
	}

	public function get_park($park_no)
	{
		$result = array();
		$this->db->select("*");
		$this->db->from($this->_table);

		$this->db->where('park_no', $park_no);

		$query_result = $this->db->get();
		// debug_last_query();
		return $query_result->row_array();
	}

	public function insert_park($data)
	{
		if ( ! empty($data)) {
			$this->db->insert($this->_table, $data);
			$insert_id = $this->db->insert_id();

			return $insert_id;
		} else {
			return false;
		}
	}

	public function update_park($park_no, $data)
	{
		if (is_array($park_no)) {
			$this->db->where_in('park_no', $park_no);
		} else if (is_string($park_no)) {
			$this->db->where('park_no', $park_no);
		} else {
			return false;
		}
		$this->db->set($data);
		$result = $this->db->update($this->_table);
		// debug_last_query();
		return $result;
	}

	public function delete_park($park_no)
	{
		$this->db->trans_start();
		if (is_array($park_no)) {
			$this->db->where_in('park_no', $park_no);
		} else if (is_string($park_no)) {
			$this->db->where('park_no', $park_no);
		}
		$this->db->delete($this->_table);

		if ($result) {
			if (is_array($park_no)) {
				$this->db->where_in('park_no', $park_no);
			} else if (is_string($park_no)) {
				$this->db->where('park_no', $park_no);
			}
			$this->db->delete("cb_mp_product");
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return false;
		}
		return true;
	}

}
