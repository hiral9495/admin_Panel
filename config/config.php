<?php 
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $serverName = $_SERVER['SERVER_NAME'];
    $directory = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$baseDirectory = str_replace('/public', '', $directory);

	  // Combine the parts to create the base URL
	return $protocol . $serverName . $baseDirectory . "/";
}
$baseUrl = getBaseUrl();
?>