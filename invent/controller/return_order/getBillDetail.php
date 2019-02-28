<?php

$code = $_GET['reference'];
$order = new order();
$cs = $order->getDataByReference($code);
if($cs != FALSE)
{
  $ods = new order_sold();
  $cus = new customer();
  $emp = new employee();
  $sc = array(
    'customerName' => $cus->getName($cs->id_customer),
    'id_customer' => $cs->id_customer,
    'empName' => $emp->getName($cs->id_employee),
    'payment' => 'เครดิต',
    'data' => ''
  );

  $ds = array();

  $qs = $ods->getData($cs->id_order);

  if(dbNumRows($qs) > 0)
  {
    $no = 1;
    $totalQty = 0;
    while($rs = dbFetchObject($qs))
    {
      $arr = array(
        'no' => $no,
        'id' => $rs->id_order_detail_sold,
        'barcode' => $rs->barcode,
        'product' => $rs->product_reference.' : '.$rs->product_name,
        'price' => $rs->final_price,
        'qty' => $rs->sold_qty
      );

      array_push($ds, $arr);
      $totalQty += $rs->sold_qty;
      $no++;
    }

    array_push($ds, array('totalQty' => $totalQty));
  }
  else
  {
    array($ds, array('nodata' => 'nodata'));
  }

  $sc['data'] = $ds;
  echo json_encode($sc);
}
else
{
  echo 'ไม่พบเลขที่เอกสารในระบบ';
}


 ?>
