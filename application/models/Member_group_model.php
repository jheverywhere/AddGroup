<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member Group model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_group_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'member_group';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mgr_id'; // 사용되는 테이블의 프라이머리키

    public $cache_name = 'member_group/member-group-model-get'; // 캐시 사용시 프리픽스
    public $cache_name_driver = 'member_group/member-group-model-get-all-driver'; // 캐시 사용시 프리픽스

	public $cache_time = 86400; // 캐시 저장시간

	function __construct()
	{
		parent::__construct();

		check_cache_dir('member_group');
	}


	public function get_all_group()
	{
		$cachename = $this->cache_name;
		if ( ! $result = $this->cache->get($cachename)) {
			$result = array();
			$res = $this->get($primary_value = '', $select = '', $where = '', $limit = '', $offset = 0, $findex = 'mgr_order', $forder = 'ASC');
			if ($res && is_array($res)) {
				foreach ($res as $val) {
					$result[$val['mgr_id']] = $val;
				}
			}
			$this->cache->save($cachename, $result, $this->cache_time);
		}
		return $result;
	}


	public function get_all_driver_group()
	{
		$cachename = $this->cache_name_driver;
		if ( ! $result = $this->cache->get($cachename)) {
			$result = array();
            $res = $this->get($primary_value = '', $select = '', $where = "mgr_type='driver'", $limit = '', $offset = 0, $findex = 'mgr_order', $forder = 'ASC');
			if ($res && is_array($res)) {
				foreach ($res as $val) {
					$result[$val['mgr_id']] = $val;
				}
			}
			$this->cache->save($cachename, $result, $this->cache_time);
		}
		return $result;
	}


	public function item($groupid = 0)
	{
		$groupid = (int) $groupid;
		if (empty($groupid) OR $groupid < 1) {
			return false;
		}

		$data = $this->get_all_group();
		$result = isset($data[ $groupid ]) ? $data[ $groupid ] : false;

		return $result;
	}


	public function update_group($data = '')
	{
        $order = 1;
        $now = cdate('Y-m-d H:i:s');
		if (element('mgr_id', $data) && is_array(element('mgr_id', $data))) {
			foreach (element('mgr_id', $data) as $key => $value) {
				if ( ! element($key, element('mgr_title', $data))) {
					continue;
				}
				if ($value) {
					$is_default = isset($data['mgr_is_default'][$key]) && $data['mgr_is_default'][$key] ? 1 : 0;
					$updatedata = array(
						'mgr_title' => $data['mgr_title'][$key],
						'mgr_is_default' => $is_default,
						'mgr_datetime' => $now,
						'mgr_order' => $order,
                        'mgr_description' => $data['mgr_description'][$key],
                        'mgr_type' => element($key, element('mgr_type', $data)),
					);
					$this->update($value, $updatedata);
				} else {
					$is_default = isset($data['mgr_is_default'][$key]) && $data['mgr_is_default'][$key] ? 1 : 0;
					$insertdata = array(
						'mgr_title' => $data['mgr_title'][$key],
						'mgr_is_default' => $is_default,
						'mgr_datetime' => $now,
						'mgr_order' => $order,
                        'mgr_description' => $data['mgr_description'][$key],
                        'mgr_type' => element($key, element('mgr_type', $data)),
					);
					$this->insert($insertdata);
				}
			$order++;
			}
		}
		$deletewhere = array(
            'mgr_datetime !=' => $now,
		);
		$this->delete_where($deletewhere);
		$this->cache->delete($this->cache_name);
	}


	public function update_driver_group($data = '')
	{
        debug($data);
        $order = 1;
        $now = cdate('Y-m-d H:i:s');
		if (element('mgr_id', $data) && is_array(element('mgr_id', $data))) {
			foreach (element('mgr_id', $data) as $key => $value) {
				if ( ! element($key, element('mgr_title', $data))) {
					continue;
				}
				if ($value) {
					$is_default = isset($data['mgr_is_default'][$key]) && $data['mgr_is_default'][$key] ? 1 : 0;
					$updatedata = array(
						'mgr_title' => $data['mgr_title'][$key],
						'mgr_is_default' => $is_default,
						'mgr_datetime' => $now,
						'mgr_order' => $order,
                        'mgr_description' => $data['mgr_description'][$key],
					);
					$this->update($value, $updatedata);
				} else {
					$is_default = isset($data['mgr_is_default'][$key]) && $data['mgr_is_default'][$key] ? 1 : 0;
					$insertdata = array(
						'mgr_title' => $data['mgr_title'][$key],
						'mgr_is_default' => $is_default,
						'mgr_datetime' => $now,
						'mgr_order' => $order,
                        'mgr_description' => $data['mgr_description'][$key],
                        'mgr_type' => element($key, element('mgr_type', $data)),
					);
					$this->insert($insertdata);
				}
			$order++;
			}
		}
		$deletewhere = array(
            'mgr_datetime !=' => $now,
            "mgr_type =" => "driver"
		);
        $this->delete_where($deletewhere);
		$this->cache->delete($this->cache_name);
    }
    
    public function setCategoryTree() {
        $categories = array();
        $query = $this->db->query('SELECT id, name as text, parent_id as children FROM categoryTable WHERE parent_id IS NULL');
        foreach ($query->result() as $row) {
            $child = $this->getChilderen($row->id);
            if (count($child) > 0) {
                $row->children = $child;
            } else {
                $row->children = false;
            }
            $categories[] = $row;
        }
        return $categories;
    }

    private function getChilderen($parentId) {
        $child = array();
        $query = $this->db->query('SELECT id, name as text, parent_id as children FROM categoryTable WHERE parent_id = ' . $parentId);
        if (count($query->result()) > 0) {
            foreach ($query->result() as $i => $row) {
                if ($row->children > 0) {
                    $row->children = $this->getChilderen($row->id);
                }
                $child[$i] = $row;
            }
            return $child;
        } else {
            return false;
        }
    }
}
