<!---  Order Table  ----------------------------------------------------------->
<?php
	$qm = dbQuery("SELECT discount_amount FROM tbl_order_discount WHERE id_order = ".$id_order);
	if( dbNumRows($qm) )
	{
		$rm = dbFetchArray($qm);
		$l_discount = $rm['discount_amount'];
	}else{
		$l_discount = 0;
	}
	$orderTxt	= '';
?>

<?php if($order->current_state != 9 && $order->current_state != 8 ) : ?>
	<?php if($edit || $add) : ?>
        <button type='button' id='edit_reduction' class='btn btn-default' >แก้ไขส่วนลด</button>
        <button type='button' id='save_reduction' class='btn btn-primary' onclick="verifyPassword()" style="display:none;" >บันทึกส่วนลด</button>
        <button type="button" id="btn-edit-price" class="btn btn-default" onClick="getEditPrice()">แก้ไขราคา</button>
        <button type="button" id="btn-update-price" class="btn btn-primary" onClick="confirmEditPrice()" style="display:none;">บันทึกราคา</button>
       <?php if(!$l_discount) : ?>
       		<button type="button" id="btn_add_discount" class="btn btn-default" ><i class="fa fa-plus"></i>&nbsp;เพิ่มส่วนลดท้ายบิล</button>
            <button type="button" id="btn_save_discount" class="btn btn-success" onclick="add_discount()" style="display:none;"><i class="fa fa-save"></i>&nbsp;บันทึกส่วนลดท้ายบิล</button>
       <?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
	<table id='product_table' class='table' style='width:100%; padding:10px; border: 1px solid #ccc; margin-top:10px;'>
    <thead>
    	<th style='width:10%; text-align:center;'>รูปภาพ</th>
        <th>สินค้า</th>
        <th style='width:10%; text-align:center;'>ราคา</th>
        <th style='width:12%; text-align:center;'>ส่วนลด</th>
        <th style='width:10%; text-align:center;'>จำนวน</th>
        <th style='width:10%; text-align:center;'>มูลค่า</th>
        <th style='width:5% text-align:center;'></th>
    </thead>
    <tbody id="orderTable">
<?php	$qs = dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id_order);		?>
<?php 	$orderTxt = 'สรุปการสั่งซื้อ<br/> Order No : '.$order->reference.' <br/>';		?>
<?php 	$orderTxt .= '--------------------------------------- <br/>';	?>
<?php	if( dbNumRows($qs) > 0 ) :	?>
<?php	$total_disc = 0;  $total_price = 0; $total_amount = 0; 	?>
<?php		$product = new product();	?>
<?php		while( $rs = dbFetchArray($qs) ) : 	?>
<?php			$id 		= $rs['id_order_detail'];	?>
<?php 			$id_pa 	= $rs['id_product_attribute']; 	?>
<?php 			$disc		= $rs['reduction_percent'] > 0 ? $rs['reduction_percent'] : $rs['reduction_amount']; 	?>
				<tr id="row_<?php echo $id; ?>" style="font-size:12px;">
                    <td style='text-align:center; vertical-align:middle;'><img src="<?php echo $product->get_product_attribute_image($id_pa,1); ?>" width="35px" height="35px" /></td>
                    <td style='vertical-align:middle;'><?php echo $rs['product_reference']." : ".get_product_name($rs['id_product'])." : ".$rs['barcode']; ?></td>
                    <td style='text-align:center; vertical-align:middle;'>
                    	<span id="price_<?php echo $id; ?>" class="price_label"><?php echo number_format($rs['product_price'], 2); ?></span>
                        <input type="text" class="form-control input-sm input-price" name="price[<?php echo $id; ?>]" id="price<?php echo $id; ?>"
                        		 value="<?php echo $rs['product_price']; ?>" style="display:none;" />
                    </td>
                    <td style='text-align:center; vertical-align:middle;'>
                        <p class='reduction'><?php echo discountLabel($rs['reduction_percent'], $rs['reduction_amount']); ?></p>
                       <div class='input_reduction' style='display:none;'>
                       		<input type='text' class='form-control input-sm input-discount' id="reduction<?php echo $id; ?>"
                       				name="reduction[<?php echo $id; ?>]" value='<?php echo $disc; ?>' onKeyUp="verifyDiscount(<?php echo $id; ?>, '<?php echo $rs['product_price']; ?>')" />
                            <select class="form-control input-sm input-unit" id="unit<?php echo $id; ?>" name="unit[<?php echo $id; ?>]" onChange="verifyDiscount(<?php echo $id; ?>, '<?php echo $rs['product_price']; ?>')" >
                                <option value="percent" <?php if( $rs['reduction_percent'] > 0 ) { echo "selected"; } ?> >%</option>
                                <option value="amount" <?php if( $rs['reduction_amount'] > 0 ) { echo "selected"; } ?> >฿</option>
                            </select>
                        </div>
                    </td>
                    <td style='text-align:center; vertical-align:middle;'>	<?php echo number_format($rs['product_qty']); ?></td>
                    <td style='text-align:center; vertical-align:middle;'><?php echo number_format($rs['total_amount'], 2); ?></td>
                    <td style='text-align:center; vertical-align:middle;'>
                    <?php if($edit && ($order->current_state == 3 || $order->current_state == 1 ) ) : ?>
                            <button type="button" class="btn btn-danger btn-sm" onClick="deleteItem(<?php echo $id; ?>, '<?php echo $rs['product_reference']; ?>')"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                    </td>
                </tr>
