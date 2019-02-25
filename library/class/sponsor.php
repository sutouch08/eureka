<?php
class sponsor
{
  public $id;
  public $id_sponsor;

  public $id_budget;
  public $id_sponsor_budget;
  public $limit;
  public $balance;


  public function __construct($id = '')
  {
    if($id != '')
    {
      $this->getData($id);
    }
  }


  public function getDataByCustomer($id)
  {
    $qr  = "SELECT sp.* ,bd.id_sponsor_budget, bd.limit_amount, bd.balance FROM tbl_sponsor AS sp ";
    $qr .= "LEFT JOIN tbl_sponsor_budget AS bd ON sp.id_sponsor = bd.id_sponsor ";
    $qr .= "AND sp.year = bd.year ";
    $qr .= "WHERE sp.id_customer = ".$id;

    $qs = dbQuery($qr);

    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchObject($qs);
      $this->id = $rs->id_sponsor;
      $this->id_sponsor = $rs->id_sponsor;
      $this->id_budget = $rs->id_sponsor_budget;
      $this->id_sponsor_budget = $rs->id_sponsor_budget;
      $this->limit = $rs->limit_amount;
      $this->balance = $rs->balance;
    }
  }


  public function update_budget($id, $amount)
  {
    $qr = "UPDATE tbl_sponsor_budget SET balance = balance + ".$amount." WHERE id_sponsor_budget = ".$id;
    return dbQuery($qr);
  }


  public function getOrderSponsor($id_order)
  {
    $qr = "SELECT * FROM tbl_order_sponsor WHERE id_order = ".$id_order;
    $qs = dbQuery($qr);
    if(dbNumRows($qs) == 1)
    {
      return dbFetchObject($qs);
    }
  }

} //--- end class


 ?>
