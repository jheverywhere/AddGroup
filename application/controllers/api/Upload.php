<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CB_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();

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
        $this->load->view('api/upload_form', array('error' => ' ' ));
    }

    function do_upload()
    {
        $config['upload_path']   = './uploads/api';
        $config['allowed_types'] = '*';
        $config['max_size']      = 10485760;
        $config['max_width']     = 1024;
        $config['max_height']    = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload())
        {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('api/upload_form', $error);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());

            $this->load->view('api/upload_success', $data);
        }
	}
}
