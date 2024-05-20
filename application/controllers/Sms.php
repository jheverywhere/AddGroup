<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH.'/third_party/vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Sms class
 */

/**
 * 게시물 전체 검색시 필요한 controller 입니다.
 */
class Sms extends CB_Controller
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
		$this->load->model("Mp_sms_history_model");

		$per_page = admin_listnum();
		$page = max(1, $this->input->get('page'));
		$sfield = '';
		$skeyword = '';

		// 불러오기
		$like = array();
		$where = array();
		if ($this->input->get('fdate')) {
			$where['sms_datetime >='] = $this->input->get('fdate')." 00:00:00";
		}
		if ($this->input->get('edate')) {
			$where['sms_datetime <='] = $this->input->get('edate')." 23:59:59";
		}
		if ($this->input->get('stx')) {
			$like['sms_mem_username'] = $this->input->get('stx');
			$like['sms_mem_phone']    = $this->input->get('stx');
			$like['sms_cust_name']    = $this->input->get('stx');
			$like['sms_cust_phone']   = str_replace('-', '', trim($this->input->get('stx')));
			$like['cb_wish_park1.park_real_name']  = $this->input->get('stx');
			$like['cb_wish_park2.park_real_name']  = $this->input->get('stx');
			$like['cb_wish_park3.park_real_name']  = $this->input->get('stx');
			$like['cb_wish_prod1.park_prod_name']  = $this->input->get('stx');
			$like['cb_wish_prod2.park_prod_name']  = $this->input->get('stx');
			$like['cb_wish_prod3.park_prod_name']  = $this->input->get('stx');
			$like['cb_ctr_park.park_real_name']    = $this->input->get('stx');
			$like['cb_ctr_prod.park_prod_name']    = $this->input->get('stx');
		}

		$view['view'] = array();
		// $view['view']['sms_list'] = $this->Mp_sms_history_model->get_sms_list(($page-1) * $per_page, $per_page, $where, $like);

		// $this->load->view('contract/bootstrap/sms_history', $view);

		$sms_list = $this->Mp_sms_history_model->get_sms_list(($page-1) * $per_page, $per_page, $where, $like);
		$view['view']['data'] = $sms_list;
		$view['view']['page'] = $page;
		$view['view']['per_page'] = $per_page;

		$config = array();
		$config['base_url'] = '/sms';
		$config['total_rows'] = element('total_rows', $sms_list);
		$config['per_page'] = $per_page;
		$config['reuse_query_string'] = TRUE;
		$this->pagination->initialize($config);
		$view['view']['pagination'] = $this->pagination->create_links();

		// 보여주기
		$view['layout'] = $this->_layout("list");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	private function _layout($skin, $layout='layout', $path="sms")
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
