<?php
extract($view);
$history_type_list = get_history_type_list();
// debug_var($history_list);
?>

<div class="modal fade" id="history_form" tabindex="-1" role="dialog" aria-labelledby="history_form_label" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="history_form_label">진행내용</h4>
			</div>
			<div class="modal-body">

				<form name="fhistory" class="form-horizontal" autocomplete="off">
					<input type="hidden" name="history_no" value="">
					<input type="hidden" name="ctr_no" value="">
					<div class="form-group">
						<label for="history_date" class="col-sm-2 control-label">날짜</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="text" class="form-control" id="history_date" name="history_date" value="<?=date("Y-m-d H:i")?>" placeholder="날짜">
								<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="history_type" class="col-sm-2 control-label">항목</label>
						<div class="col-sm-10">
							<select class="form-control" name="history_type" id="history_type" required>
								<?php foreach ($history_type_list as $history_type => $history_type_opt) { ?>
								<option value="<?=$history_type?>" style="background : <?=element('color', $history_type_opt)?>;color: #000;"<?=get_selected($history_type, $this->input->get('history_type'))?>><?=$history_type?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="history_content" class="col-sm-2 control-label">세부내용</label>
						<div class="col-sm-10">
							<textarea class="form-control" name="history_content" rows="10"></textarea>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">닫기</button> -->
				<button type="button" class="btn btn-primary btn-sm" onclick="update_history()">저장하기</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function () {
    // $('#history_type').select2({
    //     tags: true
	// });
	$('#history_date').datetimepicker({
		dayViewHeaderFormat: "YYYY년 MM월",
		format: 'YYYY-MM-DD HH:mm',
		locale: 'ko',
		stepping: 30, 
		widgetPositioning: {
			horizontal: 'left',
			vertical: 'bottom'
		}
	});
    $('#history_form').on('show.bs.modal', function (e) {
        enable_history_controls();
    });
    $('#history_form').on('hidden.bs.modal', function (e) {
        disable_history_controls();
    });
    disable_history_controls();
});
function enable_history_controls() {
    $('#history_form input, #history_form select, #history_form textarea, #history_form button').prop('disabled', false);
}
function disable_history_controls() {
    $('#history_form input, #history_form select, #history_form textarea, #history_form button').prop('disabled', true);
}
</script>
