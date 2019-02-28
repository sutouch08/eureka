<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if(isset($_GET['searchValidBill']))
{
  $txt = $_GET['term'];
  $sc = array();
  $qr  = "SELECT reference FROM tbl_order_detail_sold ";
  $qr .= "WHERE reference LIKE '%".$txt."%' ";
  $qr .= "AND id_role = 1 ";
  $qr .= "GROUP BY reference ";
  $qr .= "ORDER BY reference ASC LIMIT 50";
  $qs = dbQuery($qr);

  if(dbNumRows($qs) > 0)
  {
    while($rs = dbFetchObject($qs))
    {
      $sc[] = $rs->reference;
    }
  }
  else
  {
    $sc[] = 'ไม่พบข้อมูล';
  }

  echo json_encode($sc);
}



if(isset($_GET['getBillDetail']))
{
  include 'return_order/getBillDetail.php';
}


if(isset($_GET['addNew']))
{
  include 'return_order/addNew.php';
}


if(isset($_GET['cancleReturn']))
{
  include 'return_order/cancleReturn.php';
}




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
