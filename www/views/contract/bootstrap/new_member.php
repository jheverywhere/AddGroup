<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
// $this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
extract($view);
// debug_var($assigned_installer);
$status_color = array(
	"신규접수신청" => "alert-default",
	"이전접수신청" => "alert-default",
	"A/S접수신청" => "alert-default",
	"접수" => "alert-info",
	"보류" => "alert-warning",
	"불가" => "alert-danger",
	"완료" => "alert-success",
	"탈거신청" => "alert-danger",
	"탈거완료" => "alert-danger",
);

if (element('ctr_seller', $data)) {
	$ctr_seller = element('ctr_seller', $data);
} else if ($this->member->item('mem_teamname') == '영업부') {
	$ctr_seller = $this->member->item('mem_id');
} else {
	$ctr_seller = NULL;
}
?>
<script type="text/javascript" src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<h3>고객등록</h3>

<form class="form-horizontal" name="fcontract" id="fcontract" action="/contract/write_contract" onsubmit="return fcontract_submit(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">

	<input type="hidden" name="ctr_no" value="<?=element('ctr_no', $data)?>">
	<input type="hidden" name="ctr_status" value="<?=element('ctr_status', $data) ? element('ctr_status', $data) : "신규접수신청"?>">

	<div class="row">
		<div class="col-md-12 col-xs-12">

			<?php if (element('ctr_status', $data)) { ?>
			<div class="alert <?=element(element('ctr_status', $data), $status_color)?>" role="alert">
				현재 <?=element('ctr_status', $data)?> 상태입니다.
			</div>
			<?php } ?>

			<table class="table">
				<caption>사용자 정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="driver_mem_id">사용자 아이디</label>
								<input type="text" class="form-control" id="driver_mem_id" name="driver_mem_id" value="<?=element('driver_mem_id', $data)?>" placeholder="사용자 아이디">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<button type="button" name="btn_id" class="btn btn-default btn-lg">불러오기</button>
								<a href="http://www.4422.co.kr/register/form" target="new_member" class="btn btn-default btn-lg">신규발급</a>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="etas_id">ETAS 아이디</label>
								<input type="text" class="form-control" id="etas_id" name="etas_id" value="<?=element('etas_id', $data)?>" placeholder="ETAS 아이디">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="etas_pw">ETAS 패스워드</label>
								<input type="text" class="form-control" id="etas_pw" name="etas_pw" value="<?=element('etas_pw', $data)?>" placeholder="ETAS 패스워드">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<button type="button" name="btn_id" class="btn btn-default btn-lg btn-block">불러오기</button>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>기본 정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="accept_date">접수일</label>
								<div class="input-group">
									<input type="text" class="form-control datepicker" id="accept_date" name="accept_date" value="<?=element('accept_date', $data)?>" placeholder="접수일">
									<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
								</div>
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="ctr_seller">영업자</label>
								<select class="form-control" name="ctr_seller" id="ctr_seller" required>
									<option value="">영업자를 선택하세요</option>
									<?php foreach ($seller_list as $i => $seller) { ?>
									<option value="<?=element('mem_id', $seller)?>"<?=get_selected(element('mem_id', $seller), $ctr_seller)?>><?=element('mem_username', $seller)."(".element('mem_userid', $seller).")"?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="ctr_installer">장착자</label>
								<select class="form-control" name="ctr_installer" id="ctr_installer" required>
									<option value="">장착자를 선택하세요</option>
									<?php foreach ($installer_list as $i => $installer) { ?>
									<option value="<?=element('mem_id', $installer)?>"<?=get_selected(element('mem_id', $installer), element('ctr_installer', $data))?>><?=element('mem_username', $installer)."(".element('mem_userid', $installer).")"?></option>
									<?php } ?>
								</select>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>차주 정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="owner_nm">성명</label>
								<input type="text" class="form-control" id="owner_nm" name="owner_nm" value="<?=element('owner_nm', $data)?>" placeholder="성명">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="owner_phone">휴대폰</label>
								<input type="text" class="form-control" id="owner_phone" name="owner_phone" value="<?=element('owner_phone', $data)?>" placeholder="휴대폰">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="owner_region">지역</label>
								<select class="form-control" name="owner_region" id="owner_region" required>
									<option value="">지역을 선택하세요</option>
									<option value="서울"<?=get_selected('서울', element('owner_region', $data))?>>서울</option>
									<option value="인천"<?=get_selected('인천', element('owner_region', $data))?>>인천</option>
									<option value="대전"<?=get_selected('대전', element('owner_region', $data))?>>대전</option>
									<option value="대구"<?=get_selected('대구', element('owner_region', $data))?>>대구</option>
									<option value="광주"<?=get_selected('광주', element('owner_region', $data))?>>광주</option>
									<option value="부산"<?=get_selected('부산', element('owner_region', $data))?>>부산</option>
									<option value="울산"<?=get_selected('울산', element('owner_region', $data))?>>울산</option>
									<option value="세종"<?=get_selected('세종', element('owner_region', $data))?>>세종</option>
									<option value="경기"<?=get_selected('경기', element('owner_region', $data))?>>경기</option>
									<option value="강원"<?=get_selected('강원', element('owner_region', $data))?>>강원</option>
									<option value="충북"<?=get_selected('충북', element('owner_region', $data))?>>충북</option>
									<option value="충남"<?=get_selected('충남', element('owner_region', $data))?>>충남</option>
									<option value="전북"<?=get_selected('전북', element('owner_region', $data))?>>전북</option>
									<option value="전남"<?=get_selected('전남', element('owner_region', $data))?>>전남</option>
									<option value="경북"<?=get_selected('경북', element('owner_region', $data))?>>경북</option>
									<option value="경남"<?=get_selected('경남', element('owner_region', $data))?>>경남</option>
									<option value="제주"<?=get_selected('제주', element('owner_region', $data))?>>제주</option>
								</select>
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="owner_detail_region">세부지역</label>
								<input type="text" class="form-control" id="owner_detail_region" name="owner_detail_region" value="<?=element('owner_detail_region', $data)?>" placeholder="세부지역">
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>소유자 정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="driver_nm">성명</label>
								<input type="text" class="form-control" id="driver_nm" name="driver_nm" value="<?=element('driver_nm', $data)?>" placeholder="성명">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<div class="">
									<label for="exampleInputPassword1">사업자구분</label>
									<div class="form-group">
										<input type="radio" id="driver_bizcls0" name="driver_bizcls" value="개인"<?=element('driver_bizcls', $data) == '' ? ' checked' : get_checked('개인', element('driver_bizcls', $data))?>><label for="driver_bizcls0">개인</label>
										<input type="radio" id="driver_bizcls1" name="driver_bizcls" value="사업자/법인"<?=get_checked('사업자/법인', element('driver_bizcls', $data))?>><label for="driver_bizcls1">개인사업자/법인사업자</label>
										<input type="text" class="form-control" id="driver_regnum1" name="driver_regnum1" value="<?=element('driver_regnum1', $data)?>" placeholder="주민등록번호 또는 사업자등록번호">
									</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="driver_tel">연락처</label>
								<input type="text" class="form-control" id="driver_tel" name="driver_tel" value="<?=element('driver_tel', $data)?>" placeholder="연락처">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="driver_phone">휴대폰</label>
								<input type="text" class="form-control" id="driver_phone" name="driver_phone" value="<?=element('driver_phone', $data)?>" placeholder="휴대폰">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="driver_zipcode">우편번호</label>
								<label>
									<input type="text" name="driver_zipcode" value="<?php echo set_value('driver_zipcode', element('driver_zipcode', $data)); ?>" id="driver_zipcode" class="form-control" size="7" maxlength="7" required />
								</label>
								<label>
									<button type="button" class="btn btn-black btn-sm" style="margin-top:0px;" onclick="win_zip('fcontract', 'driver_zipcode', 'driver_addr1', 'driver_addr2', 'driver_addr3', 'driver_addr4', 'driver_dong_cd');">주소 검색</button>
								</label>
								<div class="addr-line">
									<label for="driver_addr1">기본주소</label>
									<input type="text" name="driver_addr1" value="<?php echo set_value('driver_addr1', element('driver_addr1', $data)); ?>" id="driver_addr1" class="form-control" size="50" placeholder="기본주소" required />
								</div>
								<div class="addr-line">
									<label for="driver_addr2">상세주소</label>
									<input type="text" name="driver_addr2" value="<?php echo set_value('driver_addr2', element('driver_addr2', $data)); ?>" id="driver_addr2" class="form-control" size="50" placeholder="상세주소" />
								</div>
								<label for="driver_addr3">참고항목</label>
								<input type="text" name="driver_addr3" value="<?php echo set_value('driver_addr3', element('driver_addr3', $data)); ?>" id="driver_addr3" class="form-control" size="50" readonly="readonly" placeholder="참고항목" />
								<input type="hidden" name="driver_addr4" value="<?php echo set_value('driver_addr4', element('driver_addr4', $data)); ?>" required />
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>차량 정보</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label>차량등록번호</label>
								<input type="text" class="form-control" name="vhc_num" value="<?=element('vhc_num', $data)?>" placeholder="차량등록번호">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_item_type">장치종류</label><br>	
								<div class="checkbox">
									<label><input type="checkbox" name="vhc_item_type[]" value="차선이탈경고장치"<?=get_checked('차선이탈경고장치', explode(",", element('vhc_item_type', $data)))?>>차선이탈경고장치</label><br>
									<label><input type="checkbox" name="vhc_item_type[]" value="위험물질단말장치"<?=get_checked('위험물질단말장치', explode(",", element('vhc_item_type', $data)))?>>위험물질단말장치</label><br>
									<label><input type="checkbox" name="vhc_item_type[]" value="운행기록장치"<?=get_checked('운행기록장치', explode(",", element('vhc_item_type', $data)))?>>운행기록장치</label><br>
									<label><input type="checkbox" name="vhc_item_type[]" value="블랙박스"<?=get_checked('블랙박스', explode(",", element('vhc_item_type', $data)))?>>블랙박스</label><br>
									<label><input type="checkbox" name="vhc_item_type[]" value="후방카메라"<?=get_checked('후방카메라', explode(",", element('vhc_item_type', $data)))?>>후방카메라</label>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_type">차종</label>
								<select class="form-control" name="vhc_type" id="vhc_type" required>
									<option value="">차종을 선택하세요</option>
									<option value="건설기계"<?=get_selected('건설기계', element('vhc_type', $data))?>>건설기계</option>
									<option value="대형승용"<?=get_selected('대형승용', element('vhc_type', $data))?>>대형승용</option>
									<option value="대형승합"<?=get_selected('대형승합', element('vhc_type', $data))?>>대형승합</option>
									<option value="대형특수"<?=get_selected('대형특수', element('vhc_type', $data))?>>대형특수</option>
									<option value="대형화물"<?=get_selected('대형화물', element('vhc_type', $data))?>>대형화물</option>
									<option value="소형승용"<?=get_selected('소형승용', element('vhc_type', $data))?>>소형승용</option>
									<option value="소형승합"<?=get_selected('소형승합', element('vhc_type', $data))?>>소형승합</option>
									<option value="소형특수"<?=get_selected('소형특수', element('vhc_type', $data))?>>소형특수</option>
									<option value="소형화물"<?=get_selected('소형화물', element('vhc_type', $data))?>>소형화물</option>
									<option value="중형승용"<?=get_selected('중형승용', element('vhc_type', $data))?>>중형승용</option>
									<option value="중형승합"<?=get_selected('중형승합', element('vhc_type', $data))?>>중형승합</option>
									<option value="중형특수"<?=get_selected('중형특수', element('vhc_type', $data))?>>중형특수</option>
									<option value="중형화물"<?=get_selected('중형화물', element('vhc_type', $data))?>>중형화물</option>
								</select>
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_purpose">용도</label>
								<select class="form-control" name="vhc_purpose" id="vhc_purpose" required>
									<option value="">용도를 선택하세요</option>
									<option value="자가용"<?=get_selected('자가용', element('vhc_purpose', $data))?>>자가용</option>
									<option value="영업용"<?=get_selected('영업용', element('vhc_purpose', $data))?>>영업용</option>
									<option value="관용"<?=get_selected('관용', element('vhc_purpose', $data))?>>관용</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_nm">차명</label>
								<input type="text" class="form-control" id="vhc_nm" name="vhc_nm" value="<?=element('vhc_nm', $data)?>" placeholder="차명">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_moter_type">원동기형식</label>
								<input type="text" class="form-control" id="vhc_moter_type" name="vhc_moter_type" value="<?=element('vhc_moter_type', $data)?>" placeholder="원동기형식">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_vin">차대번호</label>
								<input type="text" class="form-control" id="vhc_vin" name="vhc_vin" value="<?=element('vhc_vin', $data)?>" placeholder="차대번호">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_sido">지자체</label>
								<input type="text" class="form-control" id="vhc_sido" name="vhc_sido" value="<?=element('vhc_sido', $data)?>" placeholder="지자체">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_regdate">최초등록일</label>
								<div class="input-group">
									<input type="text" class="form-control datepicker" id="vhc_regdate" name="vhc_regdate" value="<?=element('vhc_regdate', $data)?>" placeholder="최초등록일">
									<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
								</div>
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_year">연식</label>
								<select class="form-control" name="vhc_year" id="vhc_year" required>
									<option value="">연식을 선택하세요</option>
									<?php for($i=date("Y"); $i>1970; $i--) { ?>
									<option value="<?=$i?>"<?=get_selected($i, element('vhc_year', $data))?>><?=$i."년식"?></option>
									<?php } ?>
								</select>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>제원</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_length">길이</label>
								<input type="text" class="form-control" id="vhc_length" name="vhc_length" value="<?=element('vhc_length', $data)?>" placeholder="길이">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_width">너비</label>
								<input type="text" class="form-control" id="vhc_width" name="vhc_width" value="<?=element('vhc_width', $data)?>" placeholder="너비">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_height">높이</label>
								<input type="text" class="form-control" id="vhc_height" name="vhc_height" value="<?=element('vhc_height', $data)?>" placeholder="높이">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_weight">총중량</label>
								<input type="text" class="form-control" id="vhc_weight" name="vhc_weight" value="<?=element('vhc_weight', $data)?>" placeholder="총중량">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_cc">배기량</label>
								<input type="text" class="form-control" id="vhc_cc" name="vhc_cc" value="<?=element('vhc_cc', $data)?>" placeholder="배기량">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="vhc_ps">정격출력</label>
								<input type="text" class="form-control" id="vhc_ps" name="vhc_ps" value="<?=element('vhc_ps', $data)?>" placeholder="정격출력">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="vhc_struct_history">구조장치변경사항</label>
								<textarea class="form-control" id="vhc_struct_history" name="vhc_struct_history" rows="5"><?=element('vhc_struct_history', $data)?></textarea>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>기타</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="vhc_remark">비고란</label>
								<textarea class="form-control" id="vhc_remark" name="vhc_remark" rows="5"><?=element('vhc_remark', $data)?></textarea>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="border_button text-center" style="margin-bottom: 40px;">
				<!-- <button type="button" class="btn btn-default btn-sm btn-history-back">취소</button> -->
				<button type="submit" class="btn btn-success btn-lg">저장하기</button>
			</div>

		</div>
	</div>
</form>

<?php if (element('mem_teamname', element('member', $layout)) == '장착부') { ?>
<script>
	$("form[name=fcontract] input,select,textarea,button").prop('disabled', true);
	$("form[name=fcontract] button, form[name=fcontract] [type=button]").hide();
	$("form[name=fcontract] [type=submit]").hide();
</script>
<?php } ?>

<?php if (element('mem_is_admin', element('member', $layout)) || element('mem_teamname', element('member', $layout)) == '장착부') { ?>
<h3>장착관리</h3>

<form class="form-horizontal" name="finstall" id="finstall" action="/contract/write_install" onsubmit="return finstall_submit(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
	<div class="row">
		<div class="col-md-12 col-xs-12">

			<input type="hidden" name="ctr_no" value="<?=element('ctr_no', $data)?>">
			<input type="hidden" name="ins_no" value="<?=element('ins_no', $data)?>">
			<input type="hidden" name="ins_mem_id" value="<?=element('ins_mem_id', $data) ? element('ins_mem_id', $data) : $this->member->item('mem_id')?>">

			<?php if (!element('ctr_installer', $data)) { ?>
				<div class="alert alert-danger" role="alert">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					장착자가 지정되지 않았습니다. 저장하기를 클릭하면 <?=$this->member->item('mem_username')?>님이 장착자로 할당됩니다.
				</div>
			<?php } else { ?>
				<div class="alert alert-success" role="alert">
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
					<?=element('mem_username', $assigned_installer)?>님이 장착자로 할당되어 있습니다.
				</div>
			<?php } ?>

			<table class="table">
				<caption>계약 진행</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="ins_date">설치일자</label>
								<div class="input-group">
									<input type="text" class="form-control datepicker" id="ins_date" name="ins_date" value="<?=element('ins_date', $data)?>" placeholder="설치일자">
									<div class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></div>
								</div>
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="serialnum">시리얼번호</label>
								<input type="text" class="form-control" id="serialnum" name="serialnum" value="<?=element('serialnum', $data)?>" placeholder="시리얼번호">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="payment_method">결제여부</label>
								<select class="form-control" name="payment_method" id="payment_method" required>
									<option value=""<?=get_selected('', element('payment_method', $data))?>>아니오</option>
									<option value="카드"<?=get_selected('카드', element('payment_method', $data))?>>카드</option>
									<option value="현금"<?=get_selected('현금', element('payment_method', $data))?>>현금</option>
								</select>
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="receipt_amount">결제금액</label>
								<input type="text" class="form-control" id="receipt_amount" name="receipt_amount" value="<?=element('receipt_amount', $data)?>" placeholder="결제금액">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="doc_transfer_yn">서류발송여부</label>
								<select class="form-control" name="doc_transfer_yn" id="doc_transfer_yn" required>
									<option value="0"<?=get_selected('0', element('doc_transfer_yn', $data))?>>아니오</option>
									<option value="1"<?=get_selected('1', element('doc_transfer_yn', $data))?>>예</option>
								</select>
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="ctr_status">진행상태</label>
								<select class="form-control" name="ctr_status" id="ctr_status" required>
									<option value="신규접수신청"<?=get_selected('신규접수신청', element('ctr_status', $data))?>>신규접수신청</option>
									<option value="이전접수신청"<?=get_selected('이전접수신청', element('ctr_status', $data))?>>이전접수신청</option>
									<option value="A/S접수신청"<?=get_selected('A/S접수신청', element('ctr_status', $data))?>>A/S접수신청</option>
									<option value="접수"<?=get_selected('접수', element('ctr_status', $data))?>>접수</option>
									<option value="보류"<?=get_selected('보류', element('ctr_status', $data))?>>보류</option>
									<option value="불가"<?=get_selected('불가', element('ctr_status', $data))?>>불가</option>
									<option value="완료"<?=get_selected('완료', element('ctr_status', $data))?>>완료</option>
									<option value="탈거신청"<?=get_selected('탈거신청', element('ctr_status', $data))?>>탈거신청</option>
									<option value="탈거완료"<?=get_selected('탈거완료', element('ctr_status', $data))?>>탈거완료</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="ins_item_type">장치종류</label><br>	
								<p class="help-block">영업자가 요청한 장치는 <span class="text-danger">붉은색</span>으로 표시됩니다.</p>
								<div class="checkbox">
									<label class="<?=(in_array('차선이탈경고장치', explode(",", element('vhc_item_type', $data))) ? "text-danger" : "")?>"><input type="checkbox" name="ins_item_type[]" value="차선이탈경고장치"<?=get_checked('차선이탈경고장치', explode(",", element('ins_item_type', $data)))?>>차선이탈경고장치</label><br>
									<label class="<?=(in_array('위험물질단말장치', explode(",", element('vhc_item_type', $data))) ? "text-danger" : "")?>"><input type="checkbox" name="ins_item_type[]" value="위험물질단말장치"<?=get_checked('위험물질단말장치', explode(",", element('ins_item_type', $data)))?>>위험물질단말장치</label><br>
									<label class="<?=(in_array('운행기록장치', explode(",", element('vhc_item_type', $data))) ? "text-danger" : "")?>"><input type="checkbox" name="ins_item_type[]" value="운행기록장치"<?=get_checked('운행기록장치', explode(",", element('ins_item_type', $data)))?>>운행기록장치</label><br>
									<label class="<?=(in_array('블랙박스', explode(",", element('vhc_item_type', $data))) ? "text-danger" : "")?>"><input type="checkbox" name="ins_item_type[]" value="블랙박스"<?=get_checked('블랙박스', explode(",", element('ins_item_type', $data)))?>>블랙박스</label><br>
									<label class="<?=(in_array('후방카메라', explode(",", element('vhc_item_type', $data))) ? "text-danger" : "")?>"><input type="checkbox" name="ins_item_type[]" value="후방카메라"<?=get_checked('후방카메라', explode(",", element('ins_item_type', $data)))?>>후방카메라</label>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table">
				<caption>기타</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-12 col-xs-12 form-group">
								<label for="ins_remark">비고란</label>
								<textarea class="form-control" id="ins_remark" name="ins_remark" rows="5"><?=element('ins_remark', $data)?></textarea>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<?php if (element('mem_is_admin', element('member', $layout))) { ?>
			<table class="table">
				<caption>영업비/장착비 지급여부</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="sales_fee_yn">영업비</label>
								<select class="form-control" name="sales_fee_yn" id="sales_fee_yn" required>
									<option value="0"<?=get_selected('0', element('sales_fee_yn', $data))?>>아니오</option>
									<option value="1"<?=get_selected('1', element('sales_fee_yn', $data))?>>예</option>
								</select>
								<input type="text" class="form-control" id="sales_fee" name="sales_fee" value="<?=element('sales_fee', $data)?>" placeholder="영업비">
							</div>
							<div class="col-md-6 col-xs-12 form-group">
								<label for="ins_fee_yn">장착비</label>
								<select class="form-control" name="ins_fee_yn" id="ins_fee_yn" required>
									<option value="0"<?=get_selected('0', element('ins_fee_yn', $data))?>>아니오</option>
									<option value="1"<?=get_selected('1', element('ins_fee_yn', $data))?>>예</option>
								</select>
								<input type="text" class="form-control" id="ins_fee" name="ins_fee" value="<?=element('ins_fee', $data)?>" placeholder="장착비">
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<?php } ?>

			<table class="table">
				<caption>장착사진</caption>
				<tbody>
					<tr>
						<td>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="vhc_lic_img">차량등록증</label>
								<?php if (element('vhc_lic_img', $data)) { ?>
								<a href="<?="/contract/view_doc/".element('ctr_no', $data)."/vhc_lic_img"?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="vhc_lic_img" accept="image/*">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="vhc_interior_img">차량 내부</label>
								<?php if (element('vhc_interior_img', $data)) { ?>
								<a href="<?=element('vhc_interior_img', $data)?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="vhc_interior_img" accept="image/*">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="vhc_front_img">차량 전면</label>
								<?php if (element('vhc_front_img', $data)) { ?>
								<a href="<?=element('vhc_front_img', $data)?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="vhc_front_img" accept="image/*">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="serialnum_img">시리얼번호</label>
								<?php if (element('serialnum_img', $data)) { ?>
								<a href="<?=element('serialnum_img', $data)?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="serialnum_img" accept="image/*">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="receipt_img">영수증</label>
								<?php if (element('receipt_img', $data)) { ?>
								<a href="<?=element('receipt_img', $data)?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="receipt_img" accept="image/*">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="bizlic_img">사업자등록증</label>
								<?php if (element('bizlic_img', $data)) { ?>
								<a href="<?=element('bizlic_img', $data)?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="bizlic_img" accept="image/*">
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="bankbook_img">통장사본</label>
								<?php if (element('bankbook_img', $data)) { ?>
								<a href="<?="/contract/view_doc/".element('ctr_no', $data)."/bankbook_img"?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="bankbook_img" accept="image/*">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="etc_doc1_img">기타서류1</label>
								<?php if (element('etc_doc1_img', $data)) { ?>
								<a href="<?=element('etc_doc1_img', $data)?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="etc_doc1_img" accept="image/*">
							</div>
							<div class="col-md-4 col-xs-12 form-group">
								<label for="etc_doc2_img">기타서류2</label>
								<?php if (element('etc_doc2_img', $data)) { ?>
								<a href="<?=element('etc_doc2_img', $data)?>" class="btn btn-info btn-sm" onclick="return show_img(this)">보기</a>
								<?php } ?>
								<input type="file" class="form-control" name="etc_doc2_img" accept="image/*">
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="border_button text-center" style="margin-bottom: 40px;">
				<!-- <button type="button" class="btn btn-default btn-sm btn-history-back">취소</button> -->
				<button type="submit" class="btn btn-success btn-lg">저장하기</button>
			</div>

		</div>
	</div>
</form>
<?php } ?>

<script>
function fcontract_submit(self) {

}
function finstall_submit(self) {

}
function show_img(self) {
	window.open(self.href , 'show_img', 'width=800,height=800,scrollbars=1');
	return false;
}
</script>