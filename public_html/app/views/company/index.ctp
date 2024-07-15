<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは自社情報設定確認の画面です。<?php if($user["AUTHORITY"]==0){ ?><br />必要な情報を入力の上「編集する」ボタンを押下すると自社情報を変更できます。<?php } ?></p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->
<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<h3><div class="company_01"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:130px;" ><span class ="float_l">自社名</span></th>
					<td style="width:750px;">
					<?php echo $customHtml->ht2br($param['Company']['NAME']); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>代表者名</th>
					<td style="width:750px;">
					<?php echo $customHtml->ht2br($param['Company']['REPRESENTATIVE']); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >郵便番号</th>
						<td style="width:750px;">
							<?php
							if(empty($param['Company']['POSTCODE1']) && empty($param['Company']['POSTCODE2'])) {

							}else {
								echo $customHtml->ht2br($param['Company']['POSTCODE1']."-".$param['Company']['POSTCODE2']);
							}
							?>
						</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">都道府県</th>
					<td style="width:750px;">
					<?php
						if($param['Company']['CNT_ID']) {
							echo $countys[$param['Company']['CNT_ID']];
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >住所</th>
					<td style="width:750px;">
							<?php echo $customHtml->ht2br($param['Company']['ADDRESS']); ?>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;" >建物名</th>
					<td style="width:750px;">
							<?php echo $customHtml->ht2br($param['Company']['BUILDING']); ?>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">電話番号</th>
					<td style="width:750px;">
					<?php
						if(empty($param['Company']['PHONE_NO1']) && empty($param['Company']['PHONE_NO2']) && empty($param['Company']['PHONE_NO3'])) {

						}else {
							echo $customHtml->ht2br($param['Company']['PHONE_NO1']." - ".$param['Company']['PHONE_NO2']." - ".$param['Company']['PHONE_NO3']);
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">FAX番号</th>
					<td style="width:750px;"><?php
						if($param['Company']['FAX_NO1']&& $param['Company']['FAX_NO2']&& $param['Company']['FAX_NO3']){
							echo $customHtml->ht2br($param['Company']['FAX_NO1']." - ".$param['Company']['FAX_NO2']." - ".$param['Company']['FAX_NO3']);
						} ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">登録番号</th>
					<td style="width:750px;"><?php
						if($param['Company']['INVOICE_NUMBER']){
							echo $this->CustomHtml->ht2br($param['Company']['INVOICE_NUMBER']);
						} ?></td>
				</tr>				
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">敬称</th>
					<td style="width:750px;">
					<?php
						if($param['Company']['HONOR_CODE'] == 2) {
							echo $param['Company']['HONOR_TITLE'];
						}else {
							echo $honor[$param['Company']['HONOR_CODE']];
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>社判登録<br /></th>
					<td><?php
						if(isset($image))
						{
							echo $html->image(array("controller" =>"companies",'action' => 'contents'),array("width"=>"100","height"=>"100"));
						}
						?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">押印設定</th>
					<td style="width:750px;">
					<?php echo $seal_flg[$param['Company']['CMP_SEAL_FLG']]?>
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>

	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="company_02"><span class="edit_txt">&nbsp;</span></div></h3>

	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:130px;">締日</th>
					<td style="width:750px;">
					<?php echo ($param['Company']['CUTOOFF_DATE'])?$param['Company']['CUTOOFF_DATE'].'日':'末日'; ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th<?php echo $form->error("PAYMENT_DAY")?' class="txt_top"':''; ?>>支払日</th>
					<td>
					<?php echo ($param['Company']['PAYMENT_MONTH']!=null)?$payment[$param['Company']['PAYMENT_MONTH']]:''; ?>
					<?php echo $payment_select[$param['Company']['PAYMENT_SELECT']]; ?>
					<?php echo ($param['Company']['PAYMENT_DAY'])?$param['Company']['PAYMENT_DAY'].'日':''; ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>数量小数部表示</th>
					<td>
						<?php echo $decimals[$param['Company']['DECIMAL_QUANTITY']]; ?>
					</td>

				</tr>
				<tr>
					<th>単価小数部表示</th>
					<td>
						<?php echo $decimals[$param['Company']['DECIMAL_UNITPRICE']]; ?>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税設定</th>
					<td>
						<?php echo $excises[$param['Company']['EXCISE']]; ?>
					</td>

				</tr>

				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				
				<tr>
					<th>消費税端数処理</th>
					<td>
						<?php echo $fractions[$param['Company']['TAX_FRACTION']]; ?>
					</td>
				</tr>
				<tr>
					<th>消費税端数計算</th>
					<td>
						<?php echo $tax_fraction_timing[$param['Company']['TAX_FRACTION_TIMING']]; ?>
					</td>
				</tr>				
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>基本端数処理</th>
					<td>
						<?php echo $fractions[$param['Company']['FRACTION']]; ?>
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>

	</div>

	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="company_03"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">

				<tr>
					<th style="width:130px;">名義</th>
					<td style="width:750px;">
						<?php echo $customHtml->ht2br($param['Company']['ACCOUNT_HOLDER']); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>銀行名</th>
					<td>
						<?php echo $customHtml->ht2br($param['Company']['BANK_NAME']); ?>
					</td>

				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>支店名</th>
					<td>
						<?php echo $customHtml->ht2br($param['Company']['BANK_BRANCH']); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>

					<th>口座区分</th>
					<td>
						<?php echo ($param['Company']['ACCOUNT_TYPE']!=null)?$account_type[$param['Company']['ACCOUNT_TYPE']]:''; ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>口座番号</th>
					<td>
						<?php echo $customHtml->ht2br($param['Company']['ACCOUNT_NUMBER']); ?>
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>








	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>

	<h3><div class="company_04"><span class="edit_txt">&nbsp;</span></div></h3>
	<div class="contents_box">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">
			<table width="880" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th style="width:130px;">枠色</th>
					<td style="width:750px;">
					<?php echo $colors[$param['Company']['COLOR']]; ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">方向</th>
					<td style="width:750px;">
					<?php echo $direction[$param['Company']['DIRECTION']]; ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">連番設定</th>
					<td style="width:750px;">
					<?php echo $serial_option[$param['Company']['SERIAL_NUMBER']]; ?>
					</td>
				</tr>
			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>









	<div class="edit_btn">
		<?php
		if($user['AUTHORITY']==0){
			echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'companies', 'action' => 'edit/'),array('escape' => false));
		}?>
	</div>
</div>
<?php echo $form->hidden("CMP_ID", array("value" => "1")); ?>