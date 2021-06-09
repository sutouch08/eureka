<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if(isset($_GET['getProductStock']) && isset($_GET['searchText']))
{
	$sc = array();
	$txt = trim($_GET['searchText']);

	$qr  = "SELECT pa.id_product_attribute AS id, pa.reference AS code, ";
	$qr .= "pd.product_name AS name ";
	$qr .= "FROM tbl_product_attribute AS pa ";
	$qr .= "LEFT JOIN tbl_product AS pd ON pa.id_product = pd.id_product ";
	$qr .= "LEFT JOIN tbl_color AS co ON pa.id_color = co.id_color ";
	$qr .= "LEFT JOIN tbl_size AS si ON pa.id_size = si.id_size ";
	$qr .= "WHERE pa.reference LIKE '{$txt}%' ";
	//$qr .= "OR pd.product_name LIKE '{$txt}%' ";
	$qr .= "ORDER BY pd.product_code ASC, co.color_code ASC, si.position ASC ";
	$qr .= "LIMIT 100";

	//echo $qr;
	$qs = dbQuery($qr);

	if(dbNumRows($qs) > 0)
	{
		$bf = new buffer();
		$cn = new cancle_zone();
		$pd = new product();
		$tr = new transfer();
		$st = new stock();
		$useSize = 1;

		while($rs = dbFetchObject($qs))
		{
			//---	stock in zone
			$stockLabel = '';

			//--- stock in buffer
			$bfQty = $bf->getStockInBuffer($rs->id);

			//--- stock in cancle zone
			$cnQty = $cn->getStockInCancle($rs->id);

			//--- stock in warehouse transfering
			$trQty = $tr->getStockInTransferTemp($rs->id);

			$stockLabel .= $bfQty == 0 ? '' : 'Buffer = '.number($bfQty).'<br/>';
			$stockLabel .= $cnQty == 0 ? '' : 'Cancle = '.number($cnQty).'<br/>';
			$stockLabel .= $trQty == 0 ? '' : 'Transfering = '.number($trQty).'<br/>';

			//--- จำนวนคงเหลือทั้งหมด
			$qty = $bfQty + $cnQty + $trQty;


			//---- get data from database
			$stock = $st->stockInZone($rs->id);

			if(dbNumRows($stock) > 0)
			{
				while($ra = dbFetchObject($stock))
				{
					$stockLabel .= $ra->name.' = '.number($ra->qty).' <br/>';
					$qty += $ra->qty;
				}

			}

			if($qty > 0)
			{
				$arr = array(
					'img' => '<img src="'.$pd->get_product_attribute_image($rs->id,$useSize).'" />',
					'pdCode' => $rs->code,
					'pdName' => $rs->name,
					'qty' => number($qty),
					'stockInZone' => $stockLabel
				);

				array_push($sc, $arr);
			}

		}
	}
	else
	{
		$arr = array(
			'nodata' => 'nocontent'
		);
		array_push($sc, $arr);
	}

	echo json_encode($sc);
}


if(isset($_GET['findOrder']) && isset($_GET['searchText']))
{
	$sc  = array();
	$txt = trim($_GET['searchText']);

	// $qr  = "SELECT od.product_code, od.qty, o.reference, ";
	// $qr .= "c.name AS customerName, em.first_name, em.last_name, s.name AS state ";
	// $qr .= "FROM tbl_order_detail AS od ";
	// $qr .= "LEFT JOIN tbl_order AS o ON od.id_order = o.id ";
	// $qr .= "LEFT JOIN tbl_order_user AS ou ON o.id = ou.id_order ";
	// $qr .= "LEFT JOIN tbl_employee AS em ON ou.id_user = em.id_employee ";
	// $qr .= "LEFT JOIN tbl_customer AS c ON o.id_customer = c.id ";
	// $qr .= "LEFT JOIN tbl_state AS s ON o.state = s.id ";
	// $qr .= "WHERE od.product_code LIKE '%".$txt."%' ";
	// $qr .= "AND o.state < 8 "; //--- เอาเฉพาะที่ยังไม่เปิดบิล
	// $qr .= "AND od.is_expired = 0 "; //---	เอาเฉพาะที่ยังไม่หมดอายุ
	// $qr .= "ORDER BY od.product_code ASC, o.reference ASC";

	$qr  = "SELECT od.product_reference AS product_code, od.product_qty AS qty, ";
	$qr .= "o.id_order, o.reference, c.company AS customerName, ";
	$qr .= "em.first_name, em.last_name, s.state_name AS state ";
	$qr .= "FROM tbl_order_detail AS od ";
	$qr .= "LEFT JOIN tbl_order AS o ON od.id_order = o.id_order ";
	$qr .= "LEFT JOIN tbl_employee AS em ON o.id_employee = em.id_employee ";
	$qr .= "LEFT JOIN tbl_customer AS c ON o.id_customer = c.id_customer ";
	$qr .= "LEFT JOIN tbl_order_state AS s ON o.current_state = s.id_order_state ";
	$qr .= "WHERE od.product_reference LIKE '{$txt}%' ";
	$qr .= "AND o.current_state < 8 ";
	$qr .= "AND od.valid_detail != 2 ";
	$qr .= "ORDER BY od.product_reference ASC, o.reference ASC";

	$qs = dbQuery($qr);

	if(dbNumRows($qs) > 0)
	{
		while($rs = dbFetchObject($qs))
		{
			$arr = array(
				'id_order' => $rs->id_order,
				'pdCode'    => $rs->product_code,
				'reference' => $rs->reference,
				'qty'       => number($rs->qty),
				'state'     => $rs->state,
				'cusName'   => $rs->customerName,
				'empName'   => $rs->first_name.' '.$rs->last_name
			);

			array_push($sc, $arr);
		}
	}
	else
	{
		$arr = array('nodata' => 'nocontent');
		array_push($sc, $arr);
	}

	echo json_encode($sc);
}



if( isset($_GET['checkBarcode']) && isset($_GET['barcode']) )
{
	$txt 	= trim($_GET['barcode']);
	$ds	= array();
	$qs 	= "SELECT id_product_attribute, reference, product_code, product_name, barcode FROM ";
	$qs 	.= "tbl_product_attribute JOIN tbl_product ON tbl_product_attribute.id_product = tbl_product.id_product ";
	$qs	.= "WHERE barcode LIKE '%".$txt."%'";
	$sql 	= dbQuery($qs);
	if(dbNumRows($sql) > 0)
	{
		$product = new product();
		while( $rs = dbFetchObject($sql) )
		{
			$arr = array(
								"img"			=> "<img src='".$product->get_product_attribute_image($rs->id_product_attribute,1)."' />",
								"barcode"	=> $rs->barcode,
								"product"		=> $rs->reference." : ".$rs->product_name,
								"style"			=> $rs->product_code
								);
			array_push($ds, $arr);
		}
	}
	else
	{
		$arr = array("nodata" =>"nocontent");
		array_push($ds, $arr);
	}
	echo json_encode($ds);
}

?>
