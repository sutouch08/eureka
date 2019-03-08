<?php

$billCode = $_POST['billCode'];
$date_add = dbDate($_POST['date_add']);
$id_customer = $_POST['id_customer'];
$id_employee = getCookie('user_id');
$remark = $_POST['remark'];
$id_zone = $_POST['id_zone'];
$qtys = $_POST['qty'];
$prices = $_POST['price'];
$sc = TRUE;

$order = new order();
$customer = new customer($id_customer);
$sale = new sale($customer->id_sale);
$cs = new return_order();
$stock = new stock();
$mv = new movement();
$zone = new zone($id_zone);
$product = new product();

$id_order = $order->getId($billCode);

  if(empty($qtys))
  {
    $sc = FALSE;
    $message = 'ไม่มีรายการรับคืน';
  }
  else
  {
    startTransection();
    $reference = $cs->getNewReference($date_add);
    $arr = array(
      'reference' => $reference,
      'order_code' => $billCode,
      'id_customer' => $id_customer,
      'id_employee' => getCookie('user_id'),
      'date_add' => $date_add,
      'remark' => $remark,
      'id_zone' => $id_zone,
      'isSave' => 1
    );

    //--- เพิ่มเอกสารรับคืน
    $id = $cs->add($arr);

    if($id == FALSE)
    {
      $sc = FALSE;
      $message = 'เพิ่มเอกสารรับคืนไม่สำเร็จ '.$cs->error;
    }
    else
    {
      foreach($qtys as $id_pa => $qty)
      {
        if($sc == FALSE)
        {
          break;
        }

        if($qty > 0)
        {
          $pd = $product->getDetail($id_pa);
          $price = $prices[$id_pa];

          //--- เตรียมข้อมูล
          $arr = array(
            'id_return_order' => $id,
            'id_product_attribute' => $pd->id_product_attribute,
            'product_code' => $pd->reference,
            'qty' => $qty,
            'price' => $price,
            'amount' => $price * $qty,
            'valid' => 1
          );

          //--- เพิ่มรายการรับคืนในเอกสาร
          if($cs->addDetail($arr) !== TRUE)
          {
            $sc = FALSE;
            $message = 'เพิ่มรายการไม่สำเร็จ';
          }

          //--- เพิ่มสต็อก
          if($pd->is_visual == 0)
          {
            if($stock->updateStockZone($id_zone, $pd->id_product_attribute, $qty) !== TRUE)
            {
              $sc = FALSE;
              $message = 'ปรับปรุงสต็อกไม่สำเร็จ';
            }
          }


          //--- บันทึก movement
          if($pd->is_visual == 0)
          {
            if($mv->move_in($reference, $zone->id_warehouse, $id_zone, $pd->id_product_attribute, $qty, $date_add) !== TRUE)
            {
              $sc = FALSE;
              $message = 'บันทึก movement ไม่สำเร็จ';
            }
          }


          $arr = array(
            'id_order' => 0,
            'reference' => $reference,
            'id_role' => 8,
            'id_customer' => $id_customer,
            'id_employee' => $id_employee,
            'id_sale' => $customer->id_sale,
            'id_product' => $pd->id_product,
            'id_product_attribute' => $pd->id_product_attribute,
            'product_name' => $pd->product_name,
            'product_reference' => $pd->reference,
            'barcode' => $pd->barcode,
            'product_price' => $price,
            'order_qty' => (-1 * $qty),
            'sold_qty' => (-1 * $qty),
            'reduction_percent' => 0.00,
            'reduction_amount' => 0.00,
            'discount_amount' => 0.00,
            'final_price' => $price,
            'total_amount' => ($price * $qty) * (-1),
            'cost' => (-1 * $pd->cost),
            'total_cost' => (-1) * ($qty * $pd->cost),
            'id_payment' => 0
          );

          if($order->sold_product($arr) !== TRUE)
          {
            $sc = FALSE;
            $message = 'บันทึกขายไม่สำเร็จ';
          }
        } //--- end if
      } //--- end foreach
    } //--- end if id === FALSE;

    if($sc === TRUE)
    {
      commitTransection();
    }
    else
    {
      dbRollback();
    }

    endTransection();

  } //--- end if empty qty;


echo $sc === TRUE ? $id : $message;

 ?>
