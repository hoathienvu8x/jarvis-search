<?php
function truncate($string, $max = 500, $replacement = '') {
    if (strlen($string) <= $max) {
        return $string;
    }
    $leave = $max - strlen ($replacement);
    return substr_replace($string, $replacement, $leave);
}
function remove_diacritics($str) {
	$unwanted_array = array(
		'Š'=>'S', 'š'=>'s',
		'Ž'=>'Z', 'ž'=>'z',
		'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A',
		'Ç'=>'C',
		'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E',
		'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I',
		'Ñ'=>'N',
		'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O',
		'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U',
		'Ý'=>'Y',
		'Þ'=>'B',
		'ß'=>'Ss',
		'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a',
		'ç'=>'c',
		'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e',
		'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i',
		'ð'=>'o',
		'ñ'=>'n',
		'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o',
		'ù'=>'u', 'ú'=>'u', 'û'=>'u',
		'ý'=>'y', 'ý'=>'y', 'ÿ'=>'y',
		'þ'=>'b',
		'Ğ'=>'G',
		'İ'=>'I',
		'Ş'=>'S',
		'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u',
		'"'=>'', "'"=>'',
		"[1]"=>'', "[2]"=>'', "[3]"=>'', "[4]"=>'', "[5]"=>'', "[6]"=>'', "[7]"=>'', "[8]"=>'', "[9]"=>''
	);
	$str = strtr($str, $unwanted_array);
	$str = filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$str = filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	return $str;
}