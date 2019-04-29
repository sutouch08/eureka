<?php

$id		= $_GET['id_receive_product'];
$ro = new receive_product($id);
$po = new po($ro->id_po);
$print = new printer();
echo $print->doc_header();
$print->add_title("เอกสารรับสินค้าเข้าตามใบสั่งซื้อ");
$header	= array(
              "เลขที่เอกสาร"=>$ro->reference,
              "เลขที่ใบสั่งซื้อ"=>$ro->po_reference,
              "ผู้ทำรายการ"=>employee_name($ro->id_employee),
              "เลขที่ใบส่งของ"=>$ro->invoice,
              "Supplier"=> supplier_name($po->id_supplier),
              "วันที่"=>thaiDate($ro->date_add)
            );
$print->add_header($header);
$detail = $ro->get_saved_items($id);
$total_row = dbNumRows($detail);
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
          array("บาร์โค้ด", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
          array("สินค้า", "width:45%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
          array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
          array("โซน", "width:20%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
          );
$print->add_subheader($thead);

//***************************** กำหนด css ของ td *****************************//
$pattern = array(
            "text-align: center; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px; padding-top:3px; padding-bottom:3px;",
            "border-left: solid 1px #ccc; border-top:0px;",
            "border-left: solid 1px #ccc; border-top:0px; text-align:center;",
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
$product = new product();
while($total_page > 0 )
{
  echo $print->page_start();
    echo $print->top_page();
    echo $print->content_start();
      echo $print->table_start();
      $i = 0;

      while($i<$row) :
        $rs = dbFetchArray($detail);
        if(!empty($rs))
        {
          $pd = $product->getDetail($rs['id_product_attribute']);
          $data = array(
            0 => $n,
            1 => barcodeImage($pd->barcode, 8),
            2 => inputRow($pd->reference.' : '.$pd->product_name),
            3 => $rs['qty'],
            4 => get_zone($rs['id_zone'])
          );

          $total_qty += $rs['qty'];
        }
        else
        {
          $data = array("", "", "", "","");
        }
        echo $print->print_row($data);
        $n++; $i++;
      endwhile;
      echo $print->table_end();
      if($print->current_page == $print->total_page){ $qty = number_format($total_qty); $remark = $ro->remark; }else{ $qty = ""; $remark = ""; }
      $sub_total = array(
          array("<td rowspan='2' style='height:".$print->row_height."mm; border-top: solid 1px #ccc; border-bottom-left-radius:10px; width:60%; font-size:10px;'><strong>หมายเหตุ : </strong>".$remark."</td>
              <td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>จำนวนรวม</strong></td>
              <td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$qty."</td>"),
          array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>มูลค่ารวม</strong></td>
          <td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'> - </td>")
          );
    echo $print->print_sub_total($sub_total);
    echo $print->content_end();
    echo $print->footer;
  echo $print->page_end();
  $total_page --; $print->current_page++;
}
echo $print->doc_footer();
 ?>
