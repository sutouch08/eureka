<?php
  function check_cart($id_sale)
  {
  	$qs = dbQuery("SELECT id_cart, id_customer FROM tbl_cart WHERE id_sale = '".$id_sale."' AND valid = 0 ");
  	if(dbNumRows($qs) > 0 )
  	{
  		return dbFetchArray($qs);
  	}

  	return FALSE;
  }

 ?>
