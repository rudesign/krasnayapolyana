<?php

/**
 * HTTP interface for Getthumb API
 *
 * Sends the result image to the output. You can configure the arguments
 * in the getthumb.ini or by the GET parameters.
 *
 * @package Getthumb
 * @subpackage HTTP interface
 * @version 0.7.1-dev
 * @author Gábor László <gopher@vipmail.hu>
 * @license LGPL
 */

require_once dirname(__FILE__).'/getthumb.class.php';

$gt = new Getthumb();

// Get the source file path

$src = @$_GET['src'];

// Init config from INI file and from 'config' parameter

$config = $gt->getConfig();

$ini = parse_ini_file('getthumb.ini', true);
if (isset($ini['getthumb'])) {
	$config = array_merge($config, $ini['getthumb']);
}

$section = @$_GET['config'];
if (isset($ini[$section])) {
	$config = array_merge($config, $ini[$section]);
}

// Protect some parameters

$pp = array('cache_dir', 'no_cache');
foreach ($config as $name => $defVal)
{
	if (in_array($name, $pp))
		continue;
	
	$config[$name] = isset($_GET[$name]) ? $_GET[$name] : $defVal;
}

$config['output'] = true;

try
{
	// Get the thumb

	$gt->get($src, $config);
}
catch (GetthumbException $e)
{

	// If exception catched, create error image output
	
	$lines = explode("\n", $e->getMessage());
	
	$image = imagecreatetruecolor(300, 20 + count($lines) * 14);
	$color = imagecolorallocate($image, 255, 0, 0);
	imagestring($image, 2, 2, 2, 'getthumb exception', $color);
	$color = imagecolorallocate($image, 255, 255, 255);
	$y = 16;
	
	foreach ($lines as $line)
	{
		imagestring($image, 2, 2, $y, $line, $color);
		$y += 12;
	}
	$gt->imageHeaders('png');
	imagepng($image);
	imagedestroy($image);
	
}
