<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

/**
 * Keys Controller
 * This is a basic Key Management REST controller to make and delete keys
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Update extends REST_Controller {

    protected $methods = [
            'index_put' => ['level' => 10, 'limit' => 10],
            'index_delete' => ['level' => 10],
            'level_post' => ['level' => 10],
            'regenerate_post' => ['level' => 10],
        ];

    /**
     * post
     *
     * @access public
     * @return void
     */
    public function index_post()
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png|xz';
        $config['max_size']             = 10485760;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
            $error = array('error' => $this->upload->display_errors());

            $this->response([$error
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR); // CREATED (201) being the HTTP response code
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());

            $this->response([
            ], REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
    }

    public function upd_post()
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png|xz';
        $config['max_size']             = 10485760;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
            $error = array('error' => $this->upload->display_errors());

            $this->response([$error
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR); // CREATED (201) being the HTTP response code
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());

            $this->response([
            ], REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
    }

}
