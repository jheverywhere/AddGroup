<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH.'/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Export class
 */

/**
 * 게시물 전체 검색시 필요한 controller 입니다.
 */
class Export extends CB_Controller
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
		$this->load->model("Member_model");

		$view['view']['select_ctr_status_list'] = $this->Mp_Contract_model->get_ctr_status_list();
		$view['view']['select_mem_id_list'] = $this->Member_model->get_saleman_list();
		$view['view']['summary'] = $this->Mp_Contract_model->get_summary();

		$view['layout'] = $this->_layout("list");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function download()
	{
		// 준비하기
		$this->load->model("Mp_history_model");
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_settlement_model");
		$this->load->model("Member_model");
		$this->load->model("Download_model");

		$where = $where_or = $like = $where_in = array();

		if (!$this->input->post()) {
			redirect("/export");
		}

		if ($this->input->post('history_type')) {
			$where['history_type'] = $this->input->post('history_type');
		}
		if ($this->input->post('mem_id')) {
			$where['ctr_mem_id'] = $this->input->post('mem_id');
		}
		if ($this->input->post('stx')) {
			$like['cust_name'] = $this->input->post('stx');
			$like['cust_phone'] = str_replace('-', '', trim($this->input->post('stx')));
		}

		if ($this->input->post('ctr_status')) {
			if ($this->input->post('ctr_status') == '진행중') {
				$where_in['ctr_status'] = array('담당배정','1차상담','2차상담','방문답사');
			} else {
				$where['ctr_status'] = $this->input->post('ctr_status');
			}
		}

		$settle_complete = $this->input->post('settle_complete', null, '');
		if ($settle_complete == '정산완료') {
			$where['settle_complete_time !='] = NULL;
		} else if ($settle_complete == '미정산') {
			$where['settle_complete_time'] = NULL;
		}

		$insert_data = array(
			'dest' => $this->input->post('dest'),
			'mem_id' => $this->input->post('mem_id'),
			'type' => $this->input->post('history_type'),
			'status' => $this->input->post('ctr_status'),
			'settlement' => $this->input->post('settle_complete'),
			'fdate' => $this->input->post('fdate'),
			'edate' => $this->input->post('edate'),
			'who' => $this->member->is_member(),
			'datetime' => cdate("Y-m-d H:i:s"),
		);
		$this->Download_model->insert($insert_data);
		// debug_var($insert_data);
		// exit;

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle($this->input->post('dest'));

		if ($this->input->post('dest') == "일정현황") {

			if ($this->input->post('fdate')) {
				$where['history_date >='] = $this->input->post('fdate');
			}
			if ($this->input->post('edate')) {
				$where['history_date <='] = $this->input->post('edate');
			}

			// public function get_history_list($offset=0, $limit='', $where='', $where_or='', $like='', $where_in)
			$list = $this->Mp_history_model->get_history_list(0, '', $where, $where_or, $like, $where_in);

			$filename = 'schedule_'.date("Ymd");
			$header = array('항목','날짜시간','요일','영업담당','고객명','고객휴대폰','희망시설1','관심상품1','희망시설2','관심상품2','희망시설3','관심상품3','계약일','계약공원','계약상품','메모','진행상태');
			$colnum = 'A';
			foreach ($header as $hdr) {
				$sheet->setCellValue(($colnum++).'1', $hdr);
			}

			foreach (element('list', $list) as $i => $row) {
				$colnum = 'A';
				$rownum = $i + 2;

				$sheet->setCellValue(($colnum++).$rownum, element('history_type', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('history_date', $row));
				$sheet->setCellValue(($colnum++).$rownum, get_week_string(element('history_date', $row)));
				$sheet->setCellValue(($colnum++).$rownum, element('mem_username', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('cust_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, get_phone(element('cust_phone', $row)));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_park1_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_prod1_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_park2_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_prod2_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_park3_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_prod3_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_date', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_park_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_prod_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('history_content', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('history_status', $row));
			}
		} else if ($this->input->post('dest') == "계약현황") {

			if ($this->input->post('fdate')) {
				$where['ctr_date >='] = $this->input->post('fdate');
			}
			if ($this->input->post('edate')) {
				$where['ctr_date <='] = $this->input->post('edate');
			}

			// public function get_contract_list($limit = '', $offset = '', $where = '', $where_or = '', $like = '', $where_in = '', $where_not_in = '')
			$list = $this->Mp_Contract_model->get_contract_list('', '', $where, $where_or, $like, $where_in);

			$filename = 'contract_'.date("Ymd");
			$header = array('접수일','계약일','진행상태','영업담당','고객명','고객휴대폰','희망시설','관심상품','계약공원','계약상품','상조요청','개장요청','정상여부');
			$colnum = 'A';
			foreach ($header as $hdr) {
				$sheet->setCellValue(($colnum++).'1', $hdr);
			}

			foreach (element('list', $list) as $i => $row) {
				$colnum = 'A';
				$rownum = $i + 2;

				$sheet->setCellValue(($colnum++).$rownum, element('accept_date', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_date', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_status', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('mem_username', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('cust_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, get_phone(element('cust_phone', $row)));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_park1_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_prod1_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_park2_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_prod2_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_park3_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('wish_prod3_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_date', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_park_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_prod_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('sangjo_req', $row) == '1' ? "요청함" : "진행완료");
				$sheet->setCellValue(($colnum++).$rownum, element('sangjo_req', $row) == '1' ? "요청함" : "진행완료");
				$sheet->setCellValue(($colnum++).$rownum, element('settle_complete_time', $row) ? element('settle_complete_time', $row) : "미정산");
			}
		} else if ($this->input->post('dest') == "정산내역") {

			if ($this->input->post('fdate')) {
				$where['ctr_date >='] = $this->input->post('fdate');
			}
			if ($this->input->post('edate')) {
				$where['ctr_date <='] = $this->input->post('edate');
			}

			// public function get_settlement_list($offset=0, $limit='', $where='', $like='')
			$list = $this->Mp_settlement_model->get_settlement_list(0, '', $where, $like, $where_in);

			$filename = 'settlement_'.date("Ymd");
			if ($this->member->item('mem_level') > 20) {
				$header = array('계약일','영업담당','고객명','고객휴대폰','추모공원','상품명','분양가','공원 수수료율','공원 수수료','할인금액','최종 분양금액','미수금','할인공제','기타공제','상조수익','개장수익','분양수익','직원 수수료율','지급금액','지급일자','회사수익','정산여부');
			} else {
				$header = array('계약일','영업담당','고객명','고객휴대폰','추모공원','상품명','분양가','공원 수수료율','공원 수수료','할인금액','최종 분양금액','미수금','할인공제','기타공제','상조수익','개장수익','분양수익','직원 수수료율','지급금액','지급일자','정산여부');
			}
			$colnum = 'A';
			foreach ($header as $hdr) {
				$sheet->setCellValue(($colnum++).'1', $hdr);
			}

			foreach (element('list', $list) as $i => $row) {
				$colnum = 'A';
				$rownum = $i + 2;

				$sheet->setCellValue(($colnum++).$rownum, element('ctr_date', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('mem_username', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('cust_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, get_phone(element('cust_phone', $row)));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_park_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_prod_name', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_prod_price', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('settle_park_commission_rate', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('settle_park_commission_amount', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_discount_amount', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('ctr_amount', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('sum_payment_price', $row) - element('ctr_amount', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('discount_rate', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('extra_deduction', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('sangjo_price', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('tombmig_price', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('income_amount', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('mem_commission_rate', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('sum_payroll_amount', $row));
				$sheet->setCellValue(($colnum++).$rownum, element('last_payroll_date', $row));
				if ($this->member->item('mem_level') > 20) {
					$sheet->setCellValue(($colnum++).$rownum, element('income_amount', $row) - element('sum_payroll_amount', $row));
				}
				$sheet->setCellValue(($colnum++).$rownum, element('settle_complete_time', $row) ? element('settle_complete_time', $row) : "미정산");
			}
		}

		// debug_last_query();
		// debug_var($this->input->post(), $list);
		// exit;

		$writer = new Xlsx($spreadsheet);
		// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output'); // download file 
	}


	private function _layout($skin, $layout='layout', $path="export")
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
