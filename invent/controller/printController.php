<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";
include '../function/print_helper.php';


//--- Print return order
if(isset($_GET['printReturnOrder']))
{
  include '../print/return_order/print_return_detail.php';
}

 ?>
