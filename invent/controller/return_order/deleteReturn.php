<?php
$id = $_GET['id_return_order'];
$sc = TRUE;
$cs = new return_order($id);
$order = new order();
$movement = new movement();
$stock = new stock();
$product = new product();
$qs = $cs->getDetails($id);

startTransection();

if(dbNumRows($qs) > 0)
{
  //-- 1 unsold order
  //-- 2 delete movement
  //-- 3 update stock
  //-- 4 delete detail
  //--- loop until all detail deleted
  //-- 5 delete document


  while($rs = dbFetchObject($qs))
  {
    if($sc === FALSE)
    {
      break;
    }

    $pd = $product->getDetail($rs->id_product_attribute);

    if($rs->valid == 1)
    {

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

    } //--- end if valid

    //-- 4 un valid return detail
    if($cs->delete_item($rs->id) !== TRUE)
    {
      $sc = FALSE;
      $message = 'ลบรายการรับเข้าไม่สำเร็จ';
    }

  } //-- endwhile
}


if($sc === TRUE)
{
  if($cs->delete($cs->id) !== TRUE)
  {
    $sc = FALSE;
    $message = 'ลบเอกสารไม่สำเร็จ';
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

echo $sc === TRUE ? 'success' : $message;

 ?>
