<!doctype html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="https://simplyedit.io/css/style.css">
	<style>
		article h1 {
			clear: both;
			font-size: 20px;
			line-height: 24px;
			padding: 4px 20px;
			background-color: #EEE;
			margin: 10px 0 0 0;
		}
		article div {
			padding-left: 20px;
		}
		article article h1 {
			float: left;
			clear: both;
			background-color: white;
			padding-left: 40px;
		}
		article article div {
			font-size: 16px;
			line-height: 24px;
			padding-top: 16px;
		}
		.simplyedit-fail {
			clear: both;
			padding-left: 60px;
			padding-top: 0;
			color: red;
		}
		.simplyedit-ok {
			color: green;
		}
		.simplyedit-ok::after {
			content: "";
			display: block;
			clear: both;
		}
	</style>
</head>
<body class="info">
	<header class="constrain-width">
		<h1>
			<a href="https://simplyedit.io/">
				<img class="logo" alt="SimplyEdit" src="https://simplyedit.io/img/logo.svg">
			</a>
		</h1>
	</header>
	<main class="constrain-width">
<?php
	require_once('config.php');
	
	if (function_exists('posix_getpwuid')) {
		$user = posix_getpwuid(posix_geteuid())['name'];
	} else {
		$user = "[ user account unknown, try 'www-data']";
	}

	$checks = [
		'.htaccess' => [
			'title' => '.htaccess (Apache configuration)',
			'checks' => [
				'exists' => [
					'title' => 'Checking if .htaccess file exists',
					'check' => function() {
						$contents = filesystem::get('/', '.htaccess');
						if ($contents && strlen($contents)) {
							ok();
							return true;
						} else {
							fail('Missing or empty .htaccess file in the document root');
							return false;
						}
					}
				] 
			]
		],
		'.htpasswd' => [
			'title' => '.htpasswd (users and passwords file)',
			'checks' => [
				'exists' => [
					'title' => 'Checking if .htpasswd file exists',
					'check' => function() {
						$contents = filesystem::get('/', '.htpasswd');
						if ($contents && strlen($contents)) {
							ok();
							return true;
						} else {
							fail('Missing or empty .htpasswd file in the document root');
							return false;
						}
					}
				],
				'user' => [
					'title' => 'Checking if it contains a valid user.',
					'check' => function() {
						htpasswd::load('.htpasswd');
						if ( htpasswd::$users && count(htpasswd::$users)) {
							ok();
							return true;
						} else {
							fail('No users defined in .htpasswd file');
							return false;
						}
					}
				],
				'simplyedit' => [
					'title' => 'Checking if simplyedit user has been disabled.',
					'check' => function() {
						if ( htpasswd::$users['simplyedit'] ) {
							fail('Please remove the user simplyedit from the .htpasswd file and replace it with a personal account.');
							return false;
						}
						ok();
						return true;
					}
				]
			]
		],
		'data' => [
			'title' => 'Checking data directory',
			'checks' => [
				'exists' => [
					'title' => 'Does it exist?',
					'check' => function() {
						if ( filesystem::exists('/data/') ) {
							ok();
							return true;
						}
						fail('data directory is missing');
						return false;
					}
				],
				'readable' => [	
					'title' => 'Is it readable?',
					'check' => function() use ($user) {
						if ( is_readable(filesystem::$basedir.'/data/') ) {
							if ( is_dir(filesystem::$basedir.'/data/') ) {
								ok();
								return true;
							} else {
								fail(filesystem::$basedir.'/data/ is not a directory');
								return false;
							}
						} else {
							fail(filesystem::$basedir.'/data/ directory is not readable. Grant read and write access for user '.$user);
							return false;
						}
					}
				],
				'writable' => [
					'title' => 'Is it writable?',
					'check' => function() use ($user) {
						if ( is_writable(filesystem::$basedir.'/data/') ) {
							ok();
							return true;
						} else {
							fail(filesystem::$basedir.'/data/ directory is not writable. Grant read and write access for user '.$user);
							return false;
						}
					}
				]
			]
		],
		'img' => [
			'title' => 'Checking img directory',
			'checks' => [
				'exists' => [
					'title' => 'Does it exist?',
					'check' => function() {
						if ( filesystem::exists('/img/') ) {
							ok();
							return true;
						}
						fail(filesystem::$basedir.'/img/ directory is missing');
						return false;
					}
				],
				'readable' => [	
					'title' => 'Is it readable?',
					'check' => function() use ($user) {
						if ( is_readable(filesystem::$basedir.'/img/') ) {
							if ( is_dir(filesystem::$basedir.'/img/') ) {
								ok();
								return true;
							} else {
								fail(filesystem::$basedir.'/img/ is not a directory');
								return false;
							}
						} else {
							fail(filesystem::$basedir.'/img/ directory is not readable. Grant read and write access for user '.$user);
							return false;
						}
					}
				],
				'writable' => [
					'title' => 'Is it writable?',
					'check' => function() use ($user) {
						if ( is_writable(filesystem::$basedir.'/img/') ) {
							ok();
							return true;
						} else {
							fail(filesystem::$basedir.'/img/ directory is not writable. Grant read and write access for user '.$user);
							return false;
						}
					}
				]
			]
		],
		'files' => [
			'title' => 'Checking files directory',
			'checks' => [
				'exists' => [
					'title' => 'Does it exist?',
					'check' => function() {
						if ( filesystem::exists('/files/') ) {
							ok();
							return true;
						}
						fail('files directory is missing');
						return false;
					}
				],
				'readable' => [	
					'title' => 'Is it readable?',
					'check' => function() use ($user) {
						if ( is_readable(filesystem::$basedir.'/files/') ) {
							if ( is_dir(filesystem::$basedir.'/files/') ) {
								ok();
								return true;
							} else {
								fail(filesystem::$basedir.'/files/ is not a directory');
								return false;
							}
						} else {
							fail(filesystem::$basedir.'/files/ directory is not readable. Grant read and write access for user '.$user);
							return false;
						}
					}
				],
				'writable' => [
					'title' => 'Is it writable?',
					'check' => function() use ($user) {
						if ( is_writable(filesystem::$basedir.'/files/') ) {
							ok();
							return true;
						} else {
							fail(filesystem::$basedir.'/files/ directory is not writable. Grant read and write access for user '.$user);
							return false;
						}
					}
				]
			]
		],
		'key' => [
			'title' => 'Checking API keys',
			'check' => function() {
				// check index.html
				// check templates/
				$files = array( filesystem::$basedir.'/index.html' );
				if ( is_dir(filesystem::$basedir.'/templates/') ) {
					if ( !is_readable(filesystem::$basedir.'/templates/') ) {
						fail(filesystem::$basedir.'/templates/ directory is not readable. Grant read access for user '.$user);
						return false;
					}
					$dir = opendir(filesystem::$basedir.'/templates/');
					if ( $dir ) {
						while (false !== ($entry = readdir($dir))) {
							$entry = filesystem::$basedir.'/templates/'.$entry;
							if (is_file($entry) && is_readable($entry)) {
								$files[] = $entry;
							}
						}
					}
				}
				$errors = 0;
				foreach ( $files as $file ) {
					$error = checkKey($file);
					if ( $error ) {
						fail($error);
						$errors++;
					}
				}
				if ( !$errors ) {
					ok();
					return true;
				} else {
					return false;
				}
			}
		]
	];

	function checkKey($file) {
		global $user;
		$contents = file_get_contents($file);
		if ( $contents === false ) {
			return 'Could not read '.$file.'. Grant read access for user '.$user;
		}
		if (!preg_match('/data-api-key\s*=\s*"([^"]*)"/',$contents, $matches)) {
			return $file.' has no data-api-key attribute.';
		}
		if ($matches[1]=='localhost') {
			return $file.' has API key "localhost", which will only work locally.';
		}
		if ($matches[1]=='simplyedit') {
			return $file.' has an invalid API key "simplyedit", please grab a license key and enter it.';
		}
	}

	function ok() {
		echo '<div class="simplyedit-ok">OK</div>';
	}

	function fail($msg) {
		echo '<div class="simplyedit-fail">'.$msg.'</div>';
	}

	function run_check($check) {
		echo '<article class="simplyedit-check"><h1>'.$check['title'].'</h1>';
		if ( $check['checks'] ) {
			$result = run_checks($check['checks']);
		} else {
			try {
				$result = $check['check']();
			} catch(\Exception $e) {
				$result = $e;
				fail($e->getMessage());
			}
		}
		echo '</article>';
		return $result;
	}

	function run_checks($checks) {
		echo '<section class="simplyedit-checks">';
		$results = [];
		foreach ($checks as $name => $check) {
			$results[$name] = run_check($check);
		}
		echo '</section>';
		return $results;			
	}

	$results = run_checks($checks);
?>
	</main>
</body>
</html>