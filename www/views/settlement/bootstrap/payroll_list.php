<?php
extract($view);
?>
<table class="table" id="payroll_list_table">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px;">번호</th>
            <th class="text-center">날짜</th>
            <th class="text-center">메모</th>
            <th class="text-center">지급금액</th>
            <?php if ($mode != 'view') { ?>
            <th class="text-center" style="width: 80px;">관리</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_price = 0;
        foreach (element('list', $payroll_list) as $i => $row) {
            $total_price += element('payroll_amount', $row);
        ?>
        <tr class="<?=(element('payroll_amount', $row) < 0) ? "bg-danger" : "bg-success"?>">
            <td class="text-center"><?=$i + 1?></td>
            <td class="text-center"><?=element('payroll_date', $row)?></td>
            <td class="text-center"><?=element('payroll_content', $row)?></td>
            <td class="text-center"><?=display_price(element('payroll_amount', $row))?></td>
            <?php if ($mode != 'view') { ?>
            <td class="text-center">
                <button type="button" class="btn btn-default btn-xs" onclick="return edt_payroll(this)" data-param="<?=http_build_query($row)?>">수정</button>
                <button type="button" class="btn btn-default btn-xs" onclick="return delete_payroll('<?=element('payroll_no', $row)?>', '<?=element('ctr_no', $row)?>')">삭제</button>
            </td>
            <?php } ?>
        </tr>
        <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center" colspan="3">합 계</th>
            <th class="text-center"><?=display_price($total_price)?></th>
            <th class="text-center"></th>
        </tr>
    </tfoot>
</table>

<?php if ($mode != 'view') { ?>
<div class="pull-right">
    <button type="button" class="btn btn-info btn-sm" onclick="return add_payroll()">추가</button>
</div>
<?php } ?>

<script>
function init_payroll_form() {
    $("#payroll_form input[name=payroll_date]").val(moment().format("YYYY-MM-DD"));
    $("#payroll_form input[name=payroll_amount]").val("");
    $("#payroll_form textarea").val("");
}
function add_payroll() {
    init_payroll_form();
    $("#payroll_form").modal('show');
}
function edt_payroll(self) {
    var param = Object.fromEntries(new URLSearchParams($(self).data('param')));
	$("#payroll_form input[name=payroll_no]").val(param.payroll_no);
	$("#payroll_form input[name=ctr_no]").val(param.ctr_no);
	$("#payroll_form input[name=payroll_date]").val(param.payroll_date);
	$("#payroll_form input[name=payroll_amount]").val(numberWithCommas(param.payroll_amount));
	$("#payroll_form textarea[name=payroll_content]").val(param.payroll_content);
	$("#payroll_form").modal('show');
}
function update_payroll() {
	var data = {
        payroll_no     : $("input[name=payroll_no]").val(),
        ctr_no         : $("input[name=ctr_no]").val(),
		payroll_date   : $("#payroll_form input[name=payroll_date]").val(),
		payroll_amount : numberWithoutCommas($("#payroll_form input[name=payroll_amount]").val()),
		payroll_content: $("#payroll_form textarea[name=payroll_content]").val()
	};
	$.ajax({
		type: 'POST',
		url: cb_url + '/settlement/update_payroll',
		data : data,
		async: false,
		success : function(data) {
            $("#payroll_list").html(data);
            init_payroll_form();
			$("#payroll_form").modal('hide');
		}
	});
}
function delete_payroll(payroll_no, ctr_no) {
	if (confirm("선택한 지급내역을 삭제하시곘습니까?")) {
		var data = {
			payroll_no : payroll_no,
			ctr_no : ctr_no
		};
		$.ajax({
			type: 'POST',
			url: cb_url + '/settlement/delete_payroll',
			data : data,
			async: false,
			success : function(data) {
				$("#payroll_list").html(data);
				alert("삭제되었습니다.");
			}
		});
	}
}

var $form = $( "form[name=fpayroll]" );
var $input = $form.find( "input.number_format" );
set_number_format($form, $input);
</script>