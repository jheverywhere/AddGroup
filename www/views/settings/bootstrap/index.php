<?php
$this->managelayout->add_css(site_url('/plugin/datatables/datatables.min.css'));
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
extract($view);
// debug_var($type_list);
?>

<div class="board">

	<h3>코드관리</h3>

	<div class="row">
		<div class="col-md-12">
			<table id="memListTable" class="table table-striped table-hover display" style="width:100%">
				<thead>
					<tr>
						<th>#</th>
						<th>구분</th>
						<th>순서</th>
						<th>값</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>	

	<!-- <div class="row">
		<div class="col-md-12 text-center">
			<nav><?php echo element("pagination", $view);?></nav>
		</div>
	</div>	 -->
</div>
<script type="text/javascript" charset="utf8" src="/plugin/datatables/datatables.min.js"></script>
<script>
$(function () {
    $('#memListTable').DataTable({
        "processing": true,
		"serverSide": true,
		"iDisplayLength": 25,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url('settings/getLists'); ?>",
            "type": "POST"
        },
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false
        }]
    });
});
</script>