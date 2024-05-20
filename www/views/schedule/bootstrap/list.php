<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));
extract($view);
$history_type_list = get_history_type_list();
?>
<h3>일정관리</h3>

<div class="row">
	<div class="col-md-12">
		<form class="form-inline" name="fsearch" autocomplete="off">
			<div class="form-group">
				<select class="form-control" name="history_type" id="history_type">
					<option value="">==항목==</option>
					<?php foreach ($history_type_list as $history_type => $history_type_opt) { ?>
					<option value="<?=$history_type?>" style="background : <?=element('color', $history_type_opt)?>;color: #000;"<?=get_selected($history_type, $this->input->get('history_type'))?>><?=$history_type?></option>
					<?php } ?>
				</select>
			</div>
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
			|
			</div>
			<div class="form-group">
				<input type="date" class="form-control " id="fdate" name="fdate" value="<?=element('fdate', $view)?>" placeholder="날짜시간 시작" autocomplete="off">
				~
				<input type="date" class="form-control " id="edate" name="edate" value="<?=element('edate', $view)?>" placeholder="날짜시간 종료" autocomplete="off">
			</div>
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
			<div class="form-group">
				<input type="text" class="form-control" id="stx" name="stx" value="<?=$this->input->get('stx')?>" placeholder="고객명 또는 고객휴대폰">
			</div>
			<div class="pull-right">
				<button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> 검색</button>
				<a href="/contract/write" class="btn btn-info">고객등록</a>
				<a href="/contract" class="btn btn-success">진행조회</a>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<form name="flist" class="table-responsive" method="POST" action="/contract/del">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>
							<!-- <input id="chk_all" type="checkbox" class="post_con_chk"> -->
						</th>
						<th class="text-center th_name">항목</th>
						<th class="text-center th_date">날짜시간</th>
						<th class="text-center th_date">요일</th>
						<th class="text-center th_name">영업담당</th>
						<th class="text-center th_name">고객명</th>
						<th class="text-center">고객휴대폰</th>
						<th class="text-center th_park_name">희망시설</th>
						<th class="text-center th_park_name">관심상품</th>
						<th class="text-center th_date">계약일</th>
						<th class="text-center th_park_name">계약공원</th>
						<th class="text-center th_park_name">계약상품</th>
						<th class="text-left">메모</th>
						<th class="text-center" style="width:100px;">진행상태</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (element('list', $data) as $i => $row) {
						$history_type_opt = element(element('history_type', $row), $history_type_list);
						$bgcolor = element('color', $history_type_opt);
						$style = "background: {$bgcolor};color: #000;";
					?>
					<tr class="" data-ctr_no="<?=element('ctr_no', $row)?>">
						<td class="text-center">
							<!-- <input type="checkbox" name="chk[]" id="chk_<?=element('ctr_no', $row)?>" class="post_con_chk" value="<?=element('ctr_no', $row)?>"> -->
							<?php // echo element('total_rows', $data) - (element('per_page', $data) * element('page', $data)) - $i?>
						</td>
						<td class="clickable text-center" style="<?=$style?>">
							<?=element('history_type', $row)?>
						</td>
						<td class="clickable text-center">
							<?=get_ymdhi_string(element('history_date', $row))?>
						</td>
						<td class="clickable text-center">
							<?php
							$week_string = get_week_string(element('history_date', $row));
							if ('토' == $week_string) {
								echo "<strong class='bg-primary' style='padding: 10px'>".$week_string.'</strong>';
							} else if ('일' == $week_string) {
								echo "<strong class='bg-danger' style='padding: 10px'>".$week_string.'</strong>';
							} else {
								echo $week_string;
							}
							?>
						</td>
						<td class="clickable text-center">
							<?=element('mem_username', $row)?>
						</td>
						<td class="clickable text-center">
							<?=element('cust_name', $row)?>
						</td>
						<td class="text-center">
							<?=get_cust_phone_link(element('cust_phone', $row), element('cust_phone', $row))?>
						</td>
						<td class="clickable">
							<?=implode('<BR>', array(element('wish_park1_name', $row), element('wish_park2_name', $row), element('wish_park3_name', $row)))?>
						</td>
						<td class="clickable">
							<?=implode('<BR>', array(element('wish_prod1_name', $row), element('wish_prod2_name', $row), element('wish_prod3_name', $row)))?>
						</td>
						<td class="clickable text-center">
							<?=get_ymd_string(element('ctr_date', $row))?>
						</td>
						<td class="clickable">
							<?=element('ctr_park_name', $row)?>
						</td>
						<td class="clickable">
							<?=element('ctr_prod_name', $row)?>
						</td>
						<td class="clickable text-left">
							<?=element('history_content', $row)?>
						</td>
						<td class="text-center">
							<select class="form-control" name="history_status" data-history_no="<?=element('history_no', $row)?>">
								<option class="bg-info" value=""<?=get_selected('', element('history_status', $row))?>>예정</option>
								<option class="bg-danger" value="취소"<?=get_selected('취소', element('history_status', $row))?>>취소</option>
								<option class="bg-success" value="진행완료"<?=get_selected('진행완료', element('history_status', $row))?>>진행완료</option>
							</select>
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
		</form>
		<div class="text-center" style="padding: 20px 0;">
			<nav><?php echo element("pagination", $view);?></nav>
		</div>
	</div>
</div>
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

	$(".status_btn").click(function () {
		window.location.href=$(this).data('href');
	});

	$('#mem_id').select2({
	});

	$("#mem_id, #history_type").change(function () {
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
		$("form[name=fsearch]").submit();
	});
})
</script>
<?php
// debug_var($view);