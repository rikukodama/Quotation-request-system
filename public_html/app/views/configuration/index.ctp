<?php
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは環境設定確認の画面です。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->
<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="edit_02_edit_mail"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th>送信者名</th>
					<td style="width:750px;">
						<?php echo $customHtml->ht2br($params['Configuration']['FROM_NAME']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>送信者アドレス</th>
					<td style="width:750px;">
						<?php echo $customHtml->ht2br($params['Configuration']['FROM']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" ><span class ="float_l">SMTPの使用</span></th>
					<td style="width:750px;">
						<?php echo $status[$params['Configuration']['STATUS']];?>
					</td>
				</tr>
			</table>
			<?php echo $form->hidden('smtp_frag',array('value'=>$params['Configuration']['STATUS']))?>
			<div class='Smtpuse'>
			<table>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" ><span class ="float_l">プロトコル</span></th>
					<td style="width:750px;">
						<?php echo ($params['Configuration']['SECURITY']!=null)?$protocol[$params['Configuration']['PROTOCOL']]:'';?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" ><span class ="float_l">SMTPセキュリティ</span></th>
					<td style="width:750px;">
						<?php echo ($params['Configuration']['SECURITY']!=null)?$security[$params['Configuration']['SECURITY']]:'';?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>SMTPサーバ</th>
					<td style="width:750px;">
						<?php echo $customHtml->ht2br($params['Configuration']['HOST']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>ポート番号</th>
					<td style="width:750px;">
						<?php echo $customHtml->ht2br($params['Configuration']['PORT']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>SMTPユーザ</th>
					<td style="width:750px;">
						<?php echo $customHtml->ht2br($params['Configuration']['USER']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>SMTPパスワード</th>
					<td style="width:750px;">
						<?php echo $customHtml->ht2br($params['Configuration']['PASS']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
			</table>
			</div>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="edit_btn">
		<?php
		if($user['AUTHORITY']==0){
			echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'configurations', 'action' => 'edit/'),array('escape' => false));
		}?>
	</div>
</div>
<?php echo $form->hidden("CMP_ID", array("value" => "1")); ?>