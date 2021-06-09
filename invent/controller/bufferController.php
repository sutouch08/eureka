<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

if( isset( $_GET['clearFilter'] ) )
{
	$cookie = array(
		's_ref',
		's_product'
	);

	foreach( $cookie as $name)
	{
		deleteCookie($name);
	}
	
	echo 'success';
}



?>
