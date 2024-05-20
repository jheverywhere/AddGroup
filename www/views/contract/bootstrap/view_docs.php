<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
extract($view);
// $data = array();
// debug_var($data);
$thumb_width = 350;
$thumb_height = 350;
?>

<div class="container-fluid" style="padding: 20px 10px;">
	<form class="" id="fdocs" name="chk[]" value="fdocs" action="<?="/contract/download_doc/{$ctr_no}"?>" method="POST">
		<div class="panel panel-primary">
			<div class="panel-heading clearfix">
				<h3 class="panel-title pull-left" style="padding-top: 7.5px;">서류보기</h3>
				<div class="btn-group pull-right">
					<button type="submit" class="btn btn-success btn-md">다운로드</button>
					<button type="button" class="btn btn-default btn-md" onclick="window.close();">닫기</button>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<table class="table">
							<caption>
								<input type="checkbox" id="chkall_doc" checked>
								<label for="chkall_doc">장착사진</label>
							</caption>
							<tbody>
								<tr>
									<td>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
											<input type="checkbox" id="vhc_lic_img" name="chk[]" value="vhc_lic_img" <?=element('vhc_lic_img', $data) ? "checked" : " disabled"?>>
											<label for="vhc_lic_img">차량등록증</label><BR>
											<?php if (element('vhc_lic_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('vhc_lic_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('vhc_lic_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
										<input type="checkbox" id="vhc_interior_img" name="chk[]" value="vhc_interior_img" <?=element('vhc_interior_img', $data) ? "checked" : " disabled"?>>
											<label for="vhc_interior_img">차량 내부</label><BR>
											<?php if (element('vhc_interior_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('vhc_interior_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('vhc_interior_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
										<input type="checkbox" id="vhc_front_img" name="chk[]" value="vhc_front_img" <?=element('vhc_front_img', $data) ? "checked" : " disabled"?>>
											<label for="vhc_front_img">차량 전면</label><BR>
											<?php if (element('vhc_front_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('vhc_front_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('vhc_front_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
										<input type="checkbox" id="serialnum_img" name="chk[]" value="serialnum_img" <?=element('serialnum_img', $data) ? "checked" : " disabled"?>>
											<label for="serialnum_img">시리얼번호</label><BR>
											<?php if (element('serialnum_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('serialnum_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('serialnum_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
										<input type="checkbox" id="receipt_img" name="chk[]" value="receipt_img" <?=element('receipt_img', $data) ? "checked" : " disabled"?>>
											<label for="receipt_img">영수증</label><BR>
											<?php if (element('receipt_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('receipt_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('receipt_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
										<input type="checkbox" id="bizlic_img" name="chk[]" value="bizlic_img" <?=element('bizlic_img', $data) ? "checked" : " disabled"?>>
											<label for="bizlic_img">사업자등록증</label><BR>
											<?php if (element('bizlic_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('bizlic_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('bizlic_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
										<input type="checkbox" id="bankbook_img" name="chk[]" value="bankbook_img" <?=element('bankbook_img', $data) ? "checked" : " disabled"?>>
											<label for="bankbook_img">통장사본</label><BR>
											<?php if (element('bankbook_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('bankbook_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('bankbook_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
										<input type="checkbox" id="etc_doc1_img" name="chk[]" value="etc_doc1_img" <?=element('etc_doc1_img', $data) ? "checked" : " disabled"?>>
											<label for="etc_doc1_img">기타서류1</label><BR>
											<?php if (element('etc_doc1_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('etc_doc1_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('etc_doc1_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 form-group">
										<input type="checkbox" id="etc_doc2_img" name="chk[]" value="etc_doc2_img" <?=element('etc_doc2_img', $data) ? "checked" : " disabled"?>>
											<label for="etc_doc2_img">기타서류2</label><BR>
											<?php if (element('etc_doc2_img', $data)) { ?>
											<a href="<?="/uploads/contract/{$ctr_no}/".element('etc_doc2_img', $data)?>" class="btn btn-default btn-sm" onclick="return show_img(this)">
												<img src="<?=thumb_url('contract', "{$ctr_no}/".element('etc_doc2_img', $data), $thumb_width, $thumb_height); ?>" style="width: 100%">
											</a>
											<?php } ?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
$(function () {
	$("#chkall_doc").click(function () {
		$("input[name='chk[]']:enabled").prop("checked", $(this).prop('checked'));
	});
});
</script>