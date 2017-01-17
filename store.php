<?php
	require_once('http.php');
	require_once('filesystem.php');
	require_once('htpasswd.php');

	filesystem::basedir(__DIR__);

	filesystem::allow('/data/','application/json.*');
	filesystem::allow('/data/','text/.*');

	filesystem::allow('/images/','image/.*');

	filesystem::allow('/files/','.*');

	filesystem::check('put', '/data/', function($filename, $realfile) {
		$contents = file_get_contents($realfile);
		$result   = json_decode($contents);
		if ( $result === null ) {
			throw new \Exception('File does not contain valid JSON',1);
		}
		return true;
	});

	filesystem::check('delete', '/data/data.json', function() {
		throw new \Exception('You cannot delete the data.json file',3);
	});

	filesystem::check('put', '/', function($filename, $realfile) {
		$disallowed = ['php','phtml','inc','phar','cgi'];
		$extension  = pathinfo($filename, PATHINFO_EXTENSION);
		if ( in_array($extension, $disallowed) ) {
			throw new \Exception('Extension '.$extension.' is disallowed', 2);
		}
	});

	$statusCodes = [
		1   => 412,
		2   => 403,
		3   => 403,
		102 => 412,	// precondition failed
		103 => 412,
		104 => 412,
		105 => 404,	// not found
		106 => 403, // access denied
		107 => 403,
		108 => 403,
		109 => 412,
		110 => 403
	];

	$request = http::request();

	$result = [];
	$status = 200;

	try {
		htpasswd::load('.htpasswd');
		$user = $request['user'] ?: $_SERVER['PHP_AUTH_USER'];
		$password = $request['password'] ?: $_SERVER['PHP_AUTH_PW'];
		if ( !$user || !$password || !htpasswd::check($user, $password)) {
			header('WWW-Authenticate: Basic realm="Simply Store"');
			$status = 401;
			$result = ['error' => 405, 'message' => 'Access denied'];
		} else if ( $request['method']=='PUT' ) {
			$result = filesystem::put($request['directory'], $request['filename']);
		} else if ( $request['method']=='DELETE' ) {
			$result = filesystem::delete($request['directory'], $request['filename']);
		} else {
			$status = 405; //Method not allowed
			$result = [ 'error' => 405, 'message' => 'Method not allowed' ];
		}
	} catch( \Exception $e ) {
		$code = $e->getCode();
		if ( isset($statusCodes[$code]) ) {
			$status = $statusCodes[$code];
		} else {
			$status = 500; // internal error
		}
		$result = [ 'error' => $code, 'message' => $e->getMessage() ];
	}

	http::response( $status, $result );
