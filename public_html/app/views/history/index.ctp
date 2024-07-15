<script><!--
try{
	window.addEventListener("load",initTableRollovers('index_table'),false);
 }catch(e){
 	window.attachEvent("onload",initTableRollovers('index_table'));
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
echo $form->create("History", array('type' => 'get', 'action' => 'index'));
?>
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="search"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="search_box">
		<div class="search_area">

			<table width="600" cellpadding="0" cellspacing="0" border="0">
			<tr><th>日付 FROM</th>
					<td width="320">
						<script language=JavaScript>
							<!--
						    var cal1 = new JKL.Calendar("calid", "HistoryIndexForm", "ACTION_DATE_FROM");
						  //-->
					  </script>
						<?php echo $form->text('ACTION_DATE_FROM', array('label' => false, 'div' => false,'onChange' => 'cal1.getFormValue(); cal1.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal')); ?>
						<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
						<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal1.write();','class' => 'pl5')); ?>
						<?php echo $html->image('bt_s_reset.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'cleartime')); ?>
					</td></tr>
			<tr><th>日付 TO</th>
					<td width="320">
						<script language=JavaScript>
							<!--
						    var cal2 = new JKL.Calendar("calid", "HistoryIndexForm", "ACTION_DATE_TO");
						  //-->
					  </script>
						<?php echo $form->text('ACTION_DATE_TO', array('label' => false, 'div' => false,'onChange' => 'cal2.getFormValue(); cal2.hide();', 'readonly'=>'readonly','error'=>false, 'class' => 'w100 p2 date cal')); ?>
						<?php echo $html->image('bt_now.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'nowtime')); ?>
						<?php echo $html->image('bt_calender.jpg', array('alt' => 'カレンダー', 'url' => '#','onClick' => 'return cal2.write();','class' => 'pl5')); ?>
						<?php echo $html->image('bt_s_reset.jpg', array('alt' => '現在', 'url' => '#', 'class' => 'pl5','class' => 'cleartime')); ?>
					</td></tr>
			<tr><th>ユーザ名</th><td><?php echo $form->text('NAME',array('class' => 'w350')); ?></td></tr>
			<tr><th>動作種別</th><td><?php echo $form->input('ACTION', array('type' => 'select','multiple' => 'checkbox','options' => $action,'label' => false, 'div' => false)); ?></td></tr>
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


	<h3><div class="edit_02_history"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['History']["count"]; ?>
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
			<?php
				if(is_array($list)){
					echo $form->create("Administer", array('type' => 'post', 'action' => 'action'));
					echo '<table width="900" cellpadding="0" cellspacing="0" border="0" id="index_table">
 					<thead>
					<tr>
						<th class="w200">'.$customHtml->sortLink('日付', 'History.ACTION_DATE').'</th>
						<th class="w100">'.$customHtml->sortLink('ユーザ名', 'User.NAME').'</th>
						<th class="w200">'.$customHtml->sortLink('動作', 'History.ACTION').'</th>
					<tr>
					</thead>';

					echo '<tbody>';
					foreach($list as $key => $val){
						echo "<tr>
						<td>".$customHtml->ht2br($val['History']['ACTION_DATE'], 'HISTORY','ACTION_DATE')."</td>
						<td>".$customHtml->ht2br($val['User']['NAME'], 'HISTORY','NAME'). "</td>
						<td>"; echo ($action[$val['History']['ACTION']] == 'ログイン')?'ログインしました':'';
							   echo ($action[$val['History']['ACTION']] == 'ログアウト')?'ログアウトしました':'';
					   		   echo ($action[$val['History']['ACTION']] == '見積書作成')?'見積書のID('.$html->link($val['History']['RPT_ID'], array('controller' => 'quotes', 'action' => '../quotes/check/'.$val['History']['RPT_ID'])).')を作成しました':'';
					    	   echo ($action[$val['History']['ACTION']] == '見積書更新')?'見積書のID('.$html->link($val['History']['RPT_ID'], array('controller' => 'quotes', 'action' => '../quotes/check/'.$val['History']['RPT_ID'])).')を更新しました':'';
					    	   echo ($action[$val['History']['ACTION']] == '見積書削除')?'見積書のID('.$customHtml->ht2br(implode(',',$ids[$key])).')を削除しました':'';
					           echo ($action[$val['History']['ACTION']] == '請求書作成')?'請求書のID('.$html->link($val['History']['RPT_ID'], array('controller' => 'bills', 'action' => '../bills/check/'.$val['History']['RPT_ID'])).')を作成しました':'';
							   echo ($action[$val['History']['ACTION']] == '請求書更新')?'請求書のID('.$html->link($val['History']['RPT_ID'], array('controller' => 'bills', 'action' => '../bills/check/'.$val['History']['RPT_ID'])).')を更新しました':'';
							   echo ($action[$val['History']['ACTION']] == '請求書削除')?'請求書のID('.$customHtml->ht2br(implode(',',$ids[$key])).')を削除しました':'';
					           echo ($action[$val['History']['ACTION']] == '納品書作成')?'納品書のID('.$html->link($val['History']['RPT_ID'], array('controller' => 'deliveries', 'action' => '../deliveries/check/'.$val['History']['RPT_ID'])).')を作成しました':'';
							   echo ($action[$val['History']['ACTION']] == '納品書更新')?'納品書のID('.$html->link($val['History']['RPT_ID'], array('controller' => 'deliveries', 'action' => '../deliveries/check/'.$val['History']['RPT_ID'])).')を更新しました':'';
							   echo ($action[$val['History']['ACTION']] == '納品書削除')?'納品書のID('.$customHtml->ht2br(implode(',',$ids[$key])).')を削除しました':'';
							   echo ($action[$val['History']['ACTION']] == '合計請求書作成')?'合計請求書のID('.$html->link($val['History']['RPT_ID'], array('controller' => 'totalbills', 'action' => '../totalbills/check/'.$val['History']['RPT_ID'])).')を作成しました':'';
							   echo ($action[$val['History']['ACTION']] == '合計請求書更新')?'合計請求書のID('.$html->link($val['History']['RPT_ID'], array('controller' => 'totalbills', 'action' => '../totalbills/check/'.$val['History']['RPT_ID'])).')を更新しました':'';
							   echo ($action[$val['History']['ACTION']] == '合計請求書削除')?'合計請求書のID('.$customHtml->ht2br(implode(',',$ids[$key])).')を削除しました':'';
							   echo ($action[$val['History']['ACTION']] == '定期請求書雛形作成')?'定期請求書雛形のID('.$html->link($val['History']['RPT_ID'], array('plugin' => 'regularbill', 'controller' => 'regularbill', 'action' => 'check/'.$val['History']['RPT_ID'])).')を作成しました':'';
							   echo ($action[$val['History']['ACTION']] == '定期請求書雛形更新')?'定期請求書雛形のID('.$html->link($val['History']['RPT_ID'], array('plugin' => 'regularbill', 'controller' => 'regularbill', 'action' => 'check/'.$val['History']['RPT_ID'])).')を更新しました':'';
							   echo ($action[$val['History']['ACTION']] == '定期請求書雛形削除')?'定期請求書雛形のID('.$customHtml->ht2br(implode(',',$ids[$key])).')を削除しました':'';
                        if ($action[$val['History']['ACTION']] == '定期請求書作成'){
							   echo '定期請求書雛形から請求書のID(';
                            foreach ($ids[$key] as $ikey => $ival){
                                if($ikey>0){echo',';}
                                echo $html->link($ival, array('controller' => 'bills', 'action' => '../bills/check/'.$ival));
                            }
                               echo ')を作成しました';
                        }
						echo "</td></tr>";
					}
					echo '</tbody>';
				}
			?>
			</table>
			<div class="list_btn">
				<?php echo $form->end(); ?>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
</div>