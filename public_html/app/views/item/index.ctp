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
echo $form->create('Item', array('type' => 'get', 'action' => 'index'));
?>
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="search"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="search_box">
		<div class="search_area">
			<table width="600" cellpadding="0" cellspacing="0" border="0">
			<tr><th>商品名</th><td><?php echo $form->text('ITEM',array('class' => 'w350')); ?></td></tr>
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
	<?php echo $html->link($html->image("bt_new.jpg"), array('controller' => 'items',   'action' => 'add'),array('escape' => false),null,false); ?>
	</div>


	<h3><div class="edit_02_item"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['Item']["count"]; ?>
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
			<?php if(is_array($list)){?>
				<?php echo $form->create('Item', array('type' => 'post', 'action' => 'delete'));?>
					<table width="900" cellpadding="0" cellspacing="0" border="0" id="index_table">
						<thead>
						<tr>
							<th class="w50"><?php echo $form->checkbox("action.select_all", array('class'=> 'chk_all', 'onclick' => 'select_all();')); ?></th>
							<th class="w50"><?php echo $customHtml->sortLink('No.', 'Item.ITM_ID'); ?></th>
							<th class="w250"><?php echo $customHtml->sortLink('商品名', 'Item.ITEM_KANA'); ?></th>
							<th class="w100"><?php echo $customHtml->sortLink('商品コード', 'Item.ITEM_CODE'); ?></th>
							<th class="w200"><?php echo $customHtml->sortLink('単位', 'Item.UNIT'); ?></th>
							<th class="w250"><?php echo $customHtml->sortLink('価格', 'Item.UNIT_PRICE'); ?></th>
							<th class="w100"><?php echo $customHtml->sortLink('税区分','Item.TAX_CLASS'); ?></th>
							<?php echo($user['AUTHORITY']!=1)?"<th class='w100'>".$customHtml->sortLink('作成者', 'Item.USR_ID')."</th>":'';?>
						</tr>
						</thead>
						
						<tbody>
						<?php foreach($list as $key => $val){?>
						<tr>
							<td><?php echo $form->checkbox($val['Item']['ITM_ID'], array('class'=> 'chk', "style" => "width:30px;"));?></td>
							<td><?php echo $val['Item']['ITM_ID'];?></td>
							<td><?php echo $html->link($val['Item']['ITEM'], array('controller' => 'items', 'action' => 'check/'.$val['Item']['ITM_ID']));?></td>
							<td><?php echo $customHtml->ht2br($val['Item']['ITEM_CODE'],'Item','ITEM_CODE') ? $customHtml->ht2br($val['Item']['ITEM_CODE'],'Item','ITEM_CODE') : "&nbsp;";?></td>
							<td><?php echo $customHtml->ht2br($val['Item']['UNIT'],'Item','UNIT') ? $customHtml->ht2br($val['Item']['UNIT'],'Item','UNIT') : "&nbsp;";?></td>
							<td><?php echo $customHtml->ht2br($val['Item']['UNIT_PRICE'],'Item','UNIT_PRICE') ? $customHtml->ht2br($val['Item']['UNIT_PRICE'],'Item','UNIT_PRICE') : "&nbsp;";?></td>
							<td><?php echo $excises[$val['Item']['TAX_CLASS']]; ?></td>
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