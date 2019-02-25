<?php
class bill
{

  public function __construct()
  {

  }


  //---- สรุปยอดจำนวนสินค้าที่สั่งและจัด
  public function getTotalBillQty($id_order)
  {
    $amount = 0;
		$qr  = "SELECT o.id_product_attribute, o.product_qty AS order_qty, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_buffer WHERE id_order = ".$id_order." ";
    $qr .= "AND id_product_attribute = o.id_product_attribute) AS prepared ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "WHERE o.id_order = ".$id_order." GROUP BY o.id_product_attribute ";
    $qr .= "HAVING prepared IS NOT NULL";

		$qs = dbQuery($qr);

		if(dbNumRows($qs) > 0)
		{
			while($rs = dbFetchObject($qs))
			{
				//--- ถ้ายอดจัดมามากกว่ายอดที่สั่ง ให้ใช้ยอดสั่ง ถ้าไม่ใช่ให้ใช้ยอดจัด
				$qty = $rs->prepared > $rs->order_qty ? $rs->order_qty : $rs->prepared;
				$amount += $qty;
			}
		}

		return $amount;
  }


  //------------- สำหรับใช้ในการบันทึกขาย ---------//
  //--- รายการสั้งซื้อ รายการจัดสินค้า รายการตรวจสินค้า
  //--- เปรียบเทียบยอดที่มีการสั่งซื้อ และมีการตรวจสอนค้า
  //--- เพื่อให้ได้ยอดที่ต้องเปิดบิล บันทึกขายจริงๆ
  //--- ผลลัพธ์จะไม่ได้ยอดที่มีการสั่งซื้อแต่ไม่มียอดตรวจ หรือ มียอดตรวจแต่ไม่มียอดสั่งซื้อ (กรณีมีการแก้ไขออเดอร์)
  public function getBillDetail($id_order)
  {
    $qr  = "SELECT o.id_order_detail AS id, ";
    $qr .= "o.id_product_attribute AS id_pa, ";
    $qr .= "o.product_reference AS code, ";
    $qr .= "o.product_name AS name, ";
    $qr .= "o.product_qty AS order_qty, ";
    $qr .= "o.product_price AS price, ";
    $qr .= "o.reduction_precent AS p_dis, ";
    $qr .= "o.reduction_amount AS a_dis, ";
    $qr .= "(o.total_amount/o.order_qty) AS final_price, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_buffer WHERE id_order = ".$id_order." ";
    $qr .= "AND id_product_attribute = o.id_pa) AS prepared ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "WHERE o.id_order = ".$id_order." GROUP BY o.id_pa ";
    $qr .= "HAVING prepared IS NOT NULL";
    
    return dbQuery($qr);
  }


  //------------------ สำหรับแสดงยอดที่มีการบันทึกขายไปแล้ว -----------//
  //--- รายการสั้งซื้อ รายการจัดสินค้า รายการตรวจสินค้า
  //--- เปรียบเทียบยอดที่มีการสั่งซื้อ และมีการตรวจสอนค้า
  //--- เพื่อให้ได้ยอดที่ต้องเปิดบิล บันทึกขายจริงๆ
  //--- ผลลัพธ์จะได้ยอดสั่งซื้อเป็นหลัก หากไม่มียอดตรวจ จะได้ยอดตรวจ เป็น NULL
  //--- กรณีสินค้าเป็นสินค้าที่ไม่นับสต็อกจะบันทึกตามยอดที่สั่งมา
  public function getBilledDetail($id_order)
  {
    $qr = "SELECT o.id_product, o.product_code, o.product_name, o.qty AS order_qty, o.isCount, ";
    $qr .= "o.price, o.discount, ";
    $qr .= "(o.discount_amount / o.qty) AS discount_amount, ";
    $qr .= "(o.total_amount/o.qty) AS final_price, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_prepare WHERE id_order = ".$id_order." AND id_product = o.id_product) AS prepared, ";
    $qr .= "(SELECT SUM(qty) FROM tbl_qc WHERE id_order = ".$id_order." AND id_product = o.id_product) AS qc ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "WHERE o.id_order = ".$id_order." GROUP BY o.id_product";

    return dbQuery($qr);
  }


  public function getNonCountBillDetail($id_order)
  {
    $qr  = "SELECT o.id, o.id_product, o.product_code, o.product_name, o.qty AS order_qty, o.isCount, ";
    $qr .= "o.price, o.discount, ";
    $qr .= "(o.discount_amount / o.qty) AS discount_amount, ";
    $qr .= "(o.total_amount/o.qty) AS final_price ";
    $qr .= "FROM tbl_order_detail AS o ";
    $qr .= "JOIN tbl_product AS p ON o.id_product = p.id ";
    $qr .= "WHERE o.id_order = ".$id_order." ";
    $qr .= "AND o.isCount = 0 ";

    return dbQuery($qr);
  }

} //--- end class


 ?>
