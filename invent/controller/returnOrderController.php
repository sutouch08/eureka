<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

if(isset($_GET['deleteReturn']))
{
  include 'return_order/deleteReturn.php';
}




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





if(isset($_GET['saveReturnEdit']))
{
  include 'return_order/saveEdit.php';
}




if(isset($_GET['updateHeader']))
{
  $sc = TRUE;
  $id = $_POST['id_return_order'];
  $date_add = dbDate($_POST['date_add']);
  $order_code = $_POST['order_code'];
  $id_customer = $_POST['id_customer'];
  $id_employee = getCookie('user_id');
  $remark = $_POST['remark'];

  $arr = array(
    'order_code' => $order_code,
    'id_customer' => $id_customer,
    'id_employee' => $id_employee,
    'date_add' => $date_add,
    'remark' => $remark
  );

  $cs = new return_order();

  if($cs->update($id, $arr) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ปรับปรุงข้อมูลไม่สำเร็จ : '.$cs->error;
  }

  echo $sc === TRUE ? 'success' : $message;
}




if(isset($_GET['updateZone']))
{
  $sc = TRUE;
  $id = $_POST['id_return_order'];
  $id_zone = $_POST['id_zone'];
  $cs = new return_order();

  $arr = array('id_zone' => $id_zone);

  if($cs->update($id, $arr) === FALSE)
  {
    $sc = FALSE;
    $message = $cs->error;
  }

  echo $sc === TRUE ? 'success' : $message;
}




if(isset($_GET['cancleReturn']))
{
  include 'return_order/cancleReturn.php';
}



if(isset($_GET['unSaveReturn']))
{
  include 'return_order/unSaveReturn.php';
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
