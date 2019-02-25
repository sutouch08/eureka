<?php
class temp
{
    public $id_temp;
    public $id_order;
    public $id_product_attribute;
    public $qty;
    public $id_warehouse;
    public $id_zone;
    public $status; //---   0 = รอจัด, 1 = กำลังจัด, 2 = จัดแล้ว, 3 = ยกเลิก
    public $id_employee;
    public $date_upd;
    public $error;


    public function __construct()
    {
        return TRUE;
    }




    private function add($id_order, $id_pa, $id_warehouse, $id_zone, $qty)
    {
      $qr = "INSERT INTO tbl_temp (id_order, id_product_attribute, qty, id_warehouse, id_zone, id_employee) ";
      $qr .= "VALUES (".$id_order.", ".$id_pa.", ".$qty.", ".$id_warehouse.", ".$id_zone.", '".getCookie('user_id')."')";
      if(dbQuery($qr) === FALSE)
      {
        $this->error = dbError();
        return FALSE;
      }

      return TRUE;
    }






    private function update($id_order, $id_pa, $id_zone, $qty)
    {
      $qr  = "UPDATE tbl_temp SET qty = qty + ".$qty." ";
      $qr .= "WHERE id_order = ".$id_order." ";
      $qr .= "AND id_product_attribute = ".$id_pa." ";
      $qr .= "AND id_zone = ".$id_zone;
      if( dbQuery($qr) === FALSE)
      {
        $this->error = dbError();
        return FALSE;
      }

      return TRUE;
    }





    public function updatePrepare($id_order, $id_pa, $id_warehouse, $id_zone, $qty)
    {
      if( $this->isExists($id_order, $id_pa, $id_zone))
      {
        return $this->update($id_order, $id_pa, $id_zone, $qty);
      }
      else
      {
        return $this->add($id_order, $id_pa, $id_warehouse, $id_zone, $qty);
      }
    }






    public function isExists($id_order, $id_pa, $id_zone)
    {
        $qs = dbQuery("SELECT id_temp FROM tbl_temp WHERE id_order = '".$id_order."' AND id_product_attribute ='".$id_pa."' AND id_zone = '".$id_zone."'");

        return dbNumRows($qs) == 1 ? TRUE : FALSE;
    }




    //--- จำนวนที่จัดไปแล้ว
    public function getPrepared($id_order, $id_pa)
    {
      $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_temp WHERE id_order = ".$id_order." AND id_product_attribute = '".$id_pa."'");
      list( $qty ) = dbFetchArray($qs);

      return is_null($qty) ? 0 : $qty;
    }




    public function getOrderPrepared($id_order)
    {
      $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_temp WHERE id_order = ".$id_order);
      list( $qty ) = dbFetchArray($qs);

      return is_null($qty) ? 0 : $qty;
    }



    //--- จัดสินค้ามาจากที่ไหนบ้าง
    public function prepareFromZone($id_order, $id_pda)
    {
      $qr = "SELECT zone_name AS name, qty FROM tbl_temp JOIN tbl_zone ON tbl_temp.id_zone = tbl_zone.id_zone ";
      $qr .= "WHERE id_order = ".$id_order." AND id_product_attribute = '".$id_pa."'";
      return dbQuery($qr);
    }




    public function getPreparedData($id_order, $id_pa)
    {
      return dbQuery("SELECT * FROM tbl_temp WHERE id_order = ".$id_order." AND id_product_attribute = '".$id_pa."'");
    }



    public function dropPreparedData($id_order)
    {
      return dbQuery("DELETE FROM tbl_temp WHERE id_order = '".$id_order."'");
    }


    public function deletePrepared($id_order, $id_pd, $id_zone)
    {
      return dbQuery("DELETE FROM tbl_temp WHERE id_order = '".$id_order."' AND id_product_attribute = '".$id_pd."' AND id_zone = '".$id_zone."'");
    }




}   //---   End class


?>
