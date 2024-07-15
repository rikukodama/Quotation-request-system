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
echo $form->create("Administer", array('type' => 'get', 'action' => 'index'));
?>
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="search"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="search_box">
		<div class="search_area">

			<table width="600" cellpadding="0" cellspacing="0" border="0">
			<tr><th>ユーザID</th><td><?php echo $form->text('LOGIN_ID',array('class' => 'w350')); ?></td></tr>
			<tr><th>ユーザ名</th><td><?php echo $form->text('NAME',array('class' => 'w350')); ?></td></tr>
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
	<?php echo $html->link($html->image("bt_new.jpg"), array('controller' => 'administers',   'action' => 'add'),array('escape' => false),null,false); ?>
	</div>


	<h3><div class="edit_02_administer"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box mb40">
		<div id='pagination'>
		<?php echo $paginator->data['Administer']["count"]; ?>
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
						<th class="w50">'.$customHtml->sortLink('No', 'Administer.USR_ID').'</th>
						<th class="w300">'.$customHtml->sortLink('ユーザID', 'Administer.LOGIN_ID').'</th>
						<th class="w200">'.$customHtml->sortLink('ユーザ名', 'Administer.NAME_KANA').'</th>
						<th class="w300">'.$customHtml->sortLink('メール', 'Administer.MAIL').'</th>
						<th class="w100">'.$customHtml->sortLink('ステータス', 'Administer.STATUS').'</th>
					<tr>
					</thead>';

					echo '<tbody>';
					foreach($list as $key => $val){
						if($val['Administer']['AUTHORITY']!=0){
							echo "	<tr>
							<td>".$customHtml->ht2br($val['Administer']['USR_ID'],'USER','USER_ID')."</td>
							<td>".$html->link($val['Administer']['LOGIN_ID'], array('controller' => 'administers', 'action' => 'check/'.$val['Administer']['USR_ID']))."</td>
							<td>".$customHtml->ht2br($val['Administer']['NAME'],'USER','NAME')."</td>";
							echo "<td>";
							if(empty($val['Administer']['MAIL'])) {
								echo "&nbsp;";
							}
							else {
								echo $customHtml->ht2br($val['Administer']['MAIL'],'USER','MAIL');
							}
							echo "</td>";
							echo "<td>".$status[$val['Administer']['STATUS']]."</td>";
						}
					}
					echo '</tbody>';
				}
			?>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
</div>