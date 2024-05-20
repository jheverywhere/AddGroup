<?php
extract($view);
// debug_var($type_list);
?>
<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="board">

	<h3>코드관리</h3>

	<div class="condition clearfix">
		<form class="form-inline" name="fsearch" method="GET">
			<div class="row">
				<div class="col-md-10">
					<select class="form-control" name="stype" id="stype">
						<option value="">구분</option>
						<?php foreach ($type_list as $i => $type_name) { ?>
						<option value="<?=$type_name?>"<?=get_selected($type_name, $this->input->get('stype'))?>><?=$type_name?></option>
						<?php } ?>
					</select>
				</div>
				<!-- <div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">검색</button>
				</div> -->
			</div>
		</form>
	</div>

	<form class="table-responsive">
		<table id="tbl_member" class="table table-hover ">
			<thead>
				<tr>
					<th width="">
						<input id="chk_all" type="checkbox" class="post_con_chk">
					</th>
					<th width="">구분</th>
					<th width="">순서</th>
					<th width="">값</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach (element('list', $data) as $i => $row) { ?>
				<tr data-id="<?=element('mem_id', $row)?>" onclick="return view(this)">
					<td>
						<input type="checkbox" name="chk[]" id="chk_<?=element('cd_key', $row)?>" class="post_con_chk" value="<?=element('cd_key', $row)?>">
					</td>
					<td>
						<?=element('cd_type', $row)?>
					</td>
					<td>
						<?=element('cd_name', $row)?>
					</td>
					<td>
						<?=element('cd_value', $row)?>
					</td>
				</tr>
				<?php } ?>
				<?php if (!$data) { ?>
				<tr>
					<td colspan="4" class="nopost">내용이 없습니다</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</form>

	<div class="row">
		<div class="col-md-12 text-center">
			<nav><?php echo element("pagination", $view);?></nav>
		</div>
	</div>	
</div>
<script>
$(function () {
	$("#chk_all").click(function () {
		$("input[name='chk[]']").prop('checked', $(this).prop('checked'));
	});
	$("form[name=fsearch] select[name=stype]").change(function () {
		$("form[name=fsearch]").submit();
	});
});

function view(self) {
	// var url = '/members/write/'+$(self).data('id');
	// location.href = url;
}
</script>