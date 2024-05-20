<?php
extract($view);
// debug_var($product_list);
foreach (element('list', $product_list) as $i => $row) {
?>
<option value="<?=element('park_prod_cd', $row)?>"<?=get_selected(element('park_prod_cd', $row), $selected_prod_cd)?> data-sel_id="<?=$selected_prod_cd?>" data-prod_price="<?=element('park_prod_price', $row)?>"><?=element('prod_name', $row)?></option>
<?php
}
?>