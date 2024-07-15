<script><!--
try{
	window.addEventListener("load",initTableRollovers('index_table'),false);
 }catch(e){
 	window.attachEvent("onload",initTableRollovers('index_table'));
}
--></script>
<?php	//完了メッセージ
	echo $session->flash();
?>
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


<?php
$paginator->options($options);
echo $form->create('Customer', array('type' => 'get', 'action' => 'index'));
?>
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="search"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="search_box">
		<div class="search_area">

			<table width="600" cellpadding="0" cellspacing="0" border="0">
			<tr><th>顧客名</th><td><?php echo $form->text('NAME',array('class' => 'w350')); ?></td></tr>
			<tr><th>住所</th><td><?php echo $form->text('ADDRESS',array('class' => 'w350')); ?></td></tr>
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
	<?php echo $html->link($html->image("bt_new.jpg"), array('controller' => 'customers',   'action' => 'add'),array('escape' => false),null,false); ?>
	</div>


	<h3><div class="edit_02_customer"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['Customer']["count"]; ?>
		</div>
		<div id='pagination'>
		<?php echo $paginator->prev('<< '.__('前へ', true),
			array(),
			null,
			array('class'=>'disabled', 'tag' => 'span')
		); ?>
		 |
		<?php echo $paginator->numbers().
		' | '.
		$paginator->next(__('次へ', true).' >>', array(), null, array('tag' => 'span', 'class' => 'disabled'));
		?>
	</div>
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="list_area">
			<?php echo $form->create('Customer', array('type' => 'post', 'action' => 'index')); ?>
			<?php if(is_array($list)){ ?>
				<table width="900" cellpadding="0" cellspacing="0" border="0"style="break-word:break-all;" id="index_table">
					<thead>
					<tr>
						<th width="50"class="w50"><?php echo $form->checkbox("action.select_all", array('class'=> 'chk_all', 'onclick' => 'select_all();')); ?></th>
						<th class="w50"><?php echo $customHtml->sortLink('No.', 'Customer.CST_ID'); ?></th>
						<th class="w250"><?php echo $customHtml->sortLink('顧客名', 'Customer.NAME_KANA'); ?></th>
						<th class="w250"><?php echo $customHtml->sortLink('住所', 'Customer.CNT_ID'); ?></th>
						<th class="w100"><?php echo $customHtml->sortLink('電話番号', 'Customer.PHONE_NO1'); ?></th>
						<th class="w200"><?php echo $customHtml->sortLink('担当者', 'Customer.CHR_ID'); ?></th>
						<?php echo($user['AUTHORITY']!=1)?"<th class='w100'>".$customHtml->sortLink('作成者', 'Customer.USR_ID')."</th>":'';?>
					</tr>
					</thead>
					
					<tbody>
					<?php foreach($list as $key => $val){ ?>
					<tr>
 						<td><?php if(!$delcheck[$val['Customer']['CST_ID']])echo $form->checkbox($val['Customer']['CST_ID'], array('class'=> 'chk')); ?></td>
						<td><?php echo $val['Customer']['CST_ID']; ?></td>
						<td><?php echo $authcheck[$val['Customer']['CST_ID']]==1?$html->link($val['Customer']['NAME'], array('controller' => 'customers', 'action' => 'check/'.$val['Customer']['CST_ID'])):$customHtml->ht2br($val['Customer']['NAME']); ?></td>
						<td><?php
								if($val['Customer']['CNT_ID'] || $customHtml->ht2br($val['Customer']['ADDRESS'],'Customer','ADDRESS')){
									if($val['Customer']['CNT_ID']) {
										echo $countys[$val['Customer']['CNT_ID']];
									}
									echo $customHtml->ht2br($val['Customer']['ADDRESS'],'Customer','ADDRESS');
								} else {
									echo "&nbsp;";
								}
							?>
						</td>
						<td>
							<?php
								if(empty($val['Customer']['PHONE_NO1']) && empty($val['Customer']['PHONE_NO2']) && empty($val['Customer']['PHONE_NO3'])) {
									echo "&nbsp;";
								}else {
									echo $val['Customer']['PHONE_NO1']."-".$val['Customer']['PHONE_NO2']."-".$val['Customer']['PHONE_NO3'];
								}
							?>

						</td>
						<td><?php echo $val['Customer']['CHR_ID']?$customHtml->ht2br($charges[$val['Customer']['CHR_ID']],'Charge','CHARGE_NAME'):'&nbsp'; ?></td>
						<?php echo($user['AUTHORITY']!=1)?"<td>".$val['User']['NAME']."</td>":'';?>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			<div class="list_btn">
				<?php echo $customHtml->hiddenToken(); ?>
				<?php echo $form->submit('/img/document/bt_delete2.jpg', array('name' => 'delete',"alt" => "削除", 'onclick' => 'return del();', 'label' => false, 'div' => false , 'class' => 'mr5')); ?>
			</div>
			<?php echo $form->end(); ?>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
</div>