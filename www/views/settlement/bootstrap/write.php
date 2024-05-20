<?php

$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));
// $this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
extract($view);
// debug_var($data);

$select_tax_type_list = array(
	array("tax_type" => "비과세"),
	array("tax_type" => "원천세"),
	array("tax_type" => "부가세"),
);
$select_discount_list = array();
$select_mem_commission_rate_list = array();
for ($i = 0; $i <= 100; $i += 5) {
	$select_discount_list[] = array("discount_rate" => $i);
	$select_mem_commission_rate_list[] = array("mem_commission_rate" => $i);
}
?>

<!-- <script type="text/javascript" src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->

<h3>수수료정산</h3>

<form class="form-horizontal" name="fsettlement" id="fsettlement" action="/settlement/write_update" onsubmit="return fsettlement_submit(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">

	<input type="hidden" name="ctr_no" value="<?=element('ctr_no', $data)?>">
	<input type="hidden" name="ctr_status" value="<?=element('ctr_status', $data) ? element('ctr_status', $data) : "신규접수신청"?>" disabled>
	<input type="hidden" name="settle_no" value="<?=element('settle_no', $data)?>">

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
		<div class="col-lg-6 col-md-12 col-xs-12">
			<table class="table">
				<caption>고객 기본정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="mem_username">영업담당</label>
								<div id="mem_username">
									<?=element('mem_username', $data)?>
									<?=get_phone_link(element('mem_phone', $data))?>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="cust_name">고객이름</label>
								<div id="cust_name">
									<?=element('cust_name', $data)?>
									<?=get_cust_phone_link(element('cust_phone', $data), element('cust_name', $data))?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_park_name">추모공원</label>
								<div id="ctr_park_name"><?=element('ctr_park_name', $data)?></div>
							</div>
							<div class="col-md-6 col-md-6 col-xs-6 form-group">
								<label for="ctr_prod_name">상품명</label>
								<div id="ctr_prod_name"><?=element('ctr_prod_name', $data)?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-md-6 col-xs-6 form-group">
								<label for="settle_park_commission_rate">공원 수수료</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="settle_park_commission_rate" name="settle_park_commission_rate" value="<?=element('settle_park_commission_rate', $data) ? element('settle_park_commission_rate', $data) * 100 : ''?>" placeholder="공원 수수료율">
									<div class="input-group-addon">%</div>
								</div>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="settle_park_commission_amount" name="settle_park_commission_amount" value="<?=element('settle_park_commission_amount', $data)?>" placeholder="공원 수수료" readonly>
									<div class="input-group-addon">원</div>
								</div>
							</div>
							<div class="col-md-6 col-md-6 col-xs-6 form-group">
								<label for="ctr_prod_price">분양가</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="ctr_prod_price" name="ctr_prod_price" value="<?=element('ctr_prod_price', $data)?>" disabled placeholder="분양가">
									<div class="input-group-addon">원</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-md-6 col-xs-6 form-group">
								<label for="ctr_discount_amount">할인금액</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="ctr_discount_amount" name="ctr_discount_amount" value="<?=element('ctr_discount_amount', $data)?>" disabled placeholder="할인금액">
									<div class="input-group-addon">원</div>
								</div>
							</div>
							<div class="col-md-6 col-md-6 col-xs-6 form-group">
								<label for="ctr_amount">최종 분양금액</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="ctr_amount" name="ctr_amount" value="<?=element('ctr_amount', $data)?>" disabled placeholder="최종 계약금액">
									<div class="input-group-addon">원</div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>공제내용</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="discount_rate">할인공제</label>
								<div class="input-group">
									<select class="form-control" name="discount_rate" id="discount_rate" required>
										<?php foreach ($select_discount_list as $i => $row) { ?>
										<option value="<?=element('discount_rate', $row)?>"<?=get_selected(element('discount_rate', $row), (element('discount_rate', $data) * 100))?>><?=element('discount_rate', $row)?></option>
										<?php } ?>
									</select>
									<div class="input-group-addon">%</div>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="extra_deduction">기타공제</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="extra_deduction" name="extra_deduction" value="<?=element('extra_deduction', $data)?>" placeholder="기타공제">
									<div class="input-group-addon">원</div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>수익내용</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="income_amount">분양수익</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="income_amount" name="income_amount" value="<?=element('income_amount', $data)?>" placeholder="분양수익" readonly>
									<div class="input-group-addon">원</div>
								</div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="vos">공급가액</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="vos" name="vos" value="<?=element('vos', $data)?>" placeholder="공급가액" disabled>
									<div class="input-group-addon">원</div>
								</div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="vat">부가세</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="vat" name="vat" value="<?=element('vat', $data)?>" placeholder="부가세" disabled>
									<div class="input-group-addon">원</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="sangjo_price">기타수익(상조수익)</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="sangjo_price" name="sangjo_price" value="<?=element('sangjo_price', $data)?>" placeholder="상조수익">
									<div class="input-group-addon">원</div>
								</div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="tombmig_price">기타수익(개장수익)</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="tombmig_price" name="tombmig_price" value="<?=element('tombmig_price', $data)?>" placeholder="개장수익">
									<div class="input-group-addon">원</div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>수수료</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="mem_commission_rate">직원 수수료율</label>
								<div class="input-group">
									<select class="form-control" name="mem_commission_rate" id="mem_commission_rate" required>
										<?php foreach ($select_mem_commission_rate_list as $i => $row) { ?>
										<option value="<?=element('mem_commission_rate', $row)?>"<?=get_selected(element('mem_commission_rate', $row), (element('mem_commission_rate', $data) * 100))?>><?=element('mem_commission_rate', $row)?></option>
										<?php } ?>
									</select>
									<div class="input-group-addon">%</div>
								</div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="tax_type">과세구분</label>
								<select class="form-control" name="tax_type" id="tax_type" required>
									<?php foreach ($select_tax_type_list as $i => $row) { ?>
									<option value="<?=element('tax_type', $row)?>"<?=get_selected(element('tax_type', $row), element('tax_type', $data))?>><?=element('tax_type', $row)?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="mem_net_amount">세후 지급금액</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="mem_net_amount" name="mem_net_amount" value="<?=element('mem_net_amount', $data)?>" placeholder="세후 지급금액">
									<div class="input-group-addon">원</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="sangjo_commission">기타수수료(상조)</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="sangjo_commission" name="sangjo_commission" value="<?=element('sangjo_commission', $data)?>" placeholder="기타수수료(상조)">
									<div class="input-group-addon">원</div>
								</div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="tombmig_commission">기타수수료(개장)</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="tombmig_commission" name="tombmig_commission" value="<?=element('tombmig_commission', $data)?>" placeholder="기타수수료(개장)">
									<div class="input-group-addon">원</div>
								</div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="etc_commission">기타수수료 총 금액</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="etc_commission" name="etc_commission" value="<?=element('etc_commission', $data)?>" placeholder="기타수수료 총 금액" disabled>
									<div class="input-group-addon">원</div>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			
			<div class="border_button text-center" style="margin-bottom: 40px;">
				<button type="submit" class="btn btn-primary btn-lg">저장하기</button>
			</div>

		</div>

		<div class="col-lg-6 col-md-12 col-xs-12">
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

			<table class="table">
				<caption>영업담당 지급내역</caption>
				<tbody>
					<tr>
						<td>
							<div id="payroll_list">
								<?php $this->load->view('settlement/bootstrap/payroll_list', $view); ?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-striped">
				<caption>정산</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="is_settle_complete_time">정산완료 여부</label>
								<div class="input-group">
									<label class="radio-inline"><input type="radio" name="is_settle_complete_time" value="0" <?=element('settle_complete_time', $data) ? '' : ' checked'?>>미완료</label>
									<label class="radio-inline"><input type="radio" name="is_settle_complete_time" value="1" <?=element('settle_complete_time', $data) ? ' checked' : ''?>>정산완료</label>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="settle_complete_time">정산완료 시간</label>
								<input type="text" class="form-control" id="settle_complete_time" value="<?=element('settle_complete_time', $data)?>" placeholder="정산완료 시간" disabled>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="settle_remark">메모</label>
								<p class="help-block">정산관련 특이사항 메모</p>
								<textarea class="form-control" id="settle_remark" name="settle_remark" rows="5"><?=element('settle_remark', $data)?></textarea>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="border_button text-center" style="margin-bottom: 40px;">
				<button type="submit" class="btn btn-primary btn-lg">저장하기</button>
			</div>

		</div>
	</div>

</form>

<style>
.form-horizontal > .form-group {padding: 10px 0;}
</style>

<?php $this->load->view('contract/bootstrap/payment_form', $view); ?>

<?php $this->load->view('settlement/bootstrap/payroll_form', $view); ?>

<script>
function fsettlement_submit(self) {

}
function show_img(self) {
	window.open(self.href , 'show_img', 'width=800,height=800,scrollbars=1');
	return false;
}
function calculate_park_commission_amount() {
	var ctr_prod_price              = numberWithoutCommas($("input[name=ctr_prod_price]").val());
	var settle_park_commission_rate = numberWithoutCommas($("input[name=settle_park_commission_rate]").val()) / 100;
	var settle_park_commission_amount = Math.round(ctr_prod_price * settle_park_commission_rate); // 공원 수수료 금액

	$("#settle_park_commission_amount").val(numberWithCommas(settle_park_commission_amount));
	calculate_income();
}
function calculate_income() {
	var ctr_prod_price                = numberWithoutCommas($("input[name=ctr_prod_price]").val());
	var ctr_discount_amount           = numberWithoutCommas($("input[name=ctr_discount_amount]").val());
	var ctr_amount                    = numberWithoutCommas($("input[name=ctr_amount]").val());
	var settle_park_commission_rate   = numberWithoutCommas($("input[name=settle_park_commission_rate]").val()) / 100;
	var settle_park_commission_amount = numberWithoutCommas($("input[name=settle_park_commission_amount]").val());
	var discount_rate                 = numberWithoutCommas($("#discount_rate").val()) / 100;
	var extra_deduction               = numberWithoutCommas($("#extra_deduction").val());
	var sangjo_price                  = numberWithoutCommas($("#sangjo_price").val());
	var tombmig_price                 = numberWithoutCommas($("#tombmig_price").val());

	var discount_amount = Math.round(settle_park_commission_amount * discount_rate); // 할인공제금액
	var income_amount = settle_park_commission_amount - ctr_discount_amount - discount_amount - extra_deduction; // 공제 후 잔액
	console.log(income_amount, settle_park_commission_amount, discount_amount, extra_deduction);
	var vos = Math.round(income_amount / 1.1); // 공급가액
	var vat = income_amount - vos; // 부가세

	$("#income_amount").val(numberWithCommas(income_amount));
	$("#vos").val(numberWithCommas(vos));
	$("#vat").val(numberWithCommas(vat));
	calculate_commission();
}
function calculate_commission() {
	var income_amount       = numberWithoutCommas($("#income_amount").val());
	var vos                 = numberWithoutCommas($("#vos").val());
	var vat                 = numberWithoutCommas($("#vat").val());
	var mem_commission_rate = numberWithoutCommas($("#mem_commission_rate").val()) / 100;
	var tax_type            = ($("#tax_type").val());

	var mem_commission_amount = Math.round(income_amount * mem_commission_rate);
	var mem_net_amount;
	switch (tax_type) {
		case "원천세":
			var vos_amount = Math.round(vos * mem_commission_rate);
			mem_net_amount = Math.round(vos_amount - (vos_amount * 0.033));
		break;
		case "부가세":
			mem_net_amount = mem_commission_amount;
			// mem_net_amount = Math.round(vos_amount * 1.1);
		break;
		default:
			mem_net_amount = mem_commission_amount;
		break;
	}

	// console.log(mem_net_amount, mem_commission_amount, income_amount, mem_commission_rate);

	// $("#mem_commission_amount").val(numberWithCommas(mem_commission_amount));
	$("#mem_net_amount").val(numberWithCommas(mem_net_amount));
}
function calculate_etc_commission() {
	var mem_commission_rate = numberWithoutCommas($("#mem_commission_rate").val()) / 100;
	var sangjo_price        = numberWithoutCommas($("#sangjo_price").val());
	var tombmig_price       = numberWithoutCommas($("#tombmig_price").val());

	var sangjo_commission  = Math.round(sangjo_price * mem_commission_rate);
	var tombmig_commission = Math.round(tombmig_price * mem_commission_rate);
	var etc_commission     = sangjo_commission + tombmig_commission;

	$("#sangjo_commission").val(numberWithCommas(sangjo_commission));
	$("#tombmig_commission").val(numberWithCommas(tombmig_commission));
	$("#etc_commission").val(numberWithCommas(etc_commission));
}
$(document).ready(function() {
    $('#discount_rate, #mem_commission_rate').select2({
	});

	$("#tax_type").change(function () {
		var $label;
		switch ($(this).val()) {
			case "원천세": $label = '지급금액 (세제 후)'; break;
			case "부가세": $label = '지급금액 (VAT포함)'; break;
			default: $label = '지급금액'; break;
		}
		$("label[for=mem_net_amount]").text($label);
	}).trigger('change');

	$("#settle_park_commission_rate").change(function () {
		calculate_park_commission_amount();
	}).trigger('change');
	$("#discount_rate, #extra_deduction, #ctr_discount_amount, #settle_park_commission_amount").change(function () {
		calculate_income();
	});
	$("#income_amount, #mem_commission_rate, #tax_type").change(function () {
		calculate_commission();
	});
	$("#sangjo_price, #tombmig_price, #mem_commission_rate").change(function () {
		calculate_etc_commission();
	});
	<?php if (!element('settle_no', $data)) { ?>
	calculate_income();
	calculate_commission();
	<?php } ?>
});
</script>