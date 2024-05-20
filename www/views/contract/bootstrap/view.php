<?php

$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
// $this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
extract($view);
// debug_var($payment_list);
// debug_var($data);

if (element('ctr_seller', $data)) {
	$ctr_seller = element('ctr_seller', $data);
} else if ($this->member->item('mem_teamname') == '영업부') {
	$ctr_seller = $this->member->item('mem_id');
} else {
	$ctr_seller = NULL;
}
// debug_var($data);
?>

<!-- <script type="text/javascript" src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->

<h3>계약내용</h3>

<form class="form-horizontal" name="fcontract" id="fcontract" action="/contract/write_contract" onsubmit="return fcontract_submit(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8">

	<input type="hidden" name="ctr_no" value="<?=element('ctr_no', $data)?>">
	<input type="hidden" name="cust_name" value="<?=element('cust_name', $data)?>">
	<input type="hidden" name="cust_phone" value="<?=element('cust_phone', $data)?>">
	<input type="hidden" name="ctr_mem_id" value="<?=element('ctr_mem_id', $data)?>">

	<div class="row">
		<div class="col-lg-6 col-md-12 col-xs-12">

			<table class="table table-striped">
				<caption>고객 기본정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="cust_name">고객이름</label>
								<div id="cust_name" class="input-group">
									<?=element('cust_name', $data)?><BR>
									<?=get_cust_phone_link(element('cust_phone', $data),element('cust_name', $data))?>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="mem_username">영업담당</label>
								<div id="mem_username" class="input-group">
									<?=element('mem_username', $data)?><BR>
									<?=get_phone_link(element('mem_phone', $data))?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_status">진행상태</label>
								<div id="ctr_status">
									<?php if (element('ctr_status', $data)) { ?>
									<div class="alert <?="alert-".$this->Mp_Contract_model->get_ctr_status_highlight(element('ctr_status', $data))?>" role="alert">
										<?=element('ctr_status', $data)?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="accept_date">접수일</label>
								<div id="accept_date"><?=get_ymdhi(element('accept_date', $data))?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_park1">희망시설1</label>
								<div id="wish_park1"><?=element('wish_park1_name', $data)?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_prod1">관심상품1</label>
								<div id="wish_prod1"><?=element('wish_prod1_name', $data)?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_park2">희망시설2</label>
								<div id="wish_park2"><?=element('wish_park2_name', $data)?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_prod2">관심상품2</label>
								<div id="wish_prod2"><?=element('wish_prod2_name', $data)?></div>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_park3">희망시설3</label>
								<div id="wish_park3"><?=element('wish_park3_name', $data)?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_prod3">관심상품3</label>
								<div id="wish_prod3"><?=element('wish_prod3_name', $data)?></div>
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
							<table class="table table-striped" id="history_list">
								<?php $this->load->view('contract/bootstrap/history_list', $view); ?>
							</table>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-striped">
				<caption>현황 보내기</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="sms_sent_history">현황 발송목록</label>
								<div id="sms_sent_history">
									<?php $this->load->view('contract/bootstrap/sms_history', $view); ?>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<button class="btn btn-info btn-lg btn-block" type="button" data-toggle="collapse" data-target="#collapse_send_sms" aria-expanded="false" aria-controls="collapse_send_sms">현황 문자 발송하기&nbsp;<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span></button>
			<div class="collapse bg-info" id="collapse_send_sms">
				<table class="table">
					<tbody>
						<tr>
							<td>
								<div class="col-lg-12 col-md-12 col-xs-12 form-group">
									<label for="sms_recv_NB">수목장</label>
									<select class="form-control multiselect2" id="sms_recv_NB" multiple style="width: 100%">
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
									<select class="form-control multiselect2" id="sms_recv_CH" multiple style="width: 100%">
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
									<select class="form-control multiselect2" id="sms_recv_CT" multiple style="width: 100%">
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
										<span id="sms_cust_name"><?=element('cust_name', $data)?></span><BR>
										<span id="sms_cust_phone"><?=get_phone(element('cust_phone', $data))?></span><BR>
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
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_park_no">계약공원</label>
								<div id="ctr_park_no"><?=element('ctr_park_name', $data) ? element('ctr_park_name', $data) : "-"?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_prod_cd">계약상품</label>
								<div id="ctr_prod_cd"><?=element('ctr_prod_name', $data) ? element('ctr_prod_name', $data) : "-"?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_date">계약일</label>
								<div id="ctr_date"><?=element('ctr_date', $data) ? element('ctr_date', $data) : '-'?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_park_contract_number">시설 계약번호</label>
								<div id="ctr_park_contract_number"><?=element('ctr_park_contract_number', $data) ? element('ctr_park_contract_number', $data) : '-'?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="ctr_prod_price">분양가</label>
								<div id="ctr_prod_price"><?=display_price(element('ctr_prod_price', $data))?></div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="ctr_discount_amount">할인금액</label>
								<div id="ctr_discount_amount"><?=display_price(element('ctr_discount_amount', $data))?></div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="ctr_amount">최종 계약금액</label>
								<div id="ctr_amount"><?=display_price(element('ctr_amount', $data))?></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-striped">
				<caption>고객 추가 요청사항</caption>
				<tbody>
					<tr style="background: #9EDFE5">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="sangjo_req">상조요청</label>
								<div id="sangjo_req">
									<?php
									switch (element('sangjo_req', $data)) {
										case 1: echo "요청함"; break;
										case 2: echo "진행완료"; break;
										default: "요청하지 않음"; break;
									}
									?>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="critical_person">위독자</label>
								<div id="critical_person"><?=element('critical_person', $data)?></div>
							</div>
						</td>
					</tr>
					<tr style="background: #9EDFE5">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="hospital_name">병원</label>
								<div id="hospital_name"><?=element('hospital_name', $data)?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="funeralhall">희망장례식장</label>
								<div id="funeralhall"><?=element('funeralhall', $data)?></div>
							</div>
						</td>
					</tr>
					<tr style="background: #C4CDC1">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_req">개장요청</label>
								<div id="tombmig_req">
									<?php
									switch (element('tombmig_req', $data)) {
										case 1: echo "요청함"; break;
										case 2: echo "진행완료"; break;
										default: "요청하지 않음"; break;
									}
									?>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_date">개장일자</label>
								<div id="tombmig_date"><?=element('tombmig_date', $data)?></div>
							</div>
						</td>
					</tr>
					<tr style="background: #C4CDC1">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_loc">묘위치</label>
								<div id="tombmig_loc"><?=element('tombmig_loc', $data)?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_stone">석물여부</label>
								<div id="tombmig_stone"><?=element('tombmig_stone', $data) ? element('tombmig_stone', $data) : "없음"?></div>
							</div>
						</td>
					</tr>
					<tr style="background: #C4CDC1">
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_cremation_type">화장구분</label>
								<div id="tombmig_cremation_type"><?=element('tombmig_cremation_type', $data)?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="tombmig_estimate_price">견적금액</label>
								<div id="tombmig_estimate_price"><?=display_price(element('tombmig_estimate_price', $data))?></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>분양대금 잔금현황</caption>
				<tbody>
					<tr>
						<td>
							<table class="table table-striped" id="payment_list">
								<?php $this->load->view('contract/bootstrap/payment_list', $view); ?>
							</table>
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
								<?php if (element('ctr_remark', $data)) { ?>
								<div class="panel panel-default">
									<div class="panel-body">
										<?=element('ctr_remark', $data)?>
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
								<?php } ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-xs-12">
			<div class="border_button text-center" style="margin-bottom: 40px;">
				<a href="/contract/write/<?=element('ctr_no', $data)?>" class="btn btn-default btn-lg">수정하기</a>
				<a href="/settlement/view/<?=element('ctr_no', $data)?>" class="btn btn-default btn-lg">정산내역 바로가기</a>
			</div>
		</div>
	</div>

</form>

<script>
function send_sms() {
	if (confirm("시설 본부장에게 문자를 발송하시겠습니까?")) {
		$.ajax({
			type: 'POST',
			url: cb_url + '/contract/send_sms',
			data : {
				ctr_no     : $("input[name=ctr_no]").val(),
				cust_name  : $("input[name=cust_name]").val(),
				cust_phone : $("input[name=cust_phone]").val(),
				ctr_mem_id : $("input[name=ctr_mem_id]").val(),
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
$(document).ready(function () {

    $('.multiselect2').select2({
		closeOnSelect: false,
		placeholder: '선택하세요',
	  	allowClear: true
	});

	$("#btn_send_sms").click(function () {
		send_sms();
	});

	$(".btn_sms_recv").click(function () {
		// console.log($(this).data('park_type_cd'), $(this).data('sel_ids'));
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

});
</script>
<?php
// dd($view);