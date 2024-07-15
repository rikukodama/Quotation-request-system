<?php
$formType = $this->name;
$formID = '';
$formController = '';
$mailAction = '';

switch($this->name) {
	case 'Quote':
		$formID = 'MQT_ID';
		$formController = 'quotes';
		$mailAction = 'quote';
		break;
	case 'Bill':
		$formID = 'MBL_ID';
		$formController = 'bills';
		$mailAction = 'bill';
		break;
	case 'Delivery':
		$formID = 'MDV_ID';
		$formController = 'deliveries';
		$mailAction = 'delivery';
		break;
}
?>
<div class="edit_btn2">
<?php if($editauth){
	echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => $formController, 'action' => 'edit/'.$param[$formType][$formID]),array('escape' => false));
	if($param[$formType]['STATUS']==1){
		echo $html->link($html->image('bt_send_mail.jpg',array('class'=>'imgover','alt'=>'メール送付')), array('controller' => 'mails', 'action' => "sendmail/$mailAction/".$param[$formType][$formID]),array('escape' => false));
	}
}?>
	<?php echo $html->link($html->image('bt_download.jpg',array('class'=>'imgover','alt'=>'ダウンロード')), array('controller' => $formController, 'action' => 'pdf/'.$param[$formType][$formID].'/download/'),array('escape' => false)); ?>
	<?php echo $html->link($html->image('bt_preview.jpg',array('class'=>'imgover','alt'=>'プレビュー')), array('controller' => $formController, 'action' => 'pdf/'.$param[$formType][$formID].'/preview/'),array('target'=>'_blank','escape' => false)); ?>
<?php
		if($param[$formType]['STATUS']==1){
			echo $html->link($html->image('bt_invoice.jpg',array('class'=>'imgover','alt'=>'ダウンロード')), array('controller' => $formController, 'action' => 'pdf/'.$param[$formType][$formID].'/download_with_coverpage/'),array('escape' => false));
		}
?>
<?php
	echo $form->create($this->name, array('type' => 'post', 'action' => 'action', 'style' => 'display:inline;'));
	echo $form->hidden('Action.type', array('value' => strtolower($this->name)));
	echo $form->hidden($param[$formType][$formID], array('value' => 1));

	echo $customHtml->hiddenToken();
	//echo $html->link($html->image('bt_copy.jpg',array('class'=>'imgover','alt'=>'転記')), 'javascript:void(0);' ,array('escape' => false, 'onclick' => "if(confirm('削除してよろしいですか？'))console.log(document.forms[1].submit()); "));
	//echo $form->submit('', array('style' => 'display:none'));
	echo $form->submit('bt_copy.jpg', array('div' => false, 'style' => 'vertical-align:bottom;', 'onmouseover' => 'this.src="'.$html->url("/img/").'bt_copy_on.jpg"', 'onmouseout' => 'this.src="'.$html->url("/img/").'bt_copy.jpg"'));
	echo $form->end();
?>
   
    <?php if(isset($rb_flag) && $rb_flag && $this->name == 'Bill' ){ 
        echo $html->link($html->image('/regularbill/img/bt_rb_copy.jpg',array('class'=>'imgover','alt'=>'定期請求書へコピー')), array('plugin'=>'regularbill','controller' => 'regularbill', 'action' => 'add/'.$param[$formType][$formID]),array('escape' => false));
    } ?>
    
<?php //echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => $formController, 'action' => 'index'),array('escape' => false)); ?>
<?php
	echo $form->create($this->name, array('type' => 'post', 'action' => 'moveback', 'style' => 'display:inline;'));
    echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), 'javascript:move_to_index();',array('escape' => false));
	echo $form->end();
?>

</div>

