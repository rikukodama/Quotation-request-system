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

<?php echo $session->flash(); ?>
<?php
$paginator->options($options);
echo $form->create("Quote", array('type' => 'get', 'action' => 'index'));
?>
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="quote_search"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="quote_search_box">
		<div class="quote_search_area">

			<table width="940" cellpadding="0" cellspacing="0" border="0">
			<tr><th>管理番号</th><td><?php echo $form->text('NO',array('class' => 'w300')); ?></td>
                <th>件名</th><td><?php echo $form->text('SUBJECT',array('class' => 'w300')); ?></td></tr>
            <tr><th>顧客名</th><td><?php echo $form->text('NAME',array('class' => 'w300')); ?></td>
                <th>自社担当者</th><td colspan="3"><?php echo $form->text('CHR_USR_NAME',array('class' => 'w300')); ?></td></tr>
            <tr><th>作成者</th><td><?php echo $form->text('USR_NAME',array('class' => 'w300')); ?></td>
                    <th>更新者</th><td><?php echo $form->text('UPD_USR_NAME',array('class' => 'w300')); ?></td></tr>
			<tr><th>発行ステータス</th><td colspan="3"><?php echo $form->input('STATUS', array('type' => 'select','multiple' => 'checkbox','options' => $status,'label' => false, 'div' => false)); ?></td></tr>
			</table>

            <div class="quote_extend">
                <div class="quote_extend_btn" id="quote_open_btn">
                    <?php echo $html->image('button/d_down.png',array('class' =>'imgover','alt'  => 'off','onclick'=>'toggle_quote_extend_open();')); ?>　詳細検索を表示する
                </div>
                <div class="quote_extend_btn" id="quote_close_btn" style="display:none;">
                    <?php echo $html->image('button/d_up.png',array('class' =>'imgover','alt'  => 'off','onclick'=>'toggle_quote_extend_close();')); ?>　詳細検索を非表示にする
                </div>
                <div class="quote_extend_area">
                    <table width="940" cellpadding="0" cellspacing="0" border="0">

                        <tr><th>商品名</th><td><?php echo $form->text('ITEM_NAME',array('class' => 'w300')); ?></td>
                        <th>商品コード</th><td><?php echo $form->text('ITEM_CODE',array('class' => 'w300')); ?></td></tr>


                        <tr><th>合計金額</th><td><?php echo $form->text('TOTAL_FROM',array('class' => 'w100')); ?> 円 ～ <?php echo $form->text('TOTAL_TO',array('class' => 'w100')); ?> 円</td></tr>


                        <tr><th>発行日 開始日</th>
                            <td width="320">
                                <script language=JavaScript>
                                    <!--
                                    var cal1 = new JKL.Calendar("calid", "QuoteIndexForm", "ACTION_DATE_FROM");
                                  //-->
                              </script>
                                <?php echo $form->text('ACTION_DATE_FROM', array('label' => false, 'div' => false,'onChange' => 'cal1.getFormValue(); cal1.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal')); ?>
                                <?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
                                <?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal1.write();','class' => 'pl5')); ?>
                                <?php echo $html->image('bt_s_reset.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'cleartime')); ?>
                            </td>
                        <th>発行日 終了日</th>
                            <td width="320">
                                <script language=JavaScript>
                                    <!--
                                    var cal2 = new JKL.Calendar("calid", "QuoteIndexForm", "ACTION_DATE_TO");
                                  //-->
                              </script>
                                <?php echo $form->text('ACTION_DATE_TO', array('label' => false, 'div' => false,'onChange' => 'cal2.getFormValue(); cal2.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal')); ?>
                                <?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
                                <?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal2.write();','class' => 'pl5')); ?>
                                <?php echo $html->image('bt_s_reset.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'cleartime')); ?>
                            </td>
                        </tr>
                        <tr><th>備考</th><td><?php echo $form->text('NOTE',array('class' => 'w300')); ?></td>
                            <th>メモ</th><td><?php echo $form->text('MEMO',array('class' => 'w300')); ?></td></tr>
                    </table>
                </div>
            </div>

		</div>
			<div class="quote_search_btn">
               <table style="margin:0 auto">
                    <tr>
                        <td style="border:none;">
							<?php echo $html->link($html->image("bt_search.jpg"), '#',array('escape' => false, 'onclick' => "$('#".$this->name.ucfirst($this->action)."Form').submit();"),null,false); ?>
                            <?php ///echo $form->submit('bt_search.jpg', array('name' => 'search', 'alt' => '検索する')); ?>
                        </td>
                        <td style="border:none;">
                            <?php echo $html->link($html->image("bt_search_reset.jpg"), '#',array('escape' => false, 'onclick'=>'reset_forms();'),null,false); ?>
                        </td>
                    </tr>
                </table>
            </div>

	</div><div id="calid"></div>

<?php echo $form->end(); ?>

	<div class="new_document">
	<?php echo $html->link($html->image("bt_new.jpg"), array('controller' => 'quotes',   'action' => 'add'),array('escape' => false),null,false); ?>
	<?php echo $html->link($html->image("bt_excel.jpg"), array('controller' => 'quotes',   'action' => 'export'),array('escape' => false),null,false); ?>
	</div>

	<h3><div class="edit_02_quote"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['Quote']["count"]; ?>
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
			<?php if(is_array($list)): ?>
			<?php echo $form->create("Quote", array('type' => 'post', 'action' => 'action')); ?>
				<table width="900" cellpadding="0" cellspacing="0" border="0" id="index_table">
					<thead>
					<tr>
						<th class="w50"><?php echo $form->checkbox("action.select_all", array('class'=> 'chk_all', 'onclick' => 'select_all();')); ?></th>
						<th class="w50"><?php echo $customHtml->sortLink('No.', 'Quote.MQT_ID'); ?></th>
						<th class="w100"><?php echo $customHtml->sortLink('顧客名', 'Customer.NAME_KANA', array('model' => 'Customer')); ?></th>
						<th class="w150"><?php echo $customHtml->sortLink('件名', 'Quote.SUBJECT'); ?></th>
						<th class="w100"><?php echo $customHtml->sortLink('合計金額', 'Quote.CAST_TOTAL'); ?></th>
						<th class="w100"><?php echo $customHtml->sortLink('発行日', 'Quote.ISSUE_DATE'); ?></th>
						<?php echo($user['AUTHORITY']!=1)?'<th class="w150">'.$customHtml->sortLink('作成者', 'Quote.USR_ID'):'';?>
						<?php echo '/'; ?>
						<?php echo($user['AUTHORITY']!=1)?$customHtml->sortLink('更新者', 'Quote.UPDATE_USR_ID').'</th>':'';?>
						<th class="w100"><?php echo $customHtml->sortLink('発行ステータス', 'Quote.STATUS'); ?></th>
						<th class="w100">メモ</th>
					</tr>
					</thead>

					<tbody>
					<?php foreach($list as $key => $val): ?>
					<tr>
						<td class="v50"><?php echo $form->checkbox($val['Quote']['MQT_ID'], array('class'=> 'chk')); ?></td>
						<?php echo  (isset($authcheck[$key]))?'<div class=auth'.$val["Quote"]["MQT_ID"].' style=display:none;>'.$authcheck[$key].'</div>':''; ?>
						<td class="v50"><?php echo $customHtml->ht2br($val['Quote']['MQT_ID'],'Quote','MQT_ID'); ?></td>
						<td class="v100"><?php echo $customHtml->ht2br($val['Customer']['NAME'],'Customer','NAME'); ?></td>
						<td class="v100"><?php echo $html->link($val['Quote']['SUBJECT'], array('controller' => 'quotes', 'action' => 'check/'.$val['Quote']['MQT_ID'])); ?></td>
						<td class="v150"><?php echo isset($val['Quote']['TOTAL'])?$customHtml->ht2br($val['Quote']['TOTAL'], 'Quote', 'TOTAL').'円':'&nbsp'; ?></td>
						<td class="v150"><?php echo $val['Quote']['ISSUE_DATE']?$val['Quote']['ISSUE_DATE']:'&nbsp'; ?></td>
						<?php 	echo($user['AUTHORITY']!=1)?'<td class="v50">'.$customHtml->ht2br($val["User"]["NAME"],"Quote","NAME").' / ':''?>
					<?php 	echo($user['AUTHORITY']!=1)?($val["UpdateUser"]["NAME"]==NULL)?'&nbsp;</td>':$customHtml->ht2br($val["UpdateUser"]["NAME"],"Quote","NAME").'</td>':''?>
						<td class="v50"><?php echo $status[$val['Quote']['STATUS']]; ?></td>
						<td class="v100"><?php echo $val['Quote']['MEMO']?$customHtml->ht2br($val['Quote']['MEMO'],'Quote','MEMO'):'&nbsp'; ?></td>
					</tr>
					<?php endforeach; ?>
					</tbody>
			<?php endif; ?>
			</table>
			<div class="list_btn">
				<?php echo $form->submit('/img/document/bt_delete2.jpg', array('name' => 'delete',"alt" => "削除", 'onclick' => 'return del();', 'label' => false, 'div' => false , 'class' => 'mr5')); ?>
				<?php echo $form->submit('/img/bt_01.jpg', array('name' => 'reproduce_quote', "alt" => "複製", 'label' => false, 'div' => false, 'class' => 'mr5')); ?>
				<?php echo $form->submit('/img/bt_02.jpg', array('name' => 'reproduce_bill', "alt" => "複製", 'label' => false, 'div' => false, 'class' => 'mr5')); ?>
				<?php echo $form->submit('/img/bt_03.jpg', array('name' => 'reproduce_delivery', "alt" => "複製", 'label' => false, 'div' => false, 'class' => 'mr5')); ?>
				
				<?php echo $this->element('status_change');?>
				
				<?php
					if (isset($customer_id)) {
						echo $form->hidden('Customer.id', array('value' => $customer_id));
					}
				?>
				<?php echo $customHtml->hiddenToken(); ?>
				<?php echo $form->end(); ?>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>

	</div>

</div>