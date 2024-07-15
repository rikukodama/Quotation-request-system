<div class="contents_box mb20">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
		<?php
			echo $html->link(__('クリックすると、テーブル・初期データを作成します。', true), array(
				'plugin' => 'install',
				'controller' => 'install',
				'action' => 'data',
				'run' => 1,
			));
		?>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
</div>