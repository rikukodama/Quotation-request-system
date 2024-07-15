<?php //住所検索の結果画面 ?>
<?php if(is_array($data)){ ?>
<?php
foreach($data as $key => $value){
	echo '<a href="/" onclick="return setaddress(\''.$value["Post"]["POSTCODE"].'\');"> 〒'.substr($value['Post']['POSTCODE'], 0, 3).'-'.substr($value['Post']['POSTCODE'], 3, 4).' '.$value['Post']['COUNTY'].$value['Post']['CITY'].$value['Post']['AREA']."</a><br />\n";
}
?>
<?php }else{ ?>
<?php } ?>