<div class="status_change">
	<div class="status_text">発行ステータス一括変更</div>
	<?php echo $form->input('STATUS_CHANGE', array('label' => false, 'options' => $status, 'div' => false, 'error' => false)); ?>

	<?php echo $form->submit('/img/bt_set.jpg', array('name' => 'status_change',"alt" => "ステータス変更", 'onclick' => 'return status_change();', 'label' => false, 'div' => false , 'class' => 'mr5')); ?>
</div>