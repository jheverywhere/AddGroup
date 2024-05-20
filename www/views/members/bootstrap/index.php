<?php
extract($view);
// debug_var($view);
?>
<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="board">

	<h3>영업자관리</h3>

	<?php if ($this->member->is_admin()) { ?>
	<div class="form-group" style="text-align: right;">
		<a href="/memorialpark/write" class="btn btn-default btn-sm ">추가</a>
	</div>
	<?php } ?>

	<div class="condition clearfix">
		<form class="form-inline" name="fsearch" method="GET">
			<div class="row">
				<div class="col-md-10">
					<select class="form-control" name="sfl" id="sfl">
						<option value="mem_username"<?=get_selected('mem_username', $this->input->get('sfl'))?>>성명</option>
						<option value="mem_phone"<?=get_selected('mem_phone', $this->input->get('sfl'))?>>연락처</option>
						<option value="mem_addr"<?=get_selected('mem_addr', $this->input->get('sfl'))?>>주소</option>
					</select>
					<input type-="text" class="form-control" name="stx" value="<?=$this->input->get('stx')?>">
				</div>
				<div class="col-md-2 text-right">
					<button type="submit" class="btn btn-primary">검색</button>
				</div>
			</div>
		</form>
	</div>

	<form>
		<table class="table table-hover park_table">
			<thead>
				<tr>
					<th width="">
						<input id="chk_all" type="checkbox" class="post_con_chk">
					</th>
					<th width="">성명</th>
					<th width="">연락처</th>
					<th width="">주소</th>
					<th width="">주민등록번호</th>
					<th width="">부서명</th>
					<th width="">직급</th>
					<th width="">계좌번호</th>
					<th width="">주 취급품목</th>
					<th width="">주 장착지역</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach (element('list', $data) as $i => $row) { ?>
				<tr>
					<td>
						<input type="checkbox" name="chk[]" id="chk_<?=element('mem_id', $row)?>" class="post_con_chk" value="<?=element('mem_id', $row)?>">
					</td>
					<td>
						<a href="/members/write/<?=element('mem_id', $row)?>" class=""><?=element('mem_username', $row)?></a>
					</td>
					<td>
						<?=element('mem_phone', $row)?>
					</td>
					<td>
						<?=combine_address(element('mem_address1', $row), element('mem_address2', $row), element('mem_address3', $row), element('mem_address4', $row))?>
					</td>
					<td>
						<?=element('mem_regnum', $row)?>
					</td>
					<td>
						<?=element('mem_teamname', $row)?>
					</td>
					<td>
						<?=element('mem_grade', $row)?>
					</td>
					<td>
						<?=element('mem_account', $row)?>
					</td>
					<td>
						<?=element('mem_main_item', $row)?>
					</td>
					<td>
						<?=element('mem_main_sido_cd', $row)?>
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
	</form>

	<div class="row">
		<div class="col-md-12 text-center">
			<button type="button" name="" class="btn btn-primary btn-lg">선택항목만 모아보기</button>
		</div>
	</div>
	
	<!-- <div class="park_finder"> -->
		<!-- <input type="submit" value="선택항목만 모아보기" class="btn-primary"> -->
		<!-- <a href="javascript:;" id="btn-print" class="btn-primary" onClick="post_print('<?php echo element('post_id', element('post', $view)); ?>', 'post-print');" title="이 글을 프린트하기">인쇄하기</a> -->
		<!-- <a href="" class="btn-primary">공유하기</a> -->
	<!-- </div> -->

	<nav><?php echo element("pagination", $view);?></nav>
</div>
<script>
$(function () {
	$("#chk_all").click(function () {
		$("input[name='chk[]']").prop('checked', $(this).prop('checked'));
	});
});
</script>