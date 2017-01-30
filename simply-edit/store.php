<?php
	require_once('config.php');

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
		$user     = $request['user'];
		$password = $request['password'];
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
