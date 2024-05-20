<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH.'/third_party/vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * park class
 */

/**
 * 게시물 전체 검색시 필요한 controller 입니다.
 */
class park extends CB_Controller
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
		$this->load->model("Mp_park_model");

		$per_page = admin_listnum();
		$page = max(1, $this->input->get('page'));
		$sfield = '';
		$skeyword = '';

		// 불러오기
		$like = array();
		$where = array();
		if ($this->input->get('park_type_cd')) {
			$where['park_type_cd'] = $this->input->get('park_type_cd');
		}
		if ($this->input->get('park_use')) {
			$where['park_use'] = $this->input->get('park_use');
		}
		if ($this->input->get('stx')) {
			$like['park_no']            = $this->input->get('stx');
			$like['park_real_name']     = $this->input->get('stx');
			$like['park_name']          = $this->input->get('stx');
			$like['park_manager_name']  = $this->input->get('stx');
			$like['park_manager_phone'] = $this->input->get('stx');
			$like['park_addr1']         = $this->input->get('stx');
			$like['park_addr2']         = $this->input->get('stx');
			$like['park_addr3']         = $this->input->get('stx');
		}

		$park_list = $this->Mp_park_model->get_park_list(($page-1) * $per_page, $per_page, $where, $like);
		$view['view']['data'] = $park_list;
		$view['view']['page'] = $page;
		$view['view']['per_page'] = $per_page;

		$config = array();
		$config['base_url'] = '/park';
		$config['total_rows'] = element('total_rows', $park_list);
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


	// public function view($ctr_no)
	// {
	// 	$view = array();
	// 	$view['view'] = array();

	// 	// 준비하기
	// 	$this->load->model("Mp_Contract_model");
	// 	$this->load->model("Mp_payment_model");
	// 	$this->load->model("Mp_park_model");

	// 	if (!$ctr_no) {
	// 		alert("유효하지 않은 계약번호입니다.");
	// 	}

	// 	// 불러오기
	// 	$view['view']['mode'] = "view";
	// 	$view['view']['payment_list'] = $this->Mp_payment_model->get_payment_list($ctr_no);
	// 	$view['view']['product_list'] = $this->Mp_park_model->get_product_list($ctr_no);

	// 	$like = array();
	// 	$where = array();

	// 	$park = $this->Mp_park_model->get_park($ctr_no);
	// 	$view['view']['data'] = $park;

	// 	// 보여주기
	// 	$view['layout'] = $this->_layout("view");
	// 	$this->data = $view;
	// 	$this->layout = element('layout_skin_file', element('layout', $view));
	// 	$this->view = element('view_skin_file', element('layout', $view));
	// }


	public function write($park_no='')
	{
		$view = array();
		$view['view'] = array();

		// 준비하기
		$this->load->model("Mp_park_model");
		$this->load->model("Mp_product_model");

		// 불러오기
		$view['view']['mode'] = "write";
		$view['view']['product_list'] = $this->Mp_product_model->get_product_list($park_no);

		$like = array();
		$where = $where_or = array();

		$park = $this->Mp_park_model->get_park($park_no);
		if (element('park_use', $park) == '') {
			$park['park_use'] = 1;
		}
		$view['view']['data'] = $park;

		// 보여주기
		$view['layout'] = $this->_layout("write");
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function write_update()
	{
		// 준비하기
		$this->load->model("Mp_park_model");

		// 저장하기
		if ($this->input->post()) {

			$now       = cdate("Y-m-d H:i:s");
			$park_no = $this->input->post('park_no');

			$fld = $this->input->post();
			if ($fld['park_commission_rate']) {
				$fld['park_commission_rate'] /= 100;
			}

			if (0 < $park_no) { // 수정
				$fld['modtime'] = $now;
				$update_result = $this->Mp_park_model->update_park($park_no, $fld);
				$park_no = ($update_result) ? $park_no : null;

				// alert("정산내용이 수정되었습니다.", "/park");
			} else { // 신규
				unset($fld['park_no']);
				$fld['regtime'] = $now;
				$park_no = $this->Mp_park_model->insert_park($fld);

				// alert("정산내용이 추가되었습니다.", "/park");
			}
		}

		// redirect("/park");
		redirect("/park/write/".$park_no);
	}

	public function list_update()
	{
		$this->load->model("Mp_park_model");

		$result = true;
		$park_no_list = $this->input->post('chk');
		$order_list   = $this->input->post('odr');

		if (!$park_no_list) {
			alert("항목을 선택해주세요.");
		}

		if ($this->input->post('btn_submit') == 'del') {
			$result = $this->Mp_park_model->delete_park($park_no_list);
		} else if ($this->input->post('btn_submit') == 'edt' && $order_list) {
			foreach ($park_no_list as $i => $park_no) {
				$fld = array(
					"park_order" => element($park_no, $order_list),
				);
				$result = $this->Mp_park_model->update_park($park_no, $fld);
				if (!$result) {
					break;
				}
			}
		}


		if ($result) {
			// alert("선택한 계약내용이 모두 삭제 되었습니다.", "/contract");
			redirect('/park');
		} else {
			log_message("ERROR", __FILE__.":".__LINE__." ".json_encode($this->db->error()));
			alert("오류가 발생하여 선택한 공원이 삭제되지 않았습니다. 관리자에게 문의해주십시오.");
		}

	}

	public function update_product()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_product_model");

		$now = date("Y-m-d H:i:s");

		// 공통
		$park_prod_cd      = $this->input->post('park_prod_cd');
		$park_type_cd      = $this->input->post('park_type_cd');
		$park_no           = $this->input->post('park_no');
		$park_prod_soldout = $this->input->post('park_prod_soldout');
		$park_prod_name    = $this->input->post('park_prod_name');
		$park_prod_price   = str_replace( ',', '', $this->input->post('park_prod_price') );
		$park_prod_fee     = str_replace( ',', '', $this->input->post('park_prod_fee') );
		$anchisu           = $this->input->post('anchisu');

		// 납골당
		$dan_type          = $this->input->post('dan_type');
		$dan_floor         = $this->input->post('dan_floor');
		// 수목장
		$public_yn         = $this->input->post('public_yn');
		$tree_type         = $this->input->post('tree_type');

		if ($park_prod_cd) { // 수정

			$fld = array(
				"park_prod_name"    => $park_prod_name,
				"park_prod_price"   => get_price($park_prod_price, '만'),
				"park_prod_fee"     => get_price($park_prod_fee, '만'),
				"modtime"           => $now,
			);

			$this->Mp_product_model->update_product($park_prod_cd, $fld);

		} else { // 추가

			$new_park_prod_seq = $this->Mp_product_model->new_park_prod_seq($park_no);


			$park_dan_cd = 0;
			$park_no_36 = base_convert($park_no, 10, 36);
			if ($park_type_cd == 'CH') {
				$park_dan_cd  = $dan_floor;
				$park_prod_cd = get_park_prod_cd($park_type_cd, $park_no, $new_park_prod_seq, $dan_type, $dan_floor, $anchisu);
			} else if ($park_type_cd == 'NB') {
				$park_prod_cd = get_park_prod_cd($park_type_cd, $park_no, $new_park_prod_seq, $public_yn, $tree_type, $anchisu);
				$park_prod_cd = $park_type_cd.str_pad($park_no_36, 2, '0', STR_PAD_LEFT).sprintf("%02d", $new_park_prod_seq).str_pad($public_yn, 2, '0', STR_PAD_LEFT).str_pad($tree_type, 2, '0', STR_PAD_LEFT).sprintf("%02d", $anchisu);
			} else if ($park_type_cd == 'CT') {
				$park_prod_cd = get_park_prod_cd($park_type_cd, $park_no, $new_park_prod_seq, 0, 0, $anchisu);
			} else if ($park_type_cd == 'CF') {
				$park_prod_cd = get_park_prod_cd($park_type_cd, $park_no, $new_park_prod_seq, 0, 0, $anchisu);
			} else if ($park_type_cd == 'OV') {
				$park_prod_cd = get_park_prod_cd($park_type_cd, $park_no, $new_park_prod_seq, 0, 0, $anchisu);
			}
			$park_prod_cd = strtoupper($park_prod_cd);
			$fld = array(
				"park_prod_cd"      => $park_prod_cd,
				"park_type_cd"      => $park_type_cd,
				"park_no"           => $park_no,
				"park_prod_seq"     => $new_park_prod_seq,
				"park_dan_cd"       => $park_dan_cd,
				"park_prod_name"    => $park_prod_name,
				"park_prod_desc"    => NULL,
				"park_prod_price"   => get_price($park_prod_price, '만'),
				"park_prod_fee"     => get_price($park_prod_fee, '만'),
				"park_prod_soldout" => "판매중",
				"regtime"           => $now,
			);

			$park_prod_cd = $this->Mp_product_model->insert_product($fld);

		}

		$view['view'] = array();
		$view['view']['mode'] = 'write';
		$view['view']['product_list'] = $this->Mp_product_model->get_product_list($park_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);

		$this->load->view('park/bootstrap/product_list', $view);
	}

	public function delete_product()
	{
		if (!$this->input->is_ajax_request()) {
			alert("접근 권한이 없습니다.");
		}

		$this->load->model("Mp_product_model");

		$now = date("Y-m-d H:i:s");

		// 공통
		$park_prod_cd      = $this->input->post('park_prod_cd');
		$park_no           = $this->input->post('park_no');

		$this->Mp_product_model->delete_product($park_prod_cd);

		$view['view'] = array();
		$view['view']['mode'] = 'write';
		$view['view']['product_list'] = $this->Mp_product_model->get_product_list($park_no);
		// debug_var($ctr_no, $park_no, $wish_idx, $contract);

		$this->load->view('park/bootstrap/product_list', $view);
	}


	private function _layout($skin, $layout='layout', $path="park")
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
