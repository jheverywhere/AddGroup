<?php

$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));
// $this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
extract($view);
// debug_var($data);
?>

<!-- <script type="text/javascript" src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->

<h3>수수료정산</h3>

<form class="form-horizontal" name="fsettlement" id="fsettlement" action="/settlement/write_update" onsubmit="return fsettlement_submit(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8">

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
			<table class="table table-striped">
				<caption>고객 기본정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="mem_username">영업담당</label>
								<div id="mem_username">
									<?=element('mem_username', $data)?><BR>
									<?=get_phone_link(element('mem_phone', $data))?>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="cust_name">고객이름</label>
								<div id="cust_name">
									<?=element('cust_name', $data)?><BR>
									<?=get_cust_phone_link(element('cust_phone', $data),element('cust_name', $data))?>
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
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_prod_name">상품명</label>
								<div id="ctr_prod_name"><?=element('ctr_prod_name', $data)?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="wish_prod2">공원 수수료</label>
								<div id="wish_prod2"><?=get_percent(element('settle_park_commission_rate', $data))." (".display_price(element('settle_park_commission_amount', $data)).")"?></div>
								</select>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_prod_price">분양가</label>
								<div id="ctr_prod_price"><?=display_price(element('ctr_prod_price', $data))?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_discount_amount">할인금액</label>
								<div id="ctr_discount_amount"><?=display_price(element('ctr_discount_amount', $data))?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="ctr_amount">최종 분양금액</label>
								<div id="ctr_amount"><?=display_price(element('ctr_amount', $data))?></div>
								</select>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-striped">
				<caption>공제내용</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="discount_rate">할인공제</label>
								<div id="discount_rate"><?=get_percent(element('discount_rate', $data))?></div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="extra_deduction">기타공제</label>
								<div id="extra_deduction"><?=display_price(element('extra_deduction', $data))?></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-striped">
				<caption>수익내용</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-lg-4 col-md-6 col-xs-6 form-group">
								<label for="income_amount">분양수익</label>
								<div id="income_amount"><?=display_price(element('income_amount', $data))?></div>
							</div>
							<div class="col-lg-4 col-md-6 col-xs-6 form-group">
								<label for="vos">공급가액</label>
								<div id="vos"><?=display_price(round(element('income_amount', $data) / 1.1))?></div>
							</div>
							<div class="col-lg-4 col-md-6 col-xs-6 form-group">
								<label for="vat">부가세</label>
								<div id="vat"><?=display_price(element('income_amount', $data) - round(element('income_amount', $data) / 1.1))?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-lg-4 col-md-6 col-xs-6 form-group">
								<label for="sangjo_price">기타수익(상조수익)</label>
								<div id="sangjo_price"><?=display_price(element('sangjo_price', $data))?></div>
							</div>
							<div class="col-lg-4 col-md-6 col-xs-6 form-group">
								<label for="tombmig_price">기타수익(개장수익)</label>
								<div id="tombmig_price"><?=display_price(element('tombmig_price', $data))?></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-striped">
				<caption>수수료</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="mem_commission_rate">직원 수수료율</label>
								<div id="mem_commission_rate"><?=get_percent(element('mem_commission_rate', $data))?></div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="tax_type">과세구분</label>
								<div id="tax_type"><?=(element('tax_type', $data))?></div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="mem_net_amount">
								<?php
								$label = '';
								switch (element('tax_type', $data)) {
									case "원천세": $label = '지급금액 (세제 후)'; break;
									case "부가세": $label = '지급금액 (VAT포함)'; break;
									default: $label = '지급금액'; break;
								}
								echo $label;
								?>
								</label>
								<div id="mem_net_amount"><?=display_price(element('mem_net_amount', $data))?></div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="sangjo_commission">기타수수료(상조)</label>
								<div id="sangjo_commission"><?=display_price(element('sangjo_commission', $data))?></div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="tombmig_commission">기타수수료(개장)</label>
								<div id="tombmig_commission"><?=display_price(element('tombmig_commission', $data))?></div>
							</div>
							<div class="col-md-4 col-xs-6 form-group">
								<label for="etc_commission">기타수수료 총 금액</label>
								<div id="etc_commission"><?=display_price(element('etc_commission', $data))?></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="col-lg-6 col-md-12 col-xs-12">
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

			<table class="table">
				<caption>영업담당 지급내역</caption>
				<tbody>
					<tr>
						<td>
							<table class="table table-striped" id="payroll_list">
								<?php $this->load->view('settlement/bootstrap/payroll_list', $view); ?>
							</table>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>정산</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="settle_complete_time">정산완료 여부</label>
								<div class="input-group">
									<?php echo element('settle_complete_time', $data) ? "정산완료" : "미완료"; ?>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="settle_complete_time">정산완료 시간</label>
								<div class="input-group">
									<?php echo element('settle_complete_time', $data); ?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="settle_remark">메모</label>
								<div class="input-group">
									<?php echo element('settle_remark', $data); ?>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<?php if ($this->member->item('mem_level') >= 50) { ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-xs-12">
			<div class="border_button text-center" style="margin-bottom: 40px;">
				<a href="/settlement/write/<?=element('ctr_no', $data)?>" class="btn btn-default btn-lg">수정하기</a>
				<a href="/contract/view/<?=element('ctr_no', $data)?>" class="btn btn-default btn-lg">계약내용 바로가기</a>
			</div>
		</div>
	</div>
	<?php } ?>

</form>
