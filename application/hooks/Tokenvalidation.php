<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tokenvalidation extends CI_Controller {

    public function tokenValidate()
    {
        $headers = $this->input->get_request_header('Authorization'); //get token from request header

        try {
            $decoded = JWT::decode($headers, $this->config->item('jwt_key'), array('HS256'));
            $this->set_response($decoded, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            $invalid = ['status' => $e->getMessage()]; //Respon if credential invalid
            $this->set_response($invalid, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
