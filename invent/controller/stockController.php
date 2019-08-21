<?php
require "../../library/config.php";
require "../../library/functions.php";
require "../function/tools.php";


if(isset($_GET['recalStockZone']))
{
    $id_zone  = $_GET['id_zone'];
    $mv = new movement();
    $stock = new stock();
    $zone = new zone();

    //--- ลบรายการที่ไม่มี movement ออกจากโซน
    //
    // $qr  = "DELETE FROM tbl_stock ";
    // $qr .= "WHERE id_zone = ".$id_zone." ";
    // $qr .= "AND id_product_attribute NOT IN(SELECT id_product_attribute FROM tbl_stock_movement WHERE id_zone = ".$id_zone." GROUP BY id_product_attribute)";
    // $qs  = dbQuery($qr);

    $qs = dbQuery("DELETE FROM tbl_stock WHERE id_zone = ".$id_zone);

    //--- ดึงข้อมูลสินค้าทั้งหมดในโซน
    $qr  = "SELECT id_product_attribute, SUM(move_in) AS move_in, SUM(move_out) AS move_out ";
    $qr .= "FROM tbl_stock_movement ";
    $qr .= "WHERE id_zone = ".$id_zone." ";
    $qr .= "GROUP BY id_product_attribute";

    $qs = dbQuery($qr);
    if(dbNumRows($qs) > 0)
    {
      while($rs = dbFetchObject($qs))
      {
        $qty = $rs->move_in - $rs->move_out;
        $stock->updateStockZone($id_zone, $rs->id_product_attribute, $qty);
      }
    }

    $qr = "SELECT id_product_attribute, SUM(qty) AS qty FROM tbl_buffer ";
    $qr .= "WHERE id_zone = ".$id_zone." GROUP BY id_product_attribute";
    $qs = dbQuery($qr);

    if(dbNumRows($qs) > 0)
    {
      while($rs = dbFetchObject($qs))
      {
        $stock->updateStockZone($id_zone, $rs->id_product_attribute, $rs->qty);
      }
    }

    $qr = "SELECT id_product_attribute, SUM(qty) AS qty FROM tbl_cancle ";
    $qr .= "WHERE id_zone = ".$id_zone." GROUP BY id_product_attribute";
    $qs = dbQuery($qr);

    if(dbNumRows($qs) > 0)
    {
      while($rs = dbFetchObject($qs))
      {
        $stock->updateStockZone($id_zone, $rs->id_product_attribute, $rs->qty);
      }
    }


    echo 'success';
}

?>
