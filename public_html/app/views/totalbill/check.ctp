<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('i_guide02.jpg'); ?>
		<p>こちらのページは合計請求書確認の画面です。<br />「編集する」ボタンを押すと合計請求書を編集できます。</p>
	</div>
</div>
<br class="clear" />

<!-- contents_Start -->
<div id="contents">

	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<div class="edit_btn2">
		<?php if($editauth){ ?>
		<?php echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'totalbills', 'action' => 'edit/'.$param['Totalbill']['TBL_ID']),array('escape' => false)); ?>
		<?php }?>
		<?php echo $html->link($html->image('bt_download.jpg',array('class'=>'imgover','alt'=>'ダウンロード')), array('controller' => 'totalbills', 'action' => 'pdf/'.$param['Totalbill']['TBL_ID'].'/download/'),array('escape' => false)); ?>
		<?php echo $html->link($html->image('bt_preview.jpg',array('class'=>'imgover','alt'=>'プレビュー')), array('controller' => 'totalbills', 'action' => 'pdf/'.$param['Totalbill']['TBL_ID'].'/preview/'),array('target'=>'_blank','escape' => false)); ?>
		<?php //echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => 'totalbills', 'action' => 'index'),array('escape' => false)); ?>
<?php
	echo $form->create($this->name, array('type' => 'post', 'action' => 'moveback', 'style' => 'display:inline;'));
    echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), 'javascript:move_to_index();',array('escape' => false));
	echo $form->end();
