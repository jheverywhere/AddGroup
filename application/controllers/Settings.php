<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings class
 */

class Settings extends CB_Controller
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

		// // 준비하기
		// $this->load->model("Mp_Code_model");

		// $per_page = admin_listnum();
		// $page = max(1, $this->input->get('page'));

		// $where = array();
		// if ($this->input->get('stype')) {
		// 	$where['cd_type'] = $this->input->get('stype');
		// }

		// $view['view']['type_list'] = $this->Mp_Code_model->get_type_list();

		// $list = $this->Mp_Code_model->get_code_list($per_page, ($page-1) * $per_page, $where);
		// $view['view']['data'] = $list;

		// $config = array();
		// $config['base_url'] = '/settings';
		// $config['total_rows'] = element('total_rows', $list);
		// $config['per_page'] = $per_page;
		// $config['reuse_query_string'] = TRUE;
		// $this->pagination->initialize($config);
		// $view['view']['pagination'] = $this->pagination->create_links();

		$view['layout'] = $this->_layout("index");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

    function getLists(){
		$this->load->model("Mp_Code_model");

        $data = $row = array();
        
        // Fetch member's records
		$rows = $this->Mp_Code_model->getRows($_POST);

		$i = $this->input->post('start');
		foreach ($rows as $row) {
			$data[] = array(
				++$i,
				$row->cd_type,
				$row->cd_name,
				$row->cd_value,
			);
		}
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mp_Code_model->countAll(),
            "recordsFiltered" => $this->Mp_Code_model->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
    }

	private function _layout($skin, $layout='layout', $path="settings")
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
