<?php
class stock
{
	public $error = '';

	public function __construct()
	{

	}


	public function updateStockZone($id_zone, $id_pa, $qty)
	{
		if($this->isExists($id_zone, $id_pa) === TRUE)
		{
			$sc = $this->update($id_zone, $id_pa, $qty);
		}
		else
		{
			$sc = $this->add($id_zone, $id_pa, $qty);
		}

		$this->removeZero();

		return $sc;
	}





	private function add($id_zone, $id_pa, $qty)
	{
		$rs = dbQuery("INSERT INTO tbl_stock (id_zone, id_product_attribute, qty) VALUES (".$id_zone.", '".$id_pa."', ".$qty.")");
		if($rs === FALSE)
		{
			$this->error = dbError();
			return FALSE;
		}

		return TRUE;
	}





	private function update($id_zone, $id_pa, $qty)
	{
		return dbQuery("UPDATE tbl_stock SET qty = (qty + ".$qty.") WHERE id_zone = ".$id_zone." AND id_product_attribute = '".$id_pa."'");
	}






	private function removeZero()
	{
		dbQuery("DELETE FROM tbl_stock WHERE qty = 0");
	}





	public function isExists($id_zone, $id_pa)
	{
		$sc = FALSE;
		$qs = dbQuery("SELECT id_stock FROM tbl_stock WHERE id_zone = '".$id_zone."' AND id_product_attribute = '".$id_pa."'");
		if( dbNumRows($qs) == 1 )
		{
			$sc = TRUE;
		}
		return $sc;
	}





	//---	มีสต็อกคงเหลือเพียงพอให้ตัดหรือไม่
	public function isEnough($id_zone, $id_pa, $qty)
	{
		$qs = dbQuery("SELECT id_stock FROM tbl_stock WHERE id_zone = '".$id_zone."' AND id_product_attribute = '".$id_pa."' AND qty >= ".$qty);
		return dbNumRows($qs) == 1 ? TRUE : FALSE;
	}




	public function getStockZone($id_zone, $id_pa)
	{
		$qs = dbQuery("SELECT qty FROM tbl_stock WHERE id_zone = ".$id_zone." AND id_product_attribute = '".$id_pa."'");
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
			return $sc;
		}

		return 0;
	}



	//---- แสดงที่เก็บสินค้า สำหรับการจัดสินค้า
	public function stockInZone($id_pa)
	{
		$qr = "SELECT z.zone_name AS name, s.qty FROM tbl_stock AS s ";
		$qr .= "JOIN tbl_zone AS z ON s.id_zone = z.id_zone ";
		$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id_warehouse ";
		$qr .= "WHERE id_product_attribute = '".$id_pa."' ";
		$qr .= "AND w.active = 1 ";

		return dbQuery($qr);
	}


}//--- end class

 ?>
