<?php
    $id_employee = $_COOKIE['user_id'];
    $id_order =  $_GET['id_order'];
    $bill_discount = bill_discount($id_order);
    $order = new order($id_order);
    $role = $order->role;
    $customer = new customer($order->id_customer);
    $sale = new sale($order->id_sale);
    $temp = new temp();
    $bill = new bill();

    $reference	= $order->reference;
    $cus_label	= $role == 3 ? '' : (($role == 7 OR $role == 4 )? 'ผู้รับ : ' :  'ลูกค้า : ' );
    $onlineLabel	= $order->payment == 'ออนไลน์' ? ' ( '.getCustomerOnlineReference($id_order).' )' : '';
    $cus_info	= $customer->full_name;
    $em_label	= $role == 3 ? 'ผู้ยืม : ' : ( ($role == 1 OR $role == 5) ? 'พนักงานขาย : ' : 'ผู้เบิก : ');
    $em_info		= ($role == 1 OR $role == 5) ? $sale->full_name : employee_name($order->id_employee);
    $onlineEmp	= $order->payment == 'ออนไลน์' ? ' ( '.employee_name($order->id_employee).' ) ' : '';
    $user			= $role == 7 ? employee_name( get_id_user_support($id_order) ) : ( $role == 4 ? employee_name( get_id_user_sponsor($id_order) ) : employee_name( $order->id_employee ) );

if($order->current_state != 9 && $order->current_state != 10) :

	include 'page_error.php';

else :
?>


<div class="row">
  <div class="col-lg-2 col-sm-3">	<strong><?php echo $reference; ?></strong></div>
  <div class="col-lg-5 col-sm-5"><strong><?php echo $cus_label . $cus_info .$onlineLabel; ?></strong></div>
  <div class="col-lg-5 col-sm-4"><strong><p class="pull-right"><?php echo $em_label . $em_info . $onlineEmp; ?></p></strong> </div>
</div>
<hr style="border-color:#CCC; margin-top: 0px; margin-bottom:15px;" />
<div class="row">
  <div class="col-lg-12">

    <dl style="float:left; margin-left:10px;">
      <dt style="float:left; margin:0px; padding-right:10px">วันที่สั่ง : &nbsp;</dt>
      <dd style="float:left; margin:0px; padding-right:10px"><?php echo thaiDate($order->date_add); ?></dd>  |
    </dl>

    <dl style="float:left; margin-left:10px;">
      <dt style="float:left; margin:0px; padding-right:10px">สินค้า :&nbsp;</dt>
      <dd style="float:left; margin:0px; padding-right:10px"><?php echo number_format($order->total_product); ?></dd>  |
    </dl>

    <dl style="float:left; margin-left:10px;">
      <dt style="float:left; margin:0px; padding-right:10px">จำนวน : &nbsp;</dt>
      <dd style="float:left; margin:0px; padding-right:10px"><?php echo number_format($bill->getTotalBillQty($id_order)); ?></dd>  |
    </dl>

  <?php if($order->role == 7) : ?>
    <dl style="float:left; margin-left:10px;">
      <dt style="float:left; margin:0px; padding-right:10px">ผู้ดำเนินการ : &nbsp;</dt>
      <dd style="float:left; margin:0px; padding-right:10px"><?php echo $user; ?></dd>
    </dl>
  <?php endif; ?>

    <p class="pull-right">
      <button type="button" class="btn btn-info btn-sm" onClick="printAddress(<?php echo $id_order; ?>, <?php echo $order->id_customer; ?>)">
        <i class="fa fa-file-text-o"></i> พิมพ์ใบปะหน้า
      </button>
  <?php if( ! isset( $_GET['check_order'] ) ) :  //------ ถ้าไม่ได้เป็นการเรียกดูข้อมูลจากหน้าออเดอร์ -----//	?>

  <?php   if( $order->current_state == 9 ) : ?>
            <button type="button" class="btn btn-sm btn-primary" onClick="printOrder(<?php echo $id_order; ?>)"><i class="fa fa-print"></i> พิมพ์</button>
            <button type="button" class="btn btn-success btn-sm" onClick="printBarcode(<?php echo $id_order; ?>)"><i class="fa fa-print"></i> พิมพ์บาร์โค้ด</button>
            <button type="button" class="btn btn-default btn-sm" onClick="printPackingList(<?php echo $id_order; ?>)"><i class="fa fa-file-text-o"></i> Picking List</button>
  <?php   endif; ?>

  <?php   if( $order->current_state == 10 && ($add OR $edit) ) : ?>
            <button type="button" class="btn btn-sm btn-primary" id="p_btn" onClick="save_iv()">เปิดบิลและตัดสต็อก</button>
  <?php   endif; ?>

  <?php endif; ?>

  <input type="hidden" id="id_order" value="<?php echo $id_order; ?>" />

  <?php if( $order->payment == 'ออนไลน์' ) : ?>
      <input type="hidden" name="online" id="online" value="1" />
  <?php endif; ?>
  </p>


  </div>
</div>

