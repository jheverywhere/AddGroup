<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
extract($view);
// debug_var($data);
?>

<style>
#tbl_member th {text-align: center;}
</style>

<div class="box">
	<div class="box-table">

		<!-- <form class="form-horizontal" name="fmember" id="fmember" onsubmit="return fmember_submit(this)" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate"> -->
		<div class="container-fluid" style="padding: 10px;">
			<div class="board">

				<h3>사용자 불러오기</h3>

				<div class="condition clearfix">
					<form class="form-inline" name="fsearch" method="GET">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label for="s1">사용자명</label>
									<input type="text" class="form-control" id="s1" name="s1" value="<?=$this->input->get('s1')?>" placeholder="사용자명" autocomplete="off">
								</div>
							</div>
							<div class="col-md-2 text-right">
								<button type="submit" class="btn btn-primary btn-block">검색</button>
							</div>
						</div>
					</form>
				</div>

				<form class="table-responsive">
					<table id="tbl_member" class="table table-hover ">
						<thead>
							<tr>
								<th width="">No</th>
								<th width="">아이디</th>
								<th width="">이름</th>
								<th width="">이메일</th>
								<th width="">가입일</th>
								<th width="">최근로그인</th>
								<th width="">회원그룹</th>
								<th width="">회원레벨</th>
								<th width="">선택</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach (element('list', $data) as $i => $row) { ?>
							<tr>
								<td class="text-center">
									<?=element('total_rows', $data) - $i?>
								</td>
								<td class="text-center"><?=element('mem_userid', $row)?></td>
								<td class="text-center"><?=element('mem_username', $row)?></td>
								<td class="text-center"><?=element('mem_email', $row)?></td>
								<td class="text-center"><?=element('mem_register_datetime', $row)?></td>
								<td class="text-center"><?=element('mem_lastlogin_datetime', $row)?></td>
								<td class="text-center"><?=element('member_group', $row)?></td>
								<td class="text-center"><?=element('mem_level', $row)?></td>
								<td class="text-center">
									<!-- <input type="hidden" id="mem_id" name="mem_id" value="<?=element('mem_id', $row)?>">
									<input type="hidden" id="mem_userid" name="mem_userid" value="<?=element('mem_userid', $row)?>">
									<input type="hidden" id="etas_id" name="etas_id" value="<?=element('etas_id', $row)?>">
									<input type="hidden" id="etas_pw" name="etas_pw" value="<?=element('etas_pw', $row)?>"> -->
									<button type="button" class="btn btn-primary"
										data-mem_id="<?=element('mem_id', $row)?>"
										data-mem_userid="<?=element('mem_userid', $row)?>"
										data-etas_id="<?=element('etas_id', $row)?>"
										data-etas_pw="<?=element('etas_pw', $row)?>"
										data-mem_username="<?=element('mem_username', $row)?>"
										data-mem_zipcode="<?=element('mem_zipcode', $row)?>"
										data-mem_address1="<?=element('mem_address1', $row)?>"
										data-mem_address2="<?=element('mem_address2', $row)?>"
										data-mem_address3="<?=element('mem_address3', $row)?>"
										data-mem_address4="<?=element('mem_address4', $row)?>"
										data-mem_phone="<?=element('mem_phone', $row)?>"
										data-mem_biz_zipcode="<?=element('mem_biz_zipcode', $row)?>"
										data-mem_biz_address1="<?=element('mem_biz_address1', $row)?>"
										data-mem_biz_address2="<?=element('mem_biz_address2', $row)?>"
										data-mem_biz_address3="<?=element('mem_biz_address3', $row)?>"
										data-mem_biz_address4="<?=element('mem_biz_address4', $row)?>"
										data-mem_biz_attach="<?=element('mem_biz_attach', $row)?>"
										data-mem_biz_ceo="<?=element('mem_biz_ceo', $row)?>"
										data-mem_biz_item="<?=element('mem_biz_item', $row)?>"
										data-mem_biz_name="<?=element('mem_biz_name', $row)?>"
										data-mem_biz_num="<?=element('mem_biz_num', $row)?>"
										data-mem_biz_type="<?=element('mem_biz_type', $row)?>"
										data-mem_biz_yn="<?=element('mem_biz_yn', $row)?>"
										data-mem_vhc_num="<?=element('mem_vhc_num', $row)?>"
										onclick="return sel_member(this);">선택</button>
								</td>
							</tr>
							<?php } ?>
							<?php if (!$data) { ?>
							<tr>
								<td colspan="11" class="nopost">표시할 데이터가 없습니다.</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</form>

				<nav class="text-center"><?php echo element("pagination", $view);?></nav>

			</div>
		</div>
		<!-- </form> -->

	</div>
</div>


<script>
function fmember_submit(self) {

}
function show_img(self) {
	window.open(self.href , 'show_img', 'width=800,height=800,scrollbars=1');
	return false;
}
function sel_member(self) {
	var opener_form                     = window.opener.document.fcontract;
	opener_form.driver_mem_id.value     = self.getAttribute('data-mem_id');
	opener_form.driver_mem_userid.value = self.getAttribute('data-mem_userid');
	opener_form.etas_id.value           = self.getAttribute('data-etas_id');
	opener_form.etas_pw.value           = self.getAttribute('data-etas_pw');
	opener_form.owner_nm.value          = self.getAttribute('data-mem_biz_ceo');
	opener_form.owner_phone.value       = '';
	opener_form.driver_regnum1.value    = self.getAttribute('data-mem_biz_num');
	opener_form.driver_nm.value         = self.getAttribute('data-mem_username');
	opener_form.driver_phone.value      = self.getAttribute('data-mem_phone');
	opener_form.driver_tel.value        = self.getAttribute('data-mem_phone');
	opener_form.driver_zipcode.value    = self.getAttribute('data-mem_zipcode');
	opener_form.driver_addr1.value      = self.getAttribute('data-mem_address1');
	opener_form.driver_addr2.value      = self.getAttribute('data-mem_address2');
	opener_form.driver_addr3.value      = self.getAttribute('data-mem_address3');
	opener_form.driver_addr4.value      = self.getAttribute('data-mem_address4');
	opener_form.vhc_num.value           = self.getAttribute('data-mem_vhc_num');
	window.close();
}
</script>