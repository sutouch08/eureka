<?php
class order_sold
{

  public function __construct()
  {

  }


  public function getDataByReference($reference)
  {
    return dbQuery("SELECT * FROM tbl_order_detail_sold WHERE reference = '".$reference."'");
  }



  public function getData($id)
  {
    return dbQuery("SELECT * FROM tbl_order_detail_sold WHERE id_order = '".$id."'");
  }

} //--- end class


 ?>
