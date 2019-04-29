<?php
class transport{

  public function __construct(){

  }

  public function getMainSenderName($id_customer)
  {
    $name = '';
    $qr  = "SELECT sd.name FROM tbl_transport AS tr ";
    $qr .= "JOIN tbl_sender AS sd ON tr.main_sender = sd.id_sender ";
    $qr .= "WHERE tr.id_customer = ".$id_customer;

    $qs = dbQuery($qr);
    if(dbNumRows($qs) == 1)
    {
      list($name) = dbFetchArray($qs);
    }

    return $name;
  }
} //-- end class

?>
