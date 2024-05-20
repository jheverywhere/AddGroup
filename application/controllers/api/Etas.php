<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etas extends CB_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Vt_trans_dtg', 'Vt_trans_files', 'Member');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array();

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        // $this->load->library(array('querystring', 'accesslevel', 'email', 'notelib', 'point', 'imagelib'));
        $this->load->helper(array('form', 'url'));
    }
    
    public function index()
    {
    }

    function get_task()
    {
        $first_one = $this->Vt_trans_dtg_model->get_one('', '*', " vtl_upload_time IS NULL ", '', '', '', 'vtl_upd_time ASC');
        // $output = $this->Vt_trans_dtg_model->get_list('', '', " vtl_upload_time=NULL ");
        // public function get($primary_value = '', $select = '', $where = '', $limit = '', $offset = 0, $findex = '', $forder = '')
        $list = $this->Vt_trans_dtg_model->get('', '*', " mem_userid='{$first_one['mem_userid']}' AND vtl_upload_time IS NULL ", '', '', '', 'vtl_upd_time DESC');

        $vtl_group = date("Ymd");
        $files = array();
        foreach ($list as $row) {
            $files[] = [
                "save_name" => str_replace("/home/hosting_users/vtrack/www", "", $row['vtl_filepath']),
                "org_name" => $row['vtl_filename'],
            ];
            $data = [
                "vtl_upload_time" => date("Y-m-d H:i:s"),
                "vtl_group" => $vtl_group,
            ];
            $this->Vt_trans_dtg_model->update('', $data, "vtl_id={$row['vtl_id']}");
        }

        $output = [
            "user" => [
                "uid" => $first_one['mem_userid'],
            ],
            "group" => [
                "gid" => $vtl_group,
            ],
            "etas_account" => [
                "uid" => "ake4241",
                "upw" => "ake740306!!",
            ],
            "files" => $files,
        ];
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
    
    function upd_result()
    {
        $post = $this->input->post();

        $mem_userid = $post['user_id'];
        $vtl_group = $post['group_id'];
        foreach ($post['files'] as $idx => $row) {
            $data = [
                "mem_userid" => $mem_userid,
                "vtf_filename" => $row['filename'],
                "vtf_model" => $row['model'],
                "vtl_group" => $vtl_group,
                "vtf_filesize" => $row['filesize'],
                "vtf_result" => $row['result'],
                "vtf_reason" => $row['reason'],
                "vtf_receive_time" => date("Y-m-d H:i:s"),
            ];

            try {
                $this->Vt_trans_files_model->insert($data);
            } catch (MySQLDuplicateKeyException $e) {

            }
        }

        $output = $post;
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));

    }
}
