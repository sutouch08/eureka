<?php
$id = $_GET['id_return_order'];
$sc = TRUE;
$cs = new return_order($id);
$order = new order();
$movement = new movement();
$stock = new stock();
$product = new product();
$qs = $cs->getDetails($id);

if(dbNumRows($qs) > 0 && $cs->isSave == 1)
{
  //-- 1 unsold order
  //-- 2 delete movement
  //-- 3 update stock
  //-- 4 update detail status
  //--- loop until all detail has updated
  //-- 5 update status return order

  startTransection();
  while($rs = dbFetchObject($qs))
  {
    if($sc === FALSE)
    {
      break;
    }

    if($rs->valid == 1)
    {
      $pd = $product->getDetail($rs->id_product_attribute);

      //-- 1 unsold product
      if($order->unSoldProductByReference($cs->reference, $rs->id_product_attribute) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบยอดขายไม่สำเร็จ';
      }

      //-- 2 delete movement
      if($movement->dropMovement($cs->reference, $rs->id_product_attribute) !== TRUE)
      {
        $sc = FALSE;
        $message = 'ลบ movement ไม่สำเร็จ';
      }

      //-- 3 update stock
      if($pd->is_visual == 0)
      {
        if($stock->updateStockZone($cs->id_zone, $rs->id_product_attribute, ($rs->qty * -1)) !== TRUE)
        {
          $sc = FALSE;
          $message = 'ตัดยอดสต็อกสินค้าไม่สำเร็จ';
        }
      }


      //-- 4 un valid return detail
      if($cs->unValidDetail($rs->id) !== TRUE)
      {
        $sc = FALSE;
        $message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
      }
    } //--- end if valid


  } //-- endwhile

  if($sc === TRUE)
  {
    if($cs->unSaveReturn($cs->id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'เปลี่ยนสถานะเอกสารไม่สำเร็จ';
    }
  }


  if($sc === TRUE)
  {
    commitTransection();
  }
  else
  {
    dbRollback();
  }

  endTransection();
}

echo $sc === TRUE ? 'success' : $message;

 ?>
