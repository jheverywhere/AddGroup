<?php
extract($view);
// debug_var($product_list);
?>
<table class="table table-hover table-striped" id="product_list_table">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px;">번호</th>
            <th class="text-center">상품명</th>
            <th class="text-center">안치수</th>
            <th class="text-center">상품금액</th>
            <th class="text-center">관리비</th>
            <!-- <th class="text-center">판매여부</th> -->
            <?php if ($mode != 'view') { ?>
            <th class="text-center" style="width: 80px;">관리</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_price = 0;
        foreach (element('list', $product_list) as $i => $row) {
            $total_price += element('product_amount', $row);
        ?>
        <tr>
            <td class="text-center"><?=$i + 1?></td>
            <?php if (element('park_type_cd', $row) == 'CH') { ?>
                <td class="text-center"><?=element('park_prod_name', $row).' '.element('park_dan_cd', $row).'단'?></td>
            <?php } else if (in_array(element('park_type_cd', $row), array('NB', 'CT', 'CF'))) { ?>
                <td class="text-center"><?=element('park_prod_name', $row)?></td>
            <?php } else { ?>
                <td class="text-center"><?=element('park_prod_name', $row)?></td>
            <?php } ?>
            <td class="text-center"><?=intval(substr(element('park_prod_cd', $row), 10, 2))?></td>
            <td class="text-center"><?=display_price(element('park_prod_price', $row))?></td>
            <td class="text-center"><?=display_price(element('park_prod_fee', $row))?></td>
            <!-- <td class="text-center"><?=element('park_prod_soldout', $row)?></td> -->
            <?php if ($mode != 'view') { ?>
            <td class="text-center">
                <button type="button" class="btn btn-default btn-xs" onclick="return edt_product(this)" data-param="<?=http_build_query($row)?>">수정</button>
                <button type="button" class="btn btn-default btn-xs" onclick="return delete_product('<?=element('park_prod_cd', $row)?>', '<?=element('park_no', $row)?>')">삭제</button>
            </td>
            <?php } ?>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<?php if ($mode != 'view') { ?>
<div class="pull-right">
    <button type="button" class="btn btn-info btn-sm" onclick="return add_product()">추가</button>
</div>
<?php } ?>

<script>
function init_product() {
    var $form = $("#product_form");
    $form.find("input[type=text], input[type=number], textarea").val("");
    $form.find("select > option:nth-child(1)").prop("selected", true).select2().trigger('change');
}
function add_product() {
    init_product();
    $("#product_form").modal('show');
}
function edt_product(self) {
    var param = Object.fromEntries(new URLSearchParams($(self).data('param')));
    // for (const [key, val] of Object.entries(param)) {
    //    $(`#product_form [name=${key}]`).val(val);
    // }
    var park_type_cd = param.park_prod_cd.substring(0, 2);
    var park_no = param.park_no;
    var park_prod_seq = param.park_prod_cd.substring(4, 6);

	$("#product_form").modal('show');
    $("#product_form input[name=park_prod_cd]").val(param.park_prod_cd);
    $("#product_form input[name=park_type_cd]").val(param.park_type_cd);
    $("#product_form input[name=park_prod_name]").val(param.park_prod_name);
    if (park_type_cd == "CH") {
        var dan_type = param.park_prod_cd.substring(6, 8);
        var dan_floor = param.park_prod_cd.substring(8, 10);
        console.log(param.park_prod_cd, dan_type, dan_floor);
        $("#product_form select[name=dan_type]").val(dan_type).prop('disabled', true).select2().trigger('change');
        $("#product_form input[name=dan_floor]").val(Number.parseInt(dan_floor)).prop('disabled', true);
    } else if (park_type_cd == "NB") {
        var public_yn = param.park_prod_cd.substring(6, 8);
        var tree_type = param.park_prod_cd.substring(8, 10);
        console.log(param.park_prod_cd, public_yn, tree_type);
        $("#product_form select[name=public_yn]").val(public_yn).prop('disabled', true).select2().trigger('change');
        $("#product_form select[name=tree_type]").val(tree_type).prop('disabled', true).select2().trigger('change');
    } else if (park_type_cd == "CT") {
    }
    var anchisu = Number.parseInt(param.park_prod_cd.substring(10, 12));
	$("#product_form input[name=anchisu]").val(anchisu).prop('disabled', true);
	$("#product_form input[name=park_prod_price]").val(numberWithCommas(param.park_prod_price / 10000));
	$("#product_form input[name=park_prod_fee]").val(numberWithCommas(param.park_prod_fee / 10000));
}
function update_product() {
    if ($("#park_prod_name").val() == '') {
        alert("상품명를 입력해주세요.");
        $("#park_prod_name").focus();
        return false;
    }
    if (typeof $("#dan_floor").val() != 'undefined' && $("#dan_floor").val() <= 0) {
        alert("단높이는 0 보다 큰 값이어야 합니다.");
        $("#dan_floor").focus();
        return false;
    }
    if ($("#anchisu").val() <= 0) {
        alert("안치수는 0 보다 큰 값이어야 합니다.");
        $("#anchisu").focus();
        return false;
    }

    var params = $("form[name=fproduct]").serialize();
    // console.log(params);
    params['park_prod_price'] = numberWithoutCommas(params['park_prod_price']);
	$.ajax({
		type: 'POST',
		url: cb_url + '/park/update_product',
		data : params,
		async: false,
		success : function(data) {
			$("#product_list").html(data);
            $("#product_form").modal('hide');
            init_product();
        },
        error: function(jqxhr, text_status, error_thrown) {
            console.log(jqxhr.status, text_status, error_thrown);
            if (200 <= jqxhr.status && jqxhr.status < 300) {
                alert("상품정보 등록시 오류가 발생했습니다. 관리자에게 문의해주십시오.");
            }
        }
	});
}
function delete_product(park_prod_cd, park_no) {
	if (confirm("선택한 항목을 삭제하시곘습니까?")) {
		var data = {
			park_prod_cd : park_prod_cd,
			park_no : park_no
		};
		$.ajax({
			type: 'POST',
			url: cb_url + '/park/delete_product',
			data : data,
			async: false,
			success : function(data) {
				alert("삭제되었습니다.");
				$("#product_list").html(data);
			}
		});
	}
}
var $form = $( "form[name=fproduct]" );
var $input = $form.find( "input.number_format" );
set_number_format($form, $input);
</script>