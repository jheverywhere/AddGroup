<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));
extract($view);
// debug_var($data);
$park_type_list = get_park_type_list();
?>
<h3>시설현황발송내역</h3>

<div class="row">
	<div class="col-md-12">
		<form class="form-inline" name="fsearch" autocomplete="off">
			<div class="form-group">
				<input type="date" class="form-control " id="fdate" name="fdate" value="<?=$this->input->get('fdate')?>" placeholder="발송일 시작" autocomplete="off">
				~
				<input type="date" class="form-control " id="edate" name="edate" value="<?=$this->input->get('edate')?>" placeholder="발송일 종료" autocomplete="off">
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
			|
			</div>
			<div class="form-group">
				<input type="text" class="form-control" id="stx" name="stx" value="<?=$this->input->get('stx')?>" placeholder="검색어">
			</div>
			<div class="pull-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> 검색</button>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<form name="flist" class="table-responsive" method="POST" action="/park/list_update">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<!-- <th>
							<input id="chk_all" type="checkbox" class="post_con_chk">
						</th> -->
						<th class="text-center">영업담당</th>
						<th class="text-center">희망시설</th>
						<th class="text-left">관심상품</th>
						<th class="text-center">발송시간</th>
						<th class="text-center" rowspan="2">발송내용</th>
						<!-- <th class="text-center" style="width:100px;">관리</th> -->
					</tr>
					<tr>
						<th class="text-center">고객명</th>
						<th class="text-center">계약공원</th>
						<th class="text-left">계약상품</th>
						<th class="text-center">발송건수</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (element('list', $data) as $i => $row) {
					?>
					<tr class="" data-ctr_no="<?=element('sms_ctr_no', $row)?>">
						<!-- <td class="text-center">
							<input type="checkbox" name="chk[]" id="chk_<?=element('sms_no', $row)?>" class="form-control post_con_chk" value="<?=element('sms_no', $row)?>">
						</td> -->
						<td class="text-center">
							<div class="inner">
								<?=get_highlight_keyword(element('sms_mem_username', $row), $this->input->get('stx'))?><BR>
								<?=get_phone_link(element('sms_mem_phone', $row))?>
							</div>
							<div class="inner">
								<?=get_highlight_keyword(element('sms_cust_name', $row), $this->input->get('stx'))?><BR>
								<?=get_cust_phone_link(element('sms_cust_phone', $row),element('sms_cust_name', $row))?>
							</div>
						</td>
						<td class="clickable text-center">
							<div class="inner">
								<?=get_highlight_keyword(implode('<BR>', array(element('wish_park1_name', $row), element('wish_park2_name', $row), element('wish_park3_name', $row))), $this->input->get('stx'))?>
							</div>
							<div class="inner">
								<?=get_highlight_keyword(element('ctr_park_name', $row), $this->input->get('stx'))?>
							</div>
						</td>
						<td class="clickable text-left">
							<div class="inner">
								<?=get_highlight_keyword(implode('<BR>', array(element('wish_prod1_name', $row), element('wish_prod2_name', $row), element('wish_prod3_name', $row))), $this->input->get('stx'))?>
							</div>
							<div class="inner">
								<?=get_highlight_keyword(element('ctr_prod_name', $row), $this->input->get('stx'))?>
							</div>
						</td>
						<td class="clickable text-center">
							<div class="inner">
								<?=get_highlight_keyword(element('sms_datetime', $row), $this->input->get('stx'))?>
							</div>
							<div class="inner">
								<?=number_format(element('sent_count', $row))?>
							</div>
						</td>
						<td class="text-center">
							<textarea class="form-control" rows="10" style="min-width: 200px;" disabled><?=element('sms_hq_content', $row)?></textarea>
						</td>
						<!-- <td class="clickable text-center">
							<a href="/park/write/<?=element('sms_no', $row)?>" class="btn btn-default btn-xs">수정</a>
						</td> -->
					</tr>
					<?php } ?>
					<?php if (!element('list', $data)) { ?>
					<tr>
						<td colspan="13" class="nopost">표시할 데이터가 없습니다.</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="text-center" style="padding: 20px 0;">
			<nav><?php echo element("pagination", $view);?></nav>
		</div>
	</div>
</div>
<script>
$(function () {
	// $("#chk_all").click(function () {
	// 	$("input[name='chk[]']").prop('checked', $(this).prop('checked'));
	// });

	$("form[name=flist] table td.clickable").click(function () {
		var sms_no = $(this).parent('tr').data('ctr_no');
		window.location.href = "/contract/view/" + sms_no;
	});

	$(".btn_date").click(function () {
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
