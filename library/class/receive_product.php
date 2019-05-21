<?php
class receive_product
{
	public $id;
	public $id_receive_product;
	public $reference;
	public $invoice;
	public $po_reference;
	public $id_po;
	public $id_warehouse;
	public $id_zone;
	public $id_supplier;
	public $id_employee;
	public $date_add;
	public $date_upd;
	public $remark;
	public $status;
	public $approver;
	public $error;

public function __construct($id = "")
{
	if($id != "" )
	{
		$this->get_data($id);
	}
}

public function get_data($id)
{
	$qs = dbQuery("SELECT * FROM tbl_receive_product WHERE id_receive_product = ".$id);
	if( dbNumRows($qs) == 1 )
	{
		$rs = dbFetchArray($qs);
		foreach($rs as $key => $value)
		{
			$this->$key = $value;
		}
	}
}



public function getDetails($id)
{
		$qr  = "SELECT rd.id_receive_product_detail, rd.id_receive_product, rd.id_product, rd.id_product_attribute, ";
		$qr .= "pa.reference, pd.product_name, rd.qty, rp.id_warehouse, rp.id_zone, rd.status ";
		$qr .= "FROM tbl_receive_product_detail AS rd ";
		$qr .= "JOIN tbl_receive_product AS rp ON rd.id_receive_product = rp.id_receive_product ";
		$qr .= "JOIN tbl_product_attribute AS pa ON rd.id_product_attribute = pa.id_product_attribute ";
		$qr .= "JOIN tbl_product AS pd ON rd.id_product = pd.id_product ";
		$qr .= "LEFT JOIN tbl_color AS co ON pa.id_color = co.id_color ";
		$qr .= "LEFT JOIN tbl_size AS si ON pa.id_size = si.id_size ";
		$qr .= "WHERE rd.id_receive_product = ".$id." ";
		$qr .= "ORDER BY co.color_code ASC, si.position ASC";

		return dbQuery($qr);
}



public function getSavedDetails($id)
{
	$qr  = "SELECT rd.id_receive_product_detail, rd.id_receive_product, rd.id_product, rd.id_product_attribute, ";
	$qr .= "pa.reference, pd.product_name, rd.qty, rp.id_warehouse, rp.id_zone, rd.status ";
	$qr .= "FROM tbl_receive_product_detail AS rd ";
	$qr .= "JOIN tbl_receive_product AS rp ON rd.id_receive_product = rp.id_receive_product ";
	$qr .= "JOIN tbl_product_attribute AS pa ON rd.id_product_attribute = pa.id_product_attribute ";
	$qr .= "JOIN tbl_product AS pd ON rd.id_product = pd.id_product ";
	$qr .= "LEFT JOIN tbl_color AS co ON pa.id_color = co.id_color ";
	$qr .= "LEFT JOIN tbl_size AS si ON pa.id_size = si.id_size ";
	$qr .= "WHERE rd.id_receive_product = ".$id." ";
	$qr .= "AND rd.status = 1 ";
	$qr .= "ORDER BY co.color_code ASC, si.position ASC";

	return dbQuery($qr);

	return dbQuery($qr);
}




public function updateApprover($id, $approver)
{
	return dbQuery("UPDATE tbl_receive_product SET approve = ".$approver." WHERE id_receive_product = ".$id);
}



public function inPO($id_po, $id_product_attribute)
{
	$qty = 0;
	$qs = dbQuery("SELECT qty FROM tbl_po_detail WHERE id_po = ".$id_po." AND id_product_attribute = ".$id_product_attribute);
	if( dbNumRows($qs) == 1 )
	{
		list($qty) = dbFetchArray($qs);
	}
	return $qty;
}






public function addNewItem(array $ds = array())
{
	if(!empty($ds))
	{
		$fields = "";
		$values = "";
		$i = 1;
		foreach($ds as $field => $value)
		{
			$fields .= $i == 1 ? $field : ", ".$field;
			$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
			$i++;
		}

		$qr = "INSERT INTO tbl_receive_product_detail ($fields) VALUES ($values)";
		return dbQuery($qr);
	}

	return FALSE;
}




public function updateItem(array $ds = array())
{
	if(!empty($ds))
	{
		$qr  = "UPDATE tbl_receive_product_detail SET ";
		$qr .= "qty = qty + ".$ds['qty']." ";
		$qr .= ", id_employee = ".$ds['id_employee']." ";
		$qr .= "WHERE id_receive_product = ".$ds['id_receive_product']." ";
		$qr .= "AND id_product_attribute = ".$ds['id_product_attribute']." ";
		$qr .= "AND status = 0 ";

		return dbQuery($qr);
	}

	return FALSE;
}



public function deleteAllDetails($id)
{
	return dbQuery("DELETE FROM tbl_receive_product_detail WHERE id_receive_product = $id");
}




public function isExists($id_receive_product, $id_pa)
{
	$qr  = "SELECT id_receive_product_detail FROM tbl_receive_product_detail ";
	$qr .= "WHERE id_receive_product = ".$id_receive_product." ";
	$qr .= "AND id_product_attribute = ".$id_pa." ";
	$qr .= "AND status = 0";

	$rs = dbQuery($qr);

	if(dbNumRows($rs) > 0)
	{
		return TRUE;
	}

	return FALSE;
}




public function delete($id)
{
	return dbQuery("DELETE FROM tbl_receive_product WHERE id_receive_product = ".$id);
}






public function delete_item($id)
{
	return dbQuery("DELETE FROM tbl_receive_product_detail WHERE id_receive_product_detail = ".$id);
}




public function isSaved($id)
{
	$qr  = "SELECT id_product_attribute ";
	$qr .= "FROM tbl_receive_product_detail ";
	$qr .= "WHERE id_receive_product_detail = ".$id." ";
	$qr .= "AND status = 1";

	$qs = dbQuery($qr);

	if(dbNumRows($qs) == 1)
	{
		return TRUE;
	}

	return FALSE;
}



public function get_item($id)
{
	$qs = dbQuery("SELECT * FROM tbl_receive_product_detail WHERE id_receive_product_detail = ".$id);
	if(dbNumRows($qs) == 1 )
	{
		return dbFetchArray($qs);
	}
	else
	{
		return false;
	}
}


public function add(array $data = array())
{
	if(!empty($data))
	{
		$fields = "";
		$values = "";
		$i = 1;
		foreach($data as $field => $value)
		{
			$fields .= $i == 1 ? $field : ", ".$field;
			$values .= $i == 1 ? "'".$value."'" : " , '".$value."'";
			$i++;
		}

		$qr  = "INSERT INTO tbl_receive_product ";
		$qr .= "(".$fields.") ";
		$qr .= "VALUES ";
		$qr .= "(".$values.")";

		$qs = dbQuery($qr);
		if($qs)
		{
			return dbInsertId();
		}
	}

	return FALSE;
}




public function update($id, array $data = array())
{
	if(!empty($data))
	{
		$set = "";
		$i = 1;
		foreach($data as $field => $value)
		{
			$set .= $i == 1 ? $field." = '".$value."'" : ", ".$field." = '".$value."'";
			$i++;
		}

		$qr = "UPDATE tbl_receive_product SET ".$set." WHERE id_receive_product = ".$id;

		return dbQuery($qr);
	}

	return FALSE;

}




public function change_status($id, $status)
{
	return dbQuery("UPDATE tbl_receive_product SET status = ".$status." WHERE id_receive_product = ".$id);
}




public function change_item_status($id, $status)
{
	return dbQuery("UPDATE tbl_receive_product_detail SET status = ".$status." WHERE id_receive_product_detail = ".$id);
}





public function total_qty($id)
{
	$qty = 0;
	$qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_receive_product_detail WHERE status = 1 AND id_receive_product = ".$id);
	if(dbNumRows($qs) == 1 )
	{
		list($qty)	= dbFetchArray($qs);
	}
	return $qty;
}




public function total_amount($id)
{
	$sc = 0;
	$qr = "SELECT rd.qty, pa.cost
					FROM
						tbl_receive_product_detail AS rd
					JOIN
						tbl_product_attribute AS pa
							ON rd.id_product_attribute = pa.id_product_attribute
					WHERE
						rd.status = 1
						AND
						id_receive_product = $id";

	$qs = dbQuery($qr);

	if( dbNumRows($qs) > 0 )
	{
		while( $rs = dbFetchObject($qs) )
		{
			$sc += ($rs->qty * $rs->cost);
		}
	}
	return $sc;
}




public function insert_movement($move, $reason, $id_product_attribute, $id_warehouse, $qty, $reference, $date_upd, $id_zone = 0 )
{
	if( $move == "in" )
	{
		return dbQuery("INSERT INTO tbl_stock_movement (id_reason, id_product_attribute, id_warehouse, move_in, reference, date_upd, id_zone) VALUES (".$reason.", ".$id_product_attribute.", ".$id_warehouse.", ".$qty.", '".$reference."', '".$date_upd."', ".$id_zone.")");
	}
	else if( $move == "out" )
	{
		return dbQuery("INSERT INTO tbl_stock_movement (id_reason, id_product_attribute, id_warehouse, move_out, reference, date_upd, id_zone) VALUES (".$reason.", ".$id_product_attribute.", ".$id_warehouse.", ".$qty.", '".$reference."', '".$date_upd."', ".$id_zone.")");
	}
}



public function get_po_item_qty($id_po, $id_product_attribute)
{
	$qty = 0;
	$qs = dbQuery("SELECT qty FROM tbl_po_detail WHERE id_po = ".$id_po." AND id_product_attribute =".$id_product_attribute);
	if( dbNumRows($qs) == 1 )
	{
		list($qty) = dbFetchArray($qs);
	}
	return $qty;
}




public function get_po_received_qty($id_po, $id_product_attribute)
{
	$qty = 0;
	$qs = dbQuery("SELECT received FROM tbl_po_detail WHERE id_po = ".$id_po." AND id_product_attribute = ".$id_product_attribute);
	if(dbNumRows($qs) == 1 )
	{
		list($qty) = dbFetchArray($qs);
	}
	return $qty;
}





public function get_new_reference($date = "")
{
	$prefix = getConfig("PREFIX_RECIEVE");
	if($date == ''){ $date = date("Y-m-d"); }
	$year = date("y", strtotime($date));
	$month = date("m", strtotime($date));
	$qs = dbQuery("SELECT MAX(reference) AS reference FROM tbl_receive_product WHERE reference LIKE '%".$prefix."-".$year.$month."%'");
	$rs = dbFetchArray($qs);
	$str = $rs['reference'];
	if($str !="")
	{
		$ra = explode('-', $str, 2);
		$num = $ra[1];
		$run_num = $num + 1;
		$reference = $prefix."-".$run_num;
	}else{
		$reference = $prefix."-".$year.$month."00001";
	}
	return $reference;
}




public function receive_item($id_po, $id_product_attribute, $qty)
{
	return dbQuery("UPDATE tbl_po_detail SET received = received + ".$qty." WHERE id_po = ".$id_po." AND id_product_attribute = ".$id_product_attribute);
}





public function valid_qty_with_po($id_po, $id_product_attribute)
{
	$qs = dbQuery("SELECT id_po_detail FROM tbl_po_detail WHERE id_po = ".$id_po." AND id_product_attribute = ".$id_product_attribute." AND (received > qty OR received = qty) AND valid = 0");
	if(dbNumRows($qs) == 1 )
	{
		list($id) = dbFetchArray($qs);
		return $id;
	}else{
		return false;
	}
}





public function change_valid_po_detail($id_po_detail, $i = 1)
{
	return dbQuery("UPDATE tbl_po_detail SET valid = ".$i." WHERE id_po_detail = ".$id_po_detail );
}





public function valid_po($id_po)
{
	$qs = dbQuery("SELECT id_po_detail FROM tbl_po_detail WHERE id_po = ".$id_po." AND (received < qty)");
	if(dbNumRows($qs) == 0 )
	{
		dbQuery("UPDATE tbl_po SET valid = 1 WHERE id_po = ".$id_po);
	}
}



public function roll_back_action($id)
{
	$rs = $this->get_item($id);

	if($rs)
	{
		if(!isset($this->reference) )
		{
			$this->get_data($rs['id_receive_product']);
		}

		$rd = $this->delete_movement($this->reference, $rs['id_product_attribute'], $rs['qty'], $rs['id_zone']);

		if($rd)
		{
			$rx = update_stock_zone($rs['qty']*-1, $rs['id_zone'], $rs['id_product_attribute']);
			if($rx)
			{
				return $this->receive_item($this->id_po, $rs['id_product_attribute'], $rs['qty']*-1);
			}

		}else{
			return false;
		}
	}
}




}/// end class
?>
