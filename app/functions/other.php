<?php

function addText($text) {  
	echo ' 
		<div class="row mar4"> 
		  <div class="col-sm-12">
		    <p class="pad4 text_underline"></i>
		      '.htmlentities(trim($text), ENT_QUOTES, "UTF-8").'
		    </p>
		  </div>
		</div>
	';
}

function calcTotal($price, $tax, $numformat = true) {
  $total = (($price * $tax) / 100) + $price;

  if ($numformat)
  	return numformat($total);

  return convertToOperate($total);
};

function numformat($num) {
  $Alocale = $_SESSION["Alocale"];

	return number_format($num, $Alocale["frac_digits"], $Alocale["decimal_point"], $Alocale["thousands_sep"]);
};

function convertToOperate($num) {
  return number_format($num, 2, '.', '');
};

function DatTime($DatTi) {
	$DatTime = date_create_from_format('Y-m-d H:i:s',$DatTi);
	return date_format($DatTime, 'd-m-Y H:i:s');
};

function sanistr($str) { 
	$str = filter_var($str, FILTER_SANITIZE_STRING);
	return $str;
};

function saninum($num) {
	$num = filter_var($num, FILTER_SANITIZE_NUMBER_INT);
	return $num;
};

function sanifulsp($num) { 
	$num = filter_var($num, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	return $num;
};

function lenum($num) { 
	$num = preg_replace('/[^\wd -]/i', '', $num);
	return $num;
};

function convertYmdToDmY($date)
{   
  return date('d-m-Y', strtotime($date));
}

?>