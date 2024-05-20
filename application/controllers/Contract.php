<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH.'/third_party/vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Contract class
 */

/**
 * 게시물 전체 검색시 필요한 controller 입니다.
 */
class Contract extends CB_Controller
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
		$this->load->model("Mp_Code_model");
		$this->load->model("Member_model");

		$per_page = admin_listnum();
		$page = max(1, $this->input->get('page'));
		$param =& $this->querystring;
		$view['view']['sort'] = array(
			'accept_date' => $param->sort('accept_date', 'ASC'),
			'ctr_date' => $param->sort('ctr_date', 'ASC'),
			// 'mem_userid' => $param->sort('mem_userid', 'asc'),
			// 'mem_username' => $param->sort('mem_username', 'asc'),
			// 'mem_nickname' => $param->sort('mem_nickname', 'asc'),
			// 'mem_email' => $param->sort('mem_email', 'asc'),
			// 'mem_point' => $param->sort('mem_point', 'asc'),
			// 'mem_register_datetime' => $param->sort('mem_register_datetime', 'asc'),
			// 'mem_lastlogin_datetime' => $param->sort('mem_lastlogin_datetime', 'asc'),
			// 'mem_level' => $param->sort('mem_level', 'asc'),
		);
		$findex = $this->input->get('findex', null, 'accept_date');
		$forder = $this->input->get('forder', null, 'DESC');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$where = $where_or = $like = $where_in = array();
		if ($this->input->get('fdate')) {
			$where['ctr_date >='] = $this->input->get('fdate');
		}
		if ($this->input->get('edate')) {
			$where['ctr_date <='] = $this->input->get('edate');
		}
		if ($this->input->get('ctr_status')) {
			if ($this->input->get('ctr_status') == '진행중') {
				$where_in['ctr_status'] = array('담당배정','1차상담','2차상담','방문답사');
			} else if ($this->input->get('ctr_status') == '계약완료') {
				$findex = $this->input->get('findex', null, 'ctr_date');
				$where['ctr_status'] = $this->input->get('ctr_status');
			} else {
				$where['ctr_status'] = $this->input->get('ctr_status');
			}
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

		$list = $this->Mp_Contract_model->get_contract_list($per_page, ($page-1) * $per_page, $where, $where_or, $like, $where_in, null, $findex, $forder, $sfield, $skeyword);
		$view['view']['data'] = $list;
		$view['view']['data']['page'] = $page;
		$view['view']['data']['per_page'] = $per_page;

		$config = array();
		$config['base_url'] = '/contract';
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


	public function download_excel()
	{
		// 준비하기
		$this->load->model("Mp_Contract_model");

		// 준비하기
		$this->load->model("Mp_Contract_model");
		$this->load->model("Member_model");

		$per_page = admin_listnum();
		$page = max(1, $this->input->get('page'));
		$sfield = '';
		$skeyword = '';

		// 영업자 가져오기
		$seller_list = $this->Member_model->get(
			$primary_value = '', $select = '', array('mem_teamname' => '영업부'), $limit = '', $offset = 0, $findex = '', $forder = ''
		);
		$view['view']['seller_list'] = $seller_list;

		// 장착자 가져오기
		$installer_list = $this->Member_model->get(
			$primary_value = '', $select = '', array('mem_teamname' => '장착부'), $limit = '', $offset = 0, $findex = '', $forder = ''
		);
		$view['view']['installer_list'] = $installer_list;


		$where = $where_or = $like = $unreceived_status_list = array();
		$unreceived_status_list['ctr_status'] = array('신규접수신청','이전접수신청','A/S접수신청');
		if ($this->input->post('fdate')) {
			$where['accept_date >='] = $this->input->post('fdate');
		}
		if ($this->input->post('edate')) {
			$where['accept_date <='] = $this->input->post('edate');
		}
		if ($this->input->post('ctr_status')) {
			$where['ctr_status'] = $this->input->post('ctr_status');
		}
		if ($this->input->post('vhc_item_type')) {
			$like['vhc_item_type'] = $this->input->post('vhc_item_type');
		}
		if ($this->input->post('ctr_seller')) {
			$where['ctr_seller'] = $this->input->post('ctr_seller');
		}
		if ($this->input->post('ctr_installer')) {
			$where['ctr_installer'] = $this->input->post('ctr_installer');
		}
		if ($this->input->post('vhc_num')) {
			$like['vhc_num'] = $this->input->post('vhc_num');
		}
		if ($this->input->post('driver_nm')) {
			$like['driver_nm'] = $this->input->post('driver_nm');
		}

		$unreceived_list = $this->Mp_Contract_model->get_contract_list('', '', $where, $where_or, $like, $unreceived_status_list, '');
		$list = $this->Mp_Contract_model->get_contract_list('', '', $where, $where_or, $like, '', $unreceived_status_list);
		$list = array_merge($list, $unreceived_list);


		$writer = WriterEntityFactory::createXLSXWriter();
		$writer->openToBrowser("contract_list.xlsx"); // stream data directly to the browser
		$header = array('번호','접수일','영업자','장착자','소유주','차주지역','차량번호','장치종류','상태','설치일자','서류발송');
		$writer->addRow(WriterEntityFactory::createRowFromArray($header));

		foreach (element('list', $list) as $i => $row) {
			$item = array(
				$i + 1,
				element('accept_date', $row),
				element('seller_name', $row),
				element('installer_name', $row),
				element('owner_nm', $row),
				implode(" ", array(element('owner_region', $row), element('owner_detail_region', $row))),
				element('vhc_num', $row),
				element('vhc_item_type', $row),
				element('ctr_status', $row),
				element('ins_date', $row),
				element('ins_date', $row),
			);
			$writer->addRow(WriterEntityFactory::createRowFromArray($item));
		}

		$writer->close();
	}


	public function view_doc($ctr_no, $ctr_img)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Vt_Install_model");

		// 가져오기
		$install_info = $this->Vt_Install_model->get_install_info($ctr_no);
		$view['view']['data'] = $install_info;
		$view['view']['ctr_no'] = $ctr_no;
		$view['view']['ctr_img'] = $ctr_img;
		$view['view']['ctr_img_file'] = element($ctr_img, $install_info);

		// 보여주기
		$view['layout'] = $this->_layout("view_doc", "layout_popup");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function view_docs($ctr_no)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_Contract_model");

		$view['view']['ctr_no'] = $ctr_no;
		$view['view']['data'] = $this->Mp_Contract_model->get_contract($ctr_no);

		$view['layout'] = $this->_layout("view_docs", "layout_popup");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function download_doc($ctr_no)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_Contract_model");

		// 계약내용 가져오기
		$contract = $this->Mp_Contract_model->get_contract($ctr_no);
	
		$template_filepath = APPPATH.'doc_form/excel_form.xlsx';
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($template_filepath);

		$sheet = $spreadsheet->getSheet(0);
		$sheet->setCellValue('F3',  element('vhc_num', $contract));
		$sheet->setCellValue('F4',  element('vhc_type', $contract));
		$sheet->setCellValue('F5',  element('vhc_vin', $contract));
		$sheet->setCellValue('F6',  element('driver_phone', $contract));
		$sheet->setCellValue('F7',  element('driver_nm', $contract));
		$sheet->setCellValue('F8',  element('owner_nm', $contract));
		$sheet->setCellValue('F9',  combine_address(element('driver_addr1', $contract), element('driver_addr2', $contract), element('driver_addr3', $contract)));
		$sheet->setCellValue('F10', element('vhc_length', $contract));
		$sheet->setCellValue('F11', element('vhc_weight', $contract));
		$sheet->setCellValue('F12', element('vhc_sido', $contract));

		$sheet->setCellValue('F15', element('serialnum', $contract));
		$sheet->setCellValue('F16', element('ins_date', $contract));

		$excel_filename = basename($template_filepath);
		$excel_tempfile = tempnam(sys_get_temp_dir(), $excel_filename);

		$writer = new Xlsx($spreadsheet);
		$writer->save($excel_tempfile);

		$contract = $this->Mp_Contract_model->get_contract($ctr_no);

		$temp_zip_filepath = tempnam(sys_get_temp_dir(), 'download_docs_');
		$Zip = new ZipArchive();
		$Zip->open($temp_zip_filepath,  ZipArchive::CREATE);
		$Zip->addFile($excel_tempfile, $excel_filename);

		$chk = $this->input->post('chk') ? $this->input->post('chk') : array();
		foreach ($chk as $i => $fld_name) {
			$filename = element($fld_name, $contract);
			$filepath = FCPATH."uploads/contract/{$ctr_no}/{$filename}";
			if(file_exists($filepath)) {
				$Zip->addFile($filepath, $filename);
			}
		}
		$Zip->close();

		$download_filename = "contract_docs_{$ctr_no}_".time().".zip";
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename={$download_filename}");
		header("Content-Length: " . filesize($temp_zip_filepath));
		readfile($temp_zip_filepath);

		unlink($excel_tempfile);
		unlink($temp_zip_filepath);
	}


	public function view($ctr_no=null)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_Code_model");
		$this->load->model("Mp_park_model");
		$this->load->model("Mp_product_model");
		$this->load->model("Mp_payment_model");
		$this->load->model("Mp_history_model");
		$this->load->model("Member_model");
		$this->load->model("Mp_sms_history_model");

		$teamname = $this->member->item('mem_teamname');

		// 불러오기
		$view['view']['mode'] = "view";
		$view['view']['payment_list'] = $this->Mp_payment_model->get_payment_list($ctr_no);
		$view['view']['history_list'] = $this->Mp_history_model->get_history_list_by_ctr_no($ctr_no);
		$where = array(
			"mem_teamname" => "영업담당",
		);
		$view['view']['seller_list'] = $this->Member_model->get_member_list('', '', $where);
		$view['view']['sms_list'] = $this->Mp_sms_history_model->get_sms_list_by_ctr_no($ctr_no);

		$park_list = $this->Mp_park_model->get_select_park_list();
		foreach (element('list', $park_list) as $i => $row) {
			$view['view']['park_list'][element('park_type_cd', $row)][] = $row;
		}

		// 수목장 '경기전체' 버튼 목록. 일부 수목장 제외 (공감수목장:8, 소풍수목장:22, 신세계수목장:24)
		$view['view']['sms_button_list']['NB'] = array(10,15,21,25,26,29,40,45,51);

		$contract = $this->Mp_Contract_model->get_contract($ctr_no);
		if ($ctr_no != null && !$contract) {
			alert("계약번호가 유효하지 않습니다.");
		}

		if (element('accept_date', $contract) == '') {
			$contract['accept_date'] = date("Y-m-d H:i:s");
		}

		$view['view']['data'] = $contract;


		// 보여주기
		$view['layout'] = $this->_layout("view");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function write($ctr_no = null)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_Code_model");
		$this->load->model("Mp_park_model");
		$this->load->model("Mp_product_model");
		$this->load->model("Mp_payment_model");
		$this->load->model("Mp_history_model");
		$this->load->model("Mp_sms_history_model");
		$this->load->model("Member_model");

		$teamname = $this->member->item('mem_teamname');

		// 불러오기
		$contract = $this->Mp_Contract_model->get_contract($ctr_no);
		if ($ctr_no != null && !$contract) {
			alert("계약번호가 유효하지 않습니다.");
		}

		if (element('accept_date', $contract) == '') {
			$contract['accept_date'] = date("Y-m-d H:i");
		}
		// if (element('ctr_date', $contract) == '') {
		// 	$contract['ctr_date'] = date("Y-m-d");
		// }
		// if (element('tombmig_date', $contract) == '') {
		// 	$contract['tombmig_date'] = date("Y-m-d");
		// }
		if (element('ctr_mem_id', $contract) == '') {
			$contract['ctr_mem_id'] = $this->member->is_member();
		}

		$view['view']['data'] = $contract;

		$view['view']['mode'] = "write";
		// $view['view']['select_ctr_status_list'] = $this->Mp_Code_model->get_list_by_type('ctr_status');
		$view['view']['select_ctr_status_list'] = $this->Mp_Contract_model->get_ctr_status_list();
		$view['view']['payment_list'] = $this->Mp_payment_model->get_payment_list($ctr_no);
		$view['view']['history_list'] = $this->Mp_history_model->get_history_list_by_ctr_no($ctr_no);
		$park_list = $this->Mp_park_model->get_select_park_list();
		foreach (element('list', $park_list) as $i => $row) {
			$view['view']['park_list'][element('park_type_cd', $row)][] = $row;
		}

		$view['view']['sms_list'] = $this->Mp_sms_history_model->get_sms_list_by_ctr_no($ctr_no);
		// $view['view']['sms_button_list'] = $this->Mp_park_model->get_park_list_by_region('경기');

		// 수목장 '경기전체' 버튼 목록. 일부 수목장 제외 (공감수목장:8, 소풍수목장:22, 신세계수목장:24)
		$view['view']['sms_button_list']['NB'] = array(10,15,21,25,26,29,40,45,51);

		// debug_var($view['view']['sms_list']);

		if ($this->member->item("mem_level") <= 20) {
			$view['view']['seller_list'] = $this->Member_model->get_member_list('', '', array(
				"mem_id" => $this->member->is_member(),
			));
			
		} else {
			$view['view']['seller_list'] = $this->Member_model->get_member_list('', '', array(
				"mem_teamname" => "영업담당",
			));
		}

		// 보여주기
		$view['layout'] = $this->_layout("write");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function write_contract()
	{
		// 준비하기
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_history_model");
		$this->load->model("Mp_payment_model");

		// 저장하기
		if ($this->input->post()) {

			$now    = cdate("Y-m-d H:i:s");
			$ctr_no = $this->input->post('ctr_no');

			$fld = $this->input->post();
			if (!element('accept_date', $fld)) {
				$fld['accept_date'] = NULL;
			} else {
				$fld['accept_date'] = $fld['accept_date'].' '.$fld['accept_time'];
			}
			unset($fld['accept_time']);
			if (!element('ctr_date', $fld)) { $fld['ctr_date'] = NULL; }
			if (!element('tombmig_date', $fld)) { $fld['tombmig_date'] = NULL; }
			// if (element('cust_phone', $fld)) { $fld['cust_phone'] = get_phone(element('cust_phone', $fld)); }
			if (element('cust_phone', $fld)) { $fld['cust_phone'] = str_replace('-', '', element('cust_phone', $fld)); }
			if (element('ctr_prod_price', $fld)) { $fld['ctr_prod_price'] = get_price(element('ctr_prod_price', $fld),'만'); }
			if (element('ctr_discount_amount', $fld)) { $fld['ctr_discount_amount'] = get_price(element('ctr_discount_amount', $fld),'만'); }
			if (element('ctr_amount', $fld)) { $fld['ctr_amount'] = get_price(element('ctr_amount', $fld),'만'); }
			if (element('tombmig_estimate_price', $fld)) { $fld['tombmig_estimate_price'] = get_price(element('tombmig_estimate_price', $fld),'만'); }

			$contract = $this->Mp_Contract_model->get_contract($ctr_no);
			$process_status = array('담당배정','1차상담','2차상담','방문답사');
			if (element('ctr_date', $fld) && in_array(element('ctr_status', $fld), $process_status)) {
				if (0 < $ctr_no) { // 수정
					if (element('ctr_date', $contract) == '') {
						$fld['ctr_status'] = '계약완료';
					}
				} else { // 신규
					$fld['ctr_status'] = '계약완료';
				}
			}

			if (0 < $ctr_no) { // 수정
				$fld['mod_time'] = $now;
				if (element('ctr_remark', $contract) != element('ctr_remark', $fld)) {
					if (!element('ctr_remark_reg_time', $contract)) {
						$fld['ctr_remark_reg_time'] = $now;
						$fld['ctr_remark_reg_mem_id'] = $this->member->is_member();
					}
					$fld['ctr_remark_mod_time'] = $now;
					$fld['ctr_remark_mod_mem_id'] = $this->member->is_member();
				}
				$update_result = $this->Mp_Contract_model->update_contract($ctr_no, $fld);
				$ctr_no = ($update_result) ? $ctr_no : null;

				alert("계약내용이 수정되었습니다.", "/contract/write/{$ctr_no}");
				// redirect('/contract');
			} else { // 신규
				unset($fld['ctr_no']);
				$fld['reg_time'] = $now;
				if (element('ctr_remark', $fld)) {
					$fld['ctr_remark_reg_time'] = $now;
					$fld['ctr_remark_reg_mem_id'] = $this->member->is_member();
				}
				$ctr_no = $this->Mp_Contract_model->insert($fld);

				$this->Mp_history_model->save_history($ctr_no);
				$this->Mp_history_model->clear_history();

				$this->Mp_payment_model->save_payment($ctr_no);
				$this->Mp_payment_model->clear_payment();

				alert("계약내용이 추가되었습니다.", "/contract/write/{$ctr_no}");
				// redirect('/contract');
			}
		}

		// redirect("/contract/write/".$ctr_no);
	}


	public function cust_exists()
	{
		$this->load->model("Mp_Contract_model");

		$term = $this->input->get('term');

		if (0 < strlen($term)) {
			$cust_list = $this->Mp_Contract_model->get_contract_by_cust_name($term);

			$result = array(
				"results" => array(),
				"pagination" => array(
					"more" => true
				),
			);
			if ($cust_list) {
				foreach ($cust_list as $i => $row) {
					$result['results'][] = array(
						"id" => element('cust_name', $row),
						"text" => element('cust_name', $row)."\n(".get_phone(element('cust_phone', $row)).")"
					);
				}
			}
		} else {
			$result = NULL;
		}

		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));
	}


	public function del()
	{
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_settlement_model");
		$this->load->model("Mp_history_model");
		$this->load->model("Mp_payment_model");
		$this->load->model("Mp_payroll_model");

		$ctr_no_list = $this->input->post('chk');
		// debug_var($this->input->post(), $mem_id_list);

		$result = $this->Mp_Contract_model->del_contract($ctr_no_list);
		$this->Mp_history_model->clear_history($ctr_no_list);
		$this->Mp_payment_model->clear_payment($ctr_no_list);
		$this->Mp_settlement_model->delete_settlement($ctr_no_list);
		$this->Mp_payroll_model->delete_payroll($ctr_no_list);

		if ($result) {
			// alert("선택한 계약내용이 모두 삭제 되었습니다.", "/contract");
			redirect('/contract');
		} else {
			log_message("ERROR", __FILE__.":".__LINE__." ".json_encode($this->db->error()));
			alert("오류가 발생하여 선택한 계약내용이 삭제되지 않았습니다. 관리자에게 문의해주십시오.");
		}

	}

	public function send_sms()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->library('kakaobizm');
		$this->load->model("Mp_sms_history_model");
		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_park_model");
		$this->load->model("Mp_Code_model");

		$now         = date("Y-m-d H:i:s");
		$ctr_no      = $this->input->post('ctr_no');
		$cust_name   = $this->input->post('cust_name');
		$cust_phone  = $this->input->post('cust_phone');
		$ctr_mem_id  = $this->input->post('ctr_mem_id');
		$sms_content = $this->input->post('sms_content');
		$sms_recv_CH = $this->input->post('sms_recv_CH');
		$sms_recv_NB = $this->input->post('sms_recv_NB');
		$sms_recv_CT = $this->input->post('sms_recv_CT');

		$sms_recv = array_merge(
			$sms_recv_CH ? $sms_recv_CH : array(),
			$sms_recv_NB ? $sms_recv_NB : array(),
			$sms_recv_CT ? $sms_recv_CT : array()
		);

		$hq       = $this->Member_model->get_by_userid('imissu');
		$salesman = $this->Member_model->get_by_memid($ctr_mem_id);

		$contract = $this->Mp_Contract_model->get_contract2($ctr_no);
		// $park_list = $this->Mp_park_model->get_park_list('', '', $sms_recv);
		$park_list = $this->Mp_park_model->get_sms_receiver_list($sms_recv);
		// debug_var($park_list);

		// history 테이블 업데이트
		$fld = array(
			"sms_ctr_no"       => $ctr_no,
			"sms_mem_id"       => $ctr_mem_id,
			"sms_mem_username" => element('mem_username', $salesman),
			"sms_mem_phone"    => element('mem_phone', $salesman),
			"sms_cust_name"    => $cust_name,
			"sms_cust_phone"   => $cust_phone,
			"sms_datetime"     => $now,
		);
		$sms_no = $this->Mp_sms_history_model->insert_sms($fld);

		if ($sms_no) {
			// 시설 본부장에서 문자 전송
			$park_name_list = array();
			foreach ($park_list as $i => $row) {

				$sms_msg  = element('cust_name', $contract).PHP_EOL;
				if (element('park_manager_phone_masked', $row) == 'Y') {
					$sms_msg .= get_phone_by_masked(element('cust_phone', $contract)).PHP_EOL;
				} else {
					$sms_msg .= get_phone(element('cust_phone', $contract)).PHP_EOL;
				}
				$sms_msg .= PHP_EOL;
				$sms_msg .= element('park_real_name', $row).PHP_EOL;
				if (element('park_type_cd', $row) == 'CH') {
					$sms_msg .= "부부단".PHP_EOL;
				} else if (element('park_type_cd', $row) == 'NB') {
					$sms_msg .= "부부목".PHP_EOL;
				} else if (element('park_type_cd', $row) == 'CT') {
				}
				$sms_msg .= PHP_EOL;
				$sms_msg .= $sms_content.PHP_EOL;
				$sms_msg .= PHP_EOL;
				$sms_msg .= "그리운그대".PHP_EOL;
				$sms_msg .= "동반직원 ".element('mem_username', $contract);

				// 시설 본부장
				$park_manager_phone_list = explode(';', element('park_manager_phone', $row));
				foreach ($park_manager_phone_list as $park_manager_phone) {
					$this->kakaobizm->send_sms($park_manager_phone, $sms_msg);
				}

				// recv_list 업데이트
				$fld = array(
					"sms_no"                => $sms_no,
					"sms_recv_park_no"      => element('park_no', $row),
					"sms_recv_park_type_cd" => element('park_type_cd', $row),
					"sms_recv_park_name"    => element('park_real_name', $row),
					"sms_recv_name"         => element('park_manager_name', $row),
					"sms_recv_phone"        => element('park_manager_phone', $row),
					"sms_recv_content"      => $sms_msg,
				);
				$this->Mp_sms_history_model->insert_recv($fld);

				$park_name_list[] = element('park_real_name', $row);
			}

			// history 테이블 업데이트
			$sms_msg  = element('cust_name', $contract).PHP_EOL;
			// $sms_msg .= str_replace('-', '', element('cust_phone', $contract)).PHP_EOL;
			$sms_msg .= get_phone(element('cust_phone', $contract)).PHP_EOL;
			$sms_msg .= PHP_EOL;
			$sms_msg .= implode(PHP_EOL, $park_name_list).PHP_EOL;
			$sms_msg .= PHP_EOL;
			$sms_msg .= $sms_content.PHP_EOL;
			$sms_msg .= PHP_EOL;
			$sms_msg .= "그리운그대".PHP_EOL;
			$sms_msg .= "동반직원 ".element('mem_username', $contract);

			// 본사 문자 전송
			$hq = $this->Member_model->get_by_userid('imissu');
			$this->kakaobizm->send_sms(element('mem_phone', $hq), $sms_msg);

			$salesman = $this->Member_model->get_by_memid($ctr_mem_id);
			if (!in_array(element('mem_userid', $salesman), array('imissu','jkisoon'))) { // 본사인 경우 제외
				// 영업담당 문자 전송
				$this->kakaobizm->send_sms(element('mem_phone', $salesman), $sms_msg);
			}

			// 개발자 테스트
			// $this->kakaobizm->send_sms("01034340940", $sms_msg);

			$this->Mp_sms_history_model->update_sms($sms_no, array(
				"sms_hq_content" => $sms_msg,
			));

			alert("문자 발송이 완료되었습니다.", "/contract/write/{$ctr_no}");
		}

		redirect("/contract/write/{$ctr_no}");
	}

	// public function sms_history($ctr_no)
	// {
	// 	$this->load->model("Mp_sms_history_model");

	// 	$view['view'] = array();
	// 	$view['view']['sms_list'] = $this->Mp_sms_history_model->get_sms_list_by_ctr_no($ctr_no);

	// 	$this->load->view('contract/bootstrap/sms_history', $view);
	// }

	public function prod_list()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_product_model");
		$this->load->model("Mp_Contract_model");

		$ctr_no = $this->input->post('ctr_no');
		$park_no = $this->input->post('park_no');
		$wish_idx = $this->input->post('wish_idx');

		$view['view'] = array();

		$contract = $this->Mp_Contract_model->get_contract($ctr_no);
		switch ($wish_idx) {
			case 1: 
				$view['view']['selected_prod_cd'] = element('wish_prod1', $contract);
			break;
			case 2: 
				$view['view']['selected_prod_cd'] = element('wish_prod2', $contract);
			break;
			case 3: 
				$view['view']['selected_prod_cd'] = element('wish_prod3', $contract);
			break;
			default:
				$view['view']['selected_prod_cd'] = element('ctr_prod_cd', $contract);
			break;
		}
		$view['view']['product_list'] = $this->Mp_product_model->get_product_list($park_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);
		// debug_var($view['view']['product_list']);

		$this->load->view('contract/bootstrap/wish_prod', $view);
	}

	public function exists()
	{
			if (!$this->input->is_ajax_request()) {
					alert("접근 권한이 없습니다.");
			}

			$this->load->model("Mp_Contract_model");

			$cust_name = $this->input->post('cust_name');
			$cust_phone = get_phone($this->input->post('cust_phone'), false);

			$view['view'] = array();

			$contract = $this->Mp_Contract_model->get_contract_by_cust($cust_name, $cust_phone);

			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($contract));
	}

	public function update_history()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_history_model");

		$history_no      = $this->input->post('history_no');
		$ctr_no      = $this->input->post('ctr_no');
		$history_date    = $this->input->post('history_date');
		$history_type  = $this->input->post('history_type');
		$history_content = $this->input->post('history_content');

		$fld = array(
			"ctr_no"          => $ctr_no,
			"history_date"    => $history_date,
			"history_type"    => $history_type,
			"history_content" => $history_content,
		);

		if ($history_no) {
			$fld['history_no'] = $history_no;
			$this->Mp_history_model->update_history($history_no, $fld);
		} else {
			$this->Mp_history_model->insert_history($fld);
		}

		$view['view'] = array();
		$view['view']['mode'] = 'write';
		$view['view']['history_list'] = $this->Mp_history_model->get_history_list_by_ctr_no($ctr_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);

		$this->load->view('contract/bootstrap/history_list', $view);
	}

	public function del_history()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_history_model");

		$history_no = $this->input->post('history_no');
		$ctr_no     = $this->input->post('ctr_no');

		// $this->Mp_history_model->clear_history();
		$this->Mp_history_model->delete_history($history_no);

		$view['view'] = array();
		$view['view']['mode'] = "write";
		$view['view']['history_list'] = $this->Mp_history_model->get_history_list_by_ctr_no($ctr_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);

		$this->load->view('contract/bootstrap/history_list', $view);
	}

	public function update_payment()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_payment_model");
		$this->load->model("Mp_Contract_model");

		$payment_no      = $this->input->post('payment_no');
		$ctr_no          = $this->input->post('ctr_no');
		$payment_date    = $this->input->post('payment_date');
		$payment_type    = $this->input->post('payment_type');
		$payment_method  = $this->input->post('payment_method');
		$payment_price   = $this->input->post('payment_price');
		$payment_content = $this->input->post('payment_content');

		$fld = array(
			"ctr_no"          => $ctr_no,
			"payment_date"    => $payment_date,
			"payment_type"    => $payment_type,
			"payment_method"  => $payment_method,
			"payment_price"   => get_price($payment_price, '만'),
			"payment_content" => $payment_content,
		);

		if ($payment_no) {
			$fld['payment_no'] = $payment_no;
			$this->Mp_payment_model->update_payment($payment_no, $fld);
		} else {
			$this->Mp_payment_model->insert_payment($fld);
		}

		// 잔금현황 업데이트 후 최종 분양금액과 일치할 경우 잔금완료 처리
		$contract = $this->Mp_Contract_model->get_contract($ctr_no);
		$payment = $this->Mp_payment_model->sum_amount($ctr_no);
		if (element('ctr_amount', $contract) == element('sum_payment_price', $payment)) {
			$fld = array(
				'ctr_status' => '잔금완료'
			);
			$update_result = $this->Mp_Contract_model->update_contract($ctr_no, $fld);
			if (!$update_result) {
			}
		}

		$view['view'] = array();
		$view['view']['mode'] = "write";
		$view['view']['payment_list'] = $this->Mp_payment_model->get_payment_list($ctr_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);

		$this->load->view('contract/bootstrap/payment_list', $view);
	}

	public function del_payment()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_payment_model");

		$payment_no = $this->input->post('payment_no');
		$ctr_no     = $this->input->post('ctr_no');

		// $this->Mp_payment_model->clear_payment();
		$this->Mp_payment_model->delete_payment($payment_no);

		$view['view'] = array();
		$view['view']['mode'] = "write";
		$view['view']['payment_list'] = $this->Mp_payment_model->get_payment_list($ctr_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);

		$this->load->view('contract/bootstrap/payment_list', $view);
	}

	public function sms_form()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_Contract_model");
		$this->load->model("Mp_park_model");

		$ctr_no      = $this->input->post('ctr_no');
		$sms_recv_CH = $this->input->post('sms_recv_CH');
		$sms_recv_NB = $this->input->post('sms_recv_NB');

		$sms_recv = array_merge(
			$sms_recv_CH ? $sms_recv_CH : array(),
			$sms_recv_NB ? $sms_recv_NB : array()
		);
		// debug_var($sms_recv);

		$view['view'] = array();
		$view['view']['mode'] = "write";
		$view['view']['data'] = $this->Mp_Contract_model->get_contract($ctr_no);

		// $view['view']['sms_recv_list'] = $this->Mp_park_model->get_sms_receiver_list($sms_recv);
		$view['view']['park_list'] = $this->Mp_park_model->get_select_park_list();

		$this->load->view('contract/bootstrap/sms_form', $view);
	}

	public function load_member($ctr_no=null)
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_Contract_model");
		$this->load->model("Member_group_model");
		$this->load->model("Member_group_member_model");

		$s1 = $this->input->get('s1') ? $this->input->get('s1') : NULL;

		// 불러오기
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		$like = array();
		if ($s1) {
			$like["mem_username like '%{$s1}%'"] = NULL;
		}
		$member_result = $this->Mp_Contract_model->get_user_list($limit = '', $offset = '', $s1);
		$list_num = $member_result['total_rows'] - ($page - 1) * $per_page;
		// debug_last_query();

		if (element('list', $member_result)) {
			foreach (element('list', $member_result) as $key => $val) {

				$member_result['list'][$key]['member_group_member'] = $this->Mp_Contract_model->get_member_group_member(element('mem_id', $val));
				// debug_last_query();
				$mgroup = array();
				if ($member_result['list'][$key]['member_group_member']) {
					foreach ($member_result['list'][$key]['member_group_member'] as $mk => $mv) {
						if (element('mgr_id', $mv)) {
							$mgroup[] = $this->Mp_Contract_model->get_member_item(element('mgr_id', $mv));
							// debug_var($mgroup);
						}
					}
				}
				$member_result['list'][$key]['member_group'] = '';
				$member_result['list'][$key]['etas_id'] = '';
				$member_result['list'][$key]['etas_pw'] = '';
				if ($mgroup) {
					foreach ($mgroup as $mk => $mv) {
						if ($member_result['list'][$key]['member_group']) {
							$member_result['list'][$key]['member_group'] .= ', ';
						}
						$member_result['list'][$key]['member_group'] .= element('mgr_title', $mv);
						$member_result['list'][$key]['etas_id'] .= element('mgr_etas_id', $mv);
						$member_result['list'][$key]['etas_pw'] .= element('mgr_etas_pw', $mv);
					}
				}
				$member_result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_username', $val),
					element('mem_icon', $val)
				);

				$extra_vars = $this->member->get_all_extras(element('mem_id', $val));
				if ($extra_vars) {
					$member_result['list'][$key] = array_merge($member_result['list'][$key], $extra_vars);
				}

				$member_result['list'][$key]['num'] = $list_num--;
			}
		}

		// debug_var($member_result);

		$view['view']['data'] = $member_result;

		// 보여주기
		$view['layout'] = $this->_layout("load_member", "layout_popup");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	private function _layout($skin, $layout='layout', $path="contract")
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
