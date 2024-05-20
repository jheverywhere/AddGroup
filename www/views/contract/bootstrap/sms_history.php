<?php
extract($view);
// debug_var($sms_list);
?>
<table class="table table-striped">
	<thead>
		<tr>
			<th class="text-center" style="width: 100px;">날짜</th>
			<th class="text-center" style="width: 60px;">발송자</th>
			<th class="text-center" style="width: 60px;">고객명</th>
			<th class="text-left">수신시설</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($sms_list as $sms_no => $row) {
			$recv_row_id = "recv_row{$sms_no}";
		?>
		<tr>
			<td class="text-center"><?=element('sms_datetime', $row)?></td>
			<td class="text-center"><?=element('sms_mem_username', $row)?></td>
			<td class="text-center"><?=element('sms_cust_name', $row)?></td>
			<td class="text-left">
				<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="<?="#{$recv_row_id}"?>" aria-expanded="false" aria-controls="<?=$recv_row_id?>">
					<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>&nbsp;목록보기(<?=count(element('recv_list', $row))?>)
				</button>
				<div class="collapse" id="<?=$recv_row_id?>">
					<div class="well well-sm">
					<?php
					foreach (element('recv_list', $row) as $i => $recv_row) {
						$park_type_name = get_park_type_name(element('sms_recv_park_type_cd', $recv_row));
						$sms_recv_park_name = element('sms_recv_park_name', $recv_row);
						$sms_recv_content = html_escape(str_replace("\n", "<BR>", element('sms_recv_content', $recv_row)));
						echo "<button type=\"button\" class=\"btn btn-xs btn-success btn_sms_content\" data-toggle=\"popover\" data-trigger=\"focus\" data-content=\"{$sms_recv_content}\">[{$park_type_name}] {$sms_recv_park_name}</button>".PHP_EOL;
					}
					?>
					</div>
				</div>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<style>
div.popover.sms_content{width: 300px;}
</style>
<script>
$(function () {
	$('.btn_sms_content').popover({
		html: true,
		placement: 'auto',
		template: '<div class="popover sms_content" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
	});
});
</script>