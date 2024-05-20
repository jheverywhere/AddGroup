<?php
extract($view);
// debug_var($mode, $payment_list);
?>

<div class="modal fade" id="payment_form" tabindex="-1" role="dialog" aria-labelledby="payment_form_label" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="payment_form_label">결제내용</h4>
			</div>
			<div class="modal-body">

				<form name="fpayment" class="form-horizontal" autocomplete="off">
					<input type="hidden" name="payment_no" value="">
					<input type="hidden" name="ctr_no" value="">
					<div class="form-group">
						<label for="payment_date" class="col-sm-2 control-label">결제날짜</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="text" class="form-control" id="payment_date" name="payment_date" value="<?=date("Y-m-d")?>" placeholder="날짜">
								<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="payment_type" class="col-sm-2 control-label">결제내용</label>
						<div class="col-sm-10">
							<select class="form-control" name="payment_type" id="payment_type" required>
								<option value="계약금">계약금</option>
								<option value="중도금">중도금</option>
								<option value="잔금">잔금</option>
								<option value="할인">할인</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="payment_method" class="col-sm-2 control-label">결제방법</label>
						<div class="col-sm-10">
							<select class="form-control" name="payment_method" id="payment_method" required>
								<option value="현금">현금</option>
								<option value="신용카드">신용카드</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="payment_price" class="col-sm-2 control-label">결제금액</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="text" class="form-control number_format text-right" id="payment_price" name="payment_price" value="" placeholder="결제금액">
								<div class="input-group-addon">만원</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="payment_content" class="col-sm-2 control-label">메모</label>
						<div class="col-sm-10">
							<textarea class="form-control" name="payment_content" rows="10"></textarea>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">닫기</button> -->
				<button type="button" class="btn btn-primary btn-sm" onclick="update_payment()">저장하기</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function () {
    $('#payment_form').on('show.bs.modal', function (e) {
        enable_payment_controls();
    });
    $('#payment_form').on('hidden.bs.modal', function (e) {
        disable_payment_controls();
    });
    disable_payment_controls();
});
function enable_payment_controls() {
    $('#payment_form input, #payment_form select, #payment_form textarea, #payment_form button').prop('disabled', false);
}
function disable_payment_controls() {
    $('#payment_form input, #payment_form select, #payment_form textarea, #payment_form button').prop('disabled', true);
}
$('#payment_date').datepicker({
    format: 'yyyy-mm-dd',
    language: 'kr',
    autoclose: true,
    todayHighlight: true,
    orientation: 'bottom'
});
</script>
