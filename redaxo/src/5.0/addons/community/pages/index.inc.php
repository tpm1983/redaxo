<?php

require $REX['SRC_PATH'] .'/core/layout/top.php';

$page = 'community';

rex_title("Community", $REX['ADDON'][$page]['SUBPAGES']);

$subpage = rex_request("subpage","string","");

function deep_in_array($value, $array, $case_insensitive = false){
   foreach($array as $item){
       if(is_array($item)) $ret = deep_in_array($value, $item, $case_insensitive);
       else $ret = ($case_insensitive) ? strtolower($item)==$value : $item==$value;
       if($ret)return $ret;
   }
   return false;
}

if (!deep_in_array($subpage,$REX['ADDON'][$page]['SUBPAGES'])) 
	$subpage = "";


if ($subpage != "")
{
	if (substr($subpage,0,7)=="plugin.")
		require $REX['SRC_PATH'] .'/addons/'.$page.'/plugins/'.substr($subpage,7,strlen($subpage)-7).'/pages/index.inc.php';
	else
		require $REX['SRC_PATH'] .'/addons/'.$page.'/pages/'.$subpage.'.inc.php';
}else
{
	echo '<table class="rex-table">';
	echo '<tr><th>Community - �bersicht</th></tr>';
	foreach($REX['ADDON'][$page]['SUBPAGES'] as $sp)
		echo '<tr><td>&raquo; <a href="index.php?page='.$page.'&amp;subpage='.$sp[0].'">'.$sp[1].'</a></td></tr>';
	echo '</table>';
}

require $REX['SRC_PATH'] .'/core/layout/bottom.php';