<?php
	$check_flg = true;
	$ok = '<span style="color:#6495ED;">○</span>';
	$no = '<span style="color:#FF7F50;">×</span>';

	// tmp is writable
	if (is_writable(TMP)) {
		$check1[0] = $ok;
		$check1[1] = '問題ありません';
	} else {
		$check_flg = false;
		$check1[0] = $no;
		$check1[1] = '書き込み権限がありません';
	}

	// config is writable
	if (is_writable(APP.'config')) {
		$check2[0] = $ok;
		$check2[1] = '問題ありません';
	} else {
		$check_flg = false;
		$check2[0] = $no;
		$check2[1] = '書き込み権限がありません';
	}

	// cache is writable
	if (is_writable(TMP.'cache')) {
		$check3[0] = $ok;
		$check3[1] = '問題ありません';
	} else {
		$check_flg = false;
		$check3[0] = $no;
		$check3[1] = '書き込み権限がありません';
	}

	// cache is writable
	if (is_writable(TMP.'cache/models')) {
		$check4[0] = $ok;
		$check4[1] = '問題ありません';
	} else {
		$check_flg = false;
		$check4[0] = $no;
		$check4[1] = '書き込み権限がありません';
	}

	// cache is writable
	if (is_writable(TMP.'cache/persistent')) {
		$check5[0] = $ok;
		$check5[1] = '問題ありません';
	} else {
		$check_flg = false;
		$check5[0] = $no;
		$check5[1] = '書き込み権限がありません';
	}

	// tmp/img is writable
	if (is_writable(TMP.'img')) {
		$check8[0] = $ok;
		$check8[1] = '問題ありません';
	} else {
		$check_flg = false;
		$check8[0] = $no;
		$check8[1] = '書き込み権限がありません';
	}

	// img is writable
	if (is_writable(WWW_ROOT.'img'.DS.'cms')) {
		$check6[0] = $ok;
		$check6[1] = '問題ありません';
	} else {
		$check_flg = false;
		$check6[0] = $no;
		$check6[1] = '書き込み権限がありません';
	}
	// php version
	if (phpversion() >= 5.1) {
		$check7[0] = $ok;
		$check7[1] = sprintf('問題ありません : version %s', phpversion());
	} else {
		$check = false;
		$check7[0] = $no;
		$check7[1] = sprintf('5.1以上をインストールしてください : version %s', phpversion());
	}
	if ($check_flg) {
            $start =  '<p>' . $html->link('インストール開始', array('action' => 'database')) . '</p>';
        } else {
            $start =  '<p>' . __('問題がある為、インストールの実行は出来ません', true) . '</p>';
        }
    ?>

<style type="text/css"><!--

table.tbl {
	border: 1px #E3E3E3 solid;
	border-collapse: collapse;
	border-spacing: 0;
}

table.tbl tr.bgcl {
    background: #F5F5F5;
}

table.tbl td {
	padding: 10px;
	border: 1px #E3E3E3 solid;
	border-width: 0 0 1px 1px;
}

table.tbl td.left {
	text-align: left;
}

table.tbl td.center {
	text-align: center;
}

--></style>



<div class="contents_box mb20">
		<?php echo $html->image('bg_contents_top.jpg'); ?>
		<div class="contents_area">

			<table cellspacing="0" cellpadding="0" border="0" width="880" class="tbl">
				<tbody>
					<tr class="bgcl">
						<td class="w300">app/tmpの書き込み権限</td>
						<td class="center w100"><?php echo $check1[0]; ?></td>
						<td><?php echo $check1[1]; ?></td>
					</tr>
					<tr>
						<td>app/configの書き込み権限</td>
						<td class="center"><?php echo $check2[0]; ?></td>
						<td><?php echo $check2[1]; ?></td>
					</tr>
					<tr class="bgcl">
						<td>app/tmp/cacheの書き込み権限</td>
						<td class="center"><?php echo $check3[0]; ?></td>
						<td><?php echo $check3[1]; ?></td>
					</tr>
					<tr>
						<td>app/tmp/cache/modelsの書き込み権限</td>
						<td class="center"><?php echo $check4[0]; ?></td>
						<td><?php echo $check4[1]; ?></td>
					</tr>
					<tr class="bgcl">
						<td>app/tmp/cache/persistentの書き込み権限</td>
						<td class="center"><?php echo $check5[0]; ?></td>
						<td><?php echo $check5[1]; ?></td>
					</tr>
					<tr class="bgcl">
						<td>app/tmp/imgの書き込み権限</td>
						<td class="center"><?php echo $check8[0]; ?></td>
						<td><?php echo $check8[1]; ?></td>
					</tr>
					<tr class="bgcl">
						<td>app/webroot/img/cmsの書き込み権限</td>
						<td class="center"><?php echo $check6[0]; ?></td>
						<td><?php echo $check6[1]; ?></td>
					</tr>
					<tr>
						<td>PHP version(5.1以降)</td>
						<td class="center"><?php echo $check7[0]; ?></td>
						<td><?php echo $check7[1]; ?></td>
					</tr>
				</tbody>
			</table>
			<br />
			<?php echo $html->image('i_line_solid.gif'); ?>
			<br />
			<?php echo $start?>
		</div>
		<?php echo $html->image('bg_contents_bottom.jpg', array('class' => 'block')); ?>
</div>
