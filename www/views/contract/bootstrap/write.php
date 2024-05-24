<?php

$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');

$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));

$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));

$this->managelayout->add_css(base_url('assets/css/bootstrap-datetimepicker.css'));
$this->managelayout->add_js(base_url('assets/js/bootstrap-datetimepicker.js'));
extract($view);
// debug_var($data);
// debug_var($mode);

$park_type_list = get_park_type_list();

$submit_button = '
<div class="border_button text-center">
	<button type="submit" class="btn btn-primary btn-lg">저장하기</button>
</div>
';
?>

<h3>고객등록</h3>

<form class="form-horizontal" name="fcontract" id="fcontract" action="/contract/write_contract" onsubmit="return fcontract_submit(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">

	<input type="hidden" name="ctr_no" value="<?=element('ctr_no', $data)?>">

	<?php if (element('ctr_status', $data)) { ?>
	<div class="row">
		<div class="col-md-12">
			<div class="alert <?="alert-".$this->Mp_Contract_model->get_ctr_status_highlight(element('ctr_status', $data))?>" role="alert">
				현재 <?=element('ctr_status', $data)?> 상태입니다.
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-lg-6 col-md-12 col-xs-12" style="margin-bottom: 20px;">
			<table class="table table-striped">
				<caption>고객 기본정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-3 col-xs-3 form-group">
								<label for="cust_name">고객이름</label>
								<!-- <input type="text" class="form-control" id="cust_name" name="cust_name" value="<?=element('cust_name', $data)?>" required placeholder="성명"> -->
								<select class="form-control" name="cust_name" id="cust_name" data-idx="1" required>
									<?php if (element('cust_name', $data)) { ?>
									<option value="<?=element('cust_name', $data)?>" selected><?=element('cust_name', $data)?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-3 col-xs-3 form-group">
								<label for="cust_group">그룹</label>
								<select class="form-control" name="cust_group" id="cust_group" required>
									<?php if (element('cust_group', $data)) { ?>
									<option value="<?=element('cust_group', $data)?>" selected><?=element('cust_group', $data)?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="cust_phone">고객휴대폰</label>
								<input type="text" class="form-control" id="cust_phone" name="cust_phone" value="<?=get_phone(element('cust_phone', $data))?>" required placeholder="휴대폰">
								<!-- <div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></div>
								</div> -->
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="ctr_mem_id">영업담당</label>
								<select class="form-control" name="ctr_mem_id" id="ctr_mem_id" required>
									<option value="">영업자를 선택하세요</option>
									<?php foreach (element('list', $seller_list) as $i => $row) { ?>
									<option value="<?=element('mem_id', $row)?>"<?=get_selected(element('mem_id', $row), element('ctr_mem_id', $data))?>><?=element('mem_username', $row)."(".element('mem_userid', $row).")"?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="ctr_status">진행상태</label>
								<select class="form-control" name="ctr_status" id="ctr_status" required>
									<?php foreach ($select_ctr_status_list as $i => $row) { ?>
									<option class="bg-<?=element('highlight', $row)?>" value="<?=element('ctr_status', $row)?>"<?=get_selected(element('ctr_status', $row), element('ctr_status', $data))?>><?=element('ctr_status', $row)?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="accept_date">접수일</label>
								<input type="date" class="form-control" id="accept_date" name="accept_date" value="<?=date("Y-m-d", strtotime(element('accept_date', $data)))?>" placeholder="접수일">
								<input type="time" class="form-control" id="accept_time" name="accept_time" value="<?=date("H:i:s", strtotime(element('accept_date', $data)))?>" placeholder="접수시간">
								<!-- <div class="input-group">
									<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
								</div> -->
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_park1">희망시설1</label>
								<select class="form-control wish_park" name="wish_park1" id="wish_park1" data-idx="1" required>
									<option value="">희망시설을 선택하세요</option>
									<?php foreach ($park_list as $park_type => $park_list_row) { ?>
										<optgroup label="<?=element($park_type, $park_type_list)?>">
										<?php foreach ($park_list_row as $i => $row) { ?>
											<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park1', $data))?>><?="[".element($park_type, $park_type_list)."] ".element('park_real_name', $row)?></option>
										<?php } ?>
										</optgroup>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_prod1">관심상품1</label>
								<div id="reg_wish_prod1">
									<select class="form-control wish_prod" name="wish_prod1" id="wish_prod1" required>
									</select>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_park2">희망시설2</label>
								<select class="form-control wish_park" name="wish_park2" id="wish_park2" data-idx="2">
									<option value="">희망시설을 선택하세요</option>
									<?php foreach ($park_list as $park_type => $park_list_row) { ?>
										<optgroup label="<?=element($park_type, $park_type_list)?>">
										<?php foreach ($park_list_row as $i => $row) { ?>
											<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park2', $data))?>><?="[".element($park_type, $park_type_list)."] ".element('park_real_name', $row)?></option>
										<?php } ?>
										</optgroup>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_prod2">관심상품2</label>
								<select class="form-control wish_prod" name="wish_prod2" id="wish_prod2">
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_park3">희망시설3</label>
								<select class="form-control wish_park" name="wish_park3" id="wish_park3" data-idx="3">
									<option value="">희망시설을 선택하세요</option>
									<?php foreach ($park_list as $park_type => $park_list_row) { ?>
										<optgroup label="<?=element($park_type, $park_type_list)?>">
										<?php foreach ($park_list_row as $i => $row) { ?>
											<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park3', $data))?>><?="[".element($park_type, $park_type_list)."] ".element('park_real_name', $row)?></option>
										<?php } ?>
										</optgroup>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_prod3">관심상품3</label>
								<select class="form-control wish_prod" name="wish_prod3" id="wish_prod3">
								</select>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>방문답사 일정등록</caption>
				<tbody>
					<tr>
						<td>
							<div id="history_list">
								<?php $this->load->view('contract/bootstrap/history_list', $view); ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<?php echo $submit_button; ?>

			<table class="table table-striped">
				<caption>현황 보내기</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="sms_sent_history">현황 발송리스트</label>
								<div id="sms_sent_history">
									<?php $this->load->view('contract/bootstrap/sms_history', $view); ?>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<?php if (element('ctr_no', $data)) { ?>
				<button class="btn btn-info btn-lg btn-block" type="button" data-toggle="collapse" data-target="#collapse_send_sms" aria-expanded="false" aria-controls="collapse_send_sms">현황 문자 발송하기&nbsp;<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span></button>
			<?php } else { ?>
				<div class="alert alert-danger" role="alert">현황 문자 발송하기를 사용하려면 먼저 고객기본정보를 저장해주십시오.</div>
				<button class="btn btn-info btn-lg btn-block" type="button" disabled>현황 문자 발송하기&nbsp;<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span></button>
			<?php } ?>
			<div class="collapse bg-info" id="collapse_send_sms">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<div class="col-lg-12 col-md-12 col-xs-12 form-group">
									<label for="sms_recv_NB">수목장</label>
									<select class="form-control multlselect2" id="sms_recv_NB" multiple style="width: 100%">
										<?php foreach (element('NB', $park_list) as $i => $row) { ?>
											<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park1', $data))?>>
												<?php
												echo element('park_real_name', $row);
												if (element('park_manager_phone_masked', $row) == 'Y') {
													echo "(*)";
												}
												?>
											</option>
										<?php } ?>
									</select>
									<div class="input-group btn-group" role="group" aria-label="수목장 선택 단축키" id="shortcut_sms_recv_NB">
										<button type="button" class="btn btn-sm btn-default btn_sms_recv" data-park_type_cd="NB" data-sel_ids="<?=implode(',', element('NB', $sms_button_list))?>">경기도 전체</button>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-lg-12 col-md-12 col-xs-12 form-group">
									<label for="sms_recv_CH">납골당</label>
									<select class="form-control multlselect2" id="sms_recv_CH" multiple style="width: 100%">
										<?php foreach (element('CH', $park_list) as $i => $row) { ?>
											<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park1', $data))?>>
											<?php
											echo element('park_real_name', $row);
											if (element('park_manager_phone_masked', $row) == 'Y') {
												echo "(*)";
											}
											?>
											</option>
										<?php } ?>
									</select>
									<!-- <div class="input-group btn-group" role="group" aria-label="납골당 선택 단축키" id="shortcut_sms_recv_CH">
										<button type="button" class="btn btn-sm btn-default btn_sms_recv" data-park_type_cd="CH" data-sel_ids="<?=implode(',', element('CH', $sms_button_list))?>">경기도 전체</button>
									</div> -->
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-lg-12 col-md-12 col-xs-12 form-group">
									<label for="sms_recv_CT">공원묘원</label>
									<select class="form-control multlselect2" id="sms_recv_CT" multiple style="width: 100%">
										<?php foreach (element('CT', $park_list) as $i => $row) { ?>
											<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park1', $data))?>><?=element('park_real_name', $row)?></option>
										<?php } ?>
									</select>
									<!-- <div class="input-group btn-group" role="group" aria-label="납골당 선택 단축키" id="shortcut_sms_recv_CT">
										<button type="button" class="btn btn-sm btn-default btn_sms_recv" data-park_type_cd="CT" data-sel_ids="<?=implode(',', element('CT', $sms_button_list))?>">경기도 전체</button>
									</div> -->
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-md-12 col-xs-12 form-group border_button" style="padding: 20px;">
									<label for="sms_content">문자내용</label>

									<p class="text-left bg-warning" style="margin: 0px;">
										<span id="sms_cust_name">${고객명}</span><BR>
										<span id="sms_cust_phone">${고객휴대폰}</span><BR>
										<span id="sms_park_real_name">#{공원명}</span><BR>
										<span id="sms_prod_name">#{납골당:부부단} / #{수목장:부부목}</span><BR>
									</p>
									<textarea class="form-control" id="sms_content" rows="2" placeholder="전달 메시지">답사일정</textarea>
									<p class="text-left bg-warning">
										<span id="sms_corp_name">그리운그대</span><BR>
										<span id="sms_mem_username">${영업담당}</span>
									</p>

									<!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#sms_form" id="btn_send_sms"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>&nbsp;문자 보내기</button> -->
									<button type="button" class="btn btn-info pull-right" id="btn_send_sms"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>&nbsp;문자 보내기</button>
									<div id="sms_form_wrap"></div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="col-lg-6 col-md-12 col-xs-12">
			<table class="table table-striped">
				<caption>계약 세부내역</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-lg-12 col-md-12 col-xs-12 form-group">
								<label for="load_wish_park">희망시설 불러오기</label>
								<div class="input-group btn-group" role="group" aria-label="..." id="load_wish_park">
									<button type="button" class="btn btn-sm btn-default" data-park="wish_park1" data-prod="wish_prod1">희망시설1</button>
									<button type="button" class="btn btn-sm btn-default" data-park="wish_park2" data-prod="wish_prod2">희망시설2</button>
									<button type="button" class="btn btn-sm btn-default" data-park="wish_park3" data-prod="wish_prod3">희망시설3</button>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-lg-6 col-md-6 col-xs-6 form-group">
								<label for="ctr_park_no">계약공원</label>
								<select class="form-control" name="ctr_park_no" id="ctr_park_no">
									<option value="">계약공원을 선택하세요.</option>
									<?php foreach ($park_list as $park_type => $park_list_row) { ?>
										<optgroup label="<?=element($park_type, $park_type_list)?>">
										<?php foreach ($park_list_row as $i => $row) { ?>
											<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('ctr_park_no', $data))?>><?="[".element($park_type, $park_type_list)."] ".element('park_real_name', $row)?></option>
										<?php } ?>
										</optgroup>
									<?php } ?>
								</select>
							</div>
							<div class="col-lg-6 col-md-6 col-xs-6 form-group">
								<label for="ctr_prod_cd">계약상품</label>
								<select class="form-control" name="ctr_prod_cd" id="ctr_prod_cd">
									<option value="">계약상품을 선택하세요.</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-lg-6 col-md-6 col-xs-6 form-group">
								<label for="ctr_date">계약일</label>
								<input type="text" class="form-control datepicker" id="ctr_date" name="ctr_date" value="<?=element('ctr_date', $data)?>" placeholder="계약일">
								<!-- <div class="input-group">
									<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
								</div> -->
							</div>
							<div class="col-lg-6 col-md-6 col-xs-6 form-group">
								<label for="ctr_park_contract_number">시설 계약번호</label>
								<input type="text" class="form-control" id="ctr_park_contract_number" name="ctr_park_contract_number" value="<?=element('ctr_park_contract_number', $data)?>" placeholder="시설 계약번호">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-lg-4 col-md-6 col-xs-6 form-group">
								<label for="ctr_prod_price">분양가</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="ctr_prod_price" name="ctr_prod_price" value="<?=get_manwon(element('ctr_prod_price', $data))?>" placeholder="분양가">
									<div class="input-group-addon">만원</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-xs-6 form-group">
								<label for="ctr_discount_amount">할인금액</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="ctr_discount_amount" name="ctr_discount_amount" value="<?=get_manwon(element('ctr_discount_amount', $data))?>" placeholder="할인금액">
									<div class="input-group-addon">만원</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-xs-6 form-group">
								<label for="ctr_amount">최종 분양금액</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="ctr_amount" name="ctr_amount" value="<?=get_manwon(element('ctr_amount', $data))?>" placeholder="최종 계약금액">
									<div class="input-group-addon">만원</div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<?php echo $submit_button; ?>

			<table class="table">
				<caption>고객 추가 요청사항</caption>
				<tbody>
					<tr style="background: #7EA362">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="sangjo_req">상조요청</label>
								<select class="form-control" name="sangjo_req" id="sangjo_req" required>
									<option value="0"<?=get_selected('0', element('sangjo_req', $data))?>>상조요청안함</option>
									<option value="1"<?=get_selected('1', element('sangjo_req', $data))?>>상조요청함</option>
									<option value="2"<?=get_selected('2', element('sangjo_req', $data))?>>상조진행완료</option>
								</select>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="critical_person">위독자</label>
								<input type="text" class="form-control" id="critical_person" name="critical_person" value="<?=element('critical_person', $data)?>" placeholder="위독자">
							</div>
						</td>
					</tr>
					<tr style="background: #7EA362">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="hospital_name">병원</label>
								<input type="text" class="form-control" id="hospital_name" name="hospital_name" value="<?=element('hospital_name', $data)?>" placeholder="병원">
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="funeralhall">희망장례식장</label>
								<input type="text" class="form-control" id="funeralhall" name="funeralhall" value="<?=element('funeralhall', $data)?>" placeholder="희망장례식장">
							</div>
						</td>
					</tr>
					<tr style="background: #A39162">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_req">개장요청</label>
								<select class="form-control" name="tombmig_req" id="tombmig_req" required>
									<option value="0"<?=get_selected('0', element('tombmig_req', $data))?>>개장요청안함</option>
									<option value="1"<?=get_selected('1', element('tombmig_req', $data))?>>개장요청함</option>
									<option value="2"<?=get_selected('2', element('tombmig_req', $data))?>>개장진행완료</option>
								</select>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_date">개장일자</label>
								<input type="text" class="form-control datepicker" id="tombmig_date" name="tombmig_date" value="<?=element('tombmig_date', $data)?>" placeholder="개장일자">
							</div>
						</td>
					</tr>
					<tr style="background: #A39162">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_loc">묘위치</label>
								<input type="text" class="form-control" id="tombmig_loc" name="tombmig_loc" value="<?=element('tombmig_loc', $data)?>" placeholder="묘위치">
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_stone">석물여부</label>
								<select class="form-control" name="tombmig_stone" id="tombmig_stone" required>
									<option value="없음"<?=get_selected('없음', element('tombmig_stone', $data))?>>없음</option>
									<option value="있음"<?=get_selected('있음', element('tombmig_stone', $data))?>>있음</option>
								</select>
							</div>
						</td>
					</tr>
					<tr style="background: #A39162">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_cremation_type">화장구분</label>
								<select class="form-control" name="tombmig_cremation_type" id="tombmig_cremation_type" required>
									<option value="현장화장"<?=get_selected('현장화장', element('tombmig_cremation_type', $data))?>>현장화장</option>
									<option value="화장터화장"<?=get_selected('화장터화장', element('tombmig_cremation_type', $data))?>>화장터화장</option>
									<option value="화장완료"<?=get_selected('화장완료', element('tombmig_cremation_type', $data))?>>화장완료</option>
								</select>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_estimate_price">견적금액</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="tombmig_estimate_price" name="tombmig_estimate_price" value="<?=get_manwon(element('tombmig_estimate_price', $data))?>" placeholder="견적금액">
									<div class="input-group-addon">만원</div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<?php echo $submit_button; ?>

			<table class="table">
				<caption>분양대금 잔금현황</caption>
				<tbody>
					<tr>
						<td>
							<div id="payment_list">
								<?php $this->load->view('contract/bootstrap/payment_list', $view); ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-striped">
				<caption>기타</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="ctr_remark">비고란</label>
								<p class="help-block">고객 추가 요청사항 기입</p>
								<div class="panel panel-default">
									<div class="panel-body">
										<textarea class="form-control" id="ctr_remark" name="ctr_remark" rows="5"><?=element('ctr_remark', $data)?></textarea>
									</div>
									<?php
									$ctr_remark_time = array();
									if (element('ctr_remark_reg_time', $data)) {
										$ctr_remark_time[] = '<span class="label label-default">최초 작성시간 : '.element('ctr_remark_reg_time', $data).'</span>';
									}
									if (element('ctr_remark_mod_time', $data)) {
										$ctr_remark_time[] = '<span class="label label-default">마지막 수정시간 : '.element('ctr_remark_mod_time', $data).'</span>';
									}
									if ($ctr_remark_time) {
									?>
									<div class="panel-footer">
										<?php echo implode(" ", $ctr_remark_time) ?>
									</div>
									<?php } ?>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<?php echo $submit_button; ?>

		</div>
	</div>

</form>

<?php $this->load->view('contract/bootstrap/history_form', $view); ?>

<?php $this->load->view('contract/bootstrap/payment_form', $view); ?>

<style>
.form-horizontal > .form-group {padding: 10px 0;}
</style>

<script>
function fcontract_submit(self) {
	// $("form[name=fhistory] input, form[name=fhistory] select, form[name=fhistory] textarea, form[name=fhistory] button").prop('disabled', true);
	// $("form[name=fpayment] input, form[name=fpayment] select, form[name=fpayment] textarea, form[name=fpayment] button").prop('disabled', true);
	// return false;
}
function show_img(self) {
	window.open(self.href , 'show_img', 'width=800,height=800,scrollbars=1');
	return false;
}
function calculate_contract_amount() {
	var ctr_prod_price = $("#ctr_prod_price").val() ? numberWithoutCommas($("#ctr_prod_price").val()) : 0;
	var ctr_discount_amount = $("#ctr_discount_amount").val() ? numberWithoutCommas($("#ctr_discount_amount").val()) : 0;
	console.log(ctr_prod_price, ctr_discount_amount, numberWithCommas(ctr_prod_price - ctr_discount_amount));
	$("#ctr_amount").val(numberWithCommas((ctr_prod_price - ctr_discount_amount)));
}
function send_sms() {
	if (confirm("시설 본부장에게 문자를 발송하시겠습니까?")) {
		$.ajax({
			type: 'POST',
			url: cb_url + '/contract/send_sms',
			data : {
				ctr_no     : $("input[name=ctr_no]").val(),
				cust_name  : $("select[name=cust_name]").val(),
				cust_phone : $("input[name=cust_phone]").val(),
				ctr_mem_id : $("select[name=ctr_mem_id]").val(),
				sms_recv_CH: $("#sms_recv_CH").val(),
				sms_recv_NB: $("#sms_recv_NB").val(),
				sms_recv_CT: $("#sms_recv_CT").val(),
				sms_content: $("#sms_content").val()
			},
			async: false,
			success : function(data) {
				$("#sms_sent_history").html(data);
			}
		});
	}
}
function is_phone(phone)
{
	const regex_phone = /^(01[016789])([0-9]{3,4})([0-9]{4})$/;
	var str_phone = phone.replaceAll('-', '');
	if (regex_phone.test(str_phone)) {
		return true;
	} else {
		return false;
	}
}
function get_phone(phone, hyphen=1) {
	const regex_phone = /^(01[016789])([0-9]{3,4})([0-9]{4})$/;
	var str_phone = phone.replaceAll('-', '');
	if (is_phone(str_phone) === false) {
			return '';
		}
		if (hyphen) {
			preg = "$1-$2-$3";
		} else {
			preg = "$1$2$3";
		}

		str_phone = str_phone.replace(regex_phone, preg);
		return str_phone;
}
function get_phone_by_masked(phone, hyphen=1) {
	phone = get_phone(phone, hyphen);
	return (phone) ? phone.substr(0, 4) + "***" + phone.substr(7) : "";
}
$(document).ready(function() {
    $('#ctr_mem_id, .wish_park, .wish_prod, #ctr_park_no, #ctr_prod_cd').select2({
	});

    $('.multlselect2').select2({
		closeOnSelect: false,
		placeholder: '선택하세요',
	  	allowClear: true
	});

	$("#cust_name").select2({
		tags: true,
		ajax: {
			url: '<?=site_url()."/contract/cust_exists"?>',
			dataType: 'json',
			data: function (params) {
				var query = {
					term: params.term,
					type: 'public'
				}
				return query;
			},
			processResults: function (data) {
				if (data == null) {
					return [];
				} else {
					console.log(data);
					return {
						results: data.results
					};
				}
			}
		}
	}).on('change', function (e) {
		var cust_string = $(this).find("option:selected").text();
		if (cust_string) {
			var extract_phone_string = cust_string.match(/01[01789]-\d{3,4}-\d{4}/);
			if (extract_phone_string) {
				$("#cust_phone").val(extract_phone_string[0]);
			}
		}
	});

	$("#cust_group").select2({
		tags: true,
		ajax: {
			url: '<?=site_url()."/contract/cust_exists"?>',
			dataType: 'json',
			data: function (params) {
				var query = {
					term: params.term,
					type: 'public'
				}
				return query;
			},
			processResults: function (data) {
				if (data == null) {
					return [];
				} else {
					console.log(data);
					return {
						results: data.results
					};
				}
			}
		}
	}).on('change', function (e) {
		var cust_string = $(this).find("option:selected").text();
		if (cust_string) {
			var extract_phone_string = cust_string.match(/01[01789]-\d{3,4}-\d{4}/);
			if (extract_phone_string) {
				$("#cust_phone").val(extract_phone_string[0]);
			}
		}
	});


	$("#ctr_prod_cd").change(function () {
		var prod_price = $(this).find("option:selected").data('prod_price');
		$("#ctr_prod_price").val(numberWithCommas(prod_price / 10000));
		calculate_contract_amount();
	});

	$("#ctr_park_no").change(function () {
		var ctr_no = $("input[name=ctr_no]").val();
		var park_no = $(this).val();
		$.ajax({
			type: 'POST',
			url: cb_url + '/contract/prod_list',
			data : {
				ctr_no : ctr_no,
				park_no : park_no
			},
			async: false,
			success : function(data) {
				$("#ctr_prod_cd").html(data).trigger('change');
			}
		});
	});

	<?php if ($mode == 'write') { ?>
	// $("#cust_name, #cust_phone").change(function () {
	// 	var cust_name = $("#cust_name").val();
	// 	var cust_phone = $("#cust_phone").val();
	// 	if (cust_name != '') {
	// 		$.ajax({
	// 			type: 'POST',
	// 			url: cb_url + '/contract/exists',
	// 			data : {
	// 				cust_name : cust_name,
	// 				cust_phone : cust_phone
	// 			},
	// 			async: false,
	// 			success : function(data) {
	// 				console.log(data);
	// 				if (data) {
	// 					if (confirm(data.accept_date + "에 이미 등록된 고객입니다.\n계약화면으로 이동하시겠습니까?")) {
	// 						window.location.href = "/contract/view/" + data.ctr_no;
	// 					}
	// 				}
	// 			}
	// 		});
	// 	}
	// });
	<?php } ?>

	$("#btn_send_sms").click(function () {
		send_sms();
		// $.ajax({
		// 	type: 'POST',
		// 	url: cb_url + '/contract/sms_form',
		// 	data : {
		// 		ctr_no : $("input[name=ctr_no]").val(),
		// 		sms_recv_CH : $("#sms_recv_CH").val(),
		// 		sms_recv_NB : $("#sms_recv_NB").val()
		// 	},
		// 	async: false,
		// 	success : function(data) {
		// 		$("#sms_form_wrap").html(data);
		// 		// $("#sms_form").model('show');
		// 	}
		// });
	});


	$("#cust_name").change(function() {
		$("#sms_cust_name").text($(this).val());
	}).trigger('change');
	$("#cust_phone").change(function() {
		var phone = $("#cust_phone").val();
		$("#cust_phone").val(get_phone(phone));
		$("#sms_cust_phone").text(get_phone(phone));
	}).trigger('change');
	$("select[name=ctr_mem_id]").change(function() {
		$("#sms_mem_username").text($(this).find(":selected").text());
	}).trigger('change');

	$(".btn_sms_recv").click(function () {
		console.log($(this).data('park_type_cd'), $(this).data('sel_ids'));
		var park_type_cd = $(this).data('park_type_cd');
		var $select = $("#sms_recv_"+park_type_cd);
		var sel_ids = $(this).data('sel_ids');
		var sel_ids_array;
		
		if (typeof sel_ids != 'undefined') {
			console.log("sel_ids", sel_ids);
			sel_ids_array = sel_ids.toString().split(',');
		}
		if (typeof $select != 'undefined' && sel_ids_array) {
			$select.val(sel_ids_array).change();
		}
	});

	// $("#wish_park1").change(function () {
	// 	$('#ctr_park_no').val($("#wish_park1").val()).select2().trigger('change');
	// });

	// $("#wish_prod1").change(function () {
	// 	$('#ctr_prod_cd').val($("#wish_prod1").val()).select2().trigger('change');
	// });

	$("#load_wish_park button").click(function () {
		var wish_park = $(this).data('park');
		var wish_prod = $(this).data('prod');
		var wish_park_no = $("#"+wish_park).val();
		var wish_prod_cd = $("#"+wish_prod).val();
		$('#ctr_park_no').val(wish_park_no).trigger('change');
		$('#ctr_prod_cd').val(wish_prod_cd).trigger('change');
	});

	$(".wish_park").change(function () {
		var ctr_no = $("input[name=ctr_no]").val();
		var park_no = $(this).val();
		var wish_idx = $(this).data('idx');
		$.ajax({
			type: 'POST',
			url: cb_url + '/contract/prod_list',
			data : {
				ctr_no : ctr_no,
				park_no : park_no,
				wish_idx : wish_idx
			},
			async: false,
			success : function(data) {
				$("#wish_prod"+wish_idx).html(data).trigger('change');
			}
		});
	});

	$("#ctr_prod_price, #ctr_discount_amount").change(function () {
		calculate_contract_amount();
	});

	<?php if (element('ctr_no', $data)) { ?>
	$(".wish_park").trigger('change');
	$("#wish_prod1").trigger('change');
	$("#ctr_prod_price").trigger('change');
	<?php } ?>
	<?php if (element('ctr_park_no', $data)) { ?>
	$("#ctr_park_no").trigger('change');
	<?php } ?>
});
</script>
<?php
// debug_var($data);