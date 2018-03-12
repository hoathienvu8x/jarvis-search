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
function curl_get($url,$get = array(), $options = array()) {
	$defaults = array(
        CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_USERAGENT => 'Mozilla/5.0 (iPhone,U; CPU iPhone OS 2_2_1 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) version/3.1.1 Mobile/5H11 Safari/525.20'
    );
	$ch = curl_init();
	curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch)) {
        $response = array('status' => false, 'data' => null, 'msg' => curl_error($ch));
    } else {
		$response = array('status' => true, 'data' => $result, 'msg' => 'Recived '.strlen($result).' bytes');
	}
    curl_close($ch);
    return $response;
}
function wiki_get($question) {
	$question = str_replace(' ','+',$question);
	$url = 'https://en.wikipedia.org/w/api.php';
	$get = array(
		'action' => 'opensearch',
		'search' => $question,
		'limit' => '1',
		'namespace' => '0',
		'format' => 'xml'
	);
	$response = curl_get($url, $get);
	if ($response['status'] == false) {
		return $response['msg'];
	} else {
		$xml = simplexml_load_string($response['data']);
		if ($xml->Section->Item) {
			$item = (array)$xml->Section->Item;
			if (isset($item['Url'])) {
				$response = curl_get(str_replace('https://en.','https://en.m.',$item['Url']));
				// file_put_contents('debug.php', $response['data']);
				include_once dirname(__FILE__) . '/simple_html_dom.php';
				$response['data'] = preg_replace('/<ul id="page-actions".*?>.*?<\/ul>/i','',$response['data']);
				$html = str_get_html($response['data']);
				$obj = array(
					'defined' => '',
					'document' => ''
				);
				foreach($html->find('div#content p') as $i => $e) {
					if ($i == 0) {
						$text = $e->innertext;
						$obj['defined'] = remove_diacritics(strip_tags($text));
						break;
					}
				}
				foreach($html->find('div#content') as $i => $e) {
					$text = $e->innertext;
					$text = preg_replace('/ (style|click|onclick|class|id|data-class|role)=".*?"/i','', $text);
					$obj['document'] .= $text;
				}
				$obj['document'] = preg_replace('/(?s)<script>.*?<\/script>/i','',$obj['document']);
				$obj['document'] = trim($obj['document']);
				return $obj;
			}
		}
	}
	return '';
}