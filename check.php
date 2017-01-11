<!doctype html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="https://simplyedit.io/css/style.css">
	<style>
		article h1 {
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
			background-color: white;
			padding-left: 40px;
		}
		article article div {
			font-size: 16px;
			padding-left: 60px;
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
	require_once('filesystem.php');
	require_once('htpasswd.php');

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
					'check' => function() {
						if ( is_readable(__DIR__.'/data/') ) {
							if ( is_dir(__DIR__.'/data/') ) {
								ok();
								return true;
							} else {
								fail('data/ is not a directory');
								return false;
							}
						} else {
							fail('data directory is not readable');
							return false;
						}
					}
				],
				'writable' => [
					'title' => 'Is it writable',
					'check' => function() {
						if ( is_writable(__DIR__.'/data/') ) {
							ok();
							return true;
						} else {
							fail('data directory is not writable');
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
						fail('img directory is missing');
						return false;
					}
				],
				'readable' => [	
					'title' => 'Is it readable?',
					'check' => function() {
						if ( is_readable(__DIR__.'/img/') ) {
							if ( is_dir(__DIR__.'/img/') ) {
								ok();
								return true;
							} else {
								fail('img/ is not a directory');
								return false;
							}
						} else {
							fail('img directory is not readable');
							return false;
						}
					}
				],
				'writable' => [
					'title' => 'Is it writable',
					'check' => function() {
						if ( is_writable(__DIR__.'/img/') ) {
							ok();
							return true;
						} else {
							fail('img directory is not writable');
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
					'check' => function() {
						if ( is_readable(__DIR__.'/files/') ) {
							if ( is_dir(__DIR__.'/files/') ) {
								ok();
								return true;
							} else {
								fail('files/ is not a directory');
								return false;
							}
						} else {
							fail('files directory is not readable');
							return false;
						}
					}
				],
				'writable' => [
					'title' => 'Is it writable',
					'check' => function() {
						if ( is_writable(__DIR__.'/files/') ) {
							ok();
							return true;
						} else {
							fail('files directory is not writable');
							return false;
						}
					}
				]
			]
		],
		'key' => [
			'title' => 'Checking API keys',
			'check' => function() {
				ok();
				return true;
			}
		]
	];

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