<div id="contents">
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg',array('class'=>'block')); ?>
		<div class="contents_area">
			<?php echo $form->create("Mail", array("type" => "post", "controller" => "mail")); ?>

			<h3 class="mail_h3">本文</h3>

			<?php echo $form->textarea("BODY", array('value' => $body , 'rows'=>'10', 'class'=>'pt5 pr5 pb5 pl5 mailtextarea')); ?>

		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>

			<?php echo $form->hidden("PASSWORD1"); ?>
			<?php echo $form->hidden("USR_ID"); ?>
			<?php echo $form->hidden("TYPE"); ?>
			<?php echo $form->hidden("FRM_ID"); ?>
			<?php echo $form->hidden("TO"); ?>
			<?php echo $form->hidden("FROM"); ?>
			<?php echo $form->hidden("CUSTOMER_CHARGE"); ?>
			<?php echo $form->hidden("CHARGE"); ?>
			<?php echo $form->hidden("SUBJECT"); ?>
			<?php echo $form->hidden("COMPANY"); ?>
			<?php echo $form->hidden("CUSTOMER"); ?>
			<?php echo $form->hidden("tkn"); ?>
			<?php echo $form->hidden("CORD", array('value' => $hash)); ?>
			<div class="edit_btn">
			<?php echo $form->submit('/img/bt_back.jpg', array('div' => false , 'name' => 'mail', 'alt' => '戻る', 'class' => 'imgover')); ?>
			<?php echo $form->submit('/img/bt_check.jpg', array('div' => false , 'name' => 'reaffirmation', 'alt' => '確認', 'class' => 'imgover')); ?>
			</div>
			<?php echo $form->end(); ?>


</div>
