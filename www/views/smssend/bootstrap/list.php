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
<h3>고객문자발송내역</h3>

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
			<?php if ($this->member->item('mem_level') > 20) { ?>
			<!-- <div class="form-group">
				<select class="form-control" name="mem_id" id="mem_id">
					<option value="">==직원명==</option>
					<?php foreach (element('list', $select_mem_id_list) as $i => $row) { ?>
					<option value="<?=element('mem_id', $row)?>"<?=get_selected(element('mem_id', $row), $this->input->get('mem_id'))?>><?=element('mem_username', $row)?></option>
					<?php } ?>
				</select>
			</div> -->
			<?php } ?>
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
						<th class="text-center">발송자</th>
						<th class="text-center">발신번호</th>
						<th class="text-center" style="min-width: 150px;">시도/요청</th>
						<th class="text-center">발송시간</th>
						<th class="text-center">발송내용</th>
						<th class="text-center">발송이미지</th>
						<!-- <th class="text-center" style="width:100px;">관리</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (element('list', $data) as $i => $row) {
						$sml_no = element('sml_no', $row);
					?>
					<tr>
						<!-- <td class="text-center">
							<input type="checkbox" name="chk[]" id="chk_<?=element('sms_no', $row)?>" class="form-control post_con_chk" value="<?=element('sms_no', $row)?>">
						</td> -->
						<td class="text-center">
							<?=element('sml_mem_username', $row)?><br>
							<?=get_phone_link(element('sml_mem_phone', $row))?>
						</td>
						<td class="text-center">
							<?=(element('sml_sender_phone', $row))?>
						</td>
						<td class="text-center">
							<?=element('sml_try', $row).' / '.element('sml_request', $row)?>
							<?php
							$recv_total_rows = element('total_rows', element('recv_list', $row));
							$recv_list = element('list', element('recv_list', $row));
							foreach ($recv_list as $recv) {
								$smr_cust_name = element('smr_cust_name', $recv);
								$smr_cust_phone = get_phone(element('smr_cust_phone', $recv));
								$smr_fail_msg = element('smr_fail_msg', $recv);
								if ($smr_fail_msg) {
									echo "<p class=\"text-danger\">{$smr_cust_name} ({$smr_cust_phone}): {$smr_fail_msg}</p>".PHP_EOL;
								} else {
									echo "<p class=\"text-success\">{$smr_cust_name} ({$smr_cust_phone})</p>".PHP_EOL;
								}
							}
							if ($recv_total_rows > 10) { // 우선 10명까지만 보여주고, 그 이상은 더보기로 처리
								echo "<button type=\"button\" class=\"btn btn-default btn-block btn-xs\" role=\"button\" data-toggle=\"popover\" data-content=\"\" value=\"{$sml_no}\" onclick=\"show_recv_list(this)\">더보기</button>".PHP_EOL;
							}
							?>
						</td>
						<td class="text-center">
							<?=(element('sml_datetime', $row))?>
						</td>
						<td class="text-center">
							<textarea class="form-control" style="min-width: 200px;min-height: 200px;" disabled><?=element('sml_content', $row)?></textarea>
						</td>
						<td class="text-center">
							<?php
							$sml_imgfile = element('sml_imgfile', $row);
							if ($sml_imgfile) {
								$sml_imgurl = site_url('uploads/mms/'.$sml_imgfile);
								$thumb_url = thumb_url('mms', $sml_imgfile, 0, 200);
							?>
								<a href="<?=$sml_imgurl?>" class="" target="_blank">
									<img src="<?=$thumb_url?>" alt="발송이미지">
								</a>
							<?php } ?>
						</td>
						<!-- <td class="text-center">
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

	// $("form[name=flist] table td.clickable").click(function () {
	// 	var sms_no = $(this).parent('tr').data('ctr_no');
	// 	window.location.href = "/contract/view/" + sms_no;
	// });

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
function show_recv_list(self) {
	console.log(self.value);
	$.ajax({
		url : cb_url + '/smssend/ajax_recv_list?sml_no='+self.value,
		type : 'get',
		dataType : 'html',
		success : function(data) {
			console.log(data);
			// for (var i=0; i<data.total_rows; i++) {
			// 	console.log(data.list[i].smr_cust_phone);
			// }
			$(self).popover({
				html: true,
				placement: 'auto',
				content: data,
			}).popover('show');
		}
	});
}
</script>
<style>
div.popover-content ol li {list-style: inherit;margin-left: 10px;}
</style>