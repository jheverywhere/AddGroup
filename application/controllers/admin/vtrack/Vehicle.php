<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vehicle class
 *
 * @author shyeon (lecomteneo@gmail.com)
 */

/**
 * 관리자>운행기록계>차량관리 controller 입니다.
 */
class Vehicle extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'vtrack/vehicle';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Vehicle');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Vehicle_model';

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'random');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring'));
	}

	/**
	 * 목록을 가져오는 메소드입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_vehicle_vehicle_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$view['view']['sort'] = array(
            // 'vhc_id' => $param->sort('vhc_id', 'asc'),
            'vhc_reg_time' => $param->sort('vhc_reg_time', 'desc'),
            'vhc_num' => $param->sort('vhc_num', 'asc'),
		);
        $findex = $this->input->get('findex', null, 'vhc_reg_time');
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('vhc_num', 'vhc_chassis_num', 'vhc_name', 'vhc_manage_corp', 'vhc_owner_corp', 'vhc_manufacturer', 'vhc_model_year'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array(); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('vhc_num','vhc_reg_time'); // 정렬이 가능한 필드
		$result = $this->{$this->modelname}
            ->get_admin_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;
		/**
		 * primary key 정보를 저장합니다
		 */
        $view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

        /**
         * column type list를 가져온다. (enum 항목은 배열로 가져옴)
         */
        $colume_type_list = $this->{$this->modelname}->get_column_type_list();
        $view['view']['type_list'] = $colume_type_list;

        /**
         * group 을 가져옵니다.
         */
        $group_list = $this->{$this->modelname}->get_group_select();
        $view['view']['group_list'] = $group_list;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url']   = admin_url($this->pagedir) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page']   = $per_page;
        $this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page']   = $page;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$search_option = array('vhc_num' => '차량번호');
		$view['view']['skeyword']        = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option']   = search_option($search_option, $sfield);
		$view['view']['listall_url']     = admin_url($this->pagedir);
		$view['view']['write_url']       = admin_url($this->pagedir . '/write');
		$view['view']['list_update_url'] = admin_url($this->pagedir . '/listupdate/?' . $param->output());
		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'index');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
    }


	/**
	 * 신규 또는 수정 페이지를 가져오는 메소드입니다
	 */
	public function write($pid = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_vehicle_vehicle_write';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$primary_key = $this->{$this->modelname}->primary_key;

		/**
		 * 수정 페이지일 경우 기존 데이터를 가져옵니다
		 */
        $getdata = array();
        if ($pid) {
            $getdata = $this->{$this->modelname}->get_one($pid);
        } else {
            // 기본값 설정
            $getdata['vhc_id'] = create_guid();
        }

        /**
         * column type list를 가져온다. (enum 항목은 배열로 가져옴)
         */
        $colume_type_list = $this->{$this->modelname}->get_column_type_list();
        $view['view']['type_list'] = $colume_type_list;

        /**
         * group 을 가져옵니다.
         */
        $group_list = $this->{$this->modelname}->get_group_select();
        $view['view']['group_list'] = $group_list;

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'vhc_num',
				'label' => '차량번호',
				'rules' => 'trim|required',
			),
		);
        if ($pid) { // 수정인 경우
			$config[] = array(
				'field' => 'vhc_id',
				'label' => '차량아이디',
				'rules' => 'trim|required|alpha_dash|min_length[36]|max_length[36]',
			);
		} else { // 신규인 경우
			$config[] = array(
				'field' => 'vhc_id',
				'label' => '차량아이디',
				'rules' => 'trim|required|alpha_dash|min_length[36]|max_length[36]|is_unique[vt_vehicle.vhc_id.' . element('vhc_id', $getdata) . ']',
			);
		}
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

			$updatedata = array(
				'vgr_id'              => $this->input->post('vgr_id', null, ''),
				'vhc_num'             => $this->input->post('vhc_num', null, ''),
				'vhc_chassis_num'     => $this->input->post('vhc_chassis_num', null, ''),
				'vhc_type'            => $this->input->post('vhc_type', null, ''),
				'vhc_name'            => $this->input->post('vhc_name', null, ''),
				'vhc_manage_corp'     => $this->input->post('vhc_manage_corp', null, ''),
				'vhc_owner_corp'      => $this->input->post('vhc_owner_corp', null, ''),
				'vhc_manufacturer'    => $this->input->post('vhc_manufacturer', null, ''),
				'vhc_model_year'      => $this->input->post('vhc_model_year', null, ''),
				'vhc_usage_type'      => $this->input->post('vhc_usage_type', null, ''),
				'vhc_terminal_serial' => $this->input->post('vhc_terminal_serial', null, ''),
			);

			/**
			 * 게시물을 수정하는 경우입니다
			 */
            if ($pid) {
				$this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);

				$getdata = $this->{$this->modelname}->get_one($pid);
				$view['view']['alert_message'] = '기본정보 설정이 저장되었습니다';
			} else {
				/**
				 * 게시물을 새로 입력하는 경우입니다
				 * 기본값 설정입니다
				 */
				$upload_max_filesize = ini_get('upload_max_filesize');
				if ( ! preg_match("/([m|M])$/", $upload_max_filesize)) {
					$upload_max_filesize = (int)($upload_max_filesize / 1048576);
				} else {
					$array = array('m', 'M');
					$upload_max_filesize = str_replace($array, '', $upload_max_filesize);
				}

                $updatedata[$primary_key] = $this->input->post($primary_key);
				$pid = $this->{$this->modelname}->insert($updatedata);

				$getdata = $this->{$this->modelname}->get_one($pid);
				$this->session->set_flashdata(
					'message',
					'기본정보 설정이 저장되었습니다'
				);

				$redirecturl = admin_url($this->pagedir . '/write/' . $pid);
				redirect($redirecturl);
			}
		}

		$view['view']['data'] = $getdata;

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $primary_key;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'write');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 목록 페이지에서 선택수정을 하는 경우 실행되는 메소드입니다
	 */
	public function listupdate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_vehicle_vehicle_listupdate';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 업데이트를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {

			$vgr_id              = $this->input->post('vgr_id');
			$vhc_num             = $this->input->post('vhc_num');
			$vhc_chassis_num     = $this->input->post('vhc_chassis_num');
			$vhc_type            = $this->input->post('vhc_type');
			$vhc_name            = $this->input->post('vhc_name');
			$vhc_manage_corp     = $this->input->post('vhc_manage_corp');
			$vhc_owner_corp      = $this->input->post('vhc_owner_corp');
			$vhc_manufacturer    = $this->input->post('vhc_manufacturer');
			$vhc_model_year      = $this->input->post('vhc_model_year');
			$vhc_usage_type      = $this->input->post('vhc_usage_type');
			$vhc_terminal_serial = $this->input->post('vhc_terminal_serial');

			foreach ($this->input->post('chk') as $primary_key) {
				if ($primary_key) {
                    $updatedata = array(
                        'vgr_id'              => element($primary_key, $vgr_id),
                        'vhc_num'             => element($primary_key, $vhc_num),
                        'vhc_chassis_num'     => element($primary_key, $vhc_chassis_num),
                        'vhc_type'            => element($primary_key, $vhc_type),
                        'vhc_name'            => element($primary_key, $vhc_name),
                        'vhc_manage_corp'     => element($primary_key, $vhc_manage_corp),
                        'vhc_owner_corp'      => element($primary_key, $vhc_owner_corp),
                        'vhc_manufacturer'    => element($primary_key, $vhc_manufacturer),
                        'vhc_model_year'      => element($primary_key, $vhc_model_year),
                        'vhc_usage_type'      => element($primary_key, $vhc_usage_type),
                        'vhc_terminal_serial' => element($primary_key, $vhc_terminal_serial),
                    );
                    $this->{$this->modelname}->update($primary_key, $updatedata);
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 업데이트가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 수정되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());
		redirect($redirecturl);
	}


	/**
	 * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_vehicle_vehicle_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $primary_key) {
				if ($primary_key) {
                    $this->{$this->modelname}->delete($primary_key);
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 삭제가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 삭제되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());

		redirect($redirecturl);
	}

}