?>

	</div>

	<h3><div class="edit_01"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th class="w100">管理番号</th>
					<td class="w320"><?php echo $customHtml->ht2br($param['Totalbill']['NO'],'Totalbill','NO'); ?></td>
					<th class="w100">発行日</th>
					<td class="w320"><?php echo $customHtml->df($param['Totalbill']['ISSUE_DATE']);?></td>
				</tr>
				<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="w100">顧客名</th>
					<td colspan="3"><?php echo $customHtml->ht2br($param['Customer']['NAME'],'Customer','NAME'); ?></td>
				</tr>
								<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="w100">顧客担当者名</th>
					<td colspan="3"><?php  if(isset($param['CustomerCharge']['CHARGE_NAME'])) echo $customHtml->ht2br($param['CustomerCharge']['CHARGE_NAME'],'CustomerCharge','CHARGE_NAME'); ?></td>
				</tr>
				<tr><td colspan="4" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="w100">担当者部署名</th>
					<td colspan="3"><?php if(isset($param['CustomerCharge']['UNIT'])) echo $customHtml->ht2br($param['CustomerCharge']['UNIT'],'CustomerCharge','UNIT'); ?></td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="txt_top w100">敬称</th>
					<td colspan="3">
					<?php
						if(isset($param['Totalbill']['HONOR_CODE'])) {
							if($param['Totalbill']['HONOR_CODE'] == 2) {
								echo $param['Totalbill']['HONOR_TITLE'];
							}else {
								echo $honor[$param['Totalbill']['HONOR_CODE']];
							}
						}
					?>
					</td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="txt_top w100">件名</th>
					<td colspan="3"><?php echo $customHtml->ht2br($param['Totalbill']['SUBJECT'],'Bill','SUBJECT'); ?></td>
				</tr>
				<tr><td colspan="4"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th class="txt_top w100">振込期限</th>
					<td colspan="3"><?php echo $customHtml->ht2br($param['Totalbill']['DUE_DATE'],'Bill','DUE_DATET'); ?></td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<div class="listview">
		<h3><div class="edit_02_bill"><span class="edit_txt">&nbsp;</span></div></h3>
		<div class="contents_box">
			<?php echo $html->image('bg_contents_top.jpg'); ?>
			<div class="list_area">
			<?php
				if(isset($param['Bill'])){
					echo '<table width="900" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<th class="w150">請求書番号</th>
						<th class="w200">件名</th>
						<th class="w250">顧客名</th>
						<th class="w100">発行日</th>
						<th class="w100">振込期限</th>
						<th class="w100">合計金額</th>
					<tr>';
					$i=0;
					foreach($param['Bill'] as $key => $val){
						echo "<tr>
						<td>".$customHtml->ht2br(($val['Bill']['NO'])?$val['Bill']['NO']:'　','Totalbill','NO')."</td>
						<td>".$customHtml->ht2br($val['Bill']['SUBJECT'],'Totalbill','NO')."</td>
						<td>".$customHtml->ht2br($val['Customer']['NAME'],'Totalbill','NAME')."</td>
						<td>".$customHtml->ht2br($val['Bill']['ISSUE_DATE'],'Totalbill','ISSUE_DATE')."</td>
						<td>".$customHtml->ht2br(($val['Bill']['DUE_DATE'])?$val['Bill']['DUE_DATE']:'　','Totalbill','DUE_DATE')."</td>
						<td id=TOTAL".$i.">".(isset($val['Bill']['TOTAL'])?$customHtml->ht2br($val['Bill']['TOTAL'],'Totalbill','TOTAL'):'　')."</td>";
						echo '<div id="tax'.$i.'" style="display:none;">'.$customHtml->ht2br($val['Bill']['SALES_TAX']).'</div>';
						echo '<div id="subtotal'.$i.'" style="display:none;">'.$customHtml->ht2br($val['Bill']['SUBTOTAL']).'</div>';
						echo "</tr>";
						$i++;
					}
					echo '</table>';
				}
			?>
			</div>
			<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
	</div>
	</div>

	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<?php echo '<div id="edit_stat" style="display:none;">'.$customHtml->ht2br($param['Totalbill']['EDIT_STAT']).'</div>'; ?>

	<div class="listview hidebox_d">
		<h3><div class="edit_02_abstract"><span class="edit_txt">&nbsp;</span></div></h3>

		<div class="contents_box mb40">
			<?php echo $html->image('bg_contents_top.jpg'); ?>
			<div class="list_area">
				<?php
						echo '<table width="900" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th class="w150">前月請求額</th>
							<th class="w150">御入金額</th>
							<th class="w150">繰越金額</th>
							<th class="w150">今回御買上額</th>
							<th class="w150">消費税</th>
							<th class="w150">今回請求額</th>
						<tr>';
							echo "	<tr>
							<td>".($param['Totalbill']['LASTM_BILL']?$customHtml->ht2br($param['Totalbill']['LASTM_BILL'],'','TOTALBILL'):'0')."</td>
							<td>".($param['Totalbill']['DEPOSIT']?$customHtml->ht2br($param['Totalbill']['DEPOSIT'],'','TOTALBILL'):'0')."</td>
							<td>".($param['Totalbill']['CARRY_BILL']?$customHtml->ht2br($param['Totalbill']['CARRY_BILL'],'','TOTALBILL'):'0')."</td>
							<td>".($param['Totalbill']['SALE']?$customHtml->ht2br($param['Totalbill']['SALE'],'','TOTALBILL'):'0')."</td>
							<td>".($param['Totalbill']['SALE_TAX']?$customHtml->ht2br($param['Totalbill']['SALE_TAX'],'','TOTALBILL'):'0')."</td>
							<td>".($param['Totalbill']['THISM_BILL']?$customHtml->ht2br($param['Totalbill']['THISM_BILL'],'','TOTALBILL'):'0')."</td>
							</tr>";
				?>
				</table>
			</div>
			<?php echo $html->image('bg_contents_bottom.jpg',array('class'=>'block')); ?>
		</div>
	</div>
	<div class="listview hidebox_s">
	<h3><div class="edit_02_tbilamount""><span class="edit_txt">&nbsp;</span></div></h3>
		<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<th style="width:150px;">小計</th>
				<td style="width:730px;">
					<?php echo $param['Totalbill']['SUBTOTAL']?$customHtml->ht2br($param['Totalbill']['SUBTOTAL'],'','TOTALBILL'):'0';?>
				</td>
			</tr>
			<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th>消費税</th>
				<td>
				<?php echo $param['Totalbill']['SALE_TAX']?$customHtml->ht2br($param['Totalbill']['SALE_TAX'],'','TOTALBILL'):'0';?>
				</td>
			</tr>
			<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			<tr>
				<th>合計請求額</th>
				<td>
					<?php echo $param['Totalbill']['THISM_BILL']?$customHtml->ht2br($param['Totalbill']['THISM_BILL'],'','TOTALBILL'):'0';?>
				</td>
			</tr>
			</table>
			</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
		</div>
	</div>
	<div class="edit_btn">
	</div>
</div>
<!-- contents_End -->