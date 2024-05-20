<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
extract($view);
// debug_var($data);
$img_meta = array(
	"vhc_lic_img"       => "차량등록증",
	"vhc_interior_img" => "차량 내부",
	"vhc_front_img"    => "차량 전면",
	"serialnum_img"    => "시리얼번호",
	"receipt_img"      => "영수증",
	"bizlic_img"       => "사업자등록증",
	"bankbook_img"     => "통장사본",
	"etc_doc1_img"     => "기타서류1",
	"etc_doc2_img"     => "기타서류2",
);
?>

<style type="text/css">
@media print {
    #btn_close {display:none;}
}
</style>

<div class="container-fluid" style="padding: 20px 10px;">
	<div class="row">
		<div class="col-md-12">

			<h3>서류보기</h3>

			<table class="table">
				<caption><?=element($ct_img, $img_meta)?></caption>
				<tbody>
					<tr>
						<td class="text-center">
							<img src="<?="/uploads/contract/{$ctr_no}/{$ct_img_file}"?>">
						</td>
					</tr>
				</tbody>
			</table>

			<div class="text-center" id="btn_close">
				<button type="button" class="btn btn-default btn-md" onclick="window.close();">닫기</button>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {
	window.print();
});
//]]>
</script>
