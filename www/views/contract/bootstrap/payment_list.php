<?php
extract($view);
// debug_var($mode, $payment_list);
?>
<table class="table" id="payment_list_table">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px;">번호</th>
            <th class="text-center">날짜</th>
            <th class="text-center">결제내용</th>
            <th class="text-center">결제방법</th>
            <th class="text-center">메모</th>
            <th class="text-center">결제금액</th>
            <?php if ($mode != 'view') { ?>
            <th class="text-center" style="width: 80px;">관리</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        $total_price = 0;
        foreach (element('list', $payment_list) as $i => $row) {
            $total_price += element('payment_price', $row);
        ?>
        <tr class="<?=(element('payment_price', $row) < 0) ? "bg-danger" : "bg-success"?>">
            <td class="text-center"><?=++$no?></td>
            <td class="text-center"><?=element('payment_date', $row)?></td>
            <td class="text-center"><?=element('payment_type', $row)?></td>
            <td class="text-center"><?=element('payment_method', $row)?></td>
            <td class="text-center"><?=element('payment_content', $row)?></td>
            <td class="text-center"><?=display_price(element('payment_price', $row))?></td>
            <?php if ($mode != 'view') { ?>
            <td class="text-center">
                <button type="button" class="btn btn-default btn-xs" onclick="return edt_payment(this)" data-param="<?=http_build_query($row)?>">수정</button>
                <button type="button" class="btn btn-default btn-xs" onclick="return del_payment('<?=element('payment_no', $row)?>', '<?=element('ctr_no', $row)?>')">삭제</button>
            </td>
            <?php } ?>
        </tr>
        <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center" colspan="5">합 계</th>
            <th class="text-center"><?=display_price($total_price)?></th>
            <th class="text-center"></th>
        </tr>
    </tfoot>
</table>

<?php if ($mode != 'view') { ?>
<div class="pull-right">
    <button type="button" class="btn btn-info btn-sm" onclick="return add_payment();">추가</button>
</div>
<?php } ?>

<script>
function init_payment() {
	$("#payment_form input[name=payment_date]").val(moment().format("YYYY-MM-DD"));
	$("#payment_form input[name=payment_price]").val("");
	$("#payment_form select > option:nth-child(1)").prop("selected", true);
	$("#payment_form textarea").val("");
	$("#payment_form").modal('hide');
}
function add_payment() {
	init_payment();
	$("#payment_form").modal('show');
}
function edt_payment(self) {
    var param = Object.fromEntries(new URLSearchParams($(self).data('param')));
	$("#payment_form input[name=payment_no]").val(param.payment_no);
	$("#payment_form input[name=ctr_no]").val(param.ctr_no);
	$("#payment_form input[name=payment_date]").val(param.payment_date);
	$("#payment_form select[name=payment_type]").val(param.payment_type);
	$("#payment_form select[name=payment_method]").val(param.payment_method);
	$("#payment_form input[name=payment_price]").val(numberWithCommas(param.payment_price / 10000));
	$("#payment_form textarea[name=payment_content]").val(param.payment_content);
	$("#payment_form").modal('show');
}
function update_payment() {
	var data = {
		payment_no     : $("input[name=payment_no]").val(),
		ctr_no         : $("input[name=ctr_no]").val(),
		payment_date   : $("#payment_form input[name=payment_date]").val(),
		payment_type   : $("#payment_form select[name=payment_type]").val(),
		payment_method : $("#payment_form select[name=payment_method]").val(),
		payment_price  : numberWithoutCommas($("#payment_form input[name=payment_price]").val()),
		payment_content: $("#payment_form textarea[name=payment_content]").val()
	};
	$.ajax({
		type: 'POST',
		url: cb_url + '/contract/update_payment',
		data : data,
		async: false,
		success : function(data) {
			$("#payment_list").html(data);
			init_payment();
			$("#payment_form").modal('hide');
			window.location.reload();
		}
	});
}
function del_payment(payment_no, ctr_no) {
	if (confirm("선택한 입금내역을 삭제하시곘습니까?")) {
		var data = {
			payment_no : payment_no,
			ctr_no : ctr_no
		};
		$.ajax({
			type: 'POST',
			url: cb_url + '/contract/del_payment',
			data : data,
			async: false,
			success : function(data) {
				$("#payment_list").html(data);
				alert("삭제되었습니다.");
			}
		});
	}
}

var $form = $( "form[name=fpayment]" );
var $input = $form.find( "input.number_format" );
set_number_format($form, $input);
</script>