<hr style="border-color:#CCC; margin-top: 0px; margin-bottom:10px;" />
<?php if( ! isset( $_GET['check_order'] ) ) : ?>
<?php 	if( $order->role == 1 ) : ?>
<?php  		if( $order->current_state == 10 ) : ?>
<div class="row">
  <div class="col-lg-3">	<?php echo paymentLabel($id_order); ?></div>
  <div class="col-lg-3 col-lg-offset-4">
    <input type="text" id="bill_discount" class="form-control input-sm" placeholder="เพิ่มหรือแก้ไขส่วนลดท้ายบิล" style="text-align:center" />
  </div>
  <div class="col-lg-2">
    <button class="btn btn-warning btn-sm btn-block" onclick="process_bill_discount(<?php echo $id_order; ?>)"><i class="fa fa-plus"></i>&nbsp; เพิ่มส่วนลดท้ายบิล</button>
  </div>
</div>

<hr style="border-color:#CCC; margin-top: 10px; margin-bottom:15px;" />

<?php		 endif; ?>
<?php 	endif; ?>
<?php endif; ?>

<div class="row">
  <div class="col-lg-12">
    <table class="table table-bordered">
      <thead>
        <th style="width:4%; text-align:center">ลำดับ</th>
        <th style="width:10%; text-align:center">บาร์โค้ด</th>
        <th style="width:30%; text-align:center">สินค้า</th>
        <th style="width:10%; text-align:center">ราคา</th>
        <th style="width:8%; text-align:center">จำนวนสั่ง</th>
        <th style="width:8%; text-align:center">จำนวนที่ได้</th>
        <th style="width:10%; text-align:center">ส่วนลด</th>
        <th style="width:10%; text-align:center">มูลค่า</th>
      </thead>
  <?php
      $qr  = "SELECT od.*, pd.is_visual FROM tbl_order_detail AS od ";
      $qr .= "JOIN tbl_product AS pd ON od.id_product= pd.id_product ";
      $qr .= "WHERE od.id_order = ".$id_order;
      $qs = dbQuery($qr);

      $no = 1;
      $total_amount = 0;
      $total_discount = 0;
      $full_amount = 0;
      $total_qty = 0;
      $total_temp = 0;
  ?>

  <?php while($rs = dbFetchObject($qs)) : ?>
  <?php 	$isVisual = isVisual($rs->id_product_attribute) == 1 ? TRUE : FALSE; ?>
  <?php 	$p_dis = $rs->reduction_percent; ?>
  <?php 	$a_dis = $rs->reduction_amount; ?>
  <?php 	$prepared = $isVisual === TRUE ? 0 : $temp->getPrepared($id_order, $rs->id_product_attribute); ?>
  <?php 	$qty = $isVisual === TRUE ? $rs->product_qty : ($prepared > $rs->product_qty ? $rs->product_qty : $prepared); ?>
  <?php 	$hilight = $rs->product_qty == $qty ? '' : ' color:red;'; ?>
  <?php 	$p_name = $rs->product_reference. " : ". $rs->product_name; ?>
  <?php 	$p_name = substr($p_name, 0, 100); ?>
  <?php 	$amount = $qty * $rs->product_price; ?>
  <?php 	$discountLabel = discountLabel($p_dis, $a_dis); ?>
  <?php 	$discount_amount = $p_dis > 0 ? ($qty * ($rs->product_price * ($p_dis * 0.01))) : $qty * $a_dis; ?>

      <tr style="font-size:12px;<?php echo $hilight; ?>">
        <td align="center"><?php echo $no; ?></td>
        <td align="center"><?php echo $rs->barcode; ?></td>
        <td><?php echo $p_name;  ?></td>
        <td align="center"><?php echo number_format($rs->product_price,2); ?></td>
        <td align="center"><?php echo number_format($rs->product_qty); ?></td>
        <td align="center"><?php echo number_format($qty); ?></td>
        <td align="center"><?php echo $discountLabel; ?></td>
        <td align="right"><?php echo number_format(($amount - $discount_amount),2); ?></td>
      </tr>
  <?php
          $total_amount 	+= $amount;
          $total_discount += $discount_amount;
          $full_amount 		+= $amount;
          $total_qty 			+= $rs->product_qty;
          $total_temp 		+= $qty;
          $no++;
      endwhile;
  ?>

      <tr>
        <td colspan="4" align="right">รวม</td>
          <td align="center"><?php echo number_format($total_qty); ?></td>
          <td align="center"><?php echo number_format($total_temp); ?></td>
          <td >ส่วนลดท้ายบิล</td>
          <td align="right"><?php echo number_format($bill_discount, 2); ?></td>
      </tr>
      <tr >
        <td colspan="4" rowspan="3"><strong>หมายเหตุ : </strong><?php echo $order->comment; ?></td>
        <td colspan="2"><strong>ราคารวม</strong></td>
        <td colspan="2" align="right"><?php echo number_format($full_amount,2); ?></td>
      </tr>
      <tr>
        <td colspan="2"><strong>ส่วนลด</strong></td>
        <td colspan="2" align="right"><?php echo number_format($total_discount + $bill_discount, 2); ?></td>
      </tr>
       <tr>
        <td colspan="2"><strong>ยอดเงินสุทธิ</strong></td>
        <td colspan="2" align="right"><?php echo number_format($full_amount - ($total_discount + $bill_discount) ,2); ?></td>
      </tr>
    </table>
  </div>
</div>
<?php if( $order->current_state == 10 ) : ?>
<script>	var interv = setInterval(function(){ checkBill(); }, 2000);  </script>
<?php endif; ?>
<?php endif; ?>
