<?php
//---- Begin process
if( dbNumRows($qs) > 0 )
{
	startTransection();

	$buffer 	= new buffer();
	$movement = new movement();
	$product 	= new product();
	while( $rs = dbFetchObject($qs) )
	{
		set_time_limit(60);
		//--- id_product_attribute
		$id_pa		= $rs->id_product_attribute;

		//---- ถ้าเป็นสินค้าที่นับสต็อก บันทึกขาย update buffer บันทึก movement
		if($rs->is_visual == 0)
		{
			//---- ดึงรายการที่จัดมาไว้ก่อน
			$prepared = $buffer->getSumQty($id_order, $rs->id_product_attribute);

			//--- ถ้ามีการจัดสินค้ามา ทำการบันทึกขาย
			if($prepared > 0)
			{
				$qty 			= $rs->product_qty >= $prepared ? $prepared : $rs->product_qty;
				$price		= $rs->product_price;
				$p_dis		= $rs->reduction_percent;
				$a_dis		= $rs->reduction_amount;
				$discount_amount	= $p_dis > 0 ? $qty * ($price * ($p_dis * 0.01)) : $qty * $a_dis;
				$final_price	= $p_dis > 0 ? $price - ($price * ($p_dis * 0.01)) : $price - $a_dis;
				$total_amount	= $qty * $final_price;
				$cost = $product->get_product_cost($rs->id_product_attribute);
				$total_cost	= $qty * $cost;

				$arr = array(
					'id_order' => $id_order,
					'reference' => $order->reference,
					'id_role' => $order->role,
					'id_customer' => $order->id_customer,
					'id_employee' => $order->id_employee,
					'id_sale' => $order->id_sale,
					'id_product' => $rs->id_product,
					'id_product_attribute' => $rs->id_product_attribute,
					'product_name' => $rs->product_name,
					'product_reference' => $rs->product_reference,
					'barcode' => $rs->barcode,
					'product_price' => $price,
					'order_qty' => $rs->product_qty,
					'sold_qty' => $qty,
					'reduction_percent' => $rs->reduction_percent,
					'reduction_amount' => $rs->reduction_amount,
					'discount_amount' => $discount_amount,
					'final_price' => $final_price,
					'total_amount' => $total_amount,
					'date_upd' => $order->date_add,
					'cost' => $cost,
					'total_cost' => $total_cost
				);

				if($order->sold_product($arr) === FALSE)
				{
					$sc = FALSE;
					$message = 'บันทึกขายไม่สำเร็จ : '.$order->error;
				}
			} //--- end if prepared > 0

			//---  ตัดยอดใน buffer
			$bf = $buffer->getDetails($id_order, $id_pa);

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
						if($buffer->update($id_order, $id_pa, $rd->id_zone, ($qty * -1)) === FALSE)
						{
							$sc = FALSE;
							$message = 'Update buffer ไม่สำเร็จ';
						}

						//--- movement out move_out($reference, $id_warehouse, $id_zone, $id_pa, $qty, $date_upd)
						if($movement->move_out($order->reference, $rd->id_warehouse, $rd->id_zone, $id_pa, $qty, $order->date_add) === FALSE)
						{
							$sc = FALSE;
							$message = 'Update Movement ไม่สำเร็จ';
						}

						//--- ลดจำนวน
						$order_qty -= $qty;
					}
				} //---- end while
			}	//--- end if
		}

		//--- ถ้าเป้นสินค้าไม่นับสต็อก บันทึกขายอย่างเดียว
		if($rs->is_visual == 1)
		{
			$cost = $product->get_product_cost($rs->id_product_attribute);
			$arr = array(
				'id_order' => $id_order,
				'reference' => $order->reference,
				'id_role' => $order->role,
				'id_customer' => $order->id_customer,
				'id_employee' => $order->id_employee,
				'id_sale' => $order->id_sale,
				'id_product' => $rs->id_product,
				'id_product_attribute' => $rs->id_product_attribute,
				'product_name' => $rs->product_name,
				'product_reference' => $rs->product_reference,
				'barcode' => $rs->barcode,
				'product_price' => $price,
				'order_qty' => $rs->product_qty,
				'sold_qty' => $rs->product_qty,
				'reduction_percent' => $rs->reduction_percent,
				'reduction_amount' => $rs->reduction_amount,
				'discount_amount' => $rs->discount_amount,
				'final_price' => $rs->final_price,
				'total_amount' => $rs->total_amount,
				'date_upd' => $order->date_add,
				'cost' => $cost,
				'total_cost' => $rs->product_qty * $cost
			);

			if($order->sold_product($arr) === FALSE)
			{
				$sc = FALSE;
				$message = 'บันทึกขายไม่สำเร็จ : '.$order->error;
			}
		}

	} //-- end while


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
} //---- End process

?>
