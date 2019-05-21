<?php
$sc = "";
$id		= $_GET['id_receive_product'];
$ro = new receive_product($id);
$po = new po($ro->id_po);
$sup = new supplier($ro->id_supplier);
$zone = new zone($ro->id_zone);
$print = new printer();
$sc .= $print->doc_header();
$print->add_title("เอกสารรับสินค้าเข้าคลัง");
$header	= array(
              "เลขที่เอกสาร" => $ro->reference,
              "เลขที่ใบสั่งซื้อ" => $ro->po_reference,
              "ผู้ทำรายการ" => employee_name($ro->id_employee),
              "เลขที่ใบส่งของ" => $ro->invoice,
              "ผู้ขาย" => $sup->name,
              "วันที่" => thaiDate($ro->date_add),
              "โซน" => $zone->zone_name
            );
$print->add_header($header);
$details = $ro->getSavedDetails($id);
$total_row = dbNumRows($details);
$config = array(
            "total_row"=>$total_row,
            "font_size"=>10,
            "sub_total_row"=>2
          );

$print->config($config);
$row = $print->row;
$total_page = $print->total_page;
$total_qty = 0;
//**************  กำหนดหัวตาราง  ******************************//
$thead	= array(
          array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
          array("รหัส", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
          array("สินค้า", "text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
          array("จำนวน", "width:15%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
          );
$print->add_subheader($thead);

//***************************** กำหนด css ของ td *****************************//
$pattern = array(
            "text-align: center; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px; text-align:center"
            );
$print->set_pattern($pattern);

//*******************************  กำหนดช่องเซ็นของ footer *******************************//
$footer	= array(
          array("ผู้ทำรายการ", "","วันที่............................."),
          array("ผู้ตรวจสอบ", "","วันที่............................."),
          array("ผู้อนุมัติ", "","วันที่.............................")
          );
$print->set_footer($footer);
$n = 1;
while($total_page > 0 )
{
  $sc .= $print->page_start();
  $sc .= $print->top_page();
  $sc .= $print->content_start();
  $sc .= $print->table_start();
  $i = 0;
  $product = new product();
  while($i<$row)
  {
    $rs = dbFetchObject($details);
    if(!empty($rs))
    {
      $pd = $product->getDetail($rs->id_product_attribute);
      $data = array(
                0 => $n,
                1 => inputRow($pd->reference),
                2 => inputRow($pd->product_name),
                3 => number($rs->qty)
            );
      $total_qty += $rs->qty;
    }
    else
    {
      $data = array("", "", "", "");
    }

    $sc .= $print->print_row($data);
    $n++;
    $i++;
  }

  $sc .= $print->table_end();

  if($print->current_page == $print->total_page)
  {
    $qty = number_format($total_qty);
    $remark = $ro->remark;
  }
  else
  {
    $qty = "";
    $remark = "";
  }

  $sub_total = array(
          array("<td rowspan='2' style='height:".$print->row_height."mm; border-top: solid 1px #ccc; border-bottom-left-radius:10px; width:60%; font-size:10px;'><strong>หมายเหตุ : </strong>".$remark."</td>
              <td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>จำนวนรวม</strong></td>
              <td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$qty."</td>"),
          array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>มูลค่ารวม</strong></td>
          <td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'> - </td>")
          );

  $sc .= $print->print_sub_total($sub_total);
  $sc .= $print->content_end();
  $sc .= $print->footer;
  $sc .= $print->page_end();
  $total_page --;
  $print->current_page++;
}

$sc .= $print->doc_footer();

echo $sc;

 ?>
