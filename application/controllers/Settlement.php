<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH.'/third_party/vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Settlement class
 */

/**
 * 게시물 전체 검색시 필요한 controller 입니다.
 */
class Settlement extends CB_Controller
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
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_settlement_model");

		$per_page = admin_listnum();
		$page = max(1, $this->input->get('page'));
		$offset = ($page-1) * $per_page;
		$sfield = '';
		$skeyword = '';

		// 불러오기
		$like = array();
		$where = array();
		if ($this->input->get('fdate')) {
			$where['ctr_date >='] = $this->input->get('fdate');
			$per_page = 0;
		}
		if ($this->input->get('edate')) {
			$where['ctr_date <='] = $this->input->get('edate');
			$per_page = 0;
		}
		if ($this->input->get('ctr_status')) {
			$where['ctr_status'] = $this->input->get('ctr_status');
		}
		if ($this->input->get('mem_id')) {
			$where['ctr_mem_id'] = $this->input->get('mem_id');
		}
		if ($this->input->get('stx')) {
			$like['cust_name'] = $this->input->get('stx');
			$like['cust_phone'] = str_replace('-', '', trim($this->input->get('stx')));
		}

		$settle_complete = $this->input->get('settle_complete', null, '');

		$where['ctr_status'] = "잔금완료";

		if ($settle_complete == '정산완료') {
			$where['settle_complete_time !='] = NULL;
		} else if ($settle_complete == '미정산') {
			$where['settle_complete_time'] = NULL;
		}

		$view['view']['select_ctr_status_list'] = $this->Mp_Contract_model->get_ctr_status_list();
		$view['view']['select_mem_id_list'] = $this->Member_model->get_saleman_list();

		$settlement_list = $this->Mp_settlement_model->get_settlement_list($offset, $per_page, $where, $like);
		$view['view']['data'] = $settlement_list;
		$view['view']['data']['page'] = $page;
		$view['view']['data']['per_page'] = $per_page;
		$view['view']['data']['offset'] = $offset;

		$config = array();
		$config['base_url'] = '/settlement';
		$config['total_rows'] = element('total_rows', $settlement_list);
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


	public function view($ctr_no)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_payment_model");
		$this->load->model("Mp_settlement_model");

		if (!$ctr_no) {
			alert("유효하지 않은 계약번호입니다.");
		}

		// 불러오기
		$view['view']['mode'] = "view";
		$view['view']['payment_list'] = $this->Mp_payment_model->get_payment_list($ctr_no);
		$view['view']['payroll_list'] = $this->Mp_settlement_model->get_payroll_list($ctr_no);

		$like = array();
		$where = array();

		$settlement = $this->Mp_settlement_model->get_settlement($ctr_no);
		$view['view']['data'] = $settlement;

		// 보여주기
		$view['layout'] = $this->_layout("view");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function write($ctr_no)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_payment_model");
		$this->load->model("Mp_settlement_model");

		if (!$ctr_no) {
			alert("유효하지 않은 계약번호입니다.");
		}

		// 불러오기
		$view['view']['mode'] = "write";
		$view['view']['payment_list'] = $this->Mp_payment_model->get_payment_list($ctr_no);
		$view['view']['payroll_list'] = $this->Mp_settlement_model->get_payroll_list($ctr_no);

		$like = array();
		$where = $where_or = array();

		$settlement = $this->Mp_settlement_model->get_settlement($ctr_no);
		if (!element('mem_commission_rate', $settlement)) {
			$settlement['mem_commission_rate'] = element('employee_commission_rate', $settlement);
		}
		if (!element('settle_park_commission_rate', $settlement)) {
			$settlement['settle_park_commission_rate'] = element('park_commission_rate', $settlement);
		}
		if (!element('tax_type', $settlement)) {
			// $settlement['tax_type'] = $this->member->item('mem_tax_type');
			$this->load->model("Member_model");
			$contract_member = $this->Member_model->get_by_memid(element('ctr_mem_id', $settlement));
			$settlement['tax_type'] = element("mem_tax_type", $contract_member);
		}
		$view['view']['data'] = $settlement;

		// 보여주기
		$view['layout'] = $this->_layout("write");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function write_update()
	{
		// 준비하기
		$this->load->model("Mp_settlement_model");

		// 저장하기
		if ($this->input->post()) {

			$now       = cdate("Y-m-d H:i:s");
			$settle_no = $this->input->post('settle_no');
			$ctr_no    = $this->input->post('ctr_no');

			$fld = $this->input->post();
			if (element('discount_rate', $fld)) { $fld['discount_rate'] /= 100; }
			if (element('mem_commission_rate', $fld)) { $fld['mem_commission_rate'] /= 100; }
			if (element('settle_park_commission_rate', $fld)) { $fld['settle_park_commission_rate'] /= 100; }
			if (element('is_settle_complete_time', $fld) == 0) {
				$fld['settle_complete_time'] = NULL;
			} else {
				$fld['settle_complete_time'] = $now;
			}
			unset($fld['is_settle_complete_time']);

			if (0 < $settle_no) { // 수정
				$fld['mod_time'] = $now;
				$update_result = $this->Mp_settlement_model->update($settle_no, $fld);
				$settle_no = ($update_result) ? $settle_no : null;

				// alert("정산내용이 수정되었습니다.", "/settlement");
			} else { // 신규
				unset($fld['settle_no']);
				$fld['reg_time'] = $now;
				$settle_no = $this->Mp_settlement_model->insert($fld);

				// alert("정산내용이 추가되었습니다.", "/settlement");
			}
		}

		redirect("/settlement");
		// redirect("/settlement/write/".$ctr_no);
	}


	public function update_payroll()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_settlement_model");

		$payroll_no      = $this->input->post('payroll_no');
		$ctr_no          = $this->input->post('ctr_no');
		$payroll_date    = $this->input->post('payroll_date');
		$payroll_amount  = $this->input->post('payroll_amount');
		$payroll_content = $this->input->post('payroll_content');

		$fld = array(
			"ctr_no"          => $ctr_no,
			"payroll_date"    => $payroll_date,
			"payroll_amount"  => get_price($payroll_amount),
			"payroll_content" => $payroll_content,
		);

		if ($payroll_no) {
			// $fld['payroll_no'] = $payroll_no;
			$this->Mp_settlement_model->update_payroll($payroll_no, $fld);
		} else {
			$this->Mp_settlement_model->insert_payroll($fld);
		}

		$view['view'] = array();
		$view['view']['mode'] = "write";
		$view['view']['payroll_list'] = $this->Mp_settlement_model->get_payroll_list($ctr_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);

		$this->load->view('settlement/bootstrap/payroll_list', $view);
	}


	public function delete_payroll()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_settlement_model");

		$payroll_no = $this->input->post('payroll_no');
		$ctr_no     = $this->input->post('ctr_no');

		$this->Mp_settlement_model->remove_payroll($payroll_no);

		$view['view'] = array();
		$view['view']['mode'] = "write";
		$view['view']['payroll_list'] = $this->Mp_settlement_model->get_payroll_list($ctr_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);

		$this->load->view('settlement/bootstrap/payroll_list', $view);
	}


	private function _layout($skin, $layout='layout', $path="settlement")
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
