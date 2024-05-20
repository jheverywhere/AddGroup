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
<h3>공원관리</h3>

<div class="row">
	<div class="col-md-12">
		<form class="form-inline" name="fsearch" autocomplete="off">
			<div class="form-group">
				<select class="form-control" name="park_type_cd" id="park_type_cd">
					<option value="">==공원형식==</option>
					<?php foreach ($park_type_list as $park_type_cd => $park_type_name) { ?>
						<option value="<?=$park_type_cd?>"<?=get_selected($park_type_cd, $this->input->get('park_type_cd'))?>><?=$park_type_name?></option>
					<?php } ?>}
				</select>
			</div>
			<div class="form-group">
				<select class="form-control" name="park_use" id="park_use">
					<option value="">==사용여부==</option>
					<option value="0"<?=get_selected(0, $this->input->get('park_use'))?>>사용안함</option>
					<option value="1"<?=get_selected(1, $this->input->get('park_use'))?>>사용중</option>
				</select>
			</div>
			<div class="form-group">
			|
			</div>
			<div class="form-group">
				<input type="text" class="form-control" id="stx" name="stx" value="<?=$this->input->get('stx')?>" placeholder="검색어">
			</div>
			<div class="pull-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> 검색</button>
				<a href="/park/write" class="btn btn-default">공원추가</a>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<form name="flist" class="table-responsive" method="POST" onsubmit="return flist_submit(this);" action="/park/list_update">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>
							<input id="chk_all" type="checkbox" class="post_con_chk">
						</th>
						<th class="text-center">출력순서</th>
						<th class="text-center">공원번호</th>
						<th class="text-center th_park_name">공원 정식명칭</th>
						<th class="text-center th_park_name">장지114시설명</th>
						<th class="text-center">본부장</th>
						<th class="text-center">공원형식</th>
						<th class="text-center">사용여부</th>
						<th class="text-left">공원주소</th>
						<th class="text-center" style="width:100px;">관리</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (element('list', $data) as $i => $row) {
					?>
					<tr class="" data-park_no="<?=element('park_no', $row)?>">
						<td class="text-center">
							<input type="checkbox" name="chk[]" id="chk_<?=element('park_no', $row)?>" class="post_con_chk" value="<?=element('park_no', $row)?>"<?php echo get_disabled_for_level_20() ?>>
						</td>
						<td class="text-center col-xs-1">
							<input type="number" class="form-control" name="odr[<?=element('park_no', $row)?>]" value="<?=element('park_order', $row)?>" style="padding: 6px;"<?php echo get_disabled_for_level_20() ?>>
						</td>
						<td class="clickable text-center">
							<?=get_highlight_keyword(element('park_no', $row), $this->input->get('stx'))?>
						</td>
						<td class="clickable text-left">
							<?=get_highlight_keyword(element('park_real_name', $row).(element('park_manager_phone_masked', $row) == "Y" ? "(*)" : ""), $this->input->get('stx'))?>
						</td>
						<td class="clickable text-left">
							<?=get_highlight_keyword(element('park_name', $row), $this->input->get('stx'))?>
						</td>
						<td class="clickable text-center">
							<?=get_highlight_keyword(element('park_manager_name', $row), $this->input->get('stx'))?><BR>
							<?=get_phone_link(element('park_manager_phone', $row))?>
						</td>
						<td class="clickable text-center">
							<?=get_park_type_name(element('park_type_cd', $row))?>
						</td>
						<td class="clickable text-center">
							<?php if (element('park_use', $row) == 1) { ?>
								<span class="label label-success">사용중</span>
							<?php } else { ?>
								<span class="label label-danger">사용안함</span>
							<?php } ?>
						</td>
						<td class="clickable text-left">
							<?=get_highlight_keyword(combine_address(element('park_addr1', $row), element('park_addr2', $row), element('park_addr3', $row)), $this->input->get('stx'))?>
						</td>
						<td class="clickable text-center">
							<a href="/park/write/<?=element('park_no', $row)?>" class="btn btn-default btn-xs">수정</a>
						</td>
					</tr>
					<?php } ?>
					<?php if (!element('list', $data)) { ?>
					<tr>
						<td colspan="13" class="nopost">표시할 데이터가 없습니다.</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div style="padding: 20px 0;">
				<button type="submit" class="btn btn-default btn-sm" name="btn_submit" value="del"<?php echo get_disabled_for_level_20() ?>>삭제</button>
				<button type="submit" class="btn btn-default btn-sm" name="btn_submit" value="edt"<?php echo get_disabled_for_level_20() ?>>수정</button>
			</div>
		</form>
		<div class="text-center" style="padding: 20px 0;">
			<nav><?php echo element("pagination", $view);?></nav>
		</div>
	</div>
</div>
<script>
function flist_submit(self) {
	if (!confirm("정말 삭제 또는 수정하시겠습니까?")) {
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

	$(".status_btn").click(function () {
		window.location.href=$(this).data('href');
	});

	$("#park_type_cd, #park_use").change(function () {
		$("form[name=fsearch]").submit();
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

	$("form[name=flist] table td.clickable").click(function () {
		var park_no = $(this).parent('tr').data('park_no');
		window.location.href = "/park/write/" + park_no;
	});

	$(".btn_date").click(function () {
		var fdate, edate;
		switch ($(this).val()) {
			case '1': fdate = moment().format("YYYY-MM-DD"); edate = moment().format("YYYY-MM-DD"); break;
			case '2': fdate = moment().add(1, 'days').format("YYYY-MM-DD"); edate = moment().add(1, 'days').format("YYYY-MM-DD"); break;
			case '3': fdate = moment().format("YYYY-MM-DD"); edate = moment().add(2, 'days').format("YYYY-MM-DD"); break;
			case '4': fdate = moment().format("YYYY-MM-DD"); edate = moment().add(7, 'days').format("YYYY-MM-DD"); break;
			case '5': fdate = moment().format("YYYY-MM-DD"); edate = moment().add(30, 'days').format("YYYY-MM-DD"); break;
		}
		$("#fdate").val(fdate);
		$("#edate").val(edate);
		$("form[name=fsearch]").submit();
	});
})
</script>
