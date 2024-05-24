<?php
$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
$this->managelayout->add_css(base_url('assets/css/select2.min.css'));
$this->managelayout->add_js(base_url('assets/js/select2.min.js'));
// debug_var(element('data', $view));
?>
<!-- <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> -->
	<div class="modal-dialog">
		<div class="modal-content">
			<?php
			$attributes = array('name' => 'form_sms', 'id' => 'form_sms', 'onsubmit' => 'return sms_chk_send(this)');
			echo form_open_multipart(current_full_url(), $attributes);
			?>
			<input type="hidden" name="send_list" value="" />

			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<h4 class="modal-title" id="myModalLabel">고객문자발송하기</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<?php
						// echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
						// echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
						?>
						<div class="form-group form-inline">
							<label for="ssc_send_image">이미지</label>
							<input type="file" name="ssc_send_image" id="ssc_send_image" class="form-control" accept=".png, .jpg, .gif" />
							<p class="help-block">최대크기 : 300KB, 최대너비 : 1000px, 최대높이 : 2000px, 이미지가 첨부된 경우 MMS로 전송, 첨부되지 않은 경우 LMS 로 전송함</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div>
							<h5>보낼내용</h5>
							<textarea class="form-control" rows="5" name="sfa_content" id="sfa_content" onkeyup="byte_check('sfa_content', 'sms_bytes');" required><?php echo set_value('sfa_content', element('sfa_content', element('data', $view))); ?></textarea>
							<div class="form-inline">
								<div class="pull-right" style="position:relative;">
									<button type="button" id="write_sc_btn" class="btn btn-default btn-xs write_scemo_btn">특수기호</button>
									<div id="write_sc" class="write_scemo">
										<span class="scemo_ico"></span>
										<div class="scemo_list">
											<button type="button" class="scemo_add" onclick="javascript:add('■')">■</button>
											<button type="button" class="scemo_add" onclick="javascript:add('□')">□</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▣')">▣</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◈')">◈</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◆')">◆</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◇')">◇</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♥')">♥</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♡')">♡</button>
											<button type="button" class="scemo_add" onclick="javascript:add('●')">●</button>
											<button type="button" class="scemo_add" onclick="javascript:add('○')">○</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▲')">▲</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▼')">▼</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▶')">▶</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▷')">▷</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◀')">◀</button>
											<button type="button" class="scemo_add" onclick="javascript:add('◁')">◁</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☎')">☎</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☏')">☏</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♠')">♠</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♤')">♤</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♣')">♣</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♧')">♧</button>
											<button type="button" class="scemo_add" onclick="javascript:add('★')">★</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☆')">☆</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☞')">☞</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☜')">☜</button>
											<button type="button" class="scemo_add" onclick="javascript:add('▒')">▒</button>
											<button type="button" class="scemo_add" onclick="javascript:add('⊙')">⊙</button>
											<button type="button" class="scemo_add" onclick="javascript:add('㈜')">㈜</button>
											<button type="button" class="scemo_add" onclick="javascript:add('№')">№</button>
											<button type="button" class="scemo_add" onclick="javascript:add('㉿')">㉿</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♨')">♨</button>
											<button type="button" class="scemo_add" onclick="javascript:add('™')">™</button>
											<button type="button" class="scemo_add" onclick="javascript:add('℡')">℡</button>
											<button type="button" class="scemo_add" onclick="javascript:add('∑')">∑</button>
											<button type="button" class="scemo_add" onclick="javascript:add('∏')">∏</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♬')">♬</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♪')">♪</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♩')">♩</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♭')">♭</button>
										</div>
										<div class="pull-right"><button type="button" class="btn btn-xs scemo_cls_btn">닫기</button></div>
									</div>
									<button type="button" id="write_emo_btn" class="btn btn-default btn-xs write_scemo_btn">이모티콘</button>
									<div id="write_emo" class="write_scemo">
										<span class="scemo_ico"></span>
										<div class="scemo_list">
											<button type="button" class="scemo_add" onclick="javascript:add('*^^*')">*^^*</button>
											<button type="button" class="scemo_add" onclick="javascript:add('♡.♡')">♡.♡</button>
											<button type="button" class="scemo_add" onclick="javascript:add('@_@')">@_@</button>
											<button type="button" class="scemo_add" onclick="javascript:add('☞_☜')">☞_☜</button>
											<button type="button" class="scemo_add" onclick="javascript:add('ㅠ ㅠ')">ㅠ ㅠ</button>
											<button type="button" class="scemo_add" onclick="javascript:add('Θ.Θ')">Θ.Θ</button>
											<button type="button" class="scemo_add" onclick="javascript:add('^_~♥')">^_~♥</button>
											<button type="button" class="scemo_add" onclick="javascript:add('~o~')">~o~</button>
											<button type="button" class="scemo_add" onclick="javascript:add('★.★')">★.★</button>
											<button type="button" class="scemo_add" onclick="javascript:add('(!.!)')">(!.!)</button>
											<button type="button" class="scemo_add" onclick="javascript:add('⊙.⊙')">⊙.⊙</button>
											<button type="button" class="scemo_add" onclick="javascript:add('q.p')">q.p</button>
											<button type="button" class="scemo_add emo_long" onclick="javascript:add('┏( \'\')┛')">┏( \'\')┛</button>
											<button type="button" class="scemo_add emo_long" onclick="javascript:add('@)-)--')">@)-)--')</button>
											<button type="button" class="scemo_add emo_long" onclick="javascript:add('↖(^-^)↗')">↖(^-^)↗</button>
											<button type="button" class="scemo_add emo_long" onclick="javascript:add('(*^-^*)')">(*^-^*)</button>
										</div>
										<div class="pull-right"><button type="button" class="btn btn-xs scemo_cls_btn">닫기</button></div>
									</div>
								</div>
								<div id="sms_byte"><span id="sms_bytes">0</span> / 2000 byte</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group form-inline">
							<label for="ssc_send_phone">발신번호</label>
							<input type="text" name="ssc_send_phone" value="<?php echo $this->member->item('mem_phone'); ?>" id="ssc_send_phone" class="form-control" maxlength="20" <?=($this->member->item('mem_level') < 50) ? ' readonly' : ''?> required />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="ssc_send_phone">받는사람</label>
							<div>
							<ul id="phone_list_group" class="list-group" style="padding: 0px;">

								</ul>
							</div>
							<!-- <button type="button" class="btn btn-default btn-xs" onclick="phone_list_del()">선택삭제</button>
							<select name="phone_list" class="form-control" id="phone_list" size="5" style="height: 200px;">
								<?php
								if (element('list', element('data', $view))) {
									foreach (element('list', element('data', $view)) as $i => $row) {
								?>
									<option value="<?='h,'.element('cust_name', $row).':'.get_phone(element('cust_phone', $row))?>"><?=element('cust_name', $row).': '.get_phone(element('cust_phone', $row))?></option>
								<?php
									}
								}
								?>
							</select> -->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label for="direct">직접추가</label>
						<div class="input-group" id="direct" style="padding:20px 0;">
							<input type="text" name="receiver_name" id="receiver_name" class="form-control" maxlength="20" placeholder="이름" onkeypress="if (event.keyCode == 13) document.getElementById('receiver_phone').focus();" /><br />
							<input type="tel" name="receiver_phone" id="receiver_phone" class="form-control" maxlength="20" placeholder="전화번호(*)" onkeypress="if (event.keyCode == 13) phone_add()" />
							<span class="input-group-addon">
								<button type="button" class="btn btn-default" onclick="phone_add()">추가</button><br />
							</span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="find_cust">검색하여 추가</label>
							<select class="form-control" id="find_cust" style="width: 100%;"></select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="find_group">그룹 검색</label>
							<select class="form-control" id="find_group" style="width: 100%;"></select>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary btn-block">전송하기</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
