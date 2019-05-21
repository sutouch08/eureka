<?php
class supplier
{
  public $id;
  public $code;
  public $name;
  public $credit_term;
  public $active;

  public function __construct($id = '')
  {
    if($id != '')
    {
      $this->get_data($id);
    }
  }


  public function get_data($id)
  {
    $qs = dbQuery("SELECT * FROM tbl_supplier WHERE id = ".$id);
  	if( dbNumRows($qs) == 1 )
  	{
  		$rs = dbFetchArray($qs);
  		foreach($rs as $key => $value)
  		{
  			$this->$key = $value;
  		}
  	}
  }

} //--- end class


 ?>
