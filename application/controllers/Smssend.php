<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Smssend class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>SMS 설정>문자보내기 controller 입니다.
 */
class Smssend extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'smssend';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Sms_member', 'Sms_member_group', 'Sms_favorite');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = '';

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
		$this->load->library(array('pagination', 'querystring', 'smslib'));
	}


	/**
	 * SMS 설정>문자보내기 페이지입니다
	 */
	public function index()
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_smssend_log_model");
		$this->load->model("Mp_smssend_recv_model");

		$per_page = admin_listnum();
		$page = max(1, $this->input->get('page'));
		$sfield = '';
		$skeyword = '';

		// 불러오기
		$like = array();
		$where = array();
		if ($this->input->get('fdate')) {
			$where['sml_datetime >='] = $this->input->get('fdate')." 00:00:00";
		}
		if ($this->input->get('edate')) {
			$where['sml_datetime <='] = $this->input->get('edate')." 23:59:59";
		}
		if ($this->input->get('stx')) {
			$like['sml_mem_username'] = $this->input->get('stx');
			$like['sml_mem_phone']    = $this->input->get('stx');
			$like['sml_sender_phone']    = $this->input->get('stx');
			$like['sml_content']  = $this->input->get('stx');
		}

		$view['view'] = array();
		// $view['view']['sms_list'] = $this->Mp_smssend_log_model->get_sms_list(($page-1) * $per_page, $per_page, $where, $like);

		// $this->load->view('contract/bootstrap/sms_history', $view);

		$sms_list = $this->Mp_smssend_log_model->get_smssend_log(($page-1) * $per_page, $per_page, $where, $like);
		foreach (element('list', $sms_list) as $i => $row) {
			$sml_no = element("sml_no", $row);
			$recv_list = $this->Mp_smssend_recv_model->get_sms_recv_list($sml_no, 0, 10); // 우선 10명까지만 보여주고, 그 이상은 더보기로 처리
			$sms_list['list'][$i]['recv_list'] = $recv_list;
		}
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


	/**
	 * SMS 설정>문자보내기 페이지입니다
	 */
	public function form()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_smssend_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 모델을 로딩합니다.
		$this->load->model('Mp_Contract_model');
		
		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'sfa_content',
				'label' => '메세지 내용',
				'rules' => 'trim|required',
			),
			// array(
			// 	'field' => 'send_list',
			// 	'label' => '받는이 목록',
			// 	'rules' => 'trim|required',
			// ),
			array(
				'field' => 'ssc_send_phone',
				'label' => '회신번호',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'phone_booking',
				'label' => '예약',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_year',
				'label' => '예약 (년)',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_month',
				'label' => '예약 (월)',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_day',
				'label' => '예약 (일)',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_hour',
				'label' => '예약 (시)',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_minute',
				'label' => '예약 (분)',
				'rules' => 'trim',
			),
		);
		$this->form_validation->set_rules($config);

		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($this->form_validation->run() === false) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$list = array();
			$phones = array();

			// $send_list = explode('/', $this->input->post('send_list'));
		
			$send_list = $this->input->post('phone_list', null, array());
			
			//그룹 카테고리 따로 나누기
			foreach($send_list as $key => $find_group){
				if(strpos($find_group,"g,그룹 전송")!== false){	
					$group_list[] = $find_group;
					unset($send_list[$key]);
				}
			}
			// debug_var($group_list);			
			foreach($group_list as $group_item){
				$topic = explode(':',$group_item)[1];
				$list = $this->Mp_Contract_model->get_cust_group($topic);
			}
			
			$send_list = array_values($send_list); // 배열 재 정렬
			$chk_overlap = 1; // 중복번호를 체크함
			$overlap = 0;
			$duplicate_data = array();
			$duplicate_data['phone'] = array();
			$str_serialize = '';
			while ($row = array_shift($send_list)) {
				$item = explode(',', $row);

				for ($i =1, $max = count($item); $i <$max; $i++) {
					if ( ! trim($item[$i])) {
						continue;
					}

					$item[$i] = explode(':', $item[$i]);
					$phone = get_phone($item[$i][1], 0);
					$name = $item[$i][0];
					$group = $item[$i][2];
					if ($chk_overlap && array_overlap($phones, $phone)) {
						$overlap++;
						array_push($duplicate_data['phone'], $phone);
						continue;
					}
					array_push($list, array('cust_phone' => $phone, 'cust_name' => $name, 'cust_group'=> $group));
					array_push($phones, $phone);
				}
			}
			// debug_var($list);
			if ( count($duplicate_data['phone'])) { //중복된 번호가 있다면
				$duplicate_data['total'] = $overlap;
				$str_serialize = serialize($duplicate_data);
			}

			$mms_img_url = '';
			$updatephoto = '';
			$this->load->library('upload');
			// debug_var($_FILES);
			if (isset($_FILES) && isset($_FILES['ssc_send_image']) && isset($_FILES['ssc_send_image']['name']) && $_FILES['ssc_send_image']['name']) {
				$upload_path = config_item('uploads_dir') . '/mms/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}
				$upload_path .= cdate('Y') . '/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}
				$upload_path .= cdate('m') . '/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}

				$uploadconfig = array();
				$uploadconfig['upload_path'] = $upload_path;
				$uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
				$uploadconfig['max_size'] = '300'; // 300KB
				$uploadconfig['max_width'] = '1000';
				$uploadconfig['max_height'] = '2000';
				// $uploadconfig['max_size'] = '5120'; // 5MB
				// $uploadconfig['max_width'] = '5120';
				// $uploadconfig['max_height'] = '5120';
				$uploadconfig['encrypt_name'] = true;

				$this->upload->initialize($uploadconfig);

				if ($this->upload->do_upload('ssc_send_image')) {
					$img = $this->upload->data();
					$updatephoto = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
					$mms_img_url = site_url(config_item('uploads_dir').'/mms/'.$updatephoto);
				} else {
					$file_error = $this->upload->display_errors();
					alert($file_error);
				}
			}

			// $list
			$sender = $this->input->post('ssc_send_phone', null, '');
			$sfa_content = $this->input->post('sfa_content', null, '');

			// debug_var($list, $mms_img_url, $sender, $sfa_content);
			// exit;
			
			$this->load->model("Mp_smssend_log_model");
			$this->load->model("Mp_smssend_recv_model");
			$insertdata = array(
				"sml_mem_id" => $this->member->is_member(),
				"sml_mem_username" => $this->member->item('mem_username'),
				"sml_mem_phone" => $this->member->item('mem_phone'),
				"sml_sender_phone" => $sender,
				"sml_try" => count($list),
				"sml_request" => 0,
				"sml_success" => 0,
				"sml_content" => $sfa_content,
				"sml_imgfile" => ($updatephoto) ? $updatephoto : NULL,
				"sml_datetime" => cdate("Y-m-d H:i:s"),
			);
			$sml_no = $this->Mp_smssend_log_model->insert($insertdata);

			$sml_request = 0;
			$this->load->library('kakaobizm');
			foreach ($list as $receiver) {
				$insertdata = array(
					"sml_no" => $sml_no,
					"smr_cust_group" => element('cust_group', $receiver),
					"smr_cust_name" => element('cust_name', $receiver),
					"smr_cust_phone" => element('cust_phone', $receiver),
				);
				$smr_no = $this->Mp_smssend_recv_model->insert($insertdata);

				$results = $this->kakaobizm->send_mms($sender, element('cust_phone', $receiver), $sfa_content, $mms_img_url);
				// debug_var($results);
				if (is_array($results)) {
					foreach ($results as $phn => $result) {
						$updatedata = array(
							"smr_request" => json_encode(element('req', $result)),
							"smr_response" => json_encode(element('res', $result)),
						);
						if (element('code', element('res', $result)) == 'success') {
							// $updatedata['smr_success_datetime'] = cdate("Y-m-d H:i:s");
							$updatedata['smr_request_datetime'] = cdate("Y-m-d H:i:s");
							$sml_request++;
						} else {
							$updatedata['smr_fail_msg'] = element('message', element('res', $result)).' / '.element('originMessage', element('res', $result));
						}
						$this->Mp_smssend_recv_model->update($smr_no, $updatedata);
					}
				}
			}
			$updatedata = array(
				"sml_request" => $sml_request,
			);
			$this->Mp_smssend_log_model->update($sml_no, $updatedata);

			redirect("/smssend");
		}
		$list = array();
		// $ctr_no_list = $this->input->post('ctr_no_list', null, array());
		$ctr_no_list = $this->input->post('chk', null, array());
		$phn = $this->input->post('phn', null, '');
		if ($ctr_no_list) {
			$list = $this->Mp_Contract_model->get_cust_list('', '', $ctr_no_list);
		} else if ($phn) {
			$nm = $this->input->post('nm', null, '');
			$list = array(
				'total_rows' => 1,
				'list' => array(
					array(
						'cust_name' => $nm,
						'cust_phone' => $phn,
					)
				)
			);
		}
		$view['view']['data'] = $list;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		// 보여주기
		$view['layout'] = $this->_layout("form", 'layout');
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));

		header("Cache-Control: no cache");
	}


	public function ajax_cust_exists()
	{
		$this->load->model("Mp_Contract_model");

		$term = $this->input->get('term');
		if (0 < strlen($term)) {
			$cust_list = $this->Mp_Contract_model->get_contract_by_cust_name_or_phone($term);
			
			$result = array(
				"results" => array(),
				"pagination" => array(
					"more" => true
				),
			);
			if ($cust_list) {
				foreach ($cust_list as $i => $row) {
					$result['results'][] = array(
						// "id" => element('cust_name', $row),
						"id" => 'h,'.element('cust_name', $row).":".get_phone(element('cust_phone', $row)).":".element('cust_group', $row),
						"text" => element('cust_name', $row)." (".get_phone(element('cust_phone', $row)).")"
					);
				}
			}
		} else {
			$result = NULL;
		}

		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));
	}

	public function ajax_group_exists()
	{
		$this->load->model("Mp_Contract_model");

		$term = $this->input->get('term');

		if (0 < strlen($term)) {
			$cust_list = $this->Mp_Contract_model->get_contract_by_group($term);

			$result = array(
				"results" => array(),
				"pagination" => array(
					"more" => true
				),
			);
			if ($cust_list) {
				foreach ($cust_list as $i => $row) {
					$result['results'][] = array(
						"id" => "g,그룹 전송 :".element('cust_group', $row),
						"text" =>element('cust_group', $row)
					);
				}

			}
		} else {
			$result = NULL;
		}
		// dd($result);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));

	}


	public function ajax_recv_list()
	{
		$this->load->model("Mp_smssend_recv_model");

		$sml_no = $this->input->get('sml_no');

		$sms_recv_list = $this->Mp_smssend_recv_model->get_sms_recv_list($sml_no);

		$recv_total_rows = element("total_rows", $sms_recv_list);
		$recv_list = element("list", $sms_recv_list);

		$result = "<h5>총 {$recv_total_rows}명에게 전송되었습니다.</h5>".PHP_EOL;
		$result .= "<ol>".PHP_EOL;
		foreach ($recv_list as $i => $recv) {
			$smr_cust_name = element('smr_cust_name', $recv);
			$smr_cust_phone = get_phone(element('smr_cust_phone', $recv));
			$smr_fail_msg = element('smr_fail_msg', $recv);
			$result .= "<li>".PHP_EOL;
			if ($smr_fail_msg) {
				$result .= "<span class=\"text-danger\">{$smr_cust_name} ({$smr_cust_phone}): {$smr_fail_msg}</span>".PHP_EOL;
			} else {
				$result .= "<span class=\"text-success\">{$smr_cust_name} ({$smr_cust_phone})</span>".PHP_EOL;
			}
			$result .= "</li>".PHP_EOL;
		}
		$result .= "</ol>".PHP_EOL;

		$this->output->set_output($result);

		// $this->output->set_content_type('application/json');
		// $this->output->set_output(json_encode($result));
	}

	/**
	 * 자주보내는 문자관리
	 */
	public function ajax_sms_write_form()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_smssend_ajax_sms_write_form';
		$this->load->event($eventname);

		$this->output->set_content_type('application/json');

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->input->get('findex') ? $this->input->get('findex') : $this->Sms_favorite_model->primary_key;
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = 6;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->Sms_favorite_model->allow_search_field = array('sfa_id', 'sfa_title', 'sfa_content'); // 검색이 가능한 필드
		$this->Sms_favorite_model->search_field_equal = array('sfa_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->Sms_favorite_model->allow_order_field = array('sfa_id'); // 정렬이 가능한 필드
		$result = $this->Sms_favorite_model
			->get_admin_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);
		$list_text = '';
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$list_text .= '<div class="px170 pr20 pull-left">
					<div class="thumbnail" style="height:160px;"><textarea readonly="readonly" class="form-control" rows="6" id="sfa_contents_' . $val['sfa_id'] . '" onclick="emoticon_list.go(' . $val['sfa_id'] . ')">' . $val['sfa_content'] . '</textarea></div>
						<p>' . cut_str($val['sfa_title'], 10) . '</p>
				</div>';
			}
		}
		if (empty($list_text)) {
			$list_text .= '<div class="px200 pr20 pull-left">데이터가 없습니다.</div>';
		}

		$arr_ajax_msg['error'] = '';
		$arr_ajax_msg['list_text'] = $list_text;
		$arr_ajax_msg['page'] = $page;
		$arr_ajax_msg['total_count'] = $result['total_rows'];
		$arr_ajax_msg['total_page'] = ceil($result['total_rows']/$per_page);

		exit(json_encode($arr_ajax_msg));
	}

	public function ajax_sms_write_customer()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_smssend_ajax_sms_write_customer';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$per_page = admin_listnum();
		$page = max(1, $this->input->get('page'));
		$sfield = '';
		$skeyword = '';

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

		$this->load->model('Mp_Contract_model');
		$view['view']['select_ctr_status_list'] = $this->Mp_Contract_model->get_ctr_status_list();

		$view['view']['summary'] = $this->Mp_Contract_model->get_summary();

		$list = $this->Mp_Contract_model->get_contract_list($per_page, ($page-1) * $per_page, $where, $where_or, $like, $where_in);
		$view['view']['data'] = $list;
		$view['view']['data']['page'] = $page;
		$view['view']['data']['per_page'] = $per_page;

		$config = array();
		$config['base_url'] = '/smssend/write';
		$config['total_rows'] = element('total_rows', $list);
		$config['per_page'] = $per_page;
		$config['reuse_query_string'] = TRUE;
		$this->pagination->initialize($config);
		$view['view']['pagination'] = $this->pagination->create_links();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		// 보여주기
		$view['layout'] = $this->_layout("list", 'layout_popup', 'contract');
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	private function _layout($skin, $layout='layout', $path="smssend")
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