<?php
					$orderTxt .=   $rs['product_reference'].' :  ('.number_format($rs['product_qty']).') x '.number_format($rs['product_price'], 2).' <br/>';
					$total_disc 		+= $rs['discount_amount'];
					$total_price 	+= $rs['product_qty'] * $rs['product_price'];
					$total_amount 	+= $rs['total_amount'];
			endwhile;

?>
<?php if( $l_discount ) : ?>
		<tr id="last_discount_row" >
        	<td colspan="5" style="text-align: right; vertical-align:middle; padding-right:20px;">ส่วนลดท้ายบิล</td>
            <td style='text-align:center; vertical-align:middle;'>
            	<span id="discount_label"><?php echo number_format($l_discount, 2); ?></span>
                <input type="text" id="last_discount" class="form-control input-sm" style="text-align:right; display:none;" value="<?php echo $l_discount; ?>" />
            </td>
            <td style='text-align:center; vertical-align:middle;'>
            <?php if($order->current_state == 3 || $order->current_state == 1 ) : ?>
            	<?php if($edit) : ?>
            	<button type="button" class="btn btn-warning" id="btn_edit_discount" onclick="edit_discount()"><i class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-danger" id="btn_delete_discount" onclick="action_delete(<?php echo $id_order.", ".number_format($l_discount, 2); ?>)"><i class="fa fa-trash"></i></button>
                <button type="button" class="btn btn-success" id="btn_update_discount" style="display:none;"><i class="fa fa-save"></i>&nbsp; Update</button>
                <?php endif; ?>
            <?php endif; ?>
            </td>
        </tr>
<?php else : ?>
		<tr id="last_discount_row" style="display:none;" >
        	<td colspan="5" align="right" style="padding-right:20px;">ส่วนลดท้ายบิล</td>
            <td><input type="text" id="last_discount" class="form-control input-sm" style="text-align:right" /></td>
            <td>บาท</td>
        </tr>
<?php endif; ?>

		<tr>
			<td rowspan='3' colspan='4'></td>
			<td style='border-left:1px solid #ccc'><b>สินค้า</b></td>
            <td colspan='2' align='right' id="total_price"><b><?php echo number_format($total_price,2); ?> </b></td>
       </tr>
		<tr>
        	<td style='border-left:1px solid #ccc'><b>ส่วนลด</b></td>
        	<td colspan='2' align='right' id="total_disc"><b><?php echo number_format(($total_disc + $l_discount), 2); ?> </b></td>
        </tr>
		<tr>
        	<td style='border-left:1px solid #ccc'><b>สุทธิ </b></td>
        	<td colspan='2' align='right' id="net"><b><?php echo number_format(($total_amount-$l_discount),2); ?> </b></td>
        </tr>
<?php 	$orderTxt .= '--------------------------------------- <br/>';	?>
<?php 	if( ($total_disc + $l_discount) > 0 )
			{
				$orderTxt .= 'ส่วนลดรวม'.getSpace(number_format( ($total_disc + $l_discount), 2), 27).'<br/>';
			 	$orderTxt .= '--------------------------------------- <br/>';
			}
?>
<?php 	if( $fee > 0 ){
				$orderTxt .= 'ค่าจัดส่ง'.getSpace(number_format($fee, 2), 31).'<br/>';
			 	$orderTxt .= '--------------------------------------- <br/>';
			}
?>
<?php	$orderTxt .= 'ยอดชำระ' . getSpace(number_format( ($total_amount - $l_discount)+$fee, 2), 29).'<br/>';	?>
<?php 	$orderTxt .= '---------------------------------------';	?>
<?php	else :  ?>
		<tr>
            <td colspan='7' align='center'><h4>ไม่มีรายการสินค้า</h4></td>
       	</tr>

<?php endif;  ?>
		</tbody>
   	</table>

<!--  End order table  --------------------------------------------------------->
