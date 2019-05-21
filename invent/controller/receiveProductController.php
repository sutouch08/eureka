<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";

/*********************   New Code ***********************/

//////////////// ลบเอกสาร /////////////////
if( isset( $_GET['delete_doc'] ) && isset( $_GET['id_receive_product'] ) )
{
	$id = $_GET['id_receive_product'];
	$sc = TRUE;
	$rd	= new receive_product($id);

	startTransection();

	//--- รายการทั้งหมดใน เอกสารนี้
	$qs = $rd->getDetails($id);

	if(dbNumRows($qs) > 0)
	{
		$stock = new stock();
		$movement = new movement();
		$po = new po($rd->id_po);

		while($rs = dbFetchObject($qs))
		{
			//--- check ว่ารายการนี้ บันทึกแล้วหรือยัง
			if($rs->status == 1)
			{
				//--- delete movement
				if($movement->dropMoveIn($rd->reference, $rd->id_zone, $rs->id_product_attribute) !== TRUE)
				{
					$sc = FALSE;
					$message = 'ลบ Movement ไม่สำเร็จ';
				}

				//--- ลดยอดในโซนที่รับเข้า
				if($stock->updateStockZone($rd->id_zone, $rs->id_product_attribute, $rs->qty * -1) !== TRUE)
				{
					$sc = FALSE;
					$message = 'ตัดยอดจากโซนรับเข้าไม่สำเร็จ';
				}

				//---- Delete product received item
				if($rd->delete_item($rs->id_receive_product_detail) !== TRUE)
				{
					$sc = FALSE;
					$message = 'ลบรายการไม่สำเร็จ';
				}

				//--- Update PO detail received
				if($rd->id_po != 0)
				{
					if($po->receive_item($rd->id_po, $rs->id_product_attribute, $rs->qty * -1) !== TRUE)
					{
						$sc = FALSE;
						$message = 'ปรับปรุงยอดรับในใบสั่งซื้อไม่สำเร็จ';
					}
				}

			}
			else
			{
				if($rd->delete_item($rs->id_receive_product_detail) !== TRUE)
				{
					$sc = FALSE;
					$message = 'ลบรายการไม่สำเร็จ';
				}
			} //--- end if saved

		} //--- end while

		if($sc === TRUE)
		{
			if($rd->id_po != 0 && $po->isClosed($rd->id_po) === TRUE)
			{
				if($po->unCloseDetail($rd->id_po) === FALSE)
				{
					$sc = FALSE;
					$message = 'ย้อนสถานะรายการในใบสั่งซื้อไม่สำเร็จ';
				}

				if($po->unClosePO($rd->id_po) === FALSE)
				{
					$sc = FALSE;
					$message = 'ย้อนสถานะใบสั่งซื้อไม่สำเร็จ';
				}
			}
		}

	} //--- end if dbNumRows

	if($sc === TRUE)
	{
		if($rd->delete($id) !== TRUE)
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
}




if(isset($_GET['updateReceiveProduct']))
{
	$zone = new zone($_POST['id_zone']);
	$id = $_POST['id_receive_product'];
	$ds = array(
		'id_supplier' => $_POST['id_supplier'],
		'invoice' => $_POST['invoice'],
		'po_reference' => $_POST['po_reference'],
		'id_po' => $_POST['id_po'],
		'id_warehouse' => $zone->id_warehouse,
		'id_zone' => $_POST['id_zone'],
		'remark' => $_POST['remark'],
		'date_add' => dbDate($_POST['date_add']),
		'id_employee' => getCookie('user_id')
	);

	$rp = new receive_product();

	$rs = $rp->update($id, $ds);

	echo $rs === TRUE ? 'success' : 'fail';
}





if(isset($_GET['getPoDetail']))
{
	$id_po = $_GET['id_po'];
	$po = new po();

	$qs = $po->getPoBacklog($id_po);

	if(dbNumRows($qs) > 0)
	{
		$ds = array();
		$no = 1;
		while($rs = dbFetchObject($qs))
		{
			$qty = ($rs->qty - $rs->received);
			$qty = $qty > 0 ? $qty : '';
			$arr = array(
				'no' => $no,
				'id_pd' => $rs->id_product,
				'id_pa' => $rs->id_product_attribute,
				'pdCode' => $rs->reference,
				'pdName' => $rs->product_name,
				'qty' => $qty
			);
			array_push($ds, $arr);
			$no++;
		}

		echo json_encode($ds);
	}
	else
	{
		echo 'No data found !';
	}
}




if(isset($_GET['addItems']))
{
	$sc = TRUE;
	$id = $_POST['id_receive_product'];
	$items = json_decode($_POST['items']);
	$id_emp = getCookie('user_id');

	if(!empty($items))
	{
		$rd = new receive_product();

		startTransection();

		foreach($items as $item)
		{
			if($sc === FALSE)
			{
				break;
			}

			$ds = array(
				'id_receive_product' => $id,
				'id_product' => $item->id_product,
				'id_product_attribute' => $item->id_product_attribute,
				'qty' => $item->qty,
				'id_employee' => $id_emp
			);

			if($rd->isExists($id, $item->id_product_attribute) === TRUE)
			{
				if($rd->updateItem($ds) === FALSE)
				{
					$sc = FALSE;
					$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
				}
			}
			else
			{
				if($rd->addNewItem($ds) === FALSE)
				{
					$sc = FALSE;
					$message = 'เพิ่มข้อมูลไม่สำเร็จ';
				}
			}

		} //--- end foreach

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

}





if( isset( $_GET['removeItem']) && isset($_POST['id_receive_product']))
{
	$id_rp = $_POST['id_receive_product'];
	$id_pa = $_POST['id_product_attribute'];

	$qr  = "DELETE FROM tbl_receive_product_detail ";
	$qr .= "WHERE id_receive_product = ".$id_rp." " ;
	$qr .= "AND id_product_attribute = ".$id_pa." ";
	$qr .= "AND status = 0 ";

	$qs = dbQuery($qr);

	echo dbAffectedRows();
}




if(isset($_GET['removeAllItems']) && isset($_GET['id_receive_product']))
{
	$sc = TRUE;
	$id = $_GET['id_receive_product'];
	$rp = new receive_product();

	if($rp->deleteAllDetails($id) !== TRUE)
	{
		$sc = FALSE;
		$message = 'ลบรายการไม่สำเร็จ';
	}

	echo $sc === TRUE ? 'success' : $message;
}






if( isset($_GET['unSaveRecieved']) && isset($_POST['id_receive_product']))
{
	$sc = TRUE;
	$id = $_POST['id_receive_product'];
	$rd = new receive_product($id);
	$stock = new stock();
	$po = new po();
	$movement = new movement();
	$qs = $rd->getDetails($id);

	if(dbNumRows($qs) > 0)
	{
		startTransection();
		while($rs = dbFetchObject($qs))
		{
			if($sc === FALSE)
			{
				break;
			}

			//--- delete movement
			if($movement->dropMoveIn($rd->reference, $rd->id_zone, $rs->id_product_attribute) !== TRUE)
			{
				$sc = FALSE;
				$message = 'ลบ Movement ไม่สำเร็จ';
			}

			//--- ลดยอดในโซนที่รับเข้า
			if($stock->updateStockZone($rd->id_zone, $rs->id_product_attribute, $rs->qty * -1) !== TRUE)
			{
				$sc = FALSE;
				$message = 'ตัดยอดจากโซนรับเข้าไม่สำเร็จ';
			}

			//--- Update PO detail received
			if($rd->id_po != 0)
			{
				if($po->receive_item($rd->id_po, $rs->id_product_attribute, $rs->qty * -1) !== TRUE)
				{
					$sc = FALSE;
					$message = 'ปรับปรุงยอดรับในใบสั่งซื้อไม่สำเร็จ';
				}
			}



			if($rd->change_item_status($rs->id_receive_product_detail, 0) === FALSE)
			{
				$sc = FALSE;
				$message = 'เปลี่ยนสถานะรายการไม่สำเร็จ';
			}
		}

		if($sc === TRUE)
		{
			if($rd->change_status($rd->id_receive_product, 0) === FALSE)
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
}



if(isset($_GET['getApprover']))
{
	$pwd = $_GET['s_key'];
	$s_key = md5(trim($pwd));
	$qr  = "SELECT em.id_employee FROM tbl_employee AS em ";
	$qr .= "JOIN tbl_access AS ac ON em.id_profile = ac.id_profile ";
	$qr .= "WHERE ac.id_tab = 49 ";
	$qr .= "AND (ac.add = 1 OR ac.edit = 1 OR ac.delete = 1) ";
	$qr .= "AND em.s_key = '".$s_key."' ";

	$qs = dbQuery($qr);
	if(dbNumRows($qs) > 0)
	{
		$rs = dbFetchObject($qs);
		echo $rs->id_employee;
	}
	else
	{
		echo 'invalid';
	}
}



if(isset($_GET['saveReceive']))
{
	$sc = TRUE;
	if($_POST['id_zone'] == '' OR $_POST['id_zone'] == 0)
	{
		echo 'โซนไม่ถูกต้อง';
		exit();
	}

	$zone = new zone($_POST['id_zone']);
	$id = $_POST['id_receive_product'];
	$id_po = $_POST['id_po'] == 0 ? '' : $_POST['id_po'];
	$ds = array(
		'id_supplier' => $_POST['id_supplier'],
		'invoice' => $_POST['invoice'],
		'po_reference' => $_POST['po_reference'],
		'id_po' => $_POST['id_po'],
		'id_warehouse' => $zone->id_warehouse,
		'id_zone' => $_POST['id_zone'],
		'remark' => $_POST['remark'],
		'date_add' => dbDate($_POST['date_add']),
		'id_employee' => getCookie('user_id')
	);

	$rd = new receive_product();

	if($rd->update($id, $ds) !== TRUE)
	{
		echo 'ปรับปรุงเอกสารไม่สำเร็จ';
		exit();
	}

	//---- update ข้อมูลตัวแปรหลังจาก update ข้อมูล
	$rd->get_data($id);

	$stock = new stock();
	$po = new po($id_po);
	$movement = new movement();


	startTransection();

	$qs = $rd->getDetails($id);

	if(dbNumRows($qs) > 0)
	{
		if($id_po != '')
		{
			//--- update status po
			if($po->valid != 1)
			{
				$po->update_status($rd->id_po, 2);
			}
		}


		while($rs = dbFetchObject($qs))
		{
			//--- ถ้าเจอข้อผิดพลาดออกจาก loop ทันที
			if($sc === FALSE)
			{
				break;
			}

			if($rs->status == 0)
			{
				//--- เพิ่มสต็อก
				if($stock->updateStockZone($zone->id_zone, $rs->id_product_attribute, $rs->qty) !== TRUE)
				{
					$sc = FALSE;
					$message = 'เพิ่มสต็อกเข้าโซนไม่สำเร็จ';
				}

				//--- insert movement
				if($movement->move_in($rd->reference, $rd->id_warehouse, $rd->id_zone, $rs->id_product_attribute, $rs->qty, dbDate($rd->date_add, TRUE)) !== TRUE)
				{
					$sc = FALSE;
					$message = 'บันทึก movement ไม่สำเร็จ';
				}

				//--- update po detail
				if($id_po != '')
				{
					if($po->receive_item($rd->id_po, $rs->id_product_attribute, $rs->qty) === FALSE)
					{
						$sc = FALSE;
						$message = 'ปรับปรุงยอดรับในใบสั่งซื้อไม่สำเร็จ';
					}
				}


				//--- ถ้ารับครบแล้วปิดรายการไปเลย
				$po->validReceivedItem($rd->id_po, $rs->id_product_attribute);

				//--- เปลี่นสถานะรายการรับเข้า
				if($rd->change_item_status($rs->id_receive_product_detail, 1) !== TRUE)
				{
					$sc = FALSE;
					$message = 'เปลี่ยนสถานะรายการรับเข้าไม่สำเร็จ';
				}

				// if($sc === TRUE && $approver != 0)
				// {
				// 	$rd->updateApprover($id, $approver);
				// }

			} //--- end if
		} //--- end while

		//--- เช็คถ้ารับครบ po แล้วทุกรายการให้ปิด po
		if($sc === TRUE)
		{
			if($id_po != '')
			{
				$po->validPo($rd->id_po);
			}

			if($rd->change_status($id, 1) !== TRUE)
			{
				$sc = FALSE;
				$message = 'เปลี่ยนสถานะใบรับสินค้าไม่สำเร็จ';
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
	} //-- end if

	endTransection();

	echo $sc === TRUE ? 'success' : $message;
}


/****************** End New code  *********************/



if( isset( $_GET['save_edit'] ) && isset( $_POST['id_receive_product'] ) )
{
	$rd	= new receive_product();
	$rs	= $rd->save_add($_POST['id_receive_product']);
	if($rs)
	{
		echo "success";
	}else{
		echo "fail";
	}
}







if( isset( $_GET['delete_item'] ) && isset( $_POST['id_receive_product_detail'] ) )
{
	$rd 	= new receive_product();
	$rs	= $rd->delete_item($_POST['id_receive_product_detail']);
	if($rs)
	{
		echo "success";
	}else{
		echo "fali";
	}
}






if( isset( $_GET['sum_item'] ) && isset( $_POST['id_receive_product'] ) )
{
	$rd 	= new receive_product();
	$qs 	= $rd->get_items($_POST['id_receive_product']);
	if(dbNumRows($qs) > 0 )
	{
		$data = array();
		$n = 1;
		while($rs = dbFetchArray($qs) )
		{
			$arr = array(
						"id"						=>$rs['id_receive_product_detail'],
						"no"					=> $n,
						"product_code" 	=> get_product_reference($rs['id_product_attribute']),
						"product_name" 	=> get_product_name($rs['id_product']),
						"zone_name"		=> get_zone($rs['id_zone']),
						"qty"					=> $rs['qty'],
						"status"				=> isActived($rs['status'])
						);
			array_push($data, $arr);
			$n++;
		}
		echo json_encode($data);
	}else{
		echo "fail";
	}
}





if( isset( $_GET['add_item'] ) && isset( $_POST['id_receive_product'] ) )
{
	$product		= new product();
	$arr			= $product->check_barcode(trim($_POST['barcode']));
	if($arr['id_product_attribute'] != 0 )
	{
		$id_pro		= $product->getProductId($arr['id_product_attribute']);
		$qty			= $_POST['qty'] * $arr['qty'];
		$rd			= new receive_product();
		$data			= array(
								"id_receive_product"	=> $_POST['id_receive_product'],
								"id_product"				=> $id_pro,
								"id_po"					=> $_POST['id_po'],
								"id_product_attribute"	=> $arr['id_product_attribute'],
								"qty"						=> $qty,
								"id_warehouse"			=> get_warehouse_by_zone($_POST['id_zone']),
								"id_zone"					=> $_POST['id_zone'],
								"id_employee"			=> $_COOKIE['user_id'],
								"date_add"				=> date("Y-m-d")
							);
		$rs 	= $rd->add_item($data);
		if($rs)
		{
			if($rs =="aa")
			{
				$datax = array(
								"product_code" 	=> get_product_reference($arr['id_product_attribute']),
								"product_name" 	=> get_product_name($id_pro),
								"zone_name"		=> get_zone($_POST['id_zone']),
								"qty"					=> $qty
								);
				echo json_encode($datax);
			}
			else
			{
				echo "not_in_po";
			}
		}
		else
		{
			echo "fail";
		}
	}
	else
	{
		echo "barcode_fail";
	}
}






if( isset( $_GET['update'] ) && isset( $_GET['id_receive_product'] ) )
{
	$id		= $_GET['id_receive_product'];
	$rd	= new receive_product();
	$data	= array(
				"invoice"				=> $_POST['invoice'],
				"po_reference"		=> $_POST['po_reference'],
				"id_po"				=> $_POST['id_po'],
				"id_employee"		=> $_POST['id_employee'],
				"date_add"			=> dbDate($_POST['date_add']),
				"remark"				=> $_POST['remark']
				);
	$rs	= $rd->update($id, $data);
	if($rs)
	{
		echo "success";
	}else{
		echo "fail";
	}
}





if( isset( $_GET['add_new']) && isset( $_POST['id_supplier'] ) )
{
	$rd	= new receive_product();
	$zone = new zone($_POST['id_zone']);
	$data	= array(
				"reference"			=> $rd->get_new_reference(dbDate($_POST['date_add'])),
				"id_supplier"		=> $_POST['id_supplier'],
				"invoice"				=> $_POST['invoice'],
				"po_reference"	=> $_POST['po_reference'],
				"id_po"				  => $_POST['id_po'],
				"id_warehouse"	=> $zone->id_warehouse,
				"id_zone"				=> $_POST['id_zone'],
				"id_employee"		=> getCookie('user_id'),
				"date_add"			=> dbDate($_POST['date_add']),
				"remark"				=> $_POST['remark']
				);

	$rs	= $rd->add($data);
	if($rs)
	{
		echo $rs;
	}else{
		echo "เพิ่มเอกสารไม่สำเร็จ";
	}
}






if( isset($_GET['get_zone'] ) && isset( $_POST['barcode'] ) )
{
	$barcode = trim($_POST['barcode']);
	$qs = dbQuery("SELECT id_zone, zone_name FROM tbl_zone WHERE barcode_zone = '".$barcode."' LIMIT 1 ");
	if(dbNumRows($qs) == 1 )
	{
		list($id, $name) = dbFetchArray($qs);
		echo $id." : ".$name;
	}else{
		echo "fail";
	}
}




if(isset($_GET['printBarcode']) && isset($_GET['id_receive_product']))
{
	include '../function/print_helper.php';
	include '../print/receive_product/print_receive_barcode.php';
}



if( isset( $_GET['print'] ) && isset( $_GET['id_receive_product'] ) )
{
	include '../function/print_helper.php';
	include '../print/receive_product/print_received.php';
}





if( isset( $_GET['get_received_product'] ) && isset( $_POST['id_received_product'] ) )
{
	$id 	= $_POST['id_received_product'];
	$re 	= new receive_product($id);
	$data = array();
	$arr 	= array(
						"id"				=> $re->id_receive_product,
						"reference" 	=> $re->reference,
						"date_add"	=> thaiDate($re->date_add),
						"invoice"		=> $re->invoice,
						"po_reference"	=> $re->po_reference,
						"employee"		=> employee_name($re->id_employee),
						"remark"			=> $re->remark
						);
	array_push($data, $arr);

	$no = 1;
	$total_qty = 0;
	$qs = $re->get_items($id);
	while($rs = dbFetchArray($qs) )
	{
		$arr = array(
						"no"						=> $no,
						"product_reference" 	=> get_product_reference($rs['id_product_attribute']),
						"product_name"			=> get_product_name($rs['id_product']),
						"zone"						=> get_zone($rs['id_zone']),
						"qty"						=> number_format($rs['qty']),
						"status"					=> isActived($rs['status'])
						);
		array_push($data, $arr);
		$total_qty += $rs['qty'];
		$no++;
	}
	$arr = array("total_qty"	=> number_format($total_qty));
	array_push($data, $arr);

	echo json_encode($data);
}




if( isset( $_GET['clearFilter'] ) )
{
	deleteCookie('sCode');
	deleteCookie('sInvoice');
	deleteCookie('sPo');
	deleteCookie('sSup');
	deleteCookie('fromDate');
	deleteCookie('toDate');
	echo 'done';
}

?>
