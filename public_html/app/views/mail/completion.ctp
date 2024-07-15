<div id="contents">
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg',array('class'=>'block')); ?>
		<div class="contents_area">

			<div class="finish">データの送信が完了しました。</div>

			<div class="finish_txt">ただいまご指定のアドレスへデータを送信しました。<br />
			送信先よりコメントが届いた場合については<?php
		echo $html->link("顧客コメント確認ページ",array('controller' => 'mails', 'action' => 'index'));?>でご確認ください。<br />
			指定したアドレスへ届いていない場合については、送信先をご確認のうえ、再送ください。
			パスワードは別途送信してください。<br />
			<h4 class='h4_mailpass'>パスワード：<?php echo $pass; ?></h4>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>


	<div class="edit_btn">
		<?php
		echo $html->link($html->image('bt_mailindex.jpg',array('class'=>'imgover','alt'=>'メール送信一覧へ')), array('controller' => 'mails', 'action' => 'index'),array('escape' => false));
		?>
	</div>
</div>