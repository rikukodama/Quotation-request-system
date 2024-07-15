<?php	//完了メッセージ
	echo $session->flash();
?>
<div id="guide">
	<div id="guide_box" class="clearfix">
		<?php echo $html->image('/img/company/i_guide.jpg'); ?>
		<p>こちらのページは顧客情報確認の画面です。<br />「編集する」ボタンを押すと顧客情報を編集することができます。</p>
	</div>
</div>
<br class="clear" />
<!-- header_End -->

<!-- contents_Start -->
<div id="contents">
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>
	<div class="edit_btn2">
		<?php if($editauth){
			echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'customers', 'action' => 'edit/'.$cstID),array('escape' => false));
		}
		 ?>
		<?php //echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => 'customers', 'action' => 'index'),array('escape' => false)); ?>
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
					<th style="width:130px;">社名</th>
					<td style="width:750px;"><?php echo $customHtml->ht2br($this->data['Customer']['NAME']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">社名カナ</th>
					<td style="width:750px;"><?php echo $customHtml->ht2br($this->data['Customer']['NAME_KANA']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">敬称</th>
					<td style="width:750px;">
					<?php
						if($this->data['Customer']['HONOR_CODE'] == 2) {
							echo $this->data['Customer']['HONOR_TITLE'];
						}else {
							echo $honor[$this->data['Customer']['HONOR_CODE']];
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">郵便番号</th>
					<td style="width:750px;">
					<?php
						if(empty($this->data['Customer']['POSTCODE1']) && empty($this->data['Customer']['POSTCODE2'])) {

						}else{
							echo $customHtml->ht2br($this->data['Customer']['POSTCODE1']." - ".$this->data['Customer']['POSTCODE2']);
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">都道府県</th>
					<td style="width:750px;"><?php echo $this->data['Customer']['CNT_ID']?$customHtml->ht2br($countys[$this->data['Customer']['CNT_ID']]):""; ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">住所</th>
					<td style="width:750px;"><?php echo $customHtml->ht2br($this->data['Customer']['ADDRESS']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">建物名</th>
					<td style="width:750px;"><?php echo $customHtml->ht2br($this->data['Customer']['BUILDING']); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">電話番号</th>
					<td style="width:750px;">
					<?php
						if(empty($this->data['Customer']['PHONE_NO1']) && empty($this->data['Customer']['PHONE_NO2']) && empty($this->data['Customer']['PHONE_NO3'])) {

						}else {
							echo $customHtml->ht2br($this->data['Customer']['PHONE_NO1']." - ".$this->data['Customer']['PHONE_NO2']." - ".$this->data['Customer']['PHONE_NO3']);
						}
					?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">FAX番号</th>
					<td style="width:750px;"><?php echo $customHtml->ht2br(($this->data['Customer']['FAX_NO1']&& $this->data['Customer']['FAX_NO2']&& $this->data['Customer']['FAX_NO3'])?$this->data['Customer']['FAX_NO1']." - ".$this->data['Customer']['FAX_NO2']." - ".$this->data['Customer']['FAX_NO3']:""); ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">ホームページ</th>
					<td style="width:750px;"><?php echo ($this->data['Customer']['WEBSITE'])?$html->link($customHtml->ht2br($this->data['Customer']['WEBSITE'])):''; ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">自社担当者</th>
					<td style="width:750px;"><?php echo $customHtml->ht2br($charge); ?></td>
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
						<?php echo $cutooff_select[$this->data['Customer']['CUTOOFF_SELECT']]; ?>
						<?php echo $customHtml->ht2br($this->data['Customer']['CUTOOFF_DATE']?$this->data['Customer']['CUTOOFF_DATE']."日":""); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">支払日</th>
					<td style="width:750px;">
						<?php echo ($this->data['Customer']['PAYMENT_MONTH']||$this->data['Customer']['PAYMENT_MONTH']!=NULL)?$payment[$this->data['Customer']['PAYMENT_MONTH']]:''; ?>
						<?php echo $payment_select[$this->data['Customer']['PAYMENT_SELECT']]; ?>
						<?php echo $customHtml->ht2br($this->data['Customer']['PAYMENT_DAY']?$this->data['Customer']['PAYMENT_DAY']."日":""); ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">消費税設定</th>
					<td style="width:750px;"><?php echo $excises[$this->data['Customer']['EXCISE']]; ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税端数処理</th>
					<td>
						<?php echo $fractions[$this->data['Customer']['TAX_FRACTION']]; ?>
					</td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th>消費税端数計算</th>
					<td>
						<?php echo $tax_fraction_timing[$this->data['Customer']['TAX_FRACTION_TIMING']]; ?>
					</td>
				</tr>				
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>
				<tr>
					<th style="width:130px;">基本端数処理</th>
					<td style="width:750px;"><?php echo $fractions[$this->data['Customer']['FRACTION']]; ?></td>
				</tr>
				<tr><td colspan="2" class="line"><?php echo $html->image('i_line_solid.gif'); ?></td></tr>

				<tr>
					<th style="width:130px;">備考</th>
					<td style="width:750px;"><?php echo $customHtml->ht2br($this->data['Customer']['NOTE']); ?></td>
				</tr>

			</table>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg',array('class' => 'block')); ?>
	</div>
	<div class="arrow_under"><?php echo $html->image('i_arrow_under.jpg'); ?></div>	<div class="edit_btn2">
		<?php if($editauth){
			echo $html->link($html->image('bt_edit.jpg',array('class'=>'imgover','alt'=>'編集する')), array('controller' => 'customers', 'action' => 'edit/'.$cstID),array('escape' => false));
		}
		 ?>		<?php echo $html->link($html->image('bt_index.jpg',array('class'=>'imgover','alt'=>'一覧')), array('controller' => 'customers', 'action' => 'index'),array('escape' => false)); ?>
	</div>

</div>