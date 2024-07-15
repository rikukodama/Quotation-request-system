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
echo $form->create("Totalbill", array('type' => 'get', 'action' => 'index'));
?>
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="search"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="search_box">
		<div class="search_area">

			<table width="600" cellpadding="0" cellspacing="0" border="0">
			<tr><th>顧客名</th><td><?php echo $form->text('NAME',array('class' => 'w350')); ?></td></tr>
			<tr><th>件名</th><td><?php echo $form->text('SUBJECT',array('class' => 'w350')); ?></td></tr>
			<tr><th>発行日 開始日</th>
				<td width="320">
					<script language=JavaScript>
						<!--
					    var cal1 = new JKL.Calendar("calid", "TotalbillIndexForm", "ACTION_DATE_FROM");
					  //-->
				  </script>
					<?php echo $form->text('ACTION_DATE_FROM', array('label' => false, 'div' => false,'onChange' => 'cal1.getFormValue(); cal1.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal')); ?>
					<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
					<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal1.write();','class' => 'pl5')); ?>
					<?php echo $html->image('bt_s_reset.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'cleartime')); ?>
				</td>
			</tr>
			<tr><th>発行日 終了日</th>
				<td width="320">
					<script language=JavaScript>
						<!--
					    var cal2 = new JKL.Calendar("calid", "TotalbillIndexForm", "ACTION_DATE_TO");
					  //-->
				  </script>
					<?php echo $form->text('ACTION_DATE_TO', array('label' => false, 'div' => false,'onChange' => 'cal2.getFormValue(); cal2.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal')); ?>
					<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
					<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal2.write();','class' => 'pl5')); ?>
					<?php echo $html->image('bt_s_reset.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'cleartime')); ?>
				</td>
			</tr>
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
	</div><div id="calid"></div>

<?php echo $form->end(); ?>

	<div class="new_document">
	<?php echo $html->link($html->image("bt_new.jpg"), array('controller' => 'totalbills',   'action' => 'add'),array('escape' => false),null,false); ?>
	</div>

	<h3><div class="edit_02_totalbill"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['Totalbill']["count"]; ?>
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
			<?php echo $form->create("Totalbill", array('type' => 'post', 'action' => 'action')); ?>
			<table width="900" cellpadding="0" cellspacing="0" border="0" id="index_table">
				<thead>
				<tr>
					<th class="w50"><?php echo $form->checkbox("action.select_all", array('class'=> 'chk_all', 'onclick' => 'select_all();')); ?></th>
					<th class="w50"><?php echo $customHtml->sortLink('No.', 'Totalbill.TBL_ID'); ?></th>
					<th class="w150"><?php echo $customHtml->sortLink('顧客名', 'Customer.NAME'); ?></th>
					<th class="w200"><?php echo $customHtml->sortLink('件名', 'Totalbill.SUBJECT'); ?></th>
					<th class="w100"><?php echo $customHtml->sortLink('合計金額', 'Totalbill.CAST_THISM_BILL'); ?></th>
					<th class="w100"><?php echo $customHtml->sortLink('発行日', 'Totalbill.ISSUE_DATE'); ?></th>
					<?php echo($user['AUTHORITY']!=1)?'<th class="w150">'.$customHtml->sortLink('作成者', 'Totalbill.USR_ID').' / ':'';?>
					<?php echo($user['AUTHORITY']!=1)?$customHtml->sortLink('更新者', 'Totalbill.UPDATE_USR_ID').'</th>':'';?>
					<th class="w100"><?php echo $customHtml->sortLink('発行ステータス', 'Totalbill.EDIT_STAT'); ?></th>
				</tr>
				</thead>

				<tbody>
				<?php foreach($list as $key => $val): ?>
				<tr>
					<td><?php echo $form->checkbox($val['Totalbill']['TBL_ID'], array('class'=> 'chk'));?></td>
					<?php echo  (isset($authcheck[$key]))?'<div class=auth'.$val["Totalbill"]["TBL_ID"].' style=display:none;>'.$authcheck[$key].'</div>':''; ?>
					<td><?php echo $customHtml->ht2br($val['Totalbill']['TBL_ID'],'Totalbill','TBL_ID'); ?></td>
					<td><?php echo $customHtml->ht2br($val['Customer']['NAME'],'Customer','NAME'); ?></td>
					<td><?php echo $html->link($val['Totalbill']['SUBJECT'], array('controller' => 'totalbills', 'action' => 'check/'.$val['Totalbill']['TBL_ID'])); ?></td>
					<td><?php echo isset($val['Totalbill']['THISM_BILL'])?$customHtml->ht2br($val['Totalbill']['THISM_BILL'], 'Totalbill', 'THISM_BILL').'円':'&nbsp'; ?></td>
					<td><?php echo $val['Totalbill']['ISSUE_DATE']?$val['Totalbill']['ISSUE_DATE']:'&nbsp'; ?></td>
					<?php echo($user['AUTHORITY']!=1)?'<td>'.$customHtml->ht2br($val["User"]["NAME"],"Totalbill","NAME").' / ':''?>
					<?php echo($user['AUTHORITY']!=1)?($val["UpdateUser"]["NAME"]==NULL)?'&nbsp;</td>':$customHtml->ht2br($val["UpdateUser"]["NAME"],"Totalbill","NAME").'</td>':'';?>
					<td><?php echo $edit_stat[$val['Totalbill']['EDIT_STAT']]; ?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			<?php endif; ?>
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