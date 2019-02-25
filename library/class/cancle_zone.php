<?php
class cancle_zone
{
  public $error;

  public function __construct()
  {

  }


  public function add(array $ds = array())
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

			$qr  = "INSERT INTO tbl_cancle ";
			$qr .= "(".$fields.") ";
			$qr .= "VALUES ";
			$qr .= "(".$values.")";

			$qs = dbQuery($qr);

			if($qs === FALSE)
			{
				$this->error = dbError();
				return FALSE;
			}

			return TRUE;
		}

		return FALSE;
  }

} //--- end class

?>
