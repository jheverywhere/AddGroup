<?php
extract($view);
?>

<div class="modal fade" id="payroll_form" tabindex="-1" role="dialog" aria-labelledby="payroll_form_label" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="payroll_form_label">지급내용</h4>
			</div>
			<div class="modal-body">

				<form name="fpayroll" class="form-horizontal" autocomplete="off">
					<input type="hidden" name="payroll_no" value="">
					<input type="hidden" name="ctr_no" value="">
					<div class="form-group">
						<label for="payroll_date" class="col-sm-2 control-label">지급날짜</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="text" class="form-control datepicker" id="payroll_date" name="payroll_date" value="<?=date("Y-m-d")?>" placeholder="날짜">
								<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="payroll_amount" class="col-sm-2 control-label">지급금액</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="text" class="form-control number_format text-right" id="payroll_amount" name="payroll_amount" value="" placeholder="지급금액">
								<div class="input-group-addon">원</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="payroll_content" class="col-sm-2 control-label">지급내용</label>
						<div class="col-sm-10">
							<textarea class="form-control" name="payroll_content" rows="10"></textarea>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">닫기</button> -->
				<button type="button" class="btn btn-primary btn-sm" onclick="update_payroll()">저장하기</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function () {
    $('#payroll_form').on('show.bs.modal', function (e) {
        enable_payroll_controls();
    });
    $('#payroll_form').on('hidden.bs.modal', function (e) {
        disable_payroll_controls();
    });
    disable_payroll_controls();
});
function enable_payroll_controls() {
    $('#payroll_form input, #payroll_form select, #payroll_form textarea, #payroll_form button').prop('disabled', false);
}
function disable_payroll_controls() {
    $('#payroll_form input, #payroll_form select, #payroll_form textarea, #payroll_form button').prop('disabled', true);
}
$('#payroll_date').datepicker({
    format: 'yyyy-mm-dd',
    language: 'kr',
    autoclose: true,
    todayHighlight: true,
    orientation: 'bottom'
});
</script>