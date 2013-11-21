<?php
$q = @$_GET['q'];
$target = @$_GET['target'];
$results = get_suggestions($q);

echo !empty($results)? print_results($results) : "";

function print_results($results) {
    global $target, $q;
	$response = "<ul>";
	for($i=0;$i<count($results);$i++){
		$url = @str_ireplace("%s", urlencode($results[$i]), $_GET['url']);
		$response .= '<li><a href="'.$url.'" target="'.$target.'">'.str_ireplace($q, $q.'<strong>', $results[$i]).'</strong></a></li>';
	}
	$response .= "</ul>";
    return $response;
}

function get_suggestions($q){
	$request_uri = 'http://suggestqueries.google.com/complete/search?client=firefox&q='.urlencode($q);

    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $request_uri);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
    $data = curl_exec($ch);

	$results = json_decode($data, true);
	curl_close($ch);

	return $results[1];
}
?>