<div class="edit_btn">
	<?php echo $form->submit('bt_save3.jpg', array('div' => false , 'name' => 'submit', 'alt' => '保存する')); ?>
	<?php
	if($this->params['action'] == 'edit') {
		echo $form->submit('bt_delete4.jpg', array('div' => false , 'name' => 'del', 'alt' => '削除する', 'class' => 'imgover imgcheck', 'onmouseover' => 'this.src="'.$html->url("/img/").'bt_delete4_on.jpg"', 'onmouseout' => 'this.src="'.$html->url("/img/").'bt_delete4.jpg"' , 'onclick' => 'if(confirm("削除してもよろしいですか？")){return true;}else{return false;}'));
	}
	?>
	<?php echo $form->submit('bt_index.jpg', array('div' => false , 'name' => 'cancel', 'alt' => 'キャンセル', 'class' => 'imgover imgcheck')); ?>
</div>

	<?php if($this->action != 'edit') echo $form->hidden("USR_ID",array('value'=>$user['USR_ID'])); ?>
	<?php echo $form->hidden("UPDATE_USR_ID",array('value'=>$user['USR_ID'])); ?>
	<?php
		if($this->action == 'edit') {
			switch($this->name) {
				case 'Quote':
					echo $form->hidden("MQT_ID");
					break;
				case 'Bill':
					echo $form->hidden("MBL_ID");
					break;
				case 'Delivery':
					echo $form->hidden("MDV_ID");
					break;
			}
		}
	?>
	<?php echo $form->hidden("dataformline",array('value'=>$dataline)); ?>
	<?php echo $customHtml->hiddenToken(); ?>
	<?php echo $form->end(); ?>