<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));
extract($view);
// $data = array();
// debug_var($select_mem_id_list);
// debug_var($select_ctr_status_list);
$ctr_status_list = array();
$ctr_status_list[$this->input->get('ctr_status')] = 'active';
// debug_var($data);
?>
<h3>계약현황</h3>
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="status_btn text-center bg-primary" data-href="/contract">총 현황</th>
					<th class="status_btn text-center bg-info" data-href="/contract?ctr_status=진행중">진행중</th>
					<th class="status_btn text-center bg-warning" data-href="/contract?ctr_status=계약완료">계약완료</th>
					<th class="status_btn text-center bg-success" data-href="/contract?ctr_status=잔금완료">잔금완료</th>
					<th class="status_btn text-center active" data-href="/contract?ctr_status=진행불발">진행불발</th>
					<th class="status_btn text-center bg-danger" data-href="/contract?ctr_status=계약취소">취소</th>
				</tr>
				<tr>
					<td class="status_btn text-center <?=element('', $ctr_status_list)?>" data-href="/contract"><?=number_format(element('total', $summary))?></td>
					<td class="status_btn text-center <?=element('진행중', $ctr_status_list)?>" data-href="/contract?ctr_status=진행중"><?=number_format(element('continue', $summary))?></td>
					<td class="status_btn text-center <?=element('계약완료', $ctr_status_list)?>" data-href="/contract?ctr_status=계약완료"><?=number_format(element('hold', $summary))?></td>
					<td class="status_btn text-center <?=element('잔금완료', $ctr_status_list)?>" data-href="/contract?ctr_status=잔금완료"><?=number_format(element('complete', $summary))?></td>
					<td class="status_btn text-center <?=element('진행불발', $ctr_status_list)?>" data-href="/contract?ctr_status=진행불발"><?=number_format(element('misfire', $summary))?></td>
					<td class="status_btn text-center <?=element('계약취소', $ctr_status_list)?>" data-href="/contract?ctr_status=계약취소"><?=number_format(element('removal', $summary))?></td>
				</tr>
			</thead>
		</table>
	</div>
