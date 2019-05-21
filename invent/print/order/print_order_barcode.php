<?php
  $sc = '';
  $sc .= $print->doc_header();

  while($group = dbFetchObject($pdGroup))
  {
    $print 		= new printer();
  	$print->add_title($doc['title'].' ('.$group->name.')');
  	$header		= get_header($order);
  	$print->add_header($header);

    $qr  = "SELECT ods.* FROM tbl_order_detail_sold AS ods ";
    $qr .= "LEFT JOIN tbl_product AS pd ON ods.id_product = pd.id_product ";
    $qr .= "WHERE ods.id_order = ".$id_order." ";
    $qr .= "AND pd.id_product_group = ".$group->id;

    $qs = dbQuery($qr);

    $total_row 	= dbNumRows($qs);
  	$config 		= array(
                      "total_row" => $total_row,
                      "font_size" => 10,
                      "sub_total_row" => 4
                    );

  	$print->config($config);
  	$row 			= $print->row;
  	$total_page 	= $print->total_page;
  	$total_qty 	= 0;
  	$total_amount 		= 0;
  	$total_discount 	= 0;
    $bill_discount		= bill_discount($id_order);
    //**************  กำหนดหัวตาราง  ******************************//
        $thead	= array(
                  array("ลำดับ", "width:5%; text-align:center; border-top:0px; border-top-left-radius:10px;"),
                  array("บาร์โค้ด", "width:15%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
                  array("สินค้า", "width:40%; text-align:center;border-left: solid 1px #ccc; border-top:0px;"),
                  array("ราคา", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
                  array("จำนวน", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
                  array("ส่วนลด", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px;"),
                  array("มูลค่า", "width:10%; text-align:center; border-left: solid 1px #ccc; border-top:0px; border-top-right-radius:10px")
                  );
        $print->add_subheader($thead);

        //***************************** กำหนด css ของ td *****************************//
        $pattern = array(
                    "text-align: center; border-top:0px;",
                    "border-left: solid 1px #ccc; border-top:0px; padding-top:3px; padding-bottom:3px;",
                    "border-left: solid 1px #ccc; border-top:0px;",
                    "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
                    "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
                    "text-align:center; border-left: solid 1px #ccc; border-top:0px;",
                    "text-align:right; border-left: solid 1px #ccc; border-top:0px;"
                    );
        $print->set_pattern($pattern);

        //*******************************  กำหนดช่องเซ็นของ footer *******************************//
        $footer	= array(
                  array("ผู้รับของ", "ได้รับสินค้าถูกต้องตามรายการแล้ว","วันที่............................."),
                  array("ผู้ส่งของ", "","วันที่............................."),
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
  					$rs = dbFetchObject($qs);
  					if(!empty($rs))
            {
  						$totalPrice 	= $rs->sold_qty * $rs->product_price;
  						$discLabel = show_discount($rs->reduction_percent, $rs->reduction_amount);

              $data = array(
                0 => $n,
                1 => barcodeImage($rs->barcode, 8), //-- height="8mm"
                2 => inputRow($rs->product_reference.' : '.$rs->product_name),
                3 => number($rs->product_price,2),
                4 => number($rs->sold_qty),
                5 => $discLabel,
                6 => number($rs->total_amount, 2)
              );

  						$total_qty += $rs->sold_qty;
  						$total_amount += $totalPrice;
  						$total_discount += $rs->discount_amount;
            }
  					else
            {
  						$data = array("", "", "", "","", "","");
  					}

  					$sc .= $print->print_row($data);
  					$n++;
            $i++;
  				}

  				$sc .= $print->table_end();

  				if($print->current_page == $print->total_page)
  				{
  					$qty = number($total_qty);
  					$amount = number($total_amount,2);
  					$total_discount_amount = number($total_discount,2);
  					$net_amount = number($total_amount - $total_discount ,2);
  					$remark = $order->comment;
  				}
          else
          {
  					$qty = "";
  					$amount = "";
  					$total_discount_amount = "";
  					$net_amount = "";
  					$remark = "";
  				}
  				$sub_total = array(
  						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px; border-left:0px; width:60%; text-align:center;'>**** ส่วนลดท้ายบิล : ".number($bill_discount,2)." ****</td>
  								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>จำนวนรวม</strong></td>
  								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$qty."</td>"),
  						array("<td rowspan='3' style='height:".$print->row_height."mm; border-top: solid 1px #ccc; border-bottom-left-radius:10px; width:55%; font-size:14px;'><strong>หมายเหตุ : </strong>".$remark."</td>
  								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc;'><strong>ราคารวม</strong></td>
  								<td style='width:20%; height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; text-align:right;'>".$amount."</td>"),
  						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>ส่วนลดรวม</strong></td>
  						<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'>".$total_discount_amount."</td>"),
  						array("<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-bottom:0px;'><strong>ยอดเงินสุทธิ</strong></td>
  						<td style='height:".$print->row_height."mm; border: solid 1px #ccc; border-right:0px; border-bottom:0px; border-bottom-right-radius:10px; text-align:right;'>".$net_amount."</td>")
  						);
  			$sc .= $print->print_sub_total($sub_total);
  			$sc .= $print->content_end();
  			$sc .= $print->footer;
  		$sc .= $print->page_end();
  		$total_page --; $print->current_page++;
  	} //--- end while


  } //--- end while
  $sc .= $print->doc_footer();
  echo $sc;
