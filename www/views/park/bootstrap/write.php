<?php

$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment.min.js'));
$this->managelayout->add_js(base_url('assets/js/moment-with-locales.min.js'));
$this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
extract($view);
// debug_var($data);
// debug_var($product_list);
$park_type_list = get_park_type_list();
?>

<h3>공원관리</h3>

<form class="form-horizontal" name="fwrite" id="fwrite" action="/park/write_update" onsubmit="return fwrite_submit(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">

	<div class="row">
		<div class="col-lg-6 col-md-12 col-xs-12">
			<table class="table">
				<caption>고객 기본정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="park_no">공원 번호</label>
								<input type="text" class="form-control" id="park_no" name="park_no" value="<?=element('park_no', $data)?>" readonly placeholder="공원번호 자동생성">
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="$park_type_cd">공원형식</label>
								<select class="form-control" name="park_type_cd" id="park_type_cd" required<?php echo get_readonly_for_level_20() ?>>
									<?php foreach ($park_type_list as $park_type_cd => $park_type_name) { ?>
										<option value="<?=$park_type_cd?>"<?=get_selected($park_type_cd, element('park_type_cd', $data))?>><?=$park_type_name?></option>
									<?php } ?>}
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="park_commission_rate">공원 수수료율</label>
								<div class="input-group">
									<input type="text" class="form-control number_format text-right" id="park_commission_rate" name="park_commission_rate" value="<?=element('park_commission_rate', $data) ? element('park_commission_rate', $data) * 100 : ''?>" required<?php echo get_readonly_for_level_20() ?> placeholder="공원 수수료율">
									<div class="input-group-addon">%</div>
								</div>
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="park_use">사용여부</label>
								<select class="form-control" name="park_use" id="park_use" required<?php echo get_readonly_for_level_20() ?>>
									<option value="0"<?=get_selected(0, element('park_use', $data))?>>사용안함</option>
									<option value="1"<?=get_selected(1, element('park_use', $data))?>>사용중</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="park_real_name">공원 정식명칭</label>
								<input type="text" class="form-control" id="park_real_name" name="park_real_name" value="<?=element('park_real_name', $data)?>" required<?php echo get_readonly_for_level_20() ?> placeholder="공원 정식명칭">
							</div>
							<div class="col-md-6 col-xs-6 form-group">
								<label for="park_name">장지114 시설명</label>
								<input type="text" class="form-control" id="park_name" name="park_name" value="<?=element('park_name', $data)?>" required<?php echo get_readonly_for_level_20() ?> placeholder="장지114 시설명">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="park_manager_name">본부장 이름</label>&nbsp;&nbsp;<small>여러명인경우 ; 으로 구분해주세요.</small>
								<input type="text" class="form-control" id="park_manager_name" name="park_manager_name" value="<?=element('park_manager_name', $data)?>"<?php echo get_readonly_for_level_20() ?> placeholder="본부장 이름">
							</div>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="park_manager_phone">본부장 휴대폰</label>&nbsp;&nbsp;<small>여러명인경우 ; 으로 구분해주세요.</small>
								<div class="input-group">
									<input type="text" class="form-control" id="park_manager_phone" name="park_manager_phone" value="<?=element('park_manager_phone', $data)?>"<?php echo get_readonly_for_level_20() ?> placeholder="본부장 휴대폰">
									<div class="input-group-addon"><a href="tel:#" onclick="return call_first_manager_phone(this)"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></a></div>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="park_manager_phone">고객 연락처 일부 감추기 여부</label>
								<select class="form-control" name="park_manager_phone_masked" id="park_manager_phone_masked" required<?php echo get_readonly_for_level_20() ?>>
									<option value="N"<?=get_selected('N', element('park_manager_phone_masked', $data))?>>사용안함</option>
									<option value="Y"<?=get_selected('Y', element('park_manager_phone_masked', $data))?>>사용중</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="park_desc">주소</label>
								<div class="col-sm-12">
									<label for="park_zipcode">우편번호</label>
									<label>
										<input type="text" name="park_zipcode" value="<?php echo set_value('park_zipcode', element('park_zipcode', element('data', $view))); ?>" id="park_zipcode" class="form-control" size="7" maxlength="7"<?php echo get_readonly_for_level_20() ?> />
									</label>
									<label>
										<button type="button" class="btn btn-black btn-sm" style="margin-top:0px;" onclick="win_zip('fwrite', 'park_zipcode', 'park_addr1', 'park_addr2', 'park_addr3', 'park_addr4');"<?php echo get_disabled_for_level_20() ?>>주소 검색</button>
									</label>
									<div class="addr-line">
										<label for="park_addr1">기본주소</label>
										<input type="text" name="park_addr1" value="<?php echo set_value('park_addr1', element('park_addr1', element('data', $view))); ?>" id="park_addr1" class="form-control" size="50" placeholder="기본주소"<?php echo get_readonly_for_level_20() ?> />
									</div>
									<div class="addr-line">
										<label for="park_addr2">상세주소</label>
										<input type="text" name="park_addr2" value="<?php echo set_value('park_addr2', element('park_addr2', element('data', $view))); ?>" id="park_addr2" class="form-control" size="50" placeholder="상세주소"<?php echo get_readonly_for_level_20() ?> />
									</div>
									<label for="park_addr3">참고항목</label>
									<input type="text" name="park_addr3" value="<?php echo set_value('park_addr3', element('park_addr3', element('data', $view))); ?>" id="park_addr3" class="form-control" size="50" readonly="readonly" placeholder="참고항목" />
									<input type="hidden" name="park_addr4" value="<?php echo set_value('park_addr4', element('park_addr4', element('data', $view))); ?>" />
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="park_desc">공원설명</label>
								<textarea class="form-control" id="park_desc" name="park_desc" rows="5"<?php echo get_readonly_for_level_20() ?>><?=element('park_desc', $data)?></textarea>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="border_button text-center" style="margin-bottom: 40px;">
				<button type="submit" class="btn btn-success btn-lg"<?php echo get_disabled_for_level_20() ?>>저장하기</button>
			</div>

		</div>

		<div class="col-lg-6 col-md-12 col-xs-12">
			<table class="table table-striped">
				<caption>상품목록</caption>
				<tbody>
					<tr>
						<td>
							<div id="product_list">
								<?php
								if (element('park_no', $data)) {
									$this->load->view('park/bootstrap/product_list', $view);
								} else {
									echo "공원정보를 등록해야 상품목록을 확인하실 수 있습니다.";
								}
								?>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

	</div>

</form>

<style>
.form-horizontal > .form-group {padding: 10px 0;}
</style>

<?php $this->load->view('park/bootstrap/product_form', $view); ?>

<script>
function fwrite_submit(self) {

}
function call_first_manager_phone(self) {
	var phoneNumberString = $("#park_manager_phone").val();
	var phoneNumberArray = phoneNumberString.trim().split(';');
	var firstPhoneNumber = phoneNumberArray[0];
	if (firstPhoneNumber.trim() == '') {
		alert("전화번호가 비어있습니다.");
		return false;
	}

	self.href = "tel:" + firstPhoneNumber;
	return true;
}
$(document).ready(function() {
    $('#discount_rate, #mem_commission_rate').select2({
	});
});
</script>
<?php
// dd($view);