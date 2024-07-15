<script><!--
try{
	window.addEventListener("load",initTableRollovers('index_table'),false);
 }catch(e){
 	window.attachEvent("onload",initTableRollovers('index_table'));
}
--></script>
<script><!--
function select_all() {
	$(".chk").attr("checked", $(".chk_all").attr("checked"));
	$('input[name="delete"]').attr('disabled','');
	$('input[name="reproduce"]').attr('disabled','');
}

--></script>
<script>
$(function() {
	setBeforeSubmit('<?php echo $this->name.ucfirst($this->action).'Form'; ?>');
});
</script>



<?php	//完了メッセージ
	echo $session->flash();
?>
<?php
$paginator->options($options);
echo $form->create('CustomerCharge', array('type' => 'get', 'action' => 'index'));
?>
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="search"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="search_box">
		<div class="search_area">
			<table width="600" cellpadding="0" cellspacing="0" border="0">
			<tr><th>担当者名</th><td><?php echo $form->text('CHARGE_NAME',array('class' => 'w350')); ?></td></tr>
			<tr><th>企業名</th><td><?php echo $form->text('COMPANY_NAME',array('class' => 'w350')); ?></td></tr>
			<tr><th>ステータス</th><td><?php echo $form->select('STATUS', $status, $this->data['CustomerCharge']['STATUS'], array('empty'=>'項目を選んでください'),true); ?></td></tr>

			</table>

			<div class="search_btn">
               <table style="margin-left:-80px;">
                    <tr>
                        <td style="border:none;">
                            <?php echo $html->link($html->image("bt_search.jpg"), '#',array('escape' => false, 'onclick' => "$('#".$this->name.ucfirst($this->action)."Form').submit();"),null,false); ?>
                        </td>
                        <td style="border:none;">
                            <?php echo $html->link($html->image("bt_search_reset.jpg"), '#',array('escape' => false, 'onclick'=>'reset_forms();'),null,false); ?>
                        </td>
                    </tr>
                </table>
            </div>
		</div>
		<?php echo $html->image('/img/document/bg_search_bottom.jpg',array('class'=>'block')); ?>
	</div>
<?php echo $form->end(); ?>
	<div class="new_document">
	<?php echo $html->link($html->image("bt_new.jpg"), array('controller' => 'customer_charges',   'action' => 'add'),array('escape' => false),null,false); ?>
	</div>


	<h3><div class="edit_02_c_charge"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['CustomerCharge']["count"]; ?>
		</div>
		<div id='pagination'>
		<?php echo $paginator->prev('<< '.__('前へ', true), array(), null, array('class'=>'disabled', 'tag' => 'span')); ?>
		 |
		<?php echo $paginator->numbers().' | '.$paginator->next(__('次へ', true).' >>', array(), null, array('tag' => 'span', 'class' => 'disabled')); ?>
	</div>
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="list_area">
			<?php if(is_array($list)){?>
				<?php echo $form->create('CustomerCharge', array('type' => 'post', 'action' => 'index')); ?>
				<table width="900" cellpadding="0" cellspacing="0" border="0" id="index_table">
					<thead>
					<tr>
						<th class="w50"><?php echo $form->checkbox("action.select_all", array('class'=> 'chk_all', 'onclick' => 'select_all();')); ?></th>
						<th class="w50"><?php echo $customHtml->sortLink('No.', 'CustomerCharge.CHRC_ID'); ?></th>
						<th class="w200"><?php echo $customHtml->sortLink('担当者', 'CustomerCharge.CHARGE_NAME'); ?></th>
						<th class="w250"><?php echo $customHtml->sortLink('顧客名', 'CustomerCharge.CST_ID'); ?></th>
						<th class="w100"><?php echo $customHtml->sortLink('電話番号', 'CustomerCharge.PHONE_NO1'); ?></th>
						<th class="w100"><?php echo $customHtml->sortLink('ステータス', 'CustomerCharge.STATUS'); ?></th>
						<?php echo($user['AUTHORITY']!=1)?"<th class='w100'>".$customHtml->sortLink('作成者', 'CustomerCharge.USR_ID')."</th>":'';?>
					</tr>
					</thead>
					
					<tbody>
					<?php foreach($list as $key => $val){ ?>
					<tr>
						<td><?php if(!$delcheck[$val['CustomerCharge']['CHRC_ID']]) { echo $form->checkbox($val['CustomerCharge']['CHRC_ID'], array('class'=> 'chk'));} else {echo "&nbsp;";} ?></td>
						<td><?php echo $val['CustomerCharge']['CHRC_ID']; ?></td>
						<td><?php echo $authcheck[$val['CustomerCharge']['CHRC_ID']]?$html->link($customHtml->ht2br($val['CustomerCharge']['CHARGE_NAME'],'CustomerCharge','CHARGE_NAME'), array('controller' => 'customer_charges', 'action' => 'check/'.$val['CustomerCharge']['CHRC_ID'])):$customHtml->ht2br($val['CustomerCharge']['CHARGE_NAME'],'CustomerCharge','CHARGE_NAME'); ?></td>
						<td><?php echo $val['CustomerCharge']['CST_ID']!=0?$customer[$val['CustomerCharge']['CST_ID']]:"&nbsp"; ?></td>
						<td>
						<?php
							if(empty($val['CustomerCharge']['PHONE_NO1']) && empty($val['CustomerCharge']['PHONE_NO2']) && empty($val['CustomerCharge']['PHONE_NO3'])) {
								echo "&nbsp;";
							}else {
								echo $val['CustomerCharge']['PHONE_NO1']."-".$val['CustomerCharge']['PHONE_NO2']."-".$val['CustomerCharge']['PHONE_NO3'];
							}
						?>
						</td>
						<td><?php echo $status[$val['CustomerCharge']['STATUS']]; ?></td>
						<?php echo($user['AUTHORITY']!=1)?"<td>".$val['User']['NAME']."</td>":'';?>
					</tr>
					<?php }?>
					</tbody>
				<?php }?>
			</table>
			<div class="list_btn">
				<?php echo $customHtml->hiddenToken(); ?>
				<?php echo $form->submit('/img/document/bt_delete2.jpg', array('name' => 'delete',"alt" => "削除", 'onclick' => 'return del();', 'label' => false, 'div' => false , 'class' => 'mr5')); ?>
				<?php echo $form->end(); ?>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
</div>