</div>

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
				<select class="form-control" name="ctr_status" id="ctr_status">
					<option value="">==진행상태==</option>
					<option value="진행중"<?=get_selected("진행중", $this->input->get('ctr_status'))?>>진행중</option>
					<?php foreach ($select_ctr_status_list as $i => $row) { ?>
					<option value="<?=element('ctr_status', $row)?>"<?=get_selected(element('ctr_status', $row), $this->input->get('ctr_status'))?>><?=element('ctr_status', $row)?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
			|
			</div>
			<div class="form-group">
				<input type="date" class="form-control " id="fdate" name="fdate" value="<?=$this->input->get('fdate')?>" placeholder="계약일 시작" autocomplete="off">
				~
				<input type="date" class="form-control " id="edate" name="edate" value="<?=$this->input->get('edate')?>" placeholder="계약일 종료" autocomplete="off">
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
				<input type="text" class="form-control" id="stx" name="stx" value="<?=$this->input->get('stx')?>" placeholder="고객명 또는 고객휴대폰">
			</div>
			<div class="form-group">
			|
			</div>
			<div class="form-group">
				<select class="form-control" name="listnum" id="listnum">
					<option value="20"<?=get_selected("20", admin_listnum())?>>20개</option>
					<option value="50"<?=get_selected("50", admin_listnum())?>>50개</option>
					<option value="100"<?=get_selected("100", admin_listnum())?>>100개</option>
					<option value="200"<?=get_selected("200", admin_listnum())?>>200개</option>
					<option value="500"<?=get_selected("500", admin_listnum())?>>500개</option>
					<option value="1000"<?=get_selected("1000", admin_listnum())?>>1000개</option>
				</select>
			</div>
			<div class="pull-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> 검색</button>
				<!-- <button type="button" class="btn btn-success" onclick="return download_excel(this);"><i class="fa fa-table" aria-hidden="true"></i> 엑셀다운로드</button> -->
				<button type="button" class="btn btn-info" id="btn_smssend">문자보내기</button>
				<a href="/contract/write" class="btn btn-default">고객등록</a>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<form name="flist" class="table-responsive" method="POST" onsubmit="return flist_submit(this);" action="/contract/del">
			<h4>목록</h4>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>
							<input id="chk_all" type="checkbox" class="post_con_chk">
						</th>
						<th class="text-center th_date"><a href="<?php echo element('accept_date', element('sort', $view)); ?>">접수일</a></th>
						<th><a href="<?php echo element('ctr_date', element('sort', $view)); ?>">계약일</a></th>
						<th class="text-center">진행상태</th>
						<th class="text-center">영업담당</th>
						<th class="text-center th_name">고객명</th>
						<th class="text-center">고객휴대폰</th>
						<th class="text-center th_park_name">희망시설</th>
						<th class="text-center th_park_name">관심상품</th>
						<th class="text-center th_park_name">계약공원</th>
						<th class="text-center th_park_name">계약상품</th>
						<th class="text-center">상조요청</th>
						<th class="text-center">개장요청</th>
						<th class="text-center" style="width:100px;">관리</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (element('list', $data) as $i => $row) {
						$highlight = $this->Mp_Contract_model->get_ctr_status_highlight(element('ctr_status', $row));
					?>
					<tr data-ctr_no="<?=element('ctr_no', $row)?>">
						<td class="text-center">
							<input type="checkbox" name="chk[]" id="chk_<?=element('ctr_no', $row)?>" class="post_con_chk" value="<?=element('ctr_no', $row)?>">
							<!-- <?php echo element('total_rows', $data) - (element('per_page', $data) * (element('page', $data) - $i))?> -->
						</td>
						<td class="clickable text-center">
							<?=get_ymdhi_string(element('accept_date', $row))?>
						</td>
						<td class="clickable text-center">
							<?=get_ymd_string(element('ctr_date', $row))?>
						</td>
						<td class="clickable text-center">
							<?php if (in_array(element('ctr_status', $row), array('계약완료'))) { ?>
							<span class="label label-warning"><?=element('ctr_status', $row)?></span>
							<?php } else if (in_array(element('ctr_status', $row), array('잔금완료'))) { ?>
							<span class="label label-success"><?=element('ctr_status', $row)?></span>
							<?php } else if (in_array(element('ctr_status', $row), array('담당배정','1차상담','2차상담','방문답사'))) { ?>
							<span class="label label-info"><?=element('ctr_status', $row)?></span>
							<?php } else { ?>
							<span class="label label-default"><?=element('ctr_status', $row)?></span>
							<?php } ?>
						</td>
						<td class="clickable text-center">
							<?=element('mem_username', $row)?>
						</td>
						<td class="clickable text-center">
							<?=element('cust_name', $row)?>
						</td>
						<td class="text-center">
							<?=get_cust_phone_link(element('cust_phone', $row),element('cust_name', $row))?>
						</td>
						<td class="clickable">
							<?=implode('<BR>', array(element('wish_park1_name', $row), element('wish_park2_name', $row), element('wish_park3_name', $row)))?>
						</td>
						<td class="clickable">
							<?=implode('<BR>', array(element('wish_prod1_name', $row), element('wish_prod2_name', $row), element('wish_prod3_name', $row)))?>
						</td>
						<td class="clickable">
							<?=element('ctr_park_name', $row)?>
						</td>
						<td class="clickable">
							<?=element('ctr_prod_name', $row)?>
						</td>
						<td class="clickable text-center">
							<?php if (element('sangjo_req', $row) == '1') { ?>
							<span class="label label-warning">요청함</span>
							<?php } else if (element('sangjo_req', $row) == '2') { ?>
							<span class="label label-success">진행완료</span>
							<?php } else { ?>
							-
							<?php } ?>
						</td>
						<td class="clickable text-center">
							<?php if (element('tombmig_req', $row) == '1') { ?>
							<span class="label label-warning">요청함</span>
							<?php } else if (element('tombmig_req', $row) == '2') { ?>
							<span class="label label-success">진행완료</span>
							<?php } else { ?>
							-
							<?php } ?>
						</td>
						<td class="clickable text-center">
							<a href="/contract/write/<?=element('ctr_no', $row)?>" class="btn btn-default btn-xs">수정</a>
							<?php if (element('settle_complete_time', $row)) { ?>
							<a href="/settlement/view/<?=element('ctr_no', $row)?>" class="btn btn-success btn-xs">정산완료</a>
							<?php } else if ($this->member->item("mem_level") >= 50) { ?>
							<a href="/settlement/write/<?=element('ctr_no', $row)?>" class="btn btn-danger btn-xs">미정산</a>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<?php if (!element('list', $data)) { ?>
					<tr>
						<td colspan="14" class="nopost">표시할 데이터가 없습니다.</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="text-left" style="padding: 20px 0;">
				<button type="submit" class="btn btn-default btn-sm" name="btn_submit" value="del">삭제</button>
			</div>
		</form>
		<div class="text-center" style="padding: 20px 0;">
			<nav><?php echo element("pagination", $view);?></nav>
		</div>
	</div>
</div>

<script>
function flist_submit(self) {
	if (!confirm("정말 삭제하시겠습니까?")) {
		return false;
	}
	return true;
}
function download_excel(self) {
	var f = document.querySelector("form[name=fsearch]");
	var old_action = f.action;
	f.method = 'POST';
	f.action = "/contract/download_excel";
	f.submit();
	f.method = 'GET';
	f.action = old_action;
	return false;
}
$(function () {
	$("#chk_all").click(function () {
		$("input[name='chk[]']").prop('checked', $(this).prop('checked'));
	});

	$(".status_btn").click(function () {
		console.log('status_btn', $(this).data('href'));
		window.location.href=$(this).data('href');
	});

	$('#mem_id').select2({
	});

	$("#mem_id, #ctr_status").change(function () {
		$("form[name=fsearch]").submit();
	});

	$("form[name=flist] table td.clickable, form[name=unreceived_list] table td.clickable").click(function () {
		var ctr_no = $(this).parent('tr').data('ctr_no');
		<?php if ($this->member->item('mem_teamname') == '장착부') { ?>
		window.location.href = "/contract/install/" + ctr_no;
		<?php } else { ?>
		window.location.href = "/contract/view/" + ctr_no;		
		<?php } ?>
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
	$("#listnum").change(function () {
		$("form[name=fsearch]").submit();
	});
	$("#btn_smssend").click(function () {
		var f = document.querySelector("form[name=flist]");
		var old_action = f.action;
		f.action = "/smssend/form";
		f.submit();
		f.action = old_action;
	});
})
</script>
