<?php
class buffer
{
  public $id_buffer;
  public $id_order;
  public $id_product;
  public $id_product_attribute;
  public $qty;
  public $id_zone;
  public $id_warehouse;
  public $id_employee;
  public $date_upd;
  public $error;

  public function __construct($id = "")
  {
    if( $id !="")
    {
      //--- initialize
      $this->getData($id);
    }
  }





  public function getData($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_buffer WHERE id_buffer = ".$id);
    if( dbNumRows($qs) == 1 )
    {
      $rs = dbFetchArray($qs);
      foreach($rs as $key => $value )
      {
        $this->$key = $value;
      }
    }
  }





  public function getDetails($id_order, $id_pa)
  {
    return dbQuery("SELECT * FROM tbl_buffer WHERE id_order = ".$id_order." AND id_product_attribute = ".$id_pa);
  }




  public function add($id_order, $id_pd, $id_pa, $id_zone, $id_warehouse, $qty)
  {
    $qr  = "INSERT INTO tbl_buffer (id_order, id_product, id_product_attribute, qty, id_zone, id_warehouse) ";
    $qr .= "VALUES ";
    $qr .= "(".$id_order.", ".$id_pd.", ".$id_pa.", ".$qty.", ".$id_zone.", ".$id_warehouse.")";
    $rs = dbQuery($qr);
    if($rs === FALSE)
    {
      $this->error = dbError();
      return FALSE;
    }

    return TRUE;
  }






  public function update($id_order, $id_pa, $id_zone, $qty)
  {
    $qr  = "UPDATE tbl_buffer SET qty = qty + ".$qty." ";
    $qr .= "WHERE id_order = ".$id_order." ";
    $qr .= "AND id_product_attribute = ".$id_pa." ";
    $qr .= "AND id_zone = ".$id_zone;

    $rs = dbQuery($qr);
    if($rs === FALSE)
    {
      $this->error = dbError();
      return FALSE;
    }

    return TRUE;
  }




  public function delete($id)
  {
    return dbQuery("DELETE FROM tbl_buffer WHERE id_buffer = ".$id);
  }




  public function isExists($id_order, $id_pa, $id_zone)
  {
      $qr  = "SELECT id_buffer FROM tbl_buffer ";
      $qr .= "WHERE id_order = ".$id_order." ";
      $qr .= "AND id_product_attribute = ".$id_pa." ";
      $qr .= "AND id_zone = ".$id_zone." ";

      $qs = dbQuery($qr);

      if( dbNumRows($qs) == 1 )
      {
          return TRUE;
      }

      return FALSE;
  }






  public function updateBuffer($id_order, $id_pd, $id_pa, $id_zone, $id_warehouse, $qty)
  {
    if(!$this->isExists($id_order, $id_pa, $id_zone))
    {
      return $this->add($id_order, $id_pd, $id_pa, $id_zone, $id_warehouse, $qty);
    }

    return $this->update($id_order, $id_pa, $id_zone, $qty);
  }



  public function clearBuffer($id_order)
  {
    return dbQuery("DELETE FROM tbl_buffer WHERE id_order = ".$id_order);
  }



  //--- ยอดรวมสินค้าที่จัดไปแล้ว
  public function getSumQty($id_order, $id_pa)
  {
    $qs = dbQuery("SELECT SUM(qty) FROM tbl_buffer WHERE id_order = '".$id_order."' AND id_product_attribute = '".$id_pa."'");
    list( $qty ) = dbFetchArray($qs);
    return is_null($qty) ? 0 : $qty;
  }




  //--- drop รายการที่เป็น 0 ทิ้ง
  public function dropZero($id_order)
  {
    return dbQuery("DELETE FROM tbl_buffer WHERE id_order = ".$id_order." AND qty = 0");
  }



  //--- เอารายการที่ค้างอยู่ใน buffer
  public function getBuffer($id_order)
  {
    return dbQuery("SELECT * FROM tbl_buffer WHERE id_order = '".$id_order."' AND qty > 0");
  }



  //----  จำนวนที่ค้างใน buffer แต่ไม่มีรายการอยู่ในออเดอร์ไหนๆเลย (ถูกลบรายการเมื่อจัดสินค้าแล้ว)
  public function getBufferQty($id_pa)
  {
    $qr  = "SELECT SUM(bf.qty) AS qty FROM tbl_buffer AS bf ";
    $qr .= "LEFT JOIN tbl_order_detail AS od ON bf.id_order = od.id_order AND bf.id_product_attribute = od.id_product_attribute ";
    $qr .= "WHERE bf.id_product_attribute = '".$id_pa."' ";
    $qr .= "AND od.id_product_attribute IS NULL ";

    $qs = dbQuery($qr);

    list($sc) = dbFetchArray($qs);

    return is_null($sc) ? 0 : $sc;
  }


  public function isInOrder($id_order, $id_pa)
  {
    $qr  = "SELECT od.id_product_attribute FROM tbl_buffer AS bf ";
    $qr .= "LEFT JOIN tbl_order_detail AS od ON bf.id_order = od.id_order ";
    $qr .= "AND bf.id_product_attribute = od.id_product_attribute ";
    $qr .= "WHERE bf.id_order = '".$id_order."' ";
    $qr .= "AND bf.id_product_attribute = '".$id_pa."' ";

    //echo $qr;
    $qs = dbQuery($qr);

    list($sc) = dbFetchArray($qs);

    return is_null($sc) ? FALSE : TRUE;
  }


  public function getStockInBuffer($id_pa)
  {
    $qs = dbQuery("SELECT SUM(qty) AS qty FROM tbl_buffer WHERE id_product_attribute = '".$id_pa."'");
    list($qty) = dbFetchArray($qs);
    return is_null($qty) ? 0 : $qty;
  }






}//---- End class


 ?>
