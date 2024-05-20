<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// debug_var($view);
$column_type = element('type_list', $view);
?>
<div class="box">

	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div>
				<div class="form-group">
					<label class="col-sm-2 control-label">그룹아이디</label>
					<div class="col-sm-8">
                        <input type="text" class="form-control per100" name="vgr_id" value="<?=set_value('vgr_id', element('vgr_id', element('data', $view)))?>" readonly/>
                        <span class="help-block">그룹을 관리하기 위한 아이디로 자동 발급됩니다.</span>
					</div>
				</div>
				<!-- <div class="form-group">
					<label class="col-sm-2 control-label">상위그룹</label>
					<div class="col-sm-8">
                        <select name="vgr_parent_id" id="vgr_parent_id" class="form-control" >
                            <option value="">없음</option>
                            <?php
                            foreach (element('group_list', $view) as $group) {
                                if (element('vgr_id', $group) == element('vgr_id', element('data', $view))) {
                                    continue;
                                }
                            ?>
                            <option value="<?=element('vgr_id', $group)?>"<?=get_selected(element('vgr_id', $group), element('vgr_parent_id', element('data', $view)))?>><?=element('vgr_name', $group)?></option>
                            <?php
                            }
                            ?>
                        </select>
					</div>
				</div> -->
				<div class="form-group">
					<label class="col-sm-2 control-label">그룹명</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vgr_name" value="<?php echo set_value('vgr_name', element('vgr_name', element('data', $view))); ?>" placeholder="그룹명을 입력해주세요."/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">그룹명</label>
					<div class="col-sm-8">
                        <textarea class="form-control per100" name="vgr_desc" placeholder="그룹을 설명해주세요."><?php echo set_value('vgr_desc', element('vgr_desc', element('data', $view))); ?></textarea>
					</div>
				</div>
				<div class="btn-group pull-right" role="group" aria-label="...">
					<a href="<?php echo admin_url($this->pagedir); ?>" class="btn btn-default btn-sm">목록으로</a>
					<button type="submit" class="btn btn-success btn-sm">저장하기</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			brd_key: {required :true, alpha_dash:true, minlength:3, maxlength:50 },
			brd_name: {required :true },
			bgr_id: {required :true },
			brd_order: {required :true, number:true, min:0, max:10000}
		}
	});
});
var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
	if ($('#fadminwrite').serialize() !== form_original_data) {
		if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}
//]]>
</script>
