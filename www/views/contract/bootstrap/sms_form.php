<?php
extract($view);
// debug_var($data);
// debug_var($park_list);
$phone_list = array();
foreach ($sms_recv_list as $i => $row) {
	$phone_list[] = element('park_manager_phone', $row);
}
debug_var($phone_list);
?>

<div class="modal fade" id="sms_form" tabindex="-1" role="dialog" aria-labelledby="sms_form_label" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="sms_form_label">현황 문자보내기</h4>
			</div>
			<div class="modal-body">

				<form name="fsms" class="form-horizontal" autocomplete="off">
					<input type="hidden" name="sms_no" value="">
					<input type="hidden" name="ctr_no" value="">
					<div class="form-group">
						<label for="sms_content" class="col-sm-2 control-label">수신자</label>
						<div class="col-sm-10">
							<input type="hidden" name="sms_recv" value="<?=implode(",", $phone_list)?>">
						</div>
					</div>
					<div class="form-group">
						<label for="sms_recv_NB" class="col-sm-2 control-label">수목장 현황 보내기</label>
						<div class="col-sm-10" style="border: 1px solid #cccccc;padding: 10px;">
							<select class="form-control multiselect2" id="sms_recv_NB" multiple>
								<option value="">문자 수신 시설을 선택하세요</option>
								<?php foreach (element('NB', $park_list) as $i => $row) { ?>
									<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park1', $data))?>><?=element('park_real_name', $row)?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="sms_recv_CH" class="col-sm-2 control-label">납골당 현황 보내기</label>
						<div class="col-sm-10" style="border: 1px solid #cccccc;padding: 10px;">
							<select class="form-control multiselect2" id="sms_recv_CH" multiple>
								<option value="">문자 수신 시설을 선택하세요</option>
								<?php foreach (element('CH', $park_list) as $i => $row) { ?>
									<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park1', $data))?>><?=element('park_real_name', $row)?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="sms_recv_CT" class="col-sm-2 control-label">납골묘 현황 보내기</label>
						<div class="col-sm-10" style="border: 1px solid #cccccc;padding: 10px;">
							<select class="form-control multiselect2" id="sms_recv_CT" multiple>
								<option value="">문자 수신 시설을 선택하세요</option>
								<?php foreach (element('CT', $park_list) as $i => $row) { ?>
									<option value="<?=element('park_no', $row)?>"<?=get_selected(element('park_no', $row), element('wish_park1', $data))?>><?=element('park_real_name', $row)?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="sms_content" class="col-sm-2 control-label">문자내용</label>
						<div class="col-sm-10" style="border: 1px solid #cccccc;padding: 10px;">
							<p class="text-left" style="margin: 0px;">
								홍길동<BR>
								010-1111-2222<BR>
								로뎀파크<BR>
								부부목<BR>
							</p>
							<textarea class="form-control" name="sms_content" rows="2" placeholder="전달 메시지">답사일정</textarea>
							<p class="text-left">
								그리운그대<BR>
								영업사원이름
							</p>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" onclick="send_sms()"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>&nbsp;발송</button>
			</div>
		</div>
	</div>
</div>

<script>
function send_sms() {
	$.ajax({
		type: 'POST',
		url: cb_url + '/contract/send_sms',
		data : {
			ctr_no : $("#ctr_no").val(),
			sms_recv : ""
		},
		async: false,
		success : function(data) {
			$("#sms_sent_history").html(data);
		}
	});
}
$(document).ready(function() {
	$("#sms_form").modal('show');
});
</script>
