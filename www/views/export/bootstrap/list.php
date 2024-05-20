<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));
extract($view);
$history_type_list = get_history_type_list();
// $fdate = $this->input->get('fdate') ? $this->input->get('fdate') : date("Y-m-d");
// $edate = $this->input->get('edate') ? $this->input->get('edate') : date("Y-m-d");
$fdate = $this->input->get('fdate');
$edate = $this->input->get('edate');
?>
<h3>엑셀 다운로드</h3>

<form class="form-horizontal" name="fsearch" autocomplete="off">
	<div class="row">
		<div class="col-md-6">
				<?php if ($this->member->item('mem_level') > 10) { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label">검색대상</label>
					<div class="col-sm-10">
						<select class="form-control" name="dest" id="dest" required>
							<option value="일정현황"<?php echo get_selected($this->input->get("dest"), "일정현황") ?>>일정현황</option>
							<option value="계약현황"<?php echo get_selected($this->input->get("dest"), "계약현황") ?>>계약현황</option>
							<option value="정산내역"<?php echo get_selected($this->input->get("dest"), "정산내역") ?>>정산내역</option>
						</select>
					</div>
				</div>
				<?php } ?>
				<?php if ($this->member->item('mem_level') > 20) { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label">직원명</label>
					<div class="col-sm-10">
						<select class="form-control" name="mem_id" id="mem_id">
							<option value="">==직원명==</option>
							<?php foreach (element('list', $select_mem_id_list) as $i => $row) { ?>
							<option value="<?=element('mem_id', $row)?>"<?=get_selected(element('mem_id', $row), $this->input->get('mem_id'))?>><?=element('mem_username', $row)?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<?php } ?>
				<div class="form-group">
					<label class="col-sm-2 control-label">항목</label>
					<div class="col-sm-10">
						<select class="form-control" name="history_type" id="history_type" required>
							<option value="">==항목==</option>
							<?php foreach ($history_type_list as $history_type => $history_type_opt) { ?>
							<option value="<?=$history_type?>" style="background : <?=element('color', $history_type_opt)?>;color: #000;"<?=get_selected($history_type, $this->input->get('history_type'))?>><?=$history_type?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">진행상태</label>
					<div class="col-sm-10">
						<select class="form-control" name="ctr_status" id="ctr_status">
							<option value="">==진행상태==</option>
							<option value="진행중"<?=get_selected("진행중", $this->input->get('ctr_status'))?>>진행중</option>
							<?php foreach ($select_ctr_status_list as $i => $row) { ?>
							<option value="<?=element('ctr_status', $row)?>"<?=get_selected(element('ctr_status', $row), $this->input->get('ctr_status'))?>><?=element('ctr_status', $row)?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">정산여부</label>
					<div class="col-sm-10">
						<select class="form-control" name="settle_complete" id="settle_complete">
							<option value="">==정산여부==</option>
							<option value="정산완료" <?=get_selected('정산완료', $this->input->get('settle_complete'))?>>정산완료</option>
							<option value="미정산" <?=get_selected('미정산', $this->input->get('settle_complete'))?>>미정산</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">계약날짜</label>
					<div class="col-sm-10 form-inline">
						<input type="date" class="form-control " id="fdate" name="fdate" value="<?php echo $fdate; ?>" placeholder="날짜시간 시작" autocomplete="off">
						~
						<input type="date" class="form-control " id="edate" name="edate" value="<?php echo $edate; ?>" placeholder="날짜시간 종료" autocomplete="off">
						<div class="btn-group" role="group">
							<button type="button" class="btn btn-sm btn-default btn_date" value="-2">그제</button>
							<button type="button" class="btn btn-sm btn-default btn_date" value="-1">어제</button>
							<button type="button" class="btn btn-sm btn-default btn_date" value="0">오늘</button>
							<button type="button" class="btn btn-sm btn-default btn_date" value="1">내일</button>
							<button type="button" class="btn btn-sm btn-default btn_date" value="3">3일예정</button>
							<button type="button" class="btn btn-sm btn-default btn_date" value="4">7일예정</button>
							<button type="button" class="btn btn-sm btn-default btn_date" value="5">1달예정</button>
							<!-- <button type="button" class="btn btn-sm btn-default btn_date" value="9">전체보기</button> -->
						</div>
					</div>
				</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<button type="button" class="btn btn-block btn-lg btn-success" id="excel_download"><i class="fa fa-search" aria-hidden="true"></i> 엑셀 다운로드</button>
		</div>	
	</div>
</form>

<script>
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
function set_history_status_color(history_status, $this) {
	switch (history_status) {
		case "취소": $this.css("background", "#F2DEDE"); break;
		case "진행완료": $this.css("background", "#DFF0D8"); break;
		default: $this.css("background", "#D9EDF7"); break;
	}
}
$(function () {
	$("#chk_all").click(function () {
		$("input[name='chk[]']").prop('checked', $(this).prop('checked'));
	});

	$("#excel_download").click(function () {
		console.log("excel_downlonad");
		$("form[name=fsearch]")
			.attr("action", "/export/download")
			.attr("method", "POST")
			.submit();

		$("form[name=fsearch]").attr("action", "")
			.attr("method", "GET");
	});

	$(".status_btn").click(function () {
		window.location.href=$(this).data('href');
	});

	$('#mem_id').select2({
	});

	$("select[name=history_status]").change(function () {
		var $this = $(this);
		var history_no = $(this).data('history_no');
		var history_status = $(this).val();
		$.ajax({
			type: 'POST',
			url: cb_url + '/schedule/set_status',
			data : {
				history_no    : history_no,
				history_status: history_status
			},
			async: false,
			success : function(data) {
				if (data == true) {
					set_history_status_color(history_status, $this);
				}
			}
		});
	});
	for (var $i = 0; $i < $("select[name=history_status]").length; $i++) {
		var $this = $($("select[name=history_status]")[$i]);
		set_history_status_color($this.val(), $this);
	}
	// set_history_status_color($("select[name=history_status]").val());

	$("form[name=flist] table td.clickable, form[name=unreceived_list] table td.clickable").click(function () {
		var ctr_no = $(this).parent('tr').data('ctr_no');
		window.location.href = "/contract/view/" + ctr_no;		
	});

	$(".btn_date").click(function () {
		var fdate, edate;
		switch ($(this).val()) {
			case '-2': fdate = moment().add(-2, 'days').format("YYYY-MM-DD"); edate = moment().add(-2, 'days').format("YYYY-MM-DD"); break;
			case '-1': fdate = moment().add(-1, 'days').format("YYYY-MM-DD"); edate = moment().add(-1, 'days').format("YYYY-MM-DD"); break;
			case '0': fdate = moment().format("YYYY-MM-DD"); edate = moment().format("YYYY-MM-DD"); break;
			case '1': fdate = moment().add(1, 'days').format("YYYY-MM-DD"); edate = moment().add(1, 'days').format("YYYY-MM-DD"); break;
			case '3': fdate = moment().add(1, 'days').format("YYYY-MM-DD"); edate = moment().add(3, 'days').format("YYYY-MM-DD"); break;
			case '4': fdate = moment().add(1, 'days').format("YYYY-MM-DD"); edate = moment().add(7, 'days').format("YYYY-MM-DD"); break;
			case '5': fdate = moment().add(1, 'days').format("YYYY-MM-DD"); edate = moment().add(1, 'month').format("YYYY-MM-DD"); break;
			case '9': fdate = "0000-00-00"; edate = "0000-00-00"; break;
		}
		$("#fdate").val(fdate);
		$("#edate").val(edate);
		// $("form[name=fsearch]").submit();
	});
})
</script>
<?php
// debug_var($view);