<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

if(isset($_GET['clearFilter']))
{
  deleteCookie('sReference');
  deleteCookie('sOrderCode');
  deleteCookie('sCustomer');
  deleteCookie('fromDate');
  deleteCookie('toDate');
  echo 'done';
}

 ?>
