<?php
	error_reporting(E_ALL);
	ini_set('display_errors',1);

	require_once('http.php');
	require_once('filesystem.php');

	filesystem::basedir(__DIR__);
	http::format('html');

	$templateDir = '/';
	$request     = null;

	$request     = http::request();
	try {
		$data        = json_decode( filesystem::get('/data/','data.json'), true );
		if($data === null ){
			$data = [];
		}
	} catch ( Exception $e ) {
		$data = [];
	}

	$path        = $request['target'];
	$status      = 200;

	if( !isset($data[$path]) ) {
		$path   = '/404.html';
		$status = 404;
	}

	if ( isset($data[$path]) ) {
		$template = "index.html";

		if( isset($data[$path]['data-simply-page-template']['content'])) {
			$pageTemplate = $data[$path]['data-simply-page-template']['content'];
			if (preg_match("/\.html$/", $pageTemplate) && filesystem::exists($templateDir . $pageTemplate)) {
				$template = $pageTemplate;
			}
		}

		http::response($status);
		filesystem::readfile($templateDir, $template);

	} else {
		http::response(404);
		echo '
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>404 Not Found</title>
</head>
<body>
	<h1>Page not found (error: 404)</h1>
</body>
</html>
';
	}
