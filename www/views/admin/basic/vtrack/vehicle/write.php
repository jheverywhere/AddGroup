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
					<label class="col-sm-2 control-label">차량아이디</label>
					<div class="col-sm-8">
                        <input type="text" class="form-control per100" name="vhc_id" value="<?=set_value('vhc_id', element('vhc_id', element('data', $view)))?>" readonly/>
                        <span class="help-block">차량을 관리하기 위한 아이디로 자동 발급됩니다.</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">차량번호</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vhc_num" value="<?php echo set_value('vhc_num', element('vhc_num', element('data', $view))); ?>" placeholder="지역12가1234"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">그룹명</label>
					<div class="col-sm-8 form-inline">
						<select name="vgr_id" id="vgr_id" class="form-control" >
                            <?php foreach (element('group_list', $view) as $group) { ?>
                            <option value="<?=$group['vgr_id']?>"<?=get_selected($group['vgr_id'],'')?>><?=$group['vgr_name']?></option>
                            <?php } ?>
							<?php echo element('group_option', element('data', $view)); ?>
						</select>
						<div class="help-inline"><a href="<?php echo admin_url('vehicle/vehiclegroup'); ?>">그룹생성하러 가기</a></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">차대번호</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vhc_chassis_num" value="<?php echo set_value('vhc_chassis_num', element('vhc_chassis_num', element('data', $view))); ?>" placeholder="KMHTB41BP8A000101"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">차량종류</label>
					<div class="col-sm-8">
                        <select name="vhc_type" class="form-control">
                        <?php
                        if (is_array($column_type['vhc_type'])) {
                            foreach ($column_type['vhc_type'] as $col_name) {
                        ?>
                            <option value="<?=$col_name?>"<?=get_selected($col_name, element('vhc_type', element('data', $view)))?>><?=$col_name?></option>
                        <?php
                            }
                        }
                        ?>
                        </select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">차량명</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vhc_name" value="<?php echo set_value('vhc_name', element('vhc_name', element('data', $view))); ?>" placeholder="차량의 이름을 입력해주세요."/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">관리회사</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vhc_manage_corp" value="<?php echo set_value('vhc_manage_corp', element('vhc_manage_corp', element('data', $view))); ?>" placeholder="관리하는 회사명을 입력해주세요."/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">소유회사</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vhc_owner_corp" value="<?php echo set_value('vhc_owner_corp', element('vhc_owner_corp', element('data', $view))); ?>" placeholder="차량을 소유하고 있는 회사명을 입력해주세요."/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">제조사</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vhc_manufacturer" value="<?php echo set_value('vhc_manufacturer', element('vhc_manufacturer', element('data', $view))); ?>" placeholder="차량의 제조사를 입력해주세요."/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">연식</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vhc_model_year" value="<?php echo set_value('vhc_model_year', element('vhc_model_year', element('data', $view))); ?>" placeholder="차량의 연식을 입력해주세요."/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">유형</label>
					<div class="col-sm-8">
                        <select name="vhc_usage_type" class="form-control">
                        <?php
                        if (is_array($column_type['vhc_usage_type'])) {
                            foreach ($column_type['vhc_usage_type'] as $col_name) {
                        ?>
                            <option value="<?=$col_name?>"<?=get_selected($col_name, element('vhc_usage_type', element('data', $view)))?>><?=$col_name?></option>
                        <?php
                            }
                        }
                        ?>
                        </select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">터미널 고유번호</label>
					<div class="col-sm-8">
						<input type="text" class="form-control per100" name="vhc_terminal_serial" value="<?php echo set_value('vhc_terminal_serial', element('vhc_terminal_serial', element('data', $view))); ?>" />
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
