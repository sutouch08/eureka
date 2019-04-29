<?php

$cs = new consignment($id_order);
//----- หา id_zone ฝากขาย จาก tbl_order_consignment
$toZone		= getConsignmentIdZone( $id_order );
$zone 		= new zone($toZone);
if( $toZone === FALSE )
{
	$sc = FALSE;
	$message = "ไม่พบโซนปลายทาง";
}
else
{
	if( dbNumRows($qs) > 0 )
	{
		startTransection();
		$pd			= new product();
		$buffer = new buffer();
		$movement = new movement();
		while( $rs = dbFetchObject($qs) )
		{
			//set_time_limit(60);


			if($rs->is_visual == 0)
			{
					//---- ดึงรายการที่จัดมาไว้ก่อน
					$prepared = $buffer->getSumQty($id_order, $rs->id_product_attribute);

					//--- ถ้ามีการจัดสินค้ามา บันทึกข้อมูลลงตาราง order_consignment_detail
					if($prepared > 0)
					{
						$qty 			= $rs->product_qty >= $prepared ? $prepared : $rs->product_qty;
						$price		= $rs->product_price;

						$arr = array(
							'id_order_consignment' => $cs->id,
							'id_order' => $id_order,
							'id_product' => $rs->id_product,
							'id_product_attribute' => $rs->id_product_attribute,
							'product_code' => $rs->product_reference,
							'price' => $price,
							'qty' => $qty
						);

						if($cs->addDetail($arr) === FALSE)
						{
							$sc = FALSE;
							$message = 'บันทึกขายไม่สำเร็จ';
						}
					} //--- end if prepared > 0


				//---  ตัดยอดใน buffer
				$bf = $buffer->getDetails($id_order, $rs->id_product_attribute);

				//--- ยอดที่สั่ง
				$order_qty = $rs->product_qty;

				if(dbNumRows($bf) > 0)
				{
					while($rd = dbFetchObject($bf))
					{
						if($order_qty > 0)
						{
							$qty = $order_qty >= $rd->qty ? $rd->qty : $order_qty;

							//--- update buffer
							if($buffer->update($id_order, $rs->id_product_attribute, $rd->id_zone, ($qty * -1)) === FALSE)
							{
								$sc = FALSE;
								$message = 'Update buffer ไม่สำเร็จ';
							}

							//--- movement out move_out($reference, $id_warehouse, $id_zone, $id_pa, $qty, $date_upd)
							if($movement->move_out($order->reference, $rd->id_warehouse, $rd->id_zone, $rs->id_product_attribute, $qty, $order->date_add) === FALSE)
							{
								$sc = FALSE;
								$message = 'บันทึก Movement out ไม่สำเร็จ';
							}

							if($movement->move_in($order->reference, $zone->id_warehouse, $zone->id_zone, $rs->id_product_attribute, $qty, $order->date_add) === FALSE)
							{
								$sc = FALSE;
								$message = 'บันทึก Movement in ไม่สำเร็จ';
							}

							//--- ลดจำนวน
							$order_qty -= $qty;
						}
					} //---- end while
				}	//--- end if
			} //--- end if is_visual

			if($rs->is_visual == 1)
			{
				$arr = array(
					'id_order_consignment' => $cs->id,
					'id_order' => $id_order,
					'id_product' => $rs->id_product,
					'id_product_attribute' => $rs->id_product_attribute,
					'product_code' => $rs->product_reference,
					'price' => $price,
					'qty' => $qty
				);

				if($cs->addDetail($arr) === FALSE)
				{
					$sc = FALSE;
					$message = 'บันทึกขายไม่สำเร็จ';
				}

			}

		} ///---- end while

		if( $sc === TRUE )
		{
			/// เคลียร์ buffer กรณีจัดขาดจัดเกินหรือแก้ไขออเดอร์ที่จัดแล้วเหลือสินค้าที่จัดแล้วค้างอยู่ที่ Buffer ให้ย้ายไปอยู่ใน cancle แทน
			clear_buffer($id_order);
			commitTransection();
		}
		else
		{
			dbRollback();
		}

		endTransection();
	} //-- end if

} //-- end if


?>
