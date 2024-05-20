<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Members class
 */

class Members extends CB_Controller
{
	protected $models = array();
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();

		$this->load->library(array('pagination', 'querystring'));
	}


	 public function index()
	{
		$this->load->model("Member_model");

		$view = array();
		$view['view'] = array();

		$per_page = 20;
		$page = max(1, $this->input->get('page'));
		$sfield = '';
		$skeyword = '';

		$where = array();
		$like = array();

		if ($this->input->get('mem_teamname')) {
			$where['mem_teamname'] = $this->input->get('mem_teamname');
		}
		if ($this->input->get('stx')) {
			$like['mem_username']     = $this->input->get('stx');
			$like['mem_phone']        = $this->input->get('stx');
			$like['mem_bank_name']    = $this->input->get('stx');
			$like['mem_bank_account'] = $this->input->get('stx');
			$like['mem_bank_owner']   = $this->input->get('stx');
			$like['mem_address1']     = $this->input->get('stx');
			$like['mem_address2']     = $this->input->get('stx');
			$like['mem_address3']     = $this->input->get('stx');
		}

		$view['view']['teamname_list'] = $this->Member_model->get_teamname_list();

		$member_list = $this->Member_model->get_member_list($per_page, ($page-1) * $per_page, $where, $like);

		$view['view']['data'] = $member_list;
		$view['view']['page'] = $page;
		$view['view']['per_page'] = $per_page;

		$config = array();
		$config['base_url'] = '/members';
		$config['total_rows'] = element('total_rows', $member_list);
		$config['per_page'] = $per_page;
		$config['reuse_query_string'] = TRUE;
		$this->pagination->initialize($config);
		$view['view']['pagination'] = $this->pagination->create_links();

		$view['layout'] = $this->_layout("list");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function del()
	{
		$this->load->model("Member_model");

		$mem_id_list = $this->input->post('chk');
		// debug_var($this->input->post(), $mem_id_list);

		$result = $this->Member_model->del_member($mem_id_list);

		if ($result) {
			// alert("선택한 계정이 모두 삭제 되었습니다.", "/members");
		} else {
			log_message("ERROR", __FILE__.":".__LINE__." ".json_encode($this->db->error()));
			alert("오류가 발생하여 선택한 계정이 삭제되지 않았습니다. 관리자에게 문의해주십시오.");
		}

		redirect("/members");
	}


	public function write($mem_id=null)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Member_model");
		$this->load->model("Mp_Contract_model");

		// 불러오기
		$view['view']['mem_id'] = $mem_id;

		$view['view']['teamname_list'] = $this->Member_model->get_teamname_list();

		$member = $this->Member_model->get_by_memid($mem_id);
		$view['view']['data'] = $member;

		// 보여주기
		$view['layout'] = $this->_layout("write");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function write_update()
	{
		// 준비하기
		$this->load->model("Member_model");

		$mem_id = $this->input->post('mem_id');

		// 저장하기
		if ($this->input->post()) {
			$fld = $this->input->post();
			if (!element('mem_join_date', $fld)) { $fld['mem_join_date'] = NULL; }
			if (!element('mem_resign_date', $fld)) { $fld['mem_resign_date'] = NULL; }
			if (element('mem_phone', $fld)) { $fld['mem_phone'] = get_phone($fld['mem_phone']); }
			if (element('mem_teamname', $fld)) { $fld['mem_level'] = $this->Member_model->get_teamlevel($fld['mem_teamname']); }
			if (element('mem_commission_rate', $fld)) { $fld['mem_commission_rate'] /= 100; }
			if (element('mem_password', $fld)) {
				$fld['mem_password'] = password_hash($fld['mem_password'], PASSWORD_BCRYPT);
			} else {
				unset($fld['mem_password']);
			}

			if (0 < $mem_id) { // 수정

				$update_result = $this->Member_model->update($mem_id, $fld);
				$mem_id = ($update_result) ? $mem_id : null;

				// alert("수정한 내용이 저장되었습니다.", "/members");

			} else { // 신규

				unset($fld['mem_id']);
				$mem_id = $this->Member_model->insert($fld);

				// alert("신규 계정이 추가되었습니다.", "/members");

			}
		}
		redirect("/members");
	}


	private function _layout($skin, $layout='layout', $path="members")
	{
		/**
		 * 레이아웃을 정의합니다
		 */
		$layoutconfig = array(
			'path'               => $path,
			'layout'             => $layout,
			'skin'               => $skin,
			'layout_dir'         => $this->cbconfig->item('layout_settings'),
			'mobile_layout_dir'  => $this->cbconfig->item('mobile_layout_settings'),
			'use_sidebar'        => $this->cbconfig->item('sidebar_settings'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_settings'),
			'skin_dir'           => $this->cbconfig->item('skin_settings'),
			'mobile_skin_dir'    => $this->cbconfig->item('mobile_skin_settings'),
			'page_title'         => $this->cbconfig->item('site_meta_title_settings'),
			'meta_description'   => $this->cbconfig->item('site_meta_description_settings'),
			'meta_keywords'      => $this->cbconfig->item('site_meta_keywords_settings'),
			'meta_author'        => $this->cbconfig->item('site_meta_author_settings'),
			'page_name'          => $this->cbconfig->item('site_page_name_settings'),
		);
		return $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
	}
}
