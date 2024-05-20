<?php
extract($view);
$history_type_list = get_history_type_list();
// debug_var($history_list);
?>
<table id="history_list_table" class="table table-striped">
    <thead>
        <tr>
            <th class="text-center">번호</th>
            <th class="text-center">날짜</th>
            <th class="text-center">구분</th>
            <th class="text-center">세부내용</th>
            <?php if ($mode != 'view') { ?>
            <th class="text-center" style="width: 80px;">관리</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        foreach (element('list', $history_list) as $i => $row) {
            $history_type_opt = element(element('history_type', $row), $history_type_list);
            $bgcolor = element('color', $history_type_opt);
            $style = "background: {$bgcolor};color: #000;";
        ?>
        <tr>
            <td class="text-center"><?=++$no?></td>
            <td class="text-center"><?=date('Y-m-d H:i', strtotime(element('history_date', $row)));?></td>
            <td class="text-center" style="<?=$style?>"><?=element('history_type', $row);?></td>
            <td class="text-center"><?=element('history_content', $row);?></td>
            <?php if ($mode != 'view') { ?>
            <td class="text-center">
                <button type="button" class="btn btn-default btn-xs" onclick="return edt_history(this)" data-param="<?=http_build_query($row)?>">수정</button>
                <button type="button" class="btn btn-default btn-xs" onclick="return del_history('<?=element('history_no', $row)?>', '<?=element('ctr_no', $row)?>')">삭제</button>
            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php if ($mode != 'view') { ?>
<div class="pull-right">
    <button type="button" class="btn btn-info btn-sm" onclick="return add_history();">추가</button>
</div>
<?php } ?>

<script>
function init_history() {
	$("#history_form input").val('');
	$("#history_form input[name=history_date]").val(moment().format("YYYY-MM-DD HH:00"));
	$("#history_form select > option:nth-child(1)").prop("selected", true);
	$("#history_form textarea").val("");
}
function add_history() {
	init_history();
	$("#history_form").modal('show');
}
// function edt_history(history_no, ctr_no, history_date, history_type, history_content) {
function edt_history(self) {
    var param = Object.fromEntries(new URLSearchParams($(self).data('param')));
	$("#history_form input[name=history_no]").val(param.history_no);
	$("#history_form input[name=ctr_no]").val(param.ctr_no);
	$("#history_form input[name=history_date]").val(moment(param.history_date).format("YYYY-MM-DD HH:mm"));
	$("#history_form select[name=history_type]").val(param.history_type);
	$("#history_form textarea[name=history_content]").val(param.history_content);
	$("#history_form").modal('show');
}
function update_history() {
	var data = {
		ctr_no     : $("input[name=ctr_no]").val(),
		history_no     : $("input[name=history_no]").val(),
		history_date   : $("#history_form input[name=history_date]").val(),
		history_type : $("#history_form select[name=history_type]").val(),
		history_content: $("#history_form textarea[name=history_content]").val()
	};
	$.ajax({
		type: 'POST',
		url: cb_url + '/contract/update_history',
		data : data,
		async: false,
		success : function(data) {
			$("#history_list").html(data);
			init_history();
			$("#history_form").modal('hide');
		}
	});
}
function del_history(history_no, ctr_no) {
	if (confirm("선택한 진행내역을 삭제하시곘습니까?")) {
		var data = {
			history_no : history_no,
			ctr_no : ctr_no
		};
		$.ajax({
			type: 'POST',
			url: cb_url + '/contract/del_history',
			data : data,
			async: false,
			success : function(data) {
				$("#history_list").html(data);
				alert("삭제되었습니다.");
			}
		});
	}
}

var $form = $( "form[name=fhistory]" );
var $input = $form.find( "input.number_format" );
set_number_format($form, $input);

// $('#history_date').datepicker({
//     format: 'yyyy-mm-dd',
//     language: 'kr',
//     autoclose: true,
//     todayHighlight: true,
//     orientation: 'bottom'
// });
</script>
