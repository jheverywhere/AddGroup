<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
// $this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
extract($view);
$select_tax_type_list = array(
	array("tax_type" => "비과세"),
	array("tax_type" => "원천세"),
	array("tax_type" => "부가세"),
);
?>
<script type="text/javascript" src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<div class="board">
	<h3>직원<?=($mem_id) ? "수정" : "등록"?></h3>

	<form name="fmember" class="form-horizontal" action="/members/write_update" method="POST">
		<div class="memorial_box">
			<div class="form-horizontal box-table">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_teamname">부서</label>
					<div class="col-sm-10">
						<select class="form-control" name="mem_teamname">
							<?php foreach ($teamname_list as $i => $row) { ?>
							<option value="<?=element('name', $row)?>"<?=get_selected(element('name', $row), element('mem_teamname', $data))?>><?=element('name', $row)?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="mem_userid" class="col-sm-2 control-label">아이디</label>
					<div class="col-sm-10" style="display:table;">
						<input type="hidden" name="mem_id" value="<?=element('mem_id', $data)?>">
						<input type="text" class="form-control" name="mem_userid" id="mem_userid" value="<?=element('mem_userid', $data)?>" required <?=($mem_id) ? "readonly" : ""?>/>
					</div>
				</div>
				<div class="form-group">
					<label for="mem_password" class="col-sm-2 control-label">비밀번호</label>
					<div class="col-sm-10" style="display:table;">
						<input type="hidden" name="mem_password" value="<?=element('mem_password', $data)?>">
						<input type="text" class="form-control" name="mem_password" id="mem_password" value="" placeholder="<?=element('mem_password', $data) ? "변경할 비밀번호" : "비밀번호"?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="mem_username" class="col-sm-2 control-label">성명</label>
					<div class="col-sm-10" style="display:table;">
						<input type="text" class="form-control" name="mem_username" id="mem_username" value="<?=element('mem_username', $data)?>" required />
					</div>
				</div>
				<!-- <div class="form-group">
					<label for="mem_regnum" class="col-sm-2 control-label">주민등록번호</label>
					<div class="col-sm-10" style="display:table;">
						<input type="text" class="form-control" name="mem_regnum" id="mem_regnum" value="<?=element('mem_regnum', $data)?>" required />
					</div>
				</div> -->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_phone">전화</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="text" class="form-control" name="mem_phone" id="mem_phone" value="<?=element('mem_phone', $data)?>" />
							<div class="input-group-addon"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="post_2">주소</label>
					<div class="col-sm-10">
						<label for="mem_zipcode">우편번호</label>
						<label>
							<input type="text" name="mem_zipcode" value="<?php echo set_value('mem_zipcode', element('mem_zipcode', $data)); ?>" id="mem_zipcode" class="form-control" size="7" maxlength="7" required />
						</label>
						<label>
							<button type="button" class="btn btn-black btn-sm" style="margin-top:0px;" onclick="win_zip('fmember', 'mem_zipcode', 'mem_address1', 'mem_address2', 'mem_address3', 'mem_address4', 'mem_dong_cd');">주소 검색</button>
						</label>
						<div class="addr-line">
							<label for="mem_address1">기본주소</label>
							<input type="text" name="mem_address1" value="<?php echo set_value('mem_address1', element('mem_address1', $data)); ?>" id="mem_address1" class="form-control" size="50" placeholder="기본주소" required />
						</div>
						<div class="addr-line">
							<label for="mem_address2">상세주소</label>
							<input type="text" name="mem_address2" value="<?php echo set_value('mem_address2', element('mem_address2', $data)); ?>" id="mem_address2" class="form-control" size="50" placeholder="상세주소" />
						</div>
						<label for="mem_address3">참고항목</label>
						<input type="text" name="mem_address3" value="<?php echo set_value('mem_address3', element('mem_address3', $data)); ?>" id="mem_address3" class="form-control" size="50" readonly="readonly" placeholder="참고항목" />
						<input type="hidden" name="mem_address4" value="<?php echo set_value('mem_address4', element('mem_address4', $data)); ?>" required />

					</div>
				</div>
				<!-- <div class="form-group">
					<label class="col-sm-2 control-label" for="mem_grade">직위/직급</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mem_grade" id="mem_grade" value="<?=element('mem_grade', $data)?>" />
					</div>
				</div> -->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_commission_rate">수수료율</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="text" class="form-control" name="mem_commission_rate" id="mem_commission_rate" value="<?=element('mem_commission_rate', $data) * 100?>" />
							<div class="input-group-addon">%</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_tax_type">과세구분</label>
					<div class="col-sm-10">
						<select class="form-control" name="mem_tax_type" id="mem_tax_type" required>
							<?php foreach ($select_tax_type_list as $i => $row) { ?>
							<option value="<?=element('tax_type', $row)?>"<?=get_selected(element('tax_type', $row), element('mem_tax_type', $data))?>><?=element('tax_type', $row)?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_bank_name">급여 은행</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mem_bank_name" id="mem_bank_name" value="<?=element('mem_bank_name', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_bank_account">급여 계좌번호</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mem_bank_account" id="mem_bank_account" value="<?=element('mem_bank_account', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_bank_account">급여 예금주</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mem_bank_owner" id="mem_bank_owner" value="<?=element('mem_bank_owner', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_join_date">입사일자</label>
					<div class="col-sm-10">
						<input type="text" class="form-control datepicker" name="mem_join_date" id="mem_join_date" value="<?=element('mem_join_date', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_resign_date">퇴사일자</label>
					<div class="col-sm-10">
						<input type="text" class="form-control datepicker" name="mem_resign_date" id="mem_resign_date" value="<?=element('mem_resign_date', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_resign_reason">퇴직사유</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mem_resign_reason" id="mem_resign_reason" value="<?=element('mem_resign_reason', $data)?>" />
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 text-center">
				<div class="btn-group" role="group" style="padding-bottom: 20px;">
					<!-- <button type="submit" class="btn btn-success btn-lg">저장하기</button> -->
					<button type="button" class="btn btn-success btn-lg" onclick="document.fmember.submit();">저장하기</button>
				</div>
			</div>
		</div>

	</form>
</div>

<script>
function add_category_row(self) {
	$.ajax({
		url : "/Memorialpark/category_row/"+self.value,
		type : 'GET',
		success : function(data) {
			$("#tbl_"+self.value+" tbody").append(data);
		},
		error: function (err) {
			console.error(err);
		}
	});
}

function act_del(self) {
	var target1 = $(self).closest('tr');
	var target2 = target1.next('tr');
	target1.remove();
	target2.remove();
}
</script>