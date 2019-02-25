<?php
class consignment
{
	public $id;
	public $id_order_consignment;
	public $id_order;
	public $id_customer;
	public $id_zone;
	public $status;
	public function __construct($id_order = '')
	{
		if( $id_order != '' )
		{
			$qs = dbQuery("SELECT * FROM tbl_order_consignment WHERE id_order = ".$id_order);
			if( dbNumRows($qs) == 1 )
			{
				$rs	= dbFetchArray($qs);
				$this->id = $rs['id_order_consignment'];
				$this->id_order_consignment	= $rs['id_order_consignment'];
				$this->id_order	= $rs['id_order'];
				$this->id_customer	= $rs['id_customer'];
				$this->id_zone		= $rs['id_zone'];
				$this->status		= $rs['status'];
			}
		}
	}


	public function addDetail(array $ds = array())
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

			$qr = "INSERT INTO tbl_order_consignment_detail (".$fields.") VALUES (".$values.")";

			return dbQuery($qr);
		}

		return FALSE;
	}


	public function dropDetail($id)
	{
		return dbQuery("DELETE FROM tbl_order_consignment_detail WHERE id = ".$id);
	}


	public function addConsignment($ds)
	{
		return dbQuery("INSERT INTO tbl_order_consignment (id_order, id_customer, id_zone) VALUES (".$ds['id_order'].", ".$ds['id_customer'].", ".$ds['id_zone'].")");
	}


	public function updateConsignment($id_order, $ds)
	{
		return dbQuery("UPDATE tbl_order_consignment SET id_customer = ".$ds['id_customer'].", id_zone = ".$ds['id_zone']." WHERE id_order = ".$id_order);
	}


	public function isSaved($id_user)
	{
		$sc 	= FALSE;
		$qs 	= dbQuery("SELECT id_order FROM tbl_order WHERE id_employee = ".$id_user." AND role = 5 AND order_status = 0");
		if( dbNumRows($qs) > 0 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}

} // End class

?>
