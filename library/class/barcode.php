<?php
include "../config.php";
include "../functions.php";

$codeType = getConfig('BARCODE_TYPE');

$version = phpversion();

if($version > 6)
{
	$barcode_file = $codeType.'.php';
	include 'barcode/PHP7/code/'.$barcode_file;
}
else
{
	include 'barcode/barcode_v5.php';
}
