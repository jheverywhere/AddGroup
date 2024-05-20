<?php
extract($view);
// debug_var($data);
?>

<div class="modal fade" id="product_form" tabindex="-1" role="dialog" aria-labelledby="product_form_label" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="product_form_label">공원상품</h4>
			</div>
			<div class="modal-body">

				<form name="fproduct" class="form-horizontal" autocomplete="off">
					<input type="hidden" name="park_prod_cd" value="">
					<input type="hidden" name="park_type_cd" value="<?=element('park_type_cd', $data)?>">
					<input type="hidden" name="park_no" value="<?=element('park_no', $data)?>">
					<input type="hidden" name="park_prod_soldout" value="판매중">

					<div class="form-group">
						<label for="park_prod_name" class="col-sm-2 control-label">상품명</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="park_prod_name" name="park_prod_name" value="" required placeholder="상품명">
						</div>
					</div>
					<?php if (element('park_type_cd', $data) == 'CH') { // 납골당 ?>
					<div class="form-group">
						<label for="dan_type" class="col-sm-2 control-label">단종류</label>
						<div class="col-sm-10">
							<select class="form-control select2" name="dan_type" id="dan_type" required required>
								<option value="A1">개인단</option>
								<option value="A2">부부단</option>
								<option value="A3">가족단</option>
								<option value="A4">문중단</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="dan_floor" class="col-sm-2 control-label">단높이</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="number" class="form-control" id="dan_floor" name="dan_floor" value="" required placeholder="단높이">
								<div class="input-group-addon">단</div>
							</div>
						</div>
					</div>
					<?php } else if (element('park_type_cd', $data) == 'NB') { // 수목장 ?>
					<div class="form-group">
						<label for="public_yn" class="col-sm-2 control-label">공동여부</label>
						<div class="col-sm-10">
							<select class="form-control select2" name="public_yn" id="public_yn" required style="width: 100%;">
								<option value="B0">사용안함</option>
								<option value="B1">개인형</option>
								<option value="B2">공동형</option>
								<option value="B3">잔디장</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="tree_type" class="col-sm-2 control-label">수목종류</label>
						<div class="col-sm-10">
							<select class="form-control select2" name="tree_type" id="tree_type" required style="width: 100%;">
								<option value="00">사용안함</option>
								<option value="11">잔디장</option>
								<option value="12">수목형</option>
								<option value="13">분재형</option>
								<option value="14">오엽송</option>
								<option value="15">소나무</option>
								<option value="16">대형소나무</option>
								<option value="17">주목</option>
								<option value="18">코니카</option>
								<option value="19">용송</option>
								<option value="1A">대반송</option>
								<option value="1B">특별목</option>
								<option value="1C">개인목</option>
								<option value="1D">부부목</option>
								<option value="1E">가족목</option>
								<option value="1F">가족목(VIP)</option>
								<option value="1G">특별 가족목</option>
								<option value="1H">대가족목</option>
								<option value="1I">중가족목</option>
								<option value="1J">소가족목</option>
								<option value="1K">소가족목(분재형)</option>
								<option value="1L">중가족목(분재형)</option>
								<option value="1M">중가족목(수목형)</option>
								<option value="1N">대가족목(오엽송)</option>
								<option value="1O">대가족목(소나무)</option>
								<option value="1P">선산형(대형소나무)</option>
								<option value="1Q">공동개인형잔디장</option>
								<option value="1R">공동부부형잔디장</option>
								<option value="1S">공동목</option>
								<option value="1T">가족목1</option>
								<option value="1U">가족목2</option>
								<option value="1V">잔디장개인형</option>
								<option value="1W">잔디장부부형</option>
								<option value="1X">VIP특별목</option>
								<option value="1Y">공동개인형</option>
								<option value="1Z">일반수목(코니카)</option>
								<option value="20">일반수목 (주목, 코니카)</option>
								<option value="21">평장형(부부)</option>
								<option value="22">평장신규단지(부부고급형)</option>
								<option value="23">가족목(오엽송)</option>
								<option value="24">가족목(소나무)</option>
								<option value="25">가족목(용송)</option>
								<option value="26">가족목(대반송)</option>
								<option value="27">공동개인목</option>
								<option value="28">공동부부목</option>
								<option value="29">4위 가족목</option>
								<option value="2A">6위 가족목</option>
								<option value="2B">10위 가족목</option>
								<option value="2C">잔디장 개인형</option>
								<option value="2D">잔디장 부부형</option>
								<option value="2E">가족목 4위</option>
								<option value="2F">가족목 6위</option>
								<option value="2G">가족목 8위</option>
								<option value="2H">잔디수목장</option>
								<option value="2I">가족목(특별목)</option>
								<option value="2J">평장잔디형</option>
								<option value="2K">소나무공동목</option>
								<option value="2L">평장묘</option>
								<option value="2M">잔디형</option>
								<option value="2N">(평장)잔디장</option>
								<option value="A1">개인,부부형</option>
								<option value="A2">대가족, 특별목</option>
							</select>
						</div>
					</div>
					<?php } else if (element('park_type_cd', $data) == 'NB') { // 수목장 ?>
					<?php } ?>
					<div class="form-group">
						<label for="anchisu" class="col-sm-2 control-label">안치수</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="number" class="form-control" id="anchisu" name="anchisu" value="" required placeholder="안치수">
								<div class="input-group-addon">구</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="park_prod_price" class="col-sm-2 control-label">분양가</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="text" class="form-control number_format text-right" id="park_prod_price" name="park_prod_price" value="" required placeholder="분양가">
								<div class="input-group-addon">만원</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="park_prod_fee" class="col-sm-2 control-label">연 관리비</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="text" class="form-control number_format text-right" id="park_prod_fee" name="park_prod_fee" value="" required placeholder="연 관리비">
								<div class="input-group-addon">만원</div>
							</div>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">닫기</button> -->
				<button type="button" class="btn btn-primary btn-sm" onclick="return update_product()">저장하기</button>
			</div>
		</div>
	</div>
</div>

<script>
$(function () {
    $('#product_form').on('show.bs.modal', function (e) {
        enable_product_controls();
    });
    $('#product_form').on('hidden.bs.modal', function (e) {
        disable_product_controls();
    });
    disable_product_controls();
});
function enable_product_controls() {
    $('#product_form input, #product_form select, #product_form textarea, #product_form button').prop('disabled', false);
}
function disable_product_controls() {
    $('#product_form input, #product_form select, #product_form textarea, #product_form button').prop('disabled', true);
}
</script>