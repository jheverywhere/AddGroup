<?php

$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
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
	"탈거신청" => "alert-default",
	"탈거완료" => "alert-default",
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

<h3>계약내용</h3>

<div class="row">
	<div class="col-md-12 col-xs-12">

		<?php if (element('ctr_status', $data)) { ?>
		<div class="alert <?=element(element('ctr_status', $data), $status_color)?>" role="alert">
			현재 <?=element('ctr_status', $data)?> 상태입니다.
		</div>
		<?php } ?>

		<h4>사용자 정보</h4>
		<table class="table">
			<caption>사용자 정보</caption>
			<tbody>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="driver_mem_id">사용자 아이디</label>
							<span class="pull-right"><?=element('driver_mem_id', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="etas_id">ETAS 아이디</label>
							<span class="pull-right"><?=element('etas_id', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="etas_pw">ETAS 패스워드</label>
							<span class="pull-right"><?=element('etas_pw', $data)?></span>
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
						<div class="col-md-6 col-xs-12 form-group">
							<label for="accept_date">접수일</label>
							<span class="pull-right"><?=element('accept_date', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="ctr_status">진행상태</label>
							<span class="pull-right"><?=element('ctr_status', $data)?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="ctr_seller">영업자</label>
							<span class="pull-right"><?=element('ctr_seller', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="ctr_installer">장착자</label>
							<span class="pull-right"><?=element('ctr_installer', $data)?></span>
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
							<span class="pull-right"><?=element('owner_nm', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="owner_phone">휴대폰</label>
							<span class="pull-right"><?=element('owner_phone', $data)?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="owner_region">지역</label>
							<span class="pull-right"><?=element('owner_region', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="owner_detail_region">세부지역</label>
							<span class="pull-right"><?=element('owner_detail_region', $data)?></span>
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
							<span class="pull-right"><?=element('driver_nm', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<div class="">
								<label for="exampleInputPassword1">사업자구분</label>
								<span class="pull-right"><?=element('driver_bizcls', $data)?></span>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="driver_tel">연락처</label>
							<span class="pull-right"><?=element('driver_tel', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="driver_phone">휴대폰</label>
							<span class="pull-right"><?=element('driver_phone', $data)?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-12 col-xs-12 form-group">
							<label for="driver_zipcode">주소</label>
							<span class="pull-right"><?=combine_address(element('mem_address1', $data), element('mem_address2', $data), element('mem_address3', $data), element('mem_address4', $data))?></span>
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
							<span class="pull-right"><?=element('vhc_num', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_item_type">장치종류</label><br>	
							<span class="pull-right">
								<?php
								$item_type_list = array();
								$vhc_item_type = explode(",", element('vhc_item_type', $data));
								foreach ($vhc_item_type as $i => $item_type) {
									$item_type_list[$item_type][] = 'vhc';
								}
								$ins_item_type = explode(",", element('ins_item_type', $data));
								foreach ($ins_item_type as $i => $item_type) {
									$item_type_list[$item_type][] = 'ins';
								}
								foreach ($item_type_list as $item => $attr) {
									if (in_array('vhc', $attr) && in_array('ins', $attr)) {
										echo '<span class="label label-success">'.$item.'</span><BR>'.PHP_EOL;
									} else if (in_array('vhc', $attr)) {
										echo '<span class="label label-default">'.$item.'</span><BR>'.PHP_EOL;
									} else if (in_array('ins', $attr)) {
										echo '<span class="label label-warning">'.$item.'</span><BR>'.PHP_EOL;
									} else {
										echo '<span class="label label-danger">'.$item.'</span><BR>'.PHP_EOL;
									}
								}
								?>
							</span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_type">차종</label>
							<span class="pull-right"><?=element('vhc_type', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_purpose">용도</label>
							<span class="pull-right"><?=element('vhc_purpose', $data)?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_nm">차명</label>
							<span class="pull-right"><?=element('vhc_nm', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_moter_type">원동기형식</label>
							<span class="pull-right"><?=element('vhc_moter_type', $data)?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_vin">차대번호</label>
							<span class="pull-right"><?=element('vhc_vin', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_sido">지자체</label>
							<span class="pull-right"><?=element('vhc_sido', $data)?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_regdate">최초등록일</label>
							<span class="pull-right"><?=element('vhc_regdate', $data)?></span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_year">연식</label>
							<span class="pull-right"><?=element('vhc_year', $data)?></span>
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
							<span class="pull-right"><?=number_format(element('vhc_length', $data))?>mm</span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_width">너비</label>
							<span class="pull-right"><?=number_format(element('vhc_width', $data))?>mm</span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_height">높이</label>
							<span class="pull-right"><?=number_format(element('vhc_height', $data))?>mm</span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_weight">총중량</label>
							<span class="pull-right"><?=number_format(element('vhc_weight', $data))?>kg</span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_cc">배기량</label>
							<span class="pull-right"><?=number_format(element('vhc_cc', $data))?>cc</span>
						</div>
						<div class="col-md-6 col-xs-12 form-group">
							<label for="vhc_ps">정격출력</label>
							<span class="pull-right"><?=number_format(element('vhc_ps', $data))?>ps</span>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="col-md-12 col-xs-12 form-group">
							<label for="vhc_struct_history">구조장치변경사항</label>
							<p class="help-block">등록증 상의 7항 구조,장치변경사항 및 비고란의 구조변경내역을 상세히 기술</p>
							<textarea class="form-control" id="vhc_struct_history" name="vhc_struct_history" rows="5" disabled><?=element('vhc_struct_history', $data)?></textarea>
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
							<p class="help-block">고객 추가 요청사항 기입</p>
							<textarea class="form-control" id="vhc_remark" name="vhc_remark" rows="5" disabled><?=element('vhc_remark', $data)?></textarea>
						</div>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="border_button text-center" style="margin-bottom: 40px;">
			<!-- <button type="button" class="btn btn-default btn-sm btn-history-back">취소</button> -->
			<!-- <button type="submit" class="btn btn-success btn-lg">저장하기</button> -->
			<button type="button" class="btn btn-success btn-lg" onclick="document.fcontract.submit();">저장하기</button>
		</div>

	</div>
</div>

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
				<!-- <button type="submit" class="btn btn-success btn-lg">저장하기</button> -->
				<button type="button" class="btn btn-success btn-lg" onclick="document.finstall.submit();">저장하기</button>
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
$(document).ready(function() {
    $('#ctr_seller, #ctr_installer').select2();
});
</script>