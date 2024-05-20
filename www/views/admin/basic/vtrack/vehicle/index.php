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
						<a href="<?php echo element('write_url', $view); ?>" class="btn btn-outline btn-danger btn-sm">차량추가</a>
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
							<th>그룹명</th>
							<th><a href="<?php echo element('vhc_num', element('sort', $view)); ?>">차량번호</a></th>
							<th>차대번호</th>
							<th>차량종류</th>
							<th>차량명</th>
                            <th>관리회사</th>
                            <th>소유회사</th>
                            <th>생산회사</th>
                            <th>연식</th>
                            <th>유형</th>
                            <th>단말기 고유번호</th>
                            <th><a href="<?php echo element('vhc_reg_time', element('sort', $view)); ?>">등록일</a></th>
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
							<td>
                                <select name="vgr_id" id="vgr_id" class="form-control" >
                                    <?php foreach (element('group_list', $view) as $group) { ?>
                                    <option value="<?=$group['vgr_id']?>"<?=get_selected($group['vgr_id'],element('vgr_id', $row))?>><?=$group['vgr_name']?></option>
                                    <?php } ?>
                                </select>
							</td>
							<td>
								<input type="text" name="vhc_num[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vhc_num', $row)); ?>" />
							</td>
							<td>
								<input type="text" name="vhc_chassis_num[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vhc_chassis_num', $row)); ?>" />
							</td>
							<td>
                                <select name="vhc_type" class="form-control">
                                <?php
                                if (is_array($column_type['vhc_type'])) {
                                    foreach ($column_type['vhc_type'] as $col_name) {
                                ?>
                                    <option value="<?=$col_name?>"<?=get_selected($col_name, element('vhc_type', $row))?>><?=$col_name?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
							</td>
							<td>
								<input type="text" name="vhc_name[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vhc_name', $row)); ?>" />
							</td>
							<td>
								<input type="text" name="vhc_manage_corp[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vhc_manage_corp', $row)); ?>" />
							</td>
							<td>
								<input type="text" name="vhc_owner_corp[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vhc_owner_corp', $row)); ?>" />
							</td>
							<td>
								<input type="text" name="vhc_manufacturer[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vhc_manufacturer', $row)); ?>" />
							</td>
							<td>
								<input type="text" name="vhc_model_year[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vhc_model_year', $row)); ?>" />
							</td>
							<td>
                                <select name="vhc_usage_type" class="form-control">
                                <?php
                                if (is_array($column_type['vhc_usage_type'])) {
                                    foreach ($column_type['vhc_usage_type'] as $col_name) {
                                ?>
                                    <option value="<?=$col_name?>"<?=get_selected($col_name, element('vhc_usage_type', $row))?>><?=$col_name?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
							</td>
							<td>
								<input type="text" name="vhc_terminal_serial[<?php echo element(element('primary_key', $view), $row); ?>]" class="form-control" value="<?php echo html_escape(element('vhc_terminal_serial', $row)); ?>" />
							</td>
							<td>
                            <?php echo html_escape(element('vhc_reg_time', $row)); ?>
							</td>
							<td>
                                <?php echo html_escape(element('vhc_upd_time', $row)); ?>
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
