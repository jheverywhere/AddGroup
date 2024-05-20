<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>그리운그대</title>
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/datepicker3.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
<?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?>
<?php echo $this->managelayout->display_css(); ?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.kr.js'); ?>"></script>

<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo base_url('assets/js/html5shiv.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
<![endif]-->
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
var cb_url = "<?php echo trim(site_url(), '/'); ?>";
var cb_admin_url = "<?php echo admin_url(); ?>";
var cb_charset = "<?php echo config_item('charset'); ?>";
var cb_time_ymd = "<?php echo cdate('Y-m-d'); ?>";
var cb_time_ymdhis = "<?php echo cdate('Y-m-d H:i:s'); ?>";
var admin_skin_path = "<?php echo element('layout_skin_path', $layout); ?>";
var admin_skin_url = "<?php echo element('layout_skin_url', $layout); ?>";
var is_member = "<?php echo $this->member->is_member() ? '1' : ''; ?>";
var is_admin = "<?php echo $this->member->is_admin(); ?>";
var cb_admin_url = <?php echo $this->member->is_admin() === 'super' ? 'cb_url + "/' . config_item('uri_segment_admin') . '"' : '""'; ?>;
var cb_board = "";
var cb_csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
var cookie_prefix = "<?php echo config_item('cookie_prefix'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/sideview.js'); ?>"></script>
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="//raw.githubusercontent.com/t0m/select2-bootstrap-css/bootstrap3/select2-bootstrap.css" /> -->
<?php echo $this->managelayout->display_js(); ?>
</head>
<body>
<?php
// debug_var($view);
// debug_var(element('menu', $layout));
?>
<!-- start wrapper -->
<div class="wrapper">
	<!-- start nav -->
	<nav class="nav-default">
		<h1 class="nav-header"><a href="/"><?php echo $this->cbconfig->item('admin_logo'); ?></a></h1>
		<ul class="nav">
			<?php
			$menuhtml = '';
			if (element('menu', $layout)) {
				$menu = element('menu', $layout);
				if (element(0, $menu)) {
					foreach (element(0, $menu) as $mkey => $mval) {
						if (element(element('men_id', $mval), $menu)) {
							$mlink = element('men_link', $mval) ? element('men_link', $mval) : 'javascript:;';
							$menuhtml .= '<li class="dropdown">
							<a href="' . $mlink . '" ' . element('men_custom', $mval);
							if (element('men_target', $mval)) {
								$menuhtml .= ' target="' . element('men_target', $mval) . '"';
							}
							$menuhtml .= ' title="' . strip_tags(element('men_name', $mval)) . '">' . (element('men_name', $mval)) . '</a>
							<ul class="nav nav-second-level">';

							foreach (element(element('men_id', $mval), $menu) as $skey => $sval) {
								// debug_var($sval);
								$slink = element('men_link', $sval) ? element('men_link', $sval) : 'javascript:;';
								$menuhtml .= '<li><a href="' . $slink . '" ' . element('men_custom', $sval);
								if (element('men_target', $sval)) {
									$menuhtml .= ' target="' . element('men_target', $sval) . '"';
								}
								$menuhtml .= ' title="' . strip_tags(element('men_name', $sval)) . '">' . (element('men_name', $sval)) . '</a></li>';
							}
							$menuhtml .= '</ul></li>';

						} else {
							$mlink = element('men_link', $mval) ? element('men_link', $mval) : 'javascript:;';
							$menuhtml .= '<li><a href="' . $mlink . '" ' . element('men_custom', $mval);
							if (element('men_target', $mval)) {
								$menuhtml .= ' target="' . element('men_target', $mval) . '"';
							}
							$menuhtml .= ' title="' . strip_tags(element('men_name', $mval)) . '">' . (element('men_name', $mval)) . '</a></li>';
						}
					}
				}
			}
			echo $menuhtml;
			?>
		</ul>
	</nav>
	<script type="text/javascript">
	//<![CDATA[
	$('#menu_collapse<?php echo element('menu_dir1', $layout); ?>').collapse('show');
	function changemenu( menukey) {
		if ($('#menu_collapse' + menukey).parent().hasClass('active')) {
			close_admin_menu();
		} else {
			open_admin_menu(menukey);
		}
	}
	function close_admin_menu() {
		$('.menu_collapse').collapse('hide');
		$('.nav-first-level').removeClass('active');
		$('.menu-arrow-icon').removeClass('fa-angle-down').addClass('fa-angle-left');
	}
	function open_admin_menu(menukey) {
		close_admin_menu();
		$('.nav-first-level.nav_menuname_' + menukey).addClass('active');
		$('.menu-arrow-icon.' + menukey).removeClass('fa-angle-left').addClass('fa-angle-down');
		$('#menu_collapse' + menukey).collapse('toggle');
	}
	//]]>
	</script>
	<!-- end nav -->

	<!-- start content_wrapper -->
	<div class="content_wrapper">
		<!-- start header -->
		<div class="row header">
			<div class="navbar-minimalize"><a href="#" class="btn btn-primary btn-mini"><i class="fa fa-bars"></i></a></div>
			<script type="text/javascript">
			//<![CDATA[
			$(document).on('click', '.navbar-minimalize>a', function() {
				if ($('.nav-default').is(':visible') === true) {
					$('.nav-default').hide();
					$('.content_wrapper').css('margin-left', '0px');
				} else {
					$('.nav-default').show();
					$('.content_wrapper').css('margin-left', '220px');
				}
			});
			//]]>
			</script>
			<ul class="nav-top">
				<li>
					<span><strong><?=element('mem_username', element('member', $layout))?></strong>님</span>
				</li>
				<li><a href="<?php echo site_url('login/logout'); ?>"><i class="fa fa-sign-out"></i> Log out</a></li>
			</ul>
		</div>
		<!-- end header -->
		<div class="contents">
			<?php echo element('menu_title', $layout) ? '<h3>' . element('menu_title', $layout) . '</h3>' : ''; ?>

			<!-- 여기까지 레이아웃 상단입니다 -->

			<?php echo $yield; ?>

			<!-- 여기부터 레이아웃 하단입니다 -->

		</div>
	</div>
	<!-- end content_wrapper -->
</div>
<!-- end wrapper -->

<!-- footer start -->
<footer>
	<div class="container">
		<div>
			<ul class="company">
				<!-- <li><a href="<?php echo document_url('aboutus'); ?>" title="회사소개">회사소개</a></li> -->
				<!-- <li><a href="<?php echo document_url('provision'); ?>" title="이용약관">이용약관</a></li> -->
				<!-- <li><a href="<?php echo document_url('privacy'); ?>" title="개인정보 취급방침">개인정보 취급방침</a></li> -->
			</ul>
		</div>
		<div class="copyright">
			<?php if ($this->cbconfig->item('company_address')) { ?>
				<span><?php echo $this->cbconfig->item('company_address'); ?>
					<?php if ($this->cbconfig->item('company_zipcode')) { ?>(우편 <?php echo $this->cbconfig->item('company_zipcode'); ?>)<?php } ?>
				</span>
			<?php } ?>
			<?php if ($this->cbconfig->item('company_owner')) { ?><span><b>대표</b> <?php echo $this->cbconfig->item('company_owner'); ?></span><?php } ?>
			<?php if ($this->cbconfig->item('company_phone')) { ?><span><b>전화</b> <?php echo $this->cbconfig->item('company_phone'); ?></span><?php } ?>
			<?php if ($this->cbconfig->item('company_fax')) { ?><span><b>팩스</b> <?php echo $this->cbconfig->item('company_fax'); ?></span><?php } ?>
		</div>
		<div class="copyright">
			<?php if ($this->cbconfig->item('company_reg_no')) { ?><span><b>사업자</b> <?php echo $this->cbconfig->item('company_reg_no'); ?></span><?php } ?>
			<?php if ($this->cbconfig->item('company_retail_sale_no')) { ?><span><b>통신판매</b> <?php echo $this->cbconfig->item('company_retail_sale_no'); ?></span><?php } ?>
			<?php if ($this->cbconfig->item('company_added_sale_no')) { ?><span><b>부가통신</b> <?php echo $this->cbconfig->item('company_added_sale_no'); ?></span><?php } ?>
			<?php if ($this->cbconfig->item('company_admin_name')) { ?><span><b>정보관리책임자명</b> <?php echo $this->cbconfig->item('company_admin_name'); ?></span><?php } ?>
			<span>Copyright&copy; <?php echo $this->cbconfig->item('site_title'); ?>. All Rights Reserved.</span>
		</div>
		<?php
		if ($this->cbconfig->get_device_view_type() === 'mobile') {
		?>
			<!-- <div class="see_mobile"><a href="<?php echo current_full_url(); ?>" class="btn btn-primary btn-xs viewpcversion">PC 버전으로 보기</a></div> -->
		<?php
		} else {
			if ($this->cbconfig->get_device_type() === 'mobile') {
		?>
			<!-- <div class="see_mobile"><a href="<?php echo current_full_url(); ?>" class="btn btn-primary btn-lg viewmobileversion" style="width:100%;font-size:5em;">모바일 버전으로 보기</a></div> -->
		<?php
			} else {
		?>
			<!-- <div class="see_mobile"><a href="<?php echo current_full_url(); ?>" class="btn btn-primary btn-xs viewmobileversion">모바일 버전으로 보기</a></div> -->
		<?php
			}
		}
		?>
	</div>
</footer>
<!-- footer end -->
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$(function() {
		$('#fsearch').validate({
			rules: {
				skeyword: { required:true, minlength:2}
			}
		});
		$('.btn_phone_link').popover({
			html: true,
			placement: 'auto'
		});
	});
});
function go_smssend(self) {
	var newForm = $('<form></form>');
	newForm.attr("name","newForm");
	newForm.attr("method","post");
	newForm.attr("action","<?=site_url("/smssend/form")?>");
	newForm.append($('<input/>', {type: 'hidden', name: 'phn', value: self.value }));
	newForm.append($('<input/>', {type: 'hidden', name: 'nm', value: $(self).data('nm') }));
	newForm.appendTo('body');
	newForm.submit();
}
//]]>
</script>
</body>
</html>
