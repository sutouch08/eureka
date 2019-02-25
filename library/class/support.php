<?php
class support
{
  public $id;
  public $id_support;


  public $id_budget;
  public $id_support_budget;
  public $limit;
  public $balance;


  public function __construct($id = '')
  {

  }



  public function getDataByCustomer($id)
  {
    $qr  = "SELECT sp.* ,bd.id_support_budget, bd.limit_amount, bd.balance FROM tbl_support AS sp ";
    $qr .= "LEFT JOIN tbl_support_budget AS bd ON sp.id_support = bd.id_support ";
    $qr .= "AND sp.year = bd.year ";
    $qr .= "WHERE sp.id_employee = ".$id;

    $qs = dbQuery($qr);

    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchObject($qs);
      $this->id = $rs->id_support;
      $this->id_support = $rs->id_support;
      $this->id_budget = $rs->id_support_budget;
      $this->id_support_budget = $rs->id_support_budget;
      $this->limit = $rs->limit_amount;
      $this->balance = $rs->balance;
    }
  }




  public function getOrderSupport($id_order)
  {
    $qr = "SELECT * FROM tbl_order_support WHERE id_order = ".$id_order;
    $qs = dbQuery($qr);

    if(dbNumRows($qs) == 1)
    {
      return dbFetchObject($qs);
    }

    return FALSE;
  }


  public function update_budget($id_budget, $amount)
  {
    $qr = "UPDATE tbl_support_budget SET balance = balance + ".$amount." WHERE id_support_budget = ".$id_budget;
    return dbQuery($qr);
  }

}


 ?>
