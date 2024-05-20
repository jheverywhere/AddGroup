<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));
extract($view);
// debug_var($data);
?>
<h3>정산관리</h3>
<div class="row">
	<div class="col-md-12">
		<form class="form-inline" name="fsearch" autocomplete="off">
			<?php if ($this->member->item('mem_level') > 20) { ?>
			<div class="form-group">
				<select class="form-control" name="mem_id" id="mem_id">
					<option value="">==직원명==</option>
					<?php foreach (element('list', $select_mem_id_list) as $i => $row) { ?>
					<option value="<?=element('mem_id', $row)?>"<?=get_selected(element('mem_id', $row), $this->input->get('mem_id'))?>><?=element('mem_username', $row)?></option>
					<?php } ?>
				</select>
			</div>
			<?php } ?>
			<div class="form-group">
				<select class="form-control" name="settle_complete" id="settle_complete">
					<option value="">==정산여부==</option>
					<option value="정산완료" <?=get_selected('정산완료', $this->input->get('settle_complete'))?>>정산완료</option>
					<option value="미정산" <?=get_selected('미정산', $this->input->get('settle_complete'))?>>미정산</option>
				</select>
			</div>
			<!-- <div class="form-group">
				<select class="form-control" name="ctr_status" id="ctr_status">
					<option value="">==진행상태==</option>
					<?php foreach ($select_ctr_status_list as $i => $row) { ?>
					<option value="<?=element('ctr_status', $row)?>"<?=get_selected(element('ctr_status', $row), $this->input->get('ctr_status'))?>><?=element('ctr_status', $row)?></option>
					<?php } ?>
				</select>
			</div> -->
			<!-- <div class="form-group">
				<select class="form-control" name="sales_fee_yn" id="sales_fee_yn">
					<option value="">==직원수수료 지급여부==</option>
					<option value="1">지급완료</option>
					<option value="0">미지급</option>
				</select>
			</div> -->
			<div class="form-group">
			|
			</div>
			<div class="form-group">
				<input type="date" class="form-control " id="fdate" name="fdate" value="<?=$this->input->get('fdate')?>" placeholder="접수일 시작" autocomplete="off">
				~
				<input type="date" class="form-control " id="edate" name="edate" value="<?=$this->input->get('edate')?>" placeholder="접수일 종료" autocomplete="off">
			</div>
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-sm btn-default btn_date" value="1">오늘</button>
				<button type="button" class="btn btn-sm btn-default btn_date" value="2">어제</button>
				<button type="button" class="btn btn-sm btn-default btn_date" value="3">이번주</button>
				<button type="button" class="btn btn-sm btn-default btn_date" value="4">지난주</button>
				<button type="button" class="btn btn-sm btn-default btn_date" value="5">이번달</button>
				<button type="button" class="btn btn-sm btn-default btn_date" value="6">지난달</button>
			</div>
			<div class="form-group">
				<input type="text" class="form-control" id="stx" name="stx" value="<?=$this->input->get('stx')?>" placeholder="고객명 또는 고객휴대폰">
			</div>
			<div class="pull-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> 검색</button>
				<!-- <button type="button" class="btn btn-success" onclick="return download_excel(this);"><i class="fa fa-table" aria-hidden="true"></i> 엑셀다운로드</button> -->
				<a href="/contract/write" class="btn btn-default">고객등록</a>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<form class="table-responsive" name="flist">
			<table id="tbl_member" class="table table-hover table-striped">
				<thead>
					<tr>
						<th class="text-center">No</th>
						<th class="text-center th_date" style="width:50px;">계약일</th>
						<th class="text-center th_name">영업담당</th>
						<th class="text-center th_name">고객명</th>
						<th class="text-center">고객휴대폰</th>
						<th class="text-center th_park_name">추모공원</th>
						<th class="text-center th_park_name">상품명</th>
						<th class="text-center">분양가</th>
						<th class="text-center">공원 수수료</th>
						<th class="text-center">할인금액</th>
						<th class="text-center">최종 분양금액</th>
						<th class="text-center">미수금</th>
						<th class="text-center">할인공제</th>
						<th class="text-center">기타공제</th>
						<th class="text-center">상조수익</th>
						<th class="text-center">개장수익</th>
						<th class="text-center">분양수익</th>
						<th class="text-center">직원 수수료율</th>
						<th class="text-center">지급금액</th>
						<th class="text-center th_date">지급일자</th>
						<?php if ($this->member->item("mem_level") >= 50) { ?>
						<th class="text-center">회사수익</th>
						<th class="text-center" style="width:100px;">관리</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$total_income_amount = 0;
					$total_misu = 0;
					$total = array(
						"ctr_prod_price" => 0,
						"settle_park_commission_amount" => 0,
						"ctr_discount_amount" => 0,
						"ctr_amount" => 0,
						"discount_rate" => 0,
						"extra_deduction" => 0,
						"sangjo_price" => 0,
						"tombmig_price" => 0,
						"income_amount" => 0,
						"sum_payroll_amount" => 0,
						"sum_payment_price" => 0,
					);
					foreach (element('list', $data) as $i => $row) {
						$highlight = $this->Mp_Contract_model->get_ctr_status_highlight(element('ctr_status', $row));
						foreach ($total as $sum_key => $sum_val) {
							$total[$sum_key] = $sum_val + element($sum_key, $row);
						}
						$total_income_amount += element('income_amount', $row) - element('sum_payroll_amount', $row);
						$misu = element('sum_payment_price', $row) - element('ctr_amount', $row);
						$total_misu += $misu;
					?>
					<tr data-id="<?=element('ctr_no', $row)?>">
						<td class="text-center clickable">
							<?php echo element('total_rows', $data) - element('offset', $data) - $i?>
						</td>
						<td class="text-center clickable"><?=get_ymd_string(element('ctr_date', $row))?></td>
						<td class="text-center clickable"><?=element('mem_username', $row)?></td>
						<td class="text-center clickable"><?=element('cust_name', $row)?></td>
						<td class="text-center          "><?=get_cust_phone_link(element('cust_phone', $row), element('cust_name', $row))?></td>
						<td class="text-left   clickable"><?=element('ctr_park_name', $row)?></td>
						<td class="text-left   clickable"><?=element('ctr_prod_name', $row)?></td>
						<td class="text-center clickable"><?=display_price(element('ctr_prod_price', $row), '만')?></td>
						<td class="text-center clickable"><?=get_percent(element('settle_park_commission_rate', $row))."<BR>".display_price(element('settle_park_commission_amount', $row), '만')?></td>
						<td class="text-center clickable"><?=display_price(element('ctr_discount_amount', $row), '만')?></td>
						<td class="text-center clickable"><strong><?=display_price(element('ctr_amount', $row), '만')?></strong></td>
						<td class="text-center clickable"><?=display_price($misu, '만')?></td>
						<td class="text-center clickable"><?=display_price(element('discount_rate', $row), '만')?></td>
						<td class="text-center clickable"><?=display_price(element('extra_deduction', $row), '만')?></td>
						<td class="text-center clickable"><?=display_price(element('sangjo_price', $row), '만')?></td>
						<td class="text-center clickable"><?=display_price(element('tombmig_price', $row), '만')?></td>
						<td class="text-center clickable"><?=display_price(element('income_amount', $row), '만')?></td>
						<td class="text-center clickable"><?=get_percent(element('mem_commission_rate', $row))?></td>
						<td class="text-center clickable"><?=display_price(element('sum_payroll_amount', $row), '만')?></td>
						<td class="text-center clickable"><?=get_ymd_string(element('last_payroll_date', $row))?></td>
						<?php if ($this->member->item("mem_level") >= 50) { ?>
						<td class="text-center clickable"><?=display_price(element('income_amount', $row) - element('sum_payroll_amount', $row), '만')?></td>
						<td class="text-center">
							<a href="/settlement/write/<?=element('ctr_no', $row)?>" class="btn btn-default btn-xs">수정</a>
							<?php if (element('settle_complete_time', $row)) { ?>
							<a href="/settlement/view/<?=element('ctr_no', $row)?>" class="btn btn-success btn-xs">정산완료</a>
							<?php } else if ($this->member->item("mem_level") >= 50) { ?>
							<a href="/settlement/write/<?=element('ctr_no', $row)?>" class="btn btn-danger btn-xs">미정산</a>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
					<?php if (!$data) { ?>
					<tr>
						<td colspan="22" class="nopost">표시할 데이터가 없습니다.</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<th class="text-center" colspan="7">
							합계
						</th>
						<th class="text-center"><?=display_price(element('ctr_prod_price', $total))?></th>
						<th class="text-center"><?=display_price(element('settle_park_commission_amount', $total))?></th>
						<th class="text-center"><?=display_price(element('ctr_discount_amount', $total))?></th>
						<th class="text-center"><?=display_price(element('ctr_amount', $total))?></th>
						<th class="text-center"><?=display_price($total_misu)?></th>
						<th class="text-center"><?=display_price(element('discount_rate', $total))?></th>
						<th class="text-center"><?=display_price(element('extra_deduction', $total))?></th>
						<th class="text-center"><?=display_price(element('sangjo_price', $total))?></th>
						<th class="text-center"><?=display_price(element('tombmig_price', $total))?></th>
						<th class="text-center"><?=display_price(element('income_amount', $total))?></th>
						<th class="text-center">-</th>
						<th class="text-center"><?=display_price(element('sum_payroll_amount', $total))?></th>
						<th class="text-center">&nbsp;</th>
						<?php if ($this->member->item("mem_level") >= 50) { ?>
						<th class="text-center"><?=display_price($total_income_amount)?></th>
						<th class="text-center">&nbsp;</th>
						<?php } ?>
					</tr>
				</tfoot>
			</table>
		</form>
		<div class="text-center" style="padding: 20px 0;">
			<nav><?php echo element("pagination", $view);?></nav>
		</div>
	</div>
</div>

<script>
function add_category_row(self) {
	$.ajax({
		url : "/Memorialpark/category_row/"+self.value,
		type : 'GET',
		success : function(data) {
			$("#tbl_"+self.value+" tbody").append(data);
		},
		error: function (err) {
			console.error(err);
		}
	});
}

function act_del(self) {
	var target1 = $(self).closest('tr');
	var target2 = target1.next('tr');
	target1.remove();
	target2.remove();
}
$(function () {
	$("form[name=flist] table td.clickable").click(function () {
		var id = $(this).parent('tr').data('id');
		window.location.href = "/settlement/view/" + id;
	});
	
	$("#mem_id, #ctr_status, #settle_complete").change(function () {
		$("form[name=fsearch]").submit();
	});

	$(".btn_date").click(function () {
		console.log("hahah");
		var fdate, edate;
		switch ($(this).val()) {
			case '1': fdate = moment().format("YYYY-MM-DD"); edate = moment().format("YYYY-MM-DD"); break;
			case '2': fdate = moment().subtract(1, 'days').format("YYYY-MM-DD"); edate = moment().subtract(1, 'days').format("YYYY-MM-DD"); break;
			case '3': fdate = moment().startOf('isoWeek').format("YYYY-MM-DD"); edate = moment().format("YYYY-MM-DD"); break;
			case '4': fdate = moment().subtract(1, 'weeks').startOf('isoWeek').format("YYYY-MM-DD"); edate = moment().subtract(1, 'weeks').endOf('isoWeek').format("YYYY-MM-DD"); break;
			case '5': fdate = moment().startOf('month').format("YYYY-MM-DD"); edate = moment().format("YYYY-MM-DD"); break;
			case '6': fdate = moment().subtract(1, 'month').startOf('month').format("YYYY-MM-DD"); edate = moment().subtract(1, 'month').endOf('month').format("YYYY-MM-DD"); break;
		}
		$("#fdate").val(fdate);
		$("#edate").val(edate);
		$("form[name=fsearch]").submit();
	});

})
</script>