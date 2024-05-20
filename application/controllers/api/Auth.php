<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Auth extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
		$this->load->library(array('form_validation'));
    }

    public function login_post()
    {
        $user_id = $this->input->server('PHP_AUTH_USER') ? $this->input->server('PHP_AUTH_USER') : '';
        $user_pw = $this->input->server('PHP_AUTH_PW');
        $device_duid = $this->input->server('HTTP_X_DUID');

        // print_r(array($user_id, $user_pw, $device_duid));

        $auth_result = $this->_check_id_pw($user_pw, $user_id);
        if ($auth_result) {
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            $token['exp'] = $date->getTimestamp() + 60*60*24; //To here is to generate token
            $token['id'] = $user_id;  //From here
            $output['token'] = JWT::encode($token, $this->config->item('jwt_key')); //This is the output token

            $member = $this->Member_model->get_by_userid($user_id);
            $extras = $this->member->get_all_extras(element('mem_id', $member));
            if (is_array($extras)) {
                $member = array_merge($member, $extras);
            }
            $metas = $this->member->get_all_meta(element('mem_id', $member));
            if (is_array($metas)) {
                $member = array_merge($member, $metas);
            }
            $member['social'] = $this->member->get_all_social_meta(element('mem_id', $member));
            $output['user'] = $member;
    

            $updatedata = array(
                "mem_duid" => $device_duid,
                "mem_token" => $output['token'],
            );
            $this->load->model('Auth_model');
            $this->Auth_model->update($user_id, $updatedata);

            $this->set_response($output, REST_Controller::HTTP_OK); //This is the respon if success
        } else {
            $output['error'] = '아이디 또는 비밀번호가 일치하지 않습니다.';
            $this->set_response($output, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }


    public function token_get()
    {
        $this->set_response('', REST_Controller::HTTP_OK); //This is the respon if success
    }


    public function autologin_post()
    {
        $user_id = $this->input->post('uid');

        $member = $this->Member_model->get_by_userid($user_id);
        $extras = $this->member->get_all_extras(element('mem_id', $member));
        if (is_array($extras)) {
            $member = array_merge($member, $extras);
        }
        $metas = $this->member->get_all_meta(element('mem_id', $member));
        if (is_array($metas)) {
            $member = array_merge($member, $metas);
        }
        $member['social'] = $this->member->get_all_social_meta(element('mem_id', $member));
        $output['user'] = $member;

        $this->set_response($output, REST_Controller::HTTP_OK); //This is the respon if success
    }


    /**
     * 로그인시 아이디와 패스워드가 일치하는지 체크합니다
     */
    private function _check_id_pw($password, $userid)
    {
         if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $max_login_try_count = (int) $this->cbconfig->item('max_login_try_count');
        $max_login_try_limit_second = (int) $this->cbconfig->item('max_login_try_limit_second');

        $loginfailnum = 0;
        $loginfailmessage = '';
        if ($max_login_try_count && $max_login_try_limit_second) {
            $select = 'mll_id, mll_success, mem_id, mll_ip, mll_datetime';
            $where = array(
                'mll_ip' => $this->input->ip_address(),
                'mll_datetime > ' => strtotime(ctimestamp() - 86400 * 30),
            );
            $this->load->model('Member_login_log_model');
            $logindata = $this->Member_login_log_model
                ->get('', $select, $where, '', '', 'mll_id', 'DESC');

            if ($logindata && is_array($logindata)) {
                foreach ($logindata as $key => $val) {
                    if ((int) $val['mll_success'] === 0) {
                        $loginfailnum++;
                    } elseif ((int) $val['mll_success'] === 1) {
                        break;
                    }
                }
            }
            if ($loginfailnum > 0 && $loginfailnum % $max_login_try_count === 0) {
                $lastlogintrydatetime = $logindata[0]['mll_datetime'];
                $next_login = strtotime($lastlogintrydatetime)
                    + $max_login_try_limit_second
                    - ctimestamp();
                if ($next_login > 0) {
                    $this->form_validation->set_message(
                        '_check_id_pw',
                        '회원님은 패스워드를 연속으로 ' . $loginfailnum . '회 잘못 입력하셨기 때문에 '
                        . $next_login . '초 후에 다시 로그인 시도가 가능합니다'
                    );
                    return false;
                }
            }
            $loginfailmessage = '<br />회원님은 ' . ($loginfailnum + 1)
                . '회 연속으로 패스워드를 잘못입력하셨습니다. ';
        }

        $use_login_account = $this->cbconfig->item('use_login_account');

        $this->load->model(array('Member_dormant_model'));

        $userselect = 'mem_id, mem_password, mem_denied, mem_email_cert, mem_is_admin';
        $is_dormant_member = false;
        if ($use_login_account === 'both') {
            $userinfo = $this->Member_model->get_by_both($userid, $userselect);
            if ( ! $userinfo) {
                $userinfo = $this->Member_dormant_model->get_by_both($userid, $userselect);
                if ($userinfo) {
                    $is_dormant_member = true;
                }
            }
        } elseif ($use_login_account === 'email') {
            $userinfo = $this->Member_model->get_by_email($userid, $userselect);
            if ( ! $userinfo) {
                $userinfo = $this->Member_dormant_model->get_by_email($userid, $userselect);
                if ($userinfo) {
                    $is_dormant_member = true;
                }
            }
        } else {
            $userinfo = $this->Member_model->get_by_userid($userid, $userselect);
            if ( ! $userinfo) {
                $userinfo = $this->Member_dormant_model->get_by_userid($userid, $userselect);
                if ($userinfo) {
                    $is_dormant_member = true;
                }
            }
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);

        if ( ! element('mem_id', $userinfo) OR ! element('mem_password', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage
            );
            $this->member->update_login_log(0, $userid, 0, '회원 아이디가 존재하지 않습니다');
            return false;
        } elseif ( ! password_verify($password, element('mem_password', $userinfo))) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage
            );
            $this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '패스워드가 올바르지 않습니다');
            return false;
        } elseif (element('mem_denied', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원님의 아이디는 접근이 금지된 아이디입니다'
            );
            $this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '접근이 금지된 아이디입니다');
            return false;
        } elseif ($this->cbconfig->item('use_register_email_auth') && ! element('mem_email_cert', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원님은 아직 이메일 인증을 받지 않으셨습니다'
            );
            $this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '이메일 인증을 받지 않은 회원아이디입니다');
            return false;
        } elseif (element('mem_is_admin', $userinfo) && $this->input->post('autologin')) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '최고관리자는 자동로그인 기능을 사용할 수 없습니다'
            );
            return false;
        }

        if ($is_dormant_member === true) {
            $this->member->recover_from_dormant(element('mem_id', $userinfo));
        }

        return true;
    }

}
