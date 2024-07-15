<div class="contents_box mb20">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
		<?php
			echo $html->link(__('クリックすると、ログイン画面に遷移します。', true), array(
				'plugin' => 'users',
				'controller' => 'login',
			));
		?>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
</div>