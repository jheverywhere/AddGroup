<?php
extract($view);
// debug_var($data);
?>
<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="board">

	<h3>직원관리</h3>

	<div class="condition clearfix">
		<form class="form-inline" name="fsearch" method="GET">
			<div class="row">
				<div class="col-md-10">
					<select class="form-control" name="mem_teamname">
						<option value="">==부서명==</option>
						<?php foreach ($teamname_list as $i => $row) { ?>
						<option value="<?=element('name', $row)?>"<?=get_selected(element('name', $row), element('mem_teamname', $data))?>><?=element('name', $row)?></option>
						<?php } ?>
					</select>
					<input type-="text" class="form-control" name="stx" value="<?=$this->input->get('stx')?>" placeholder="검색어">
				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">검색</button>
					<a href="/members/write" class="btn btn-default">직원추가</a>
				</div>
			</div>
		</form>
	</div>

	<form class="table-responsive" name="flist" method="POST" action="/members/del" onsubmit="return flist_submit(this);">
		<div>
			<button type="submit" class="btn btn-default btn-sm" name="btn_submit" value="del">삭제</button>
		</div>
		<table id="tbl_member" class="table table-hover table-striped">
			<thead>
				<tr>
					<th class="text-center">
						<input id="chk_all" type="checkbox" class="post_con_chk">
					</th>
					<th class="text-center">성명</th>
					<th class="text-center">연락처</th>
					<th class="text-center">주소</th>
					<th class="text-center">부서명</th>
					<th class="text-center">계좌번호</th>
					<th class="text-center" style="width: 100px;">관리</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach (element('list', $data) as $i => $row) { ?>
				<tr data-id="<?=element('mem_id', $row)?>">
					<td class="text-center">
						<input type="checkbox" name="chk[]" id="chk_<?=element('mem_id', $row)?>" class="post_con_chk" value="<?=element('mem_id', $row)?>">
					</td>
					<td class="text-center clickable">
						<?=get_highlight_keyword(element('mem_username', $row), $this->input->get('stx'))?>
					</td>
					<td class="text-center clickable">
						<?=get_phone_link(element('mem_phone', $row))?>
					</td>
					<td class="text-center clickable">
						<?=get_highlight_keyword(combine_address(element('mem_address1', $row), element('mem_address2', $row), element('mem_address3', $row)), $this->input->get('stx'))?>
					</td>
					<td class="text-center clickable">
						<?=element('mem_teamname', $row)?>
					</td>
					<td class="text-center clickable">
						<?=get_highlight_keyword(implode("<BR>", array(element('mem_bank_name', $row), element('mem_bank_account', $row), element('mem_bank_owner', $row))), $this->input->get('stx'))?>
					</td>
					<td class="text-center">
					<a href="/members/write/<?=element('mem_id', $row)?>" class="btn btn-default btn-xs">수정</a>
						<a href="<?="settlement?mem_id=".element('mem_id', $row)?>" class="btn btn-info btn-xs">
							<span>정산내역</span>
						</a>
					</td>
				</tr>
				<?php } ?>
				<?php if (!$data) { ?>
				<tr>
					<td colspan="6" class="nopost">표시할 데이터가 없습니다.</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div>
			<button type="submit" class="btn btn-default btn-sm" name="btn_submit" value="del">삭제</button>
		</div>
	</form>
	<div class="text-center" style="padding: 20px 0;">
		<nav><?php echo element("pagination", $view);?></nav>
	</div>
</div>
<script>
$(function () {
	$("#chk_all").click(function () {
		$("input[name='chk[]']").prop('checked', $(this).prop('checked'));
	});

	$("form[name=flist] table td.clickable").click(function () {
		var id = $(this).parent('tr').data('id');
		window.location.href = "/members/write/" + id;
	});
})

function flist_submit(f) {
	if (!confirm("선택한 항목을 삭제하시겠습니까?\n삭제된 항목은 다시 복구할 수 없습니다.")) {
		return false;
	}

	return true;
}
</script>