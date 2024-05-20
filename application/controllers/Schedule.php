<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH.'/third_party/vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Schedule class
 */

/**
 * 게시물 전체 검색시 필요한 controller 입니다.
 */
class Schedule extends CB_Controller
{
	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring'));
	}


	public function index()
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_history_model");
		$this->load->model("Mp_Contract_model");
		$this->load->model("Member_model");

		$per_page = admin_listnum();
		$page = max(1, $this->input->get('page'));
		$sfield = '';
		$skeyword = '';

		$where = $where_or = $like = $where_in = array();
		if ($this->input->get('fdate') == '0000-00-00') {
		} else if ($this->input->get('fdate')) {
			$view['view']['fdate'] = $this->input->get('fdate');
			$where['history_date >='] = $view['view']['fdate']." 00:00:00";
		} else {
			$view['view']['fdate'] = date("Y-m-d");
			$where['history_date >='] = $view['view']['fdate']." 00:00:00";
		}
		if ($this->input->get('edate') == '0000-00-00') {
		} else if ($this->input->get('edate')) {
			$view['view']['edate'] = $this->input->get('edate');
			$where['history_date <='] = $view['view']['edate']." 23:59:59";
		} else {
			$view['view']['edate'] = date("Y-m-d");
			$where['history_date <='] = $view['view']['edate']." 23:59:59";
		}
		if ($this->input->get('history_type')) {
			$where['history_type'] = $this->input->get('history_type');
		}
		if ($this->input->get('mem_id')) {
			$where['ctr_mem_id'] = $this->input->get('mem_id');
		}
		if ($this->input->get('stx')) {
			$like['cust_name'] = $this->input->get('stx');
			$like['cust_phone'] = str_replace('-', '', trim($this->input->get('stx')));
		}

		// $view['view']['select_ctr_status_list'] = $this->Mp_Code_model->get_list_by_type('ctr_status');
		$view['view']['select_ctr_status_list'] = $this->Mp_Contract_model->get_ctr_status_list();
		$view['view']['select_mem_id_list'] = $this->Member_model->get_saleman_list();

		$view['view']['summary'] = $this->Mp_Contract_model->get_summary();

		$list = $this->Mp_history_model->get_history_list($per_page, ($page-1) * $per_page, $where, $where_or, $like, $where_in);
		$view['view']['data'] = $list;
		$view['view']['page'] = $page;
		$view['view']['per_page'] = $per_page;

		$config = array();
		$config['base_url'] = '/schedule';
		$config['total_rows'] = element('total_rows', $list);
		$config['per_page'] = $per_page;
		$config['reuse_query_string'] = TRUE;
		$this->pagination->initialize($config);
		$view['view']['pagination'] = $this->pagination->create_links();

		$view['layout'] = $this->_layout("list");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function set_status()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_history_model");

		$history_no     = $this->input->post('history_no');
		$history_status = $this->input->post('history_status');

		$fld = array(
			"history_status" => $history_status,
		);
		$result = $this->Mp_history_model->update_history($history_no, $fld);

		$this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
	}


	private function _layout($skin, $layout='layout', $path="schedule")
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
