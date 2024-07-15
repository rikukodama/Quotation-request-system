<?php	//完了メッセージ
 	//var_dump($session->flash());
	print $session->flash();
?>

<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは自社担当者一覧の画面です。<br />「編集する」ボタンを押下すると自社担当者を変更できます。</p>
	</div>
</div>

<br class="clear" />
<!-- header_End -->
<!-- contents_Start -->
<div class="edit_btn">
		<?php if($editauth){?>
		<?php echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'charges', 'action' => 'edit/'.$params['Charge']['CHR_ID']),array('escape' => false)); ?>
		<?php }?>
		<?php //echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => 'charges', 'action' => 'index'),array('escape' => false)); ?>
        <?php
            echo $form->create($this->name, array('type' => 'post', 'action' => 'moveback', 'style' => 'display:inline;'));
            echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), 'javascript:move_to_index();',array('escape' => false));
            echo $form->end();
        ?>
</div>
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="edit_01"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<?php echo $form->create("Charge", array("type" => "file", "controller" => "charges", "class" => "Charge")); ?>
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th>ステータス</th>
					<td>
					<?php echo $status[$params['Charge']['STATUS']]?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:150px;" ><span class ="float_l">担当者名</th>
					<td style="width:730px;">
						<?php echo $customHtml->ht2br($params['Charge']['CHARGE_NAME']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:150px;" >担当者名カナ</th>
					<td style="width:730px;">
						<?php echo $customHtml->ht2br($params['Charge']['CHARGE_NAME_KANA']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:150px;" >部署名</th>
					<td style="width:730px;">
						<?php echo $customHtml->ht2br($params['Charge']['UNIT']);?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>役職名</th>
					<td>
						<?php echo $params['Charge']['POST']?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>メールアドレス</th>
					<td>
						<?php echo $customHtml->ht2br($params['Charge']['MAIL']);?>
					</td>
				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >郵便番号</th>
							<td style="width:750px;">
							<?php
								if(empty($params['Charge']['POSTCODE1']) && empty($params['Charge']['POSTCODE2'])) {

								}else {
									echo $customHtml->ht2br($params['Charge']['POSTCODE1']."-".$params['Charge']['POSTCODE2']);
								}

							?>
							</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">都道府県</th>
					<td style="width:750px;">
					<?php
						if($params['Charge']['CNT_ID'] ) {
							echo $countys[$params['Charge']['CNT_ID']];
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >住所</th>
					<td style="width:750px;">
							<?php echo $customHtml->ht2br($params['Charge']['ADDRESS']); ?>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >建物名</th>
					<td style="width:750px;">
							<?php echo $customHtml->ht2br($params['Charge']['BUILDING']); ?>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">電話番号</th>
					<td style="width:750px;">
					<?php
						if(empty($params['Charge']['PHONE_NO1']) && empty($params['Charge']['PHONE_NO2']) && empty($params['Charge']['PHONE_NO3'])) {

						}else {
							echo $customHtml->ht2br($params['Charge']['PHONE_NO1']." - ".$params['Charge']['PHONE_NO2']." - ".$params['Charge']['PHONE_NO3']);
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">FAX番号</th>
					<td style="width:750px;">
						<?php if($params['Charge']['FAX_NO1']&& $params['Charge']['FAX_NO2']&& $params['Charge']['FAX_NO3']){
							echo $customHtml->ht2br($params['Charge']['FAX_NO1']." - ".$params['Charge']['FAX_NO2']." - ".$params['Charge']['FAX_NO3']);
							}?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>担当者印</th>
					<td><?php
						if(isset($image)){
							echo $html->image(array("controller" => "charges",'action' => 'contents/'.$id),array("width"=>"100","height"=>"100"));
						}
						?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">押印設定</th>
					<td style="width:750px;">
						<?php echo $seal_flg[$params['Charge']['CHR_SEAL_FLG']]; ?></td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="edit_btn">
		<?php if($editauth){?>
		<?php echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'charges', 'action' => 'edit/'.$params['Charge']['CHR_ID']),array('escape' => false)); ?>
		<?php }?>
		<?php echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => 'charges', 'action' => 'index'),array('escape' => false)); ?>
	</div>
</div>
<?php echo $form->hidden("USR_ID",array('value'=>$user['USR_ID'])); ?>

<?php echo $form->end(); ?>