<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Kakaobizm {
    public $bizm_url = 'https://alimtalk-api.bizmsg.kr';
    public $bizm_dev_url = 'https://dev-alimtalk-api.bizmsg.kr:1443';
    
    public $path = '/v1/sender/send';
    
    // for real server
    public $user_id = 'jkisoon';  // 비즈엠 홈페이지에 가입된 사용자 계정명 
    public $message_type = 'at';  // 메시지 타입(at : 알림톡, ft : 친구톡)
    public $profile = '876b91b668c63856e01660dc849296381fe7f382';  // 발신프로필키(메시지 발송 주체인 플러스친구에 대한 키)
    public $sms_kind = 'S';  // 카카오 비즈메시지 발송이 실패했을 때SMS 전환발송을 사용하는 경우 SMS/LMS 구분(SMS: S, LMS: L)
    public $sms_sender = '01072177080';  // SMS 전환발송 시 발신번호

    // for dev server
    // public $user_id = 'prepay_user';  // 비즈엠 홈페이지에 가입된 사용자 계정명 
    // public $message_type = 'at';  // 메시지 타입(at : 알림톡, ft : 친구톡)
    // public $profile = 'ef1c0fdb84d1e604ab7de61872adb12873137bed';  // 발신프로필키(메시지 발송 주체인 플러스친구에 대한 키)
    // public $sms_kind = 'L';  // 카카오 비즈메시지 발송이 실패했을 때SMS 전환발송을 사용하는 경우 SMS/LMS 구분(SMS: S, LMS: L)
    // public $sms_sender = '0260064943';  // SMS 전환발송 시 발신번호


    public $http_status = null;
    public $header = null;
    public $response = null;

    public $messages = array();

	public function send_sms($receive_number, $content)
	{
        $msg = array(
            'phn'          => $receive_number,
            'msg'          => $content,
            'msgSms'       => $content,
            'smsKind'      => 'L',
            'smsOnly'      => 'Y',
            'smsSender'    => $this->sms_sender,
            'userId'       => $this->user_id,
            'message_type' => $this->message_type,
            'profile'      => $this->profile,
        );
        $data = array($msg);
        $url = $this->bizm_url . $this->path;
        $this->response = $this->curl($url, json_encode($data), $this->http_status, $this->header);
        return $this->response;
    }

	public function send_mms($send_number, $receive_number, $content, $img_url)
	{
        $result = array();
        if (is_array($receive_number)) {
            $msg = array(
                'msg'          => $content,
                'msgSms'       => $content,
                'smsKind'      => 'L',
                'smsOnly'      => 'Y',
                'smsSender'    => $send_number,
                'userId'       => $this->user_id,
                'message_type' => $this->message_type,
                'profile'      => $this->profile,
            );
            if ($img_url) {
                $msg['smsKind'] = 'M';
                $msg['img_url'] = $img_url;
            }

            $max_loop = count($receive_number) / 100;
            for ($n=0; $n < $max_loop; $n++) {
                $offset = $n * 100;
                $targets = array_slice($receive_number, $offset, 100);
                $data = array();
                foreach ($targets as $phone) {
                    $phn = get_phone($phone, false);
                    $msg['phn'] = $phn;
                    $data[] = $msg;
                    $result[$phn]['req'] = $msg;
                    $response[]['data']['phn'] = $phn;
                }
                $url = $this->bizm_url . $this->path;
                $this->response = $this->curl($url, json_encode($data), $this->http_status, $this->header);
                $response = json_decode($this->response, true);
                if (is_array($response)) {
                    foreach ($response as $res_row) {
                        $phn = get_phone(element("phn", element('data', $res_row)), false);
                        $result[$phn]['res'] = $res_row;
                    }
                } else {
                    return $response;
                }
            }
        } else {
            $msg = array(
                'phn'          => $receive_number,
                'msg'          => $content,
                'msgSms'       => $content,
                'smsKind'      => 'L',
                'smsOnly'      => 'Y',
                'smsSender'    => $send_number,
                'userId'       => $this->user_id,
                'message_type' => $this->message_type,
                'profile'      => $this->profile,
            );
            if ($img_url) {
                $msg['smsKind'] = 'M';
                $msg['img_url'] = $img_url;
            }
            $data = array($msg);

            $phn = get_phone($receive_number, false);
            $result[$phn]['req'] = $data;

            $url = $this->bizm_url . $this->path;
            $this->response = $this->curl($url, json_encode($data), $this->http_status, $this->header);
            $response = json_decode($this->response, true);
            if (is_array($response)) {
                foreach ($response as $res_row) {
                    $phn = get_phone(element('data', $res_row), false);
                    $result[$phn]['res'] = $res_row;
                }
            } else {
                return $response;
            }
        }
        return $result;
    }

	public function send($only_sms = false, $server = 'real')
	{
        $data = array();
        foreach ($this->messages as $msg) {
            $msg['userId'] = $this->user_id;
            $msg['message_type'] = $this->message_type;
            $msg['profile'] = $this->profile;
            $msg['smsOnly'] = ($only_sms == true) ? "Y" : "N";
            if (isset($this->sms_kind)) {
                $msg['smsKind'] = $this->sms_kind;
                if ("S" == $this->sms_kind) {
                    $msg['smsSender'] = $this->sms_sender;
                } else if ("L" == $this->sms_kind) {
                    $msg['smsSender'] = $this->sms_sender;
                    $msg['smsLmsTit'] = "장지114";
                } else {
                    throw new Exception('Invalid sms_kind value. ('.$this->sms_kind.')');
                }
            } else {
                unset($msg['msgSms']);
                unset($msg['smsSender']);
            }
            $data[] = $msg;
        }

        $url = ($server == 'dev') ? $this->bizm_url.$this->path : $this->bizm_url.$this->path;
        $this->response = $this->curl($url, json_encode($data), $this->http_status, $this->header);
        // debug_var($data, $this->response);
        $this->messages = array(); // 초기화
        return $this->response;

        // [
        //     {
        //     "code": "success",       // 발송 성공/실패 여부(success: 성공, fail: 실패)
        //     "data": "01012345678",   // 수신자 전화번호
        //     "message": "K000",       // 처리결과 코드
        //     "type": "at"             // 발송 유형(at: 알림톡, ft: 친구톡, S: SMS, L: LMS)
        //     },
        //     ...
        // ]
    }

	public function add_msg($phone, $temp_id, $msg, $msg_sms)
	{
        $this->messages[] = array(
            'phn' => preg_replace("/[^0-9]/", "", $phone),
            'tmplId' => $temp_id,
            'msg' => $msg,
            'msgSms' => $msg_sms,
        );
    }

	function clear_msg()
	{
        unset($this->messages);
        $this->messages = array();
	}
	
	private function curl($url, $post_data, &$http_status, &$header = null)
	{
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		if (!is_null($header)) {
			curl_setopt($ch, CURLOPT_HEADER, true);
		}
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		$response = curl_exec($ch);
	
		$body = null;
		if (!$response) {
			$body = curl_error($ch);
			$http_status = -1;
			echo("CURL Error: = " . $body."\n");
		} else {
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (!is_null($header)) {
				$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
				$header = substr($response, 0, $header_size);
				$body = substr($response, $header_size);
			} else {
				$body = $response;
			}
		}
		curl_close($ch);
		return $body;
	}
}
