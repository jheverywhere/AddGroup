<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
    <?php echo $view['menu']; ?>

	<div class="page-header">
		<h4>나의 작성 댓글</h4>
	</div>
	<div class="btn-group btn-group-justified mb20" role="group" aria-label="...">
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('mypage/post'); ?>" class="btn btn-warning btn-sm">원글</a>
		</div>
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('mypage/comment'); ?>" class="btn btn-success btn-sm">댓글</a>
		</div>
	</div>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>번호</th>
				<th>내용</th>
				<th>날짜</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><?php echo element('num', $result); ?></td>
				<td><a href="<?php echo element('comment_url', $result); ?>" target="new"><?php echo cut_str(html_escape(strip_tags(element('cmt_content', $result))), 200); ?></a>
					<?php if (element('cmt_like', $result)) { ?><span class="label label-info">+ <?php echo element('cmt_like', $result); ?></span><?php } ?>
					<?php if (element('cmt_dislike', $result)) { ?><span class="label label-danger">- <?php echo element('cmt_dislike', $result); ?></span><?php } ?>
				</td>
				<td><?php echo display_datetime(element('cmt_datetime', $result), 'full'); ?></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="3" class="nopost">회원님이 작성하신 댓글이 없습니다</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<nav><?php echo element('paging', $view); ?></nav>
</div>
