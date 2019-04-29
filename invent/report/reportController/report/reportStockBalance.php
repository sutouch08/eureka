<?php
	//-----------------------------  รายงานสินค้าคงเหลือ  -----------------//

	$pdOption 	= $_POST['pdOption'];
	$whOption	= $_POST['whOption'];
	$dateOption	= $_POST['dateOption'];
	$pdFrom		= $pdOption == 1 ? $_POST['pdFrom'] : FALSE;
	$pdTo		= $pdOption == 1 ? $_POST['pdTo'] : FALSE;
	$wh			= $whOption == 1 ? $_POST['wh'] : FALSE;
	$date 		= $dateOption == 1 ? dbDate($_POST['date']) : date('Y-m-d');
	$whList		= $whOption == 1 ? warehouseIn($wh) : warehouseIn($wh, TRUE);

	$pdQuery 	= $pdOption == 1 ? "AND product_code >= '".$pdFrom."' AND product_code <= '".$pdTo."' " : "";
	$whQuery	= $whOption == 1 && $whList !== FALSE ? "AND id_warehouse IN(".$whList.") " : '';

	if( $pdOption == 0 )
	{
		$qr = "SELECT pa.id_product_attribute, pa.barcode, pa.reference, pd.product_name, pa.cost ";
		$qr .= "FROM tbl_product_attribute AS pa ";
		$qr .= "JOIN tbl_product AS pd ON pa.id_product = pd.id_product ";
		$qr .= "LEFT JOIN tbl_color AS co ON pa.id_color = co.id_color ";
		$qr .= "LEFT JOIN tbl_size AS si ON pa.id_size = si.id_size ";
		$qr .= "ORDER BY pa.id_product ASC, co.color_code ASC, si.position ASC";
	}
	else
	{
		$qr = "SELECT pa.id_product_attribute, pa.barcode, pa.reference, pd.product_name, pa.cost ";
		$qr .= "FROM tbl_product_attribute AS pa ";
		$qr .= "JOIN tbl_product AS pd ON pa.id_product = pd.id_product ";
		$qr .= "LEFT JOIN tbl_color AS co ON pa.id_color = co.id_color ";
		$qr .= "LEFT JOIN tbl_size AS si ON pa.id_size = si.id_size ";
		$qr .= "WHERE pa.id_product != 0 ";
		$qr .= $pdQuery;
		$qr .= "ORDER BY pa.id_product ASC, co.color_code ASC, si.position ASC";
	}

	$qs = dbQuery($qr);
	$ds = array();
	if( dbNumRows($qs) > 0 )
	{
		$today	= date('Y-m-d');
		$n = 1;
		$total_qty = 0;
		$total_amount = 0;
		$product = new product();
		while( $rs = dbFetchObject($qs) )
		{
			$currentQty 	= $whOption == 0 ? $product->all_available_qty($rs->id_product_attribute) : $product->stock_qty_by_warehouse($rs->id_product_attribute, $whList);
			$moveQty 	= $product->move_qty_by_warehouse($rs->id_product_attribute, $whList);
			$cancleQty	= $product->cancle_qty_by_warehouse($rs->id_product_attribute, $whList);
			$bufferQty 	= $product->buffer_qty_by_warehouse($rs->id_product_attribute, $whList);
			$qty = $currentQty + $moveQty + $cancleQty + $bufferQty;
			if( $dateOption == 1 && $date < $today) //---- ถ้าดูย้อนหลัง
			{
				$today = date('Y-m-d H:i:s');
				$viewDate = fromDate(date("Y-m-d", strtotime("+1 day", strtotime($date) ) ) ); //---- วันที่คำนวน transection
				$transectionQty = getTransectionQty($rs->id_product_attribute, $whList, $viewDate, $today);
				$movement = ( $transectionQty['move_in'] - $transectionQty['move_out'] ) * (-1);  //---- (ยอดรับเข้า - ยอดจ่ายออก) * -1 เพื่อนำไปบวกกลับ
				$qty = $qty + $movement;
			}
			if( $qty != 0 )
			{
				$arr = array(
									"no"		=> $n,
									"barcode"	=> $rs->barcode,
									"reference"	=> $rs->reference,
									"product_name"	=> $rs->product_name,
									"cost"		=> number_format($rs->cost, 2),
									"qty"		=> number_format($qty),
									"amount"	=> number_format(($qty * $rs->cost), 2)
								);
				array_push($ds, $arr);
				$total_qty += $qty;
				$total_amount += ($qty * $rs->cost);
				$n++;
			}
		}
		$arr = array("total_qty" => number_format($total_qty), "total_amount" => number_format($total_amount, 2));
		array_push($ds, $arr);
	}
	else
	{
		$arr = array(
								"no"		=> '',
								"barcode"	=> '',
								"reference"	=> '',
								"product_name"	=> '',
								"cost"		=> '',
								"qty"		=>'',
								"amount"	=> ''
							);
		array_push($ds, $arr);
		$arr = array("total_qty" => number_format($total_qty), "total_amount" => number_format($total_amount, 2));
		array_push($ds, $arr);
	}
	echo json_encode($ds);
	?>
