<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// debug_var($view);
?>
<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>">운행기록계 파일 수집 내역</a></li>
			<!-- <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/usestat'); ?>">사용통계</a></li> -->
		</ul>
	</div>
	<div class="box-table">
		<div class="box-table-header">
			<form class="form-inline" name="flist" action="<?php echo current_url(); ?>" method="get" >
				<input type="hidden" name="datetype" value="<?php echo html_escape($this->input->get('datetype')); ?>" />
				<input type="hidden" name="dep_from_type" value="<?php echo html_escape($this->input->get('dep_from_type')); ?>" />
				<input type="hidden" name="method" value="<?php echo html_escape($this->input->get('method')); ?>" />
				<div class="btn-group" role="group" aria-label="...">
					<button type="button" class="btn <?php echo ($this->input->get('dep_from_type') !== 'cash' && $this->input->get('dep_from_type') !== 'point') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fcharge_submit('');">전체</button>
					<button type="button" class="btn <?php echo ($this->input->get('dep_from_type') === 'cash') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fcharge_submit('cash');">필터1</button>
					<button type="button" class="btn <?php echo ($this->input->get('dep_from_type') === 'point') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fcharge_submit('point');">필터2</button>
				</div>
				<div class="box-table-button">
					<span class="mr10">
						기간 : <input type="text" class="form-control input-small datepicker " name="start_date" value="<?php echo element('start_date', $view); ?>" readonly="readonly" /> - <input type="text" class="form-control input-small datepicker" name="end_date" value="<?php echo element('end_date', $view); ?>" readonly="readonly" />
					</span>
					<div class="btn-group" role="group" aria-label="...">
						<button type="button" class="btn <?php echo ($this->input->get('datetype') !== 'y' && $this->input->get('datetype') !== 'm') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fdate_submit('d');">일별보기</button>
						<button type="button" class="btn <?php echo ($this->input->get('datetype') === 'm') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fdate_submit('m');">월별보기</button>
						<button type="button" class="btn <?php echo ($this->input->get('datetype') === 'y') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fdate_submit('y');">년별보기</button>
					</div>
				</div>
			</form>
			<script type="text/javascript">
			//<![CDATA[
			function fdate_submit(datetype)
			{
				var f = document.flist;
				f.datetype.value = datetype;
				f.submit();
			}
			function fcharge_submit(dep_from_type)
			{
				var f = document.flist;
				f.dep_from_type.value = dep_from_type;
				f.method.value = '';
				f.submit();
			}
			function fmethod_submit(method)
			{
				var f = document.flist;
				f.method.value = method;
				f.submit();
			}
			//]]>
			</script>
		</div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col>
					<col>
					<col>
					<col>
					<col>
					<col class="col-md-5">
				</colgroup>
				<thead>
					<tr>
						<th>번호</th>
						<th>기사명</th>
						<th>기사연락처</th>
						<th>차량번호</th>
						<th>파일사이즈</th>
						<th>수신시간</th>
						<th>eTAS 전송시간</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$list = element('list', element('data', $view));
				if ($list) {
					foreach ($list as $row) {
				?>
					<tr>
						<td><?php echo element('vtl_id', $row); ?></td>
						<td><?php echo element('mem_name', $row); ?></td>
						<td><?php echo element('mem_phone', $row); ?></td>
						<td><?php echo element('vhc_num', $row); ?></td>
						<td class="text-right"><?php echo format_bytes(element('vtl_filesize', $row), 1); ?></td>
						<td><?php echo element('vtl_reg_time', $row); ?></td>
						<td><?php echo element('vtl_upload_time', $row); ?></td>
					</tr>
				<?php
					}
				}
				if ( ! $list) {
				?>
					<tr>
						<td colspan="6" class="nopost">자료가 없습니다</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
