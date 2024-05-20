<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mp_product_model class
 */

class Mp_product_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'mp_product';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'park_prod_cd'; // 사용되는 테이블의 프라이머리키
	public $allow_search_field = "park_type";

	function __construct()
	{
		parent::__construct();
	}

	public function get_product_list($park_no, $offset=0, $limit='', $where='', $like='')
	{
		$park = $this->db->get_where("cb_mp_park", array("park_no" => $park_no))->row_array();
		// debug_var($park);

		$result = array();
		$this->db->select("
			park_type_cd.cd_value park_type_name,
			dan_type.cd_value dan_type,
			public_yn.cd_value public_yn,
			tree_type.cd_value tree_type,
			dan_type.*,
			CASE WHEN park_type_cd = 'CH' THEN 
				CONCAT_WS(' ', park_prod_name, dan_type.cd_value, CONCAT(park_dan_cd,'단'), CONCAT(FORMAT(park_prod_price / 10000, 0),'만원'))
			WHEN park_type_cd = 'NB' THEN
				CONCAT_WS(' ', park_prod_name, IF(SUBSTR(park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(park_prod_cd,11,2), UNSIGNED),'구'), ''), , CONCAT(FORMAT(park_prod_price / 10000, 0),'만원'))
			WHEN park_type_cd = 'CT' THEN
				CONCAT_WS(' ', park_prod_name, IF(SUBSTR(park_prod_cd,11,2) != 0, CONCAT(CONVERT(SUBSTR(park_prod_cd,11,2), UNSIGNED),'구'), ''), , CONCAT(FORMAT(park_prod_price / 10000, 0),'만원'))
			END prod_name,
			cb_mp_product.*
		");
		$this->db->from($this->_table);

		$this->db->join("cb_mp_code park_type_cd", "park_type_cd.cd_type = 'park_type_cd' AND SUBSTR(park_prod_cd,1,2) = park_type_cd.cd_name", "LEFT");
		$this->db->join("cb_mp_code dan_type", "dan_type.cd_type = 'dan_type' AND SUBSTR(park_prod_cd,7,2) = dan_type.cd_name", "LEFT");
		$this->db->join("cb_mp_code public_yn", "public_yn.cd_type = 'public_yn' AND SUBSTR(park_prod_cd,7,2) = public_yn.cd_name", "LEFT");
		$this->db->join("cb_mp_code tree_type", "tree_type.cd_type = 'tree_type' AND SUBSTR(park_prod_cd,9,2) = tree_type.cd_name", "LEFT");

		$this->db->where('park_no', $park_no);

		if ($where) { $this->db->group_start()->where($where)->group_end(); }
		if ($like) { $this->db->group_start()->or_like($like)->group_end(); }

		$result['total_rows'] = $this->db->count_all_results('', false);
		switch (element('park_type_cd', $park)) {
			case "CH":
				$this->db->order_by("park_prod_name", "ASC");
				$this->db->order_by("park_dan_cd", "ASC");
				$this->db->order_by("park_prod_price", "ASC");
				break;
			case "NB":
			case "CT":
			default:
				$this->db->order_by("park_prod_price", "ASC");
				break;
		}
		
		if (0 < $limit) {
			$this->db->limit($limit, $offset);
		}

		$paging_result = $this->db->get();
		// debug_last_query();
		$result['list'] = $paging_result->result_array();
		return $result;
	}

	public function new_park_prod_seq($park_no)
	{
		$result = array();
		$this->db->select("MAX(park_prod_seq) AS max_park_prod_seq");
		$this->db->from($this->_table);

		$this->db->where('park_no', $park_no);

		$query_result = $this->db->get();
		// debug_last_query();
		$row = $query_result->row_array();
		return element('max_park_prod_seq', $row) ? element('max_park_prod_seq', $row) + 1 : 1;
	}

	public function insert_product($data)
	{
		if ( ! empty($data)) {
			$this->db->insert("cb_mp_product", $data);
			$insert_id = $this->db->insert_id();

			return $insert_id;
		} else {
			return false;
		}
	}

	public function update_product($park_prod_cd, $data)
	{
		if (is_array($park_prod_cd)) {
			$this->db->where_in('park_prod_cd', $park_prod_cd);
		} else if (is_string($park_prod_cd)) {
			$this->db->where('park_prod_cd', $park_prod_cd);
		} else {
			return false;
		}
		$this->db->set($data);
		$result = $this->db->update("cb_mp_product");
		// debug_last_query();
		return $result;
	}

	public function delete_product($park_prod_cd)
	{
		if (is_array($park_prod_cd)) {
			$this->db->where_in('park_prod_cd', $park_prod_cd);
		} else if (is_string($park_prod_cd)) {
			$this->db->where('park_prod_cd', $park_prod_cd);
		}
		$result = $this->db->delete($this->_table);
		return $result;
	}

}