<!-- </div> -->


<script src="<?php echo base_url('assets/js/jquery.paging.js'); ?>"></script>
<script type="text/javascript">
	//<![CDATA[
	$(function() {

		// $.fn.modal.Constructor.prototype.enforceFocus = function() {};
		function chooseSelect2(selector, placeholder, ajaxUrl,allowClear){
			$(selector).select2({
				// tags: true,
				placeholder: placeholder,
				allowClear: allowClear,
				dropdownPosition: 'above',
				closeOnSelect: false,
				// dropdownParent: $('#myModal'),
				// dropdownParent: jQuery('.select2-container'),
				ajax: {
					url: ajaxUrl,
					dataType: 'json',
					data: function (params) {
						var query = {
							term: params.term,
							type: 'public'
						}
						return query;
					},
					processResults: function (data) {
						if (data == null) {
							return [];
						} else {
							// console.log(data);
							return {
								results: data.results
							};
						}
					}
				}
			}).on('change', function (e) {
				var cust_string = $(this).find("option:selected").text();
				add_phone(cust_string, $(this).val());
			});
		}
		chooseSelect2("#find_cust","고객이름 또는 고객전화번호를 입력하여 검색하세요.",'<?=site_url("smssend/ajax_cust_exists")?>',false);
		chooseSelect2("#find_group","단체 전송할 그룹을 선택해주세요",'<?=site_url("smssend/ajax_group_exists")?>',true);


		$(document).on('focus keydown', '#sfa_content', function() {
			$('.write_scemo').hide();
		});
		$(document).on('click', '.write_scemo_btn', function() {
			$('.write_scemo').hide();
			$(this).next('.write_scemo').show();
		});
		$(document).on('click', '.scemo_cls_btn', function() {
			$('.write_scemo').hide();
		});
	});



	var emoticon_list = {
		go: function(sfa_id) {
			var sfa_content = document.getElementById('sfa_content');

			//sfa_content.focus();
			sfa_content.value = document.getElementById('sfa_contents_' + sfa_id).value;

			byte_check('sfa_content', 'sms_bytes');
		}
	};
	(function($) {
		var $search_form = $('form#emo_sch');
		emoticon_list.fn_paging = function(hash_val, total_page) {
			$('#emo_pg').paging({
				current: hash_val ? hash_val : 1,
				max: total_page == 0 || total_page ? total_page : 45,
				length: 5,
				liitem: 'li',
				format: '{0}',
				next: '다음',
				prev: '이전',
				sideClass: 'pg_page pg_next',
				prevClass: 'pg_page pg_prev',
				first: '&lt;&lt;',
				last: '&gt;&gt;',
				href: '#',
				itemCurrent: 'pg_current',
				itemClass: 'pg_page',
				appendhtml: '',
				onclick: function(e, page) {
					e.preventDefault();
					$('#hidden_page').val(page);
					var params = $($search_form).serialize();
					emoticon_list.select_page(params, 'json');
				}
			});
		}
		emoticon_list.loading = function(el, src) {
			if (!el || !src) {
				return;
			}
			$(el).append("<span class='tmp_loading'><img src='" + src + "' title='loading...' alt='이모티콘 로딩' title='이모티콘 로딩' /></span>");
		}
		emoticon_list.loadingEnd = function(el) {
			$('.tmp_loading', $(el)).remove();
		}
		emoticon_list.select_page = function(params, type) {
			if (!type) {
				type = 'json';
			}
			emoticon_list.loading('.emo_list', cb_url + '/assets/images/ajax-loader.gif'); //로딩 이미지 보여줌
			$.ajax({
				url: '<?php echo admin_url('sms/smssend/ajax_sms_write_form'); ?>',
				cache: false,
				timeout: 30000,
				dataType: type,
				data: params,
				success: function(HttpRequest) {
					if (type === 'json') {
						if (HttpRequest.error) {
							alert(HttpRequest.error);
							return false;
						} else {
							var $emoticon_box = $('.emo_list');
							$emoticon_box.html(HttpRequest.list_text);
							emoticon_list.fn_paging(HttpRequest.page, HttpRequest.total_page);
							$('#hidden_page').val(HttpRequest.page);
						}
					}
					emoticon_list.loadingEnd('.emo_list'); //로딩 이미지 지움
				}
			});
		}

		$(document).on('change', '#emo_sel', function(e) {
			var params = {};
			$search_form[0].reset();
			emoticon_list.select_page(params, 'json');
		});
		$search_form.submit(function(e) {
			e.preventDefault();
			var $form = $(this),
				params = $(this).serialize();
			emoticon_list.select_page(params, 'json');
		});
		$('#emo_sel').trigger('change');
	})(jQuery);

	function add_phone(cust_string, cust_value) {
		var $exists = $("#phone_list_group input[value*='"+cust_value+"']")
		if ($exists.length > 0) {
			alert("이미 받는사람에 추가된 고객 또는 그룹입니다.");
			return false;
		}
		var new_item = $('<li class="list-group-item">\
			<button type="button" class="close" aria-label="Delete" onclick="this.parentNode.parentNode.removeChild(this.parentNode);"><span aria-hidden="true">&times;</span></button>\
			<span>'+cust_string+'</span>\
			<input type="hidden" name="phone_list[]" value="'+cust_value+'">\
		</li>');

		var group_item = $('<li class="list-group-item">\
			<button type="button" class="close" aria-label="Delete" onclick="this.parentNode.parentNode.removeChild(this.parentNode);"><span aria-hidden="true">&times;</span></button>\
			<span> * 그룹 선택 : '+cust_string+'</span>\
			<input type="hidden" name="phone_list[]" value="'+cust_value+'">\
		</li>');

		if(cust_value.includes("그룹 전송")){
		$("#phone_list_group").append(group_item);
		}else{
		$("#phone_list_group").append(new_item);
		}
	}

	var is_sms_submitted = false; //중복 submit방지
	function sms_chk_send(f) {
		if (is_sms_submitted === false) {
			is_sms_submitted = true;
			var phone_list = document.getElementById('phone_list');
			var sfa_content = document.getElementById('sfa_content');
			var receiver_phone = document.getElementById('receiver_phone');
			var ssc_send_phone = document.getElementById('ssc_send_phone');
			var ssc_send_phone_regExp = /^[0-9\-]+$/;
			var list = '';

			if (!sfa_content.value) {
				alert('메세지를 입력해주세요.');
				sfa_content.focus();
				is_sms_submitted = false;
				return false;
			}
			if (!ssc_send_phone_regExp.test(ssc_send_phone.value)) {
				alert('회신번호 형식이 잘못 되었습니다.');
				ssc_send_phone.focus();
				is_sms_submitted = false;
				return false;
			}
			if (phone_list.length < 1) {
				alert('받는 사람을 입력해주세요.');
				receiver_phone.focus();
				is_sms_submitted = false;
				return false;
			}

			for (i = 0; i < phone_list.length; i++)
				list += phone_list.options[i].value + '/';

			f.send_list.value = list;
			return true;
		} else {
			alert('데이터 전송중입니다.');
		}
	}

	function phone_add() {
		var receiver_phone = document.getElementById('receiver_phone'),
			receiver_name = document.getElementById('receiver_name'),
			phone_list = document.getElementById('phone_list'),
			pattern = /^01[016789][0-9]{3,4}[0-9]{4}$/,
			pattern2 = /^01[016789]-[0-9]{3,4}-[0-9]{4}$/;

		if (!receiver_phone.value) {
			alert('휴대폰번호를 입력해 주세요.');
			receiver_phone.select();
			return;
		}

		if (!pattern.test(receiver_phone.value) && !pattern2.test(receiver_phone.value)) {
			alert('휴대폰번호 형식이 올바르지 않습니다.');
			receiver_phone.select();
			return;
		}

		if (!pattern2.test(receiver_phone.value)) {
			receiver_phone.value = receiver_phone.value.replace(new RegExp("(01[016789])([0-9]{3,4})([0-9]{4})"), "$1-$2-$3");
		}

		var item = '';
		if (trim(receiver_name.value))
			item = receiver_name.value + ' (' + receiver_phone.value + ')';
		else
			item = receiver_phone.value;

		var value = 'h,' + receiver_name.value + ':' + receiver_phone.value;

		add_phone(item, value);

		// for (i = 0; i < phone_list.length; i++) {
		// 	if (phone_list[i].value == value) {
		// 		alert('이미 같은 목록이 있습니다.');
		// 		return;
		// 	}
		// }

		// if (jQuery.inArray(receiver_phone.value, sms_obj.phone_number) > -1) {
		// 	alert('목록에 이미 같은 휴대폰 번호가 있습니다.');
		// 	return;
		// } else {
		// 	sms_obj.phone_number.push(receiver_phone.value);
		// }
		// phone_list.options[phone_list.length] = new Option(item, value);

		receiver_phone.value = '';
		receiver_name.value = '';
		receiver_name.select();
	}

	function phone_list_del() {
		var phone_list = document.getElementById('phone_list');

		if (phone_list.selectedIndex < 0) {
			alert('삭제할 목록을 선택해주세요.');
			return;
		}

		var regExp = /(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?[0-9]{3,4}-?[0-9]{4}/,
			receiver_phone_option = phone_list.options[phone_list.selectedIndex],
			result = (receiver_phone_option.outerHTML.match(regExp));
		if (result !== null) {
			sms_obj.phone_number = sms_obj.array_remove(sms_obj.phone_number, result[0]);
		}
		phone_list.options[phone_list.selectedIndex] = null;
	}

	function book_change(id) {
		var book_group = document.getElementById('book_group');
		var book_person = document.getElementById('book_person');
		var num_book = document.getElementById('num_book');
		var menu_group = document.getElementById('menu_group');

		if (id === 'book_group') {
			book_group.style.fontWeight = 'bold';
			book_person.style.fontWeight = 'normal';
		} else if (id === 'book_person') {
			book_group.style.fontWeight = 'normal';
			book_person.style.fontWeight = 'bold';
		}
	}

	function booking(val) {
		if (val) {
			$('.booking_date').prop('disabled', false);
		} else {
			$('.booking_date').prop('disabled', true);
		}
	}

	function add(str) {
		var conts = document.getElementById('sfa_content');
		var bytes = document.getElementById('sms_bytes');
		conts.focus();
		conts.value += str;
		byte_check('sfa_content', 'sms_bytes');
		return;
	}

	function byte_check(sfa_content, sms_bytes) {
		var conts = document.getElementById(sfa_content);
		var bytes = document.getElementById(sms_bytes);

		var i = 0;
		var cnt = 0;
		var exceed = 0;
		var ch = '';

		for (i = 0; i < conts.value.length; i++) {
			ch = conts.value.charAt(i);
			if (escape(ch).length > 4) {
				cnt += 2;
			} else {
				cnt += 1;
			}
		}

		bytes.innerHTML = cnt;

		if (cnt > 2000) {
			exceed = cnt - 2000;
			alert('메시지 내용은 2000바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 ' + exceed + 'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
			var tcnt = 0;
			var xcnt = 0;
			var tmp = conts.value;
			for (i = 0; i < tmp.length; i++) {
				ch = tmp.charAt(i);
				if (escape(ch).length > 4) {
					tcnt += 2;
				} else {
					tcnt += 1;
				}

				if (tcnt > 2000) {
					tmp = tmp.substring(0, i);
					break;
				} else {
					xcnt = tcnt;
				}
			}
			conts.value = tmp;
			bytes.innerHTML = xcnt;
			return;
		}
	}

	byte_check('sfa_content', 'sms_bytes');
	$('#sfa_content').focus();

	$(function() {
		$(document).on('click', '.write_scemo_btn', function() {
			$('.write_scemo').hide();
			$(this).next('.write_scemo').show();
		});
		$(document).on('click', '.scemo_cls_btn', function() {
			$('.write_scemo').hide();
		});
	});

	var sms_obj = {
		phone_number: [],
		el_box: '#num_book',
		person_is_search: false,
		array_remove: function(arr, item) {
			for (var i = arr.length; i--;) {
				if (arr[i] === item) {
					arr.splice(i, 1);
				}
			}
			return arr;
		},
		book_all_checked: function(chk) {
			var sme_id = document.getElementsByName('sme_id');

			if (chk) {
				for (var i = 0; i < sme_id.length; i++) {
					sme_id[i].checked = true;
				}
			} else {
				for (var i = 0; i < sme_id.length; i++) {
					sme_id[i].checked = false;
				}
			}
		},
		person_add: function(sme_id, sme_name, sme_phone) {
			var phone_list = document.getElementById('phone_list');
			var item = sme_name + ' (' + sme_phone + ')';
			var value = 'p,' + sme_id;

			for (i = 0; i < phone_list.length; i++) {
				if (phone_list[i].value == value) {
					alert('이미 같은 목록이 있습니다.');
					return;
				}
			}
			if (jQuery.inArray(sme_phone, this.phone_number) > -1) {
				alert('목록에 이미 같은 휴대폰 번호가 있습니다.');
				return;
			} else {
				this.phone_number.push(sme_phone);
			}
			phone_list.options[phone_list.length] = new Option(item, value);
		},
		person_multi_add: function() {
			var sme_id = document.getElementsByName('sme_id');
			var ck_no = '';
			var count = 0;

			for (i = 0; i < sme_id.length; i++) {
				if (sme_id[i].checked == true) {
					count++;
					ck_no += sme_id[i].value + ',';
				}
			}

			if (!count) {
				alert('하나이상 선택해주세요.');
				return;
			}

			var phone_list = document.getElementById('phone_list');
			var item = '개인 (' + count + ' 명)';
			var value = 'p,' + ck_no;

			for (i = 0; i < phone_list.length; i++) {
				if (phone_list[i].value == value) {
					alert('이미 같은 목록이 있습니다.');
					return;
				}
			}

			phone_list.options[phone_list.length] = new Option(item, value);
		},
		person: function(bg_no) {
			var params = {
				bg_no: bg_no
			};
			this.person_is_search = true;
			this.person_select(params, 'html');
			book_change('book_person');
		},
		group_add: function(bg_no, bg_name, bg_count) {
			if (bg_count == '0') {
				alert('그룹이 비어있습니다.');
				return;
			}

			var phone_list = document.getElementById('phone_list');
			var item = bg_name + ' 그룹 (' + bg_count + ' 명)';
			var value = 'g,' + bg_no;

			for (i = 0; i < phone_list.length; i++) {
				if (phone_list[i].value == value) {
					alert('이미 같은 목록이 있습니다.');
					return;
				}
			}

			phone_list.options[phone_list.length] = new Option(item, value);
		}
	};
	(function($) {
		$('#form_sms input[type=text], #form_sms select').keypress(function(e) {
			return e.keyCode != 13;
		});
		sms_obj.fn_paging = function(hash_val, total_page, $el, $search_form) {
			$el.paging({
				current: hash_val ? hash_val : 1,
				max: total_page == 0 || total_page ? total_page : 45,
				length: 5,
				liitem: 'span',
				format: '{0}',
				next: '다음',
				prev: '이전',
				sideClass: 'pg_page pg_next',
				prevClass: 'pg_page pg_prev',
				first: '&lt;&lt;',
				last: '&gt;&gt;',
				href: '#',
				itemCurrent: 'pg_current',
				itemClass: 'pg_page',
				appendhtml: '',
				onclick: function(e, page) {
					e.preventDefault();
					$search_form.find("input[name='page']").val(page);
					var params = '';
					if (sms_obj.person_is_search) {
						params = $search_form.serialize();
					} else {
						params = {
							page: page
						};
					}
					sms_obj.person_select(params, "html");
				}
			});
		}
		sms_obj.person_select = function(params, type) {
			emoticon_list.loading(sms_obj.el_box, cb_url + '/assets/images/ajax-loader.gif'); //로딩 이미지 보여줌
			$.ajax({
				url: '<?php echo goto_url('smssend/ajax_sms_write_customer'); ?>',
				cache: false,
				timeout: 30000,
				dataType: type,
				data: params,
				success: function(data) {
					$(sms_obj.el_box).html(data);
					var $sms_person_form = $('#sms_person_form', sms_obj.el_box),
						total_page = $sms_person_form.find("input[name='total_pg']").val(),
						current_page = $sms_person_form.find("input[name='page']").val()
					sms_obj.fn_paging(current_page, total_page, $('#person_pg', sms_obj.el_box), $sms_person_form);
					$sms_person_form.bind('submit', function(e) {
						e.preventDefault();
						sms_obj.person_is_search = true;
						$(this).find("input[name='total_pg']").val('');
						$(this).find("input[name='page']").val('');
						var params = $(this).serialize();
						sms_obj.person_select(params, 'html');
						emoticon_list.loadingEnd(sms_obj.el_box); //로딩 이미지 지움
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		}
		sms_obj.triggerclick = function(sel) {
			$(sel).trigger('click');
		}
		$(document).on('click', '#book_person', function(e) {
			e.preventDefault();
			book_change($(this).attr('id'));
			sms_obj.person_is_search = false;
			sms_obj.person_select('', 'html');
		});
		$(document).on('click', '#book_group', function(e) {
			e.preventDefault();
			book_change($(this).attr('id'));
			emoticon_list.loading(sms_obj.el_box, cb_url + '/assets/images/ajax-loader.gif'); //로딩 이미지 보여줌
			$.ajax({
				url: '<?php echo site_url('smssend/ajax_sms_write_customer'); ?>',
				cache: false,
				timeout: 30000,
				dataType: 'html',
				success: function(data) {
					$(sms_obj.el_box).html(data);
					emoticon_list.loadingEnd(sms_obj.el_box); //로딩 이미지 지움
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			})
		}).trigger('click');

		sms_obj.person_select('', 'html');
	})(jQuery);
	//]]>
</script>