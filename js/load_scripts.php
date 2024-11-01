<?php

require_once('../../../../wp-load.php');

/** Set ABSPATH for execution */
define( 'ABSPATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/' );
define( 'WPINC', 'wp-includes' );

function get_file($path) {

	if ( function_exists('realpath') )
		$path = realpath($path);

	if ( ! $path || ! @is_file($path) )
		return '';

	return @file_get_contents($path);
}

$load = preg_replace( '/[^a-z0-9,_-]+/i', '', $_GET['load'] );
$load = explode(',', $load);

if ( empty($load) )
	exit;
$compress = ( isset($_GET['c']) && $_GET['c'] );
$force_gzip = ( $compress && 'gzip' == $_GET['c'] );
$expires_offset = 31536000;
$out = '';

$wp_scripts = new WP_Scripts();
wp_default_scripts($wp_scripts);

foreach( $load as $handle ) {
        
        if($handle == 'address') {
            $handle = 'jquery.address-1.4.min';
        } elseif($handle == 'nimbleloader') {
            $handle = 'nimble-loader/jquery.nimble.loader';
        }
        
	$path = $handle . '.js';
        
	$out .= get_file($path) . "\n";
}

header('Content-Type: application/x-javascript; charset=UTF-8');
header('Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT');
header("Cache-Control: public, max-age=$expires_offset");

if($_GET['post']) $post = get_post($_GET['post']);
if($post->post_type == 'smw-gallery') {

if ( $compress && ! ini_get('zlib.output_compression') && 'ob_gzhandler' != ini_get('output_handler') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) ) {
	header('Vary: Accept-Encoding'); // Handle proxies
	if ( false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') && function_exists('gzdeflate') && ! $force_gzip ) {
		header('Content-Encoding: deflate');
		$out = gzdeflate( $out, 3 );
	} elseif ( false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && function_exists('gzencode') ) {
		header('Content-Encoding: gzip');
		$out = gzencode( $out, 3 );
	}
}

echo $out;
exit;
}