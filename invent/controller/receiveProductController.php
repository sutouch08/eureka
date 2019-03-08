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
	$qs = $rd->get_items($id);

	if(dbNumRows($qs) > 0)
	{
		$stock = new stock();
		$movement = new movement();
		$po = new po();

		while($rs = dbFetchObject($qs))
		{
			//--- check ว่ารายการนี้ บันทึกแล้วหรือยัง
			if($rd->isSaved($rs->id_receive_product_detail) === TRUE)
			{
				//--- delete movement
				if($movement->dropMoveIn($rd->reference, $rs->id_zone, $rs->id_product_attribute) !== TRUE)
				{
					$sc = FALSE;
					$message = 'ลบ Movement ไม่สำเร็จ';
				}

				//--- ลดยอดในโซนที่รับเข้า
				if($stock->updateStockZone($rs->id_zone, $rs->id_product_attribute, $rs->qty * -1) !== TRUE)
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
				if($po->receive_item($rd->id_po, $rs->id_product_attribute, $rs->qty * -1) !== TRUE)
				{
					$sc = FALSE;
					$message = 'ปรับปรุงยอดรับในใบสั่งซื้อไม่สำเร็จ';
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
			if($po->isClosed($rd->id_po) === TRUE)
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
	$id 		= $_POST['id_receive_product'];
	$ds = array(
		'invoice' => $_POST['invoice'],
		'po_reference' => $_POST['po_reference'],
		'id_po' => $_POST['id_po'],
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




if(isset($_GET['receiveItems']) && isset($_POST['id_receive_product']))
{
	$id_rp = $_POST['id_receive_product'];
	$id_po = $_POST['id_po'];
	$id_zone = $_POST['id_zone'];
	$details = json_decode($_POST['detail']);
	$id_warehouse = get_warehouse_by_zone($id_zone);
	$id_emp = getCookie('user_id');
	$date = date('Y-m-d');
	$sc = TRUE;
	$message = '';

	if(!empty($details))
	{
		$rd = new receive_product();

		startTransection();

		foreach($details as $item)
		{
			if($sc === FALSE)
			{
				break;
			}

			$data = array(
				'id_receive_product' => $id_rp,
				'id_product' => $item->id_product,
				'id_product_attribute' => $item->id_product_attribute,
				'qty' => $item->qty,
				'id_warehouse' => $id_warehouse,
				'id_zone' => $id_zone,
				'id_employee' => $id_emp,
				'date_add' => $date
			);


			if($rd->isExists($id_rp, $item->id_product_attribute) === TRUE)
			{
				if($rd->updateItem($data) === FALSE)
				{
					$sc = FALSE;
					$message = 'ปรับปรุงข้อมูลไม่สำเร็จ';
				}
			}
			else
			{
				if($rd->addNewItem($data) === FALSE)
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
	}
	else
	{
		$sc = FALSE;
		$message = 'ไม่พบข้อมูลรับเข้ากรุณาตรวจสอบรายการ';
	}

	echo $sc === TRUE ? 'success' : $message;

}






if(isset($_GET['receiveItem']) && isset($_POST['id_receive_product']))
{
	$id_rp = $_POST['id_receive_product'];
	$id_po = $_POST['id_po'];
	$id_zone = $_POST['id_zone'];
	$id_pd = $_POST['id_product'];
	$id_pa = $_POST['id_product_attribute'];
	$qty   = $_POST['qty'];

	$rd = new receive_product();

	$data = array(
		'id_receive_product' => $id_rp,
		'id_product' => $id_pd,
		'id_product_attribute' => $id_pa,
		'qty' => $qty,
		'id_warehouse' => get_warehouse_by_zone($id_zone),
		'id_zone' => $id_zone,
		'id_employee' => getCookie('user_id'),
		'date_add' => date('Y-m-d')
	);

	if($rd->isExists($id_rp, $id_pa) === TRUE)
	{
		$rs = $rd->updateItem($data);
	}
	else
	{
		$rs = $rd->addNewItem($data);
	}

	echo $rs === TRUE ? 'success' : 'รับเข้าโซนไม่สำเร็จ';

}




if( isset( $_GET['cancleReceiveItem']) && isset($_POST['id_receive_product']))
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






if( isset($_GET['unSaveRecieved']) && isset($_POST['id_receive_product']))
{
	$sc = TRUE;
	$message = '';
	$id = $_POST['id_receive_product'];
	$rd = new receive_product($id);
	$stock = new stock();
	$po = new po();
	$movement = new movement();
	$qs = $rd->getSavedDetails($id);

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
			if($movement->dropMoveIn($rd->reference, $rs->id_zone, $rs->id_product_attribute) !== TRUE)
			{
				$sc = FALSE;
				$message = 'ลบ Movement ไม่สำเร็จ';
			}

			//--- ลดยอดในโซนที่รับเข้า
			if($stock->updateStockZone($rs->id_zone, $rs->id_product_attribute, $rs->qty * -1) !== TRUE)
			{
				$sc = FALSE;
				$message = 'ตัดยอดจากโซนรับเข้าไม่สำเร็จ';
			}

			//--- Update PO detail received
			if($po->receive_item($rd->id_po, $rs->id_product_attribute, $rs->qty * -1) !== TRUE)
			{
				$sc = FALSE;
				$message = 'ปรับปรุงยอดรับในใบสั่งซื้อไม่สำเร็จ';
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



if(isset($_GET['saveAdd']))
{
	$sc = TRUE;
	$id = $_POST['id_receive_product'];
	$approver = $_POST['id_approver'];

	$rd = new receive_product($id);
	$stock = new stock();
	$po = new po($rd->id_po);
	$movement = new movement();

	startTransection();

	$qs = $rd->get_items($id);

	if(dbNumRows($qs) > 0)
	{
		//--- update status po
		if($po->valid != 1)
		{
			$po->update_status($rd->id_po, 2);
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
				if($stock->updateStockZone($rs->id_zone, $rs->id_product_attribute, $rs->qty) !== TRUE)
				{
					$sc = FALSE;
					$message = 'เพิ่มสต็อกเข้าโซนไม่สำเร็จ';
				}

				//--- insert movement
				if($movement->move_in($rd->reference, $rs->id_warehouse, $rs->id_zone, $rs->id_product_attribute, $rs->qty, $rd->date_add) !== TRUE)
				{
					$sc = FALSE;
					$message = 'บันทึก movement ไม่สำเร็จ';
				}

				//--- update po detail
				if($po->receive_item($rd->id_po, $rs->id_product_attribute, $rs->qty) !== TRUE)
				{
					$sc = FALSE;
					$message = 'ปรับปรุงยอดรับในใบสั่งซื้อไม่สำเร็จ';
				}

				//--- ถ้ารับครบแล้วปิดรายการไปเลย
				$po->validReceivedItem($rd->id_po, $rs->id_product_attribute);

				//--- เปลี่นสถานะรายการรับเข้า
				if($rd->change_item_status($rs->id_receive_product_detail, 1) !== TRUE)
				{
					$sc = FALSE;
					$message = 'เปลี่ยนสถานะรายการรับเข้าไม่สำเร็จ';
				}

				if($sc === TRUE && $approver != 0)
				{
					$rd->updateApprover($id, $approver);
				}

			} //--- end if
		} //--- end while

		//--- เช็คถ้ารับครบ po แล้วทุกรายการให้ปิด po
		if($sc === TRUE)
		{
			$po->validPo($rd->id_po);
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





if( isset( $_GET['add_new']) && isset( $_POST['id_po'] ) )
{
	$rd	= new receive_product();
	$data	= array(
				"reference"			=> $rd->get_new_reference(dbDate($_POST['date_add'])),
				"invoice"				=> $_POST['invoice'],
				"po_reference"	=> $_POST['po_reference'],
				"id_po"				  => $_POST['id_po'],
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






if( isset( $_GET['print'] ) && isset( $_GET['id_receive_product'] ) )
{
	$id		= $_GET['id_receive_product'];
	$ro = new receive_product($id);
	$po = new po($ro->id_po);
	$print = new printer();
	echo $print->doc_header();
	$print->add_title("เอกสารรับสินค้าเข้าตามใบสั่งซื้อ");
	$header	= array(
								"เลขที่เอกสาร"=>$ro->reference,
								"เลขที่ใบสั่งซื้อ"=>$ro->po_reference,
								"ผู้ทำรายการ"=>employee_name($ro->id_employee),
								"เลขที่ใบส่งของ"=>$ro->invoice,
								"Supplier"=> supplier_name($po->id_supplier),
								"วันที่"=>thaiDate($ro->date_add)
							);
	$print->add_header($header);
	$detail = $ro->get_saved_items($id);
	$total_row = dbNumRows($detail);
	$config = array("total_row"=>$total_row, "font_size"=>10, "sub_total_row"=>2);
	$print->config($config);
	$row = $print->row;
	$total_page = $print->total_page;
	$total_qty = 0;
	//**************  กำหนดหัวตาราง  ******************************//
	$thead	= array(
						array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
						array("รหัส", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("สินค้า", "width:45%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
						array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
						array("โซน", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
						);
	$print->add_subheader($thead);

	//***************************** กำหนด css ของ td *****************************//
	$pattern = array(
							"text-align: center; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px;",
							"border-left: solid 1px #ccc; border-top:0px; text-align:center;",
							"border-left: solid 1px #ccc; border-top:0px; text-align:center"
							);
	$print->set_pattern($pattern);

	//*******************************  กำหนดช่องเซ็นของ footer *******************************//
	$footer	= array(
						array("ผู้ทำรายการ", "","วันที่............................."),
						array("ผู้ตรวจสอบ", "","วันที่............................."),
						array("ผู้อนุมัติ", "","วันที่.............................")
						);
	$print->set_footer($footer);
	$n = 1;
	while($total_page > 0 )
	{
		echo $print->page_start();
			echo $print->top_page();
			echo $print->content_start();
				echo $print->table_start();
				$i = 0;
				$product = new product();
				while($i<$row) :
					$rs = dbFetchArray($detail);
					if(!empty($rs)) :
						$data = array( $n, get_product_reference($rs['id_product_attribute']), get_product_name($rs['id_product']), $rs['qty'], get_zone($rs['id_zone']) );
						$total_qty += $rs['qty'];
					else :
						$data = array("", "", "", "","");
					endif;
					echo $print->print_row($data);
					$n++; $i++;
				endwhile;
				echo $print->table_end();
				if($print->current_page == $print->total_page){ $qty = number_format($total_qty); $remark = $ro->remark; }else{ $qty = ""; $remark = ""; }
				$sub_total = array(
						array("<td rowspan='2' style='height:".$print->row_height."mm; border-top: solid 1px #ccc; border-bottom-left-radius:10px; width:60%; font-size:10px;'><strong>หมายเหตุ : </strong>".$remark."</td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>จำนวนรวม</strong></td>
								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$qty."</td>"),
						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>มูลค่ารวม</strong></td>
						<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'> - </td>")
						);
			echo $print->print_sub_total($sub_total);
			echo $print->content_end();
			echo $print->footer;
		echo $print->page_end();
		$total_page --; $print->current_page++;
	}
	echo $print->doc_footer();
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
