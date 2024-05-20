<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_payment_model class
 */

class Mp_payment_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cb_mp_payment';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'payment_no'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}

	public function get_payment_list($ctr_no, $offset=0, $limit='', $where='', $like='')
	{
		$result = array();

		if (!$ctr_no) {
			$result['list'] = $this->session->userdata("ctr_payment") ? $this->session->userdata("ctr_payment") : array();
			$result['total_rows'] = count($result['list']);
		} else {
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
		}
		return $result;
	}

	public function save_payment($ctr_no)
	{
		$result = NULL;
		$ctr_payment = $this->session->userdata("ctr_payment");
		if ($ctr_payment) {
			foreach ($ctr_payment as $i => $row) {
				unset($ctr_payment[$i]['payment_no']);
				$ctr_payment[$i]['ctr_no'] = $ctr_no;
			}
			$result = $this->db->insert_batch($this->_table, $ctr_payment);
			// debug_last_query();
		}
		return $result;
	}

	public function insert_payment($data)
	{
		if (!element('ctr_no', $data)) {
			$ctr_payment = $this->session->userdata("ctr_payment") ? $this->session->userdata("ctr_payment") : array();
			$idx = ($ctr_payment) ? (min(array_keys($ctr_payment)) - 1) : -1;
			$data['payment_no'] = $idx;
			$ctr_payment[$idx] = $data;
			$this->session->set_userdata("ctr_payment", $ctr_payment);
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

	public function update_payment($payment_no, $data)
	{
		if ($payment_no < 0) {
			$ctr_payment = $this->session->userdata("ctr_payment");
			if (NULL != $ctr_payment && $ctr_payment[$payment_no]) {
				$ctr_payment[$payment_no] = $data;
				$this->session->set_userdata("ctr_payment", $ctr_payment);
			}
			return -1;
		} else {
			if (is_array($payment_no)) {
				$this->db->where_in('payment_no', $payment_no);
			} else if (is_string($payment_no)) {
				$this->db->where('payment_no', $payment_no);
			}
			$this->db->set($data);
			$result = $this->db->update($this->_table);
			// debug_last_query();
			return $result;
		}
	}

	public function delete_payment($payment_no)
	{
		if ($payment_no < 0) {
			$ctr_payment = $this->session->userdata("ctr_payment");
			if (NULL != $ctr_payment && $ctr_payment[$payment_no]) {
				unset($ctr_payment[$payment_no]);
				$this->session->set_userdata("ctr_payment", $ctr_payment);
			}
			return -1;
		} else {
			if (is_array($payment_no)) {
				$this->db->where_in('payment_no', $payment_no);
			} else if (is_string($payment_no)) {
				$this->db->where('payment_no', $payment_no);
			}

			$result = $this->db->delete($this->_table);
			// debug_last_query();
			return $result;
		}
	}

	public function clear_payment($ctr_no='')
	{
		if (!$ctr_no) {
			$this->session->unset_userdata("ctr_payment");
		} else {
			if (is_array($ctr_no)) {
				$this->db->where_in('ctr_no', $ctr_no);
			} else if (is_string($payment_no)) {
				$this->db->where('ctr_no', $ctr_no);
			}

			$result = $this->db->delete($this->_table);
			// debug_last_query();
			return $result;
		}
	}


	public function sum_amount($ctr_no='')
	{
		$this->db->from($this->_table);
		$this->db->where('ctr_no', $ctr_no);
		$this->db->select_sum('payment_price', 'sum_payment_price');
		$result = $this->db->get();
		$row = $result->row_array();
		return $row;
	}
}
