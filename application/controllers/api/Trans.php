<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Trans extends REST_Controller {

    protected $methods = [
        'index_put' => ['level' => 10, 'limit' => 10],
        'index_delete' => ['level' => 10],
        'level_post' => ['level' => 10],
        'regenerate_post' => ['level' => 10],
    ];

    protected $models = array('Auth', 'Vt_trans_dtg', 'Member');
    
    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        // $this->load->library(array('querystring', 'accesslevel', 'email', 'notelib', 'point', 'imagelib'));
        $this->load->helper(array('form', 'url'));
    }
    
    public function list_get()
    {
        // if ($auth_result) {
            // public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
            $uid = $this->input->get('uid');
            // $member = $this->Member_model->get_by_userid($this->input->get('uid'));
            // debug($member);
            // exit;
            $output = $this->Vt_trans_dtg_model->get_list('', '', "mem_userid='{$uid}'");

            $this->set_response($output, REST_Controller::HTTP_OK); //This is the respon if success
        // } else {
        //     $output['error'] = '아이디 또는 비밀번호가 일치하지 않습니다.';
        //     $this->set_response($output, REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }
    
    
    public function upload_post()
    {
        try {
            $member = $this->Member_model->get_by_userid($this->input->get('uid'));

            $config['allowed_types'] = '*';
            $config['max_size']      = 10485760;
            $config['upload_path']   = './uploads/api/'.$member['mem_userid'];
            $config['overwrite']     = false; // true 로 설정된 상태에서, 같은 이름의 파일이 이미 존재한다면 덮어쓸것입니다. false 로 설정되어있으면, 파일명에 숫자가 추가로 붙게 됩니다.
            @mkdir($config['upload_path']);
            $this->load->library('upload', $config);

            if ($this->upload->do_upload()) {
                $upload_file = $this->upload->data();
                $post_date = $this->input->post();

                // $upload_file {   // success message
                //     "file_name": "abcdef1.jpg",
                //     "file_type": "image/jpeg",
                //     "file_path": "/home/hosting_users/vtrack/www/uploads/api/",
                //     "full_path": "/home/hosting_users/vtrack/www/uploads/api/abcdef1.jpg",
                //     "raw_name": "abcdef1",
                //     "orig_name": "abcdef.jpg",
                //     "client_name": "abcdef.jpg",
                //     "file_ext": ".jpg",
                //     "file_size": 29.47,
                //     "is_image": true,
                //     "image_width": 474,
                //     "image_height": 558,
                //     "image_type": "jpeg",
                //     "image_size_str": "width=\"474\" height=\"558\""
                // }

                $exists = $this->Vt_trans_dtg_model->get_one("", "*", "mem_userid='{$member['mem_userid']}' AND vtl_filename='{$upload_file['orig_name']}'");
                if ($exists && filesize($exists['vtl_fullpath']) > 4) {
                    @unlink($upload_file['full_path']);
                    $this->set_response("", REST_Controller::HTTP_CONFLICT); //This is the respon if success
                } else {
                    try {
                        $updatedata = array(
                            "mem_userid" => $member['mem_userid'],
                            "mem_name" => $member['mem_username'],
                            "mem_phone" => $member['mem_phone'],
                            "vmd_device_id" => $this->input->get('duid'),
                            "vhc_id" => '',
                            "vhc_num" => $this->input->get('vnum'),
                            "vtl_filepath" => $upload_file['full_path'],
                            "vtl_filename" => $upload_file['orig_name'],
                            "vtl_filesize" => $upload_file['file_size'],
                            "vtl_reg_time" => cdate('Y-m-d H:i:s'),
                        );
                        $sql = $this->db->insert_string('cb_vt_trans_dtg', $updatedata) . ' ON DUPLICATE KEY UPDATE duplicate=LAST_INSERT_ID(duplicate)';

                        $post_id = $this->Vt_trans_dtg_model->insert($updatedata);
                    } catch(Exception $e) {
                        $upload_file['error'] = $this->db->error();
                    }

                    $this->set_response($upload_file, REST_Controller::HTTP_OK); //This is the respon if success
                }
            } else {
                $upload_file['error'] = $this->upload->display_errors();
                $this->set_response($upload_file, REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch(Exception $e) {
            $upload_file['error'] = $e->getMessage();
            $this->set_response($upload_file, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
