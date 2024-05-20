<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
// $this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');

extract($view);
// debug_var($data);

$teamname_list = array(
	'영업부' => '영업부',
	'장착부' => '장착부',
);
?>
<script type="text/javascript" src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<div class="board">
	<h3>직원 <?=($mem_id) ? "수정" : "등록"?></h3>

	<form name="fadminwrite" class="form-horizontal" action="/members/write_update" method="POST">
		<div class="memorial_box">
			<div class="form-horizontal box-table">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_teamname">부서</label>
					<div class="col-sm-10">
						<select class="form-control" name="mem_teamname">
							<?php foreach ($teamname_list as $key => $val) { ?>
							<option value="<?=$key?>"<?=get_selected($key, element('mem_teamname', $data))?>><?=$val?></option>
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
				<div class="form-group">
					<label for="mem_regnum" class="col-sm-2 control-label">주민등록번호</label>
					<div class="col-sm-10" style="display:table;">
						<input type="text" class="form-control" name="mem_regnum" id="mem_regnum" value="<?=element('mem_regnum', $data)?>" required />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_join_date">입사일자</label>
					<div class="col-sm-10">
						<input type="text" class="form-control datepicker" name="mem_join_date" id="mem_join_date" value="<?=element('mem_join_date', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_email">이메일</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mem_email" id="mem_email" value="<?=element('mem_email', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_birthday">생년월일</label>
					<div class="col-sm-10">
						<input type="text" class="form-control datepicker" name="mem_birthday" id="mem_birthday" value="<?=element('mem_birthday', $data)?>" />
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
							<button type="button" class="btn btn-black btn-sm" style="margin-top:0px;" onclick="win_zip('fadminwrite', 'mem_zipcode', 'mem_address1', 'mem_address2', 'mem_address3', 'mem_address4', 'mem_dong_cd');">주소 검색</button>
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
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_phone">전화</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mem_phone" id="mem_phone" value="<?=element('mem_phone', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_grade">직위/직급</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="mem_grade" id="mem_grade" value="<?=element('mem_grade', $data)?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_main_item">주 취급장치</label>
					<div class="col-sm-10 form-group">
						<div class="checkbox" style="padding-left: 20px;">
							<label><input type="checkbox" name="mem_main_item[]" value="차선이탈경고장치"<?=get_checked('차선이탈경고장치', explode(",", element('mem_main_item', $data)))?>>차선이탈경고장치</label><br>
							<label><input type="checkbox" name="mem_main_item[]" value="위험물질단말장치"<?=get_checked('위험물질단말장치', explode(",", element('mem_main_item', $data)))?>>위험물질단말장치</label><br>
							<label><input type="checkbox" name="mem_main_item[]" value="운행기록장치"<?=get_checked('운행기록장치', explode(",", element('mem_main_item', $data)))?>>운행기록장치</label><br>
							<label><input type="checkbox" name="mem_main_item[]" value="블랙박스"<?=get_checked('블랙박스', explode(",", element('mem_main_item', $data)))?>>블랙박스</label><br>
							<label><input type="checkbox" name="mem_main_item[]" value="후방카메라"<?=get_checked('후방카메라', explode(",", element('mem_main_item', $data)))?>>후방카메라</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mem_main_sido">주 장착지역</label>
					<div class="col-sm-10 form-group">
						<div class="checkbox" style="padding-left: 20px;">
							<label><input type="checkbox" name="mem_main_sido[]" value=""<?=get_checked('', explode(",", element('mem_main_sido', $data)))?>>전국</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="서울"<?=get_checked('서울', explode(",", element('mem_main_sido', $data)))?>>서울</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="인천"<?=get_checked('인천', explode(",", element('mem_main_sido', $data)))?>>인천</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="대전"<?=get_checked('대전', explode(",", element('mem_main_sido', $data)))?>>대전</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="대구"<?=get_checked('대구', explode(",", element('mem_main_sido', $data)))?>>대구</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="광주"<?=get_checked('광주', explode(",", element('mem_main_sido', $data)))?>>광주</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="부산"<?=get_checked('부산', explode(",", element('mem_main_sido', $data)))?>>부산</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="울산"<?=get_checked('울산', explode(",", element('mem_main_sido', $data)))?>>울산</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="세종"<?=get_checked('세종', explode(",", element('mem_main_sido', $data)))?>>세종</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="경기"<?=get_checked('경기', explode(",", element('mem_main_sido', $data)))?>>경기</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="강원"<?=get_checked('강원', explode(",", element('mem_main_sido', $data)))?>>강원</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="충북"<?=get_checked('충북', explode(",", element('mem_main_sido', $data)))?>>충북</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="충남"<?=get_checked('충남', explode(",", element('mem_main_sido', $data)))?>>충남</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="전북"<?=get_checked('전북', explode(",", element('mem_main_sido', $data)))?>>전북</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="전남"<?=get_checked('전남', explode(",", element('mem_main_sido', $data)))?>>전남</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="경북"<?=get_checked('경북', explode(",", element('mem_main_sido', $data)))?>>경북</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="경남"<?=get_checked('경남', explode(",", element('mem_main_sido', $data)))?>>경남</label>
							<label><input type="checkbox" name="mem_main_sido[]" value="제주"<?=get_checked('제주', explode(",", element('mem_main_sido', $data)))?>>제주</label>
						</div>
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
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 text-center">
				<div class="btn-group" role="group" style="padding-bottom: 20px;">
					<button type="submit" class="btn btn-success btn-lg">저장하기</button>
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