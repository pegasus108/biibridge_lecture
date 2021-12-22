<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.sample.php
* Type:     function
* Name:     sample
* Purpose: $_REQUESTの内容をinputタグとして出力する
* -------------------------------------------------------------
*/
function smarty_function_sample($params, &$smarty)
{
	$r = $_REQUEST;
    $rs = getHidden($r);
	if($rs)
	    return $rs;
	else
		return;
}

function getHidden($array , &$str = "" , &$keys=null , &$depth=0){
	foreach($array as $k => $val){
		if(is_array($val)){
			$depth++;
			$str .= getHidden($val, $str);
			
		}else
			$str .= getHidden($val, $str);
	}

	return $str;
}

function getHiddenValue($val,&$keys){
	$rs = "<input type=\"hidden\" name=\"";

	$first=1;
	foreach($keys as $v){
		if($first){
			$first =0;
			$rs .= $v;
		}else{
			$rs .= "[{$v}]";
		}

	$rs .= "\" value=\"{$val}\" />";
	
	return $rs;
}

?>