<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// debug_var($view);
$column_type = element('type_list', $view);
?>
<div class="box">
	<div class="box-table">
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<?php
				ob_start();
				?>
					<div class="btn-group pull-right" role="group" aria-label="...">
						<a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-update btn-list-selected disabled" data-list-update-url = "<?php echo element('list_update_url', $view); ?>" >선택수정</button>
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
						<a href="<?php echo element('write_url', $view); ?>" class="btn btn-outline btn-danger btn-sm">그룹추가</a>
					</div>
				<?php
				$buttons = ob_get_contents();
				ob_end_flush();
				?>
				<div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered">
					<thead>
						<tr>
							<th>번호</th>
							<!-- <th>상위그룹</th> -->
							<th>구분</th>
							<th>그룹명</th>
                            <th>설명</th>
                            <th>등록일</th>
                            <th>수정일</th>
							<th>수정</th>
							<th><input type="checkbox" name="chkall" id="chkall" /></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $row) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $row)); ?></td>
							<!-- <td>
                                <select name="vgr_parent_id" id="vgr_parent_id" class="form-control" >
                                    <option value="">없음</option>
                                    <?php
                                    foreach (element('group_list', $view) as $group) {
                                        if (element('vgr_id', $group) == element('vgr_id', $row)) {
                                            continue;
                                        }
                                    ?>
                                    <option value="<?=$group['vgr_id']?>"<?=get_selected(element('vgr_id', $group),element('vgr_parent_id', $row))?>><?=$group['vgr_name']?></option>
                                    <?php } ?>
                                </select>
							</td> -->
							<td>
								<input type="text" name="vgr_name[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vgr_name', $row)); ?>" />
							</td>
							<td>
                                <textarea class="form-control per100" name="vgr_desc[<?php echo element(element('primary_key', $view), $row); ?>]" placeholder="그룹을 설명해주세요."><?php echo html_escape(element('vgr_desc', $row)); ?></textarea>
							</td>
							<td>
                                <?php echo html_escape(element('vgr_reg_time', $row)); ?>
							</td>
							<td>
                                <?php echo html_escape(element('vgr_upd_time', $row)); ?>
							</td>
							<td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $row); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a></td>
							<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $row); ?>" /></td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="10" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<div class="box-info">
				<?php echo element('paging', $view); ?>
				<div class="pull-left ml20"><?php echo admin_listnum_selectbox();?></div>
				<?php echo $buttons; ?>
			</div>
		<?php echo form_close(); ?>
	</div>
	<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
		<div class="box-search">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<select class="form-control" name="sfield" >
						<?php echo element('search_option', $view); ?>
					</select>
					<div class="input-group">
						<input type="text" class="form-control" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." />
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" name="search_submit" type="submit">검색</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
