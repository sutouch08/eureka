
<div class="row">
<div class="col-sm-12">
	<table class="table table-striped">
    <thead style="color:#FFF; background-color:#48CFAD;">
      <th style="width:5%; text-align:center;">ลำดับ</th>
			<th style="width:10%;">เลขที่อ้างอิง</th>
			<th>ลูกค้า</th>
      <th style="width:10%; text-align:center;">ยอดเงิน</th>
			<th style="width:10%; text-align:center;">สถานะ</th>
			<th style="width:10%;">พนักงาน</th>
			<th style="width:10%; text-align:center;">วันที่เพิ่ม</th>
			<th style="width:10%; text-align:center;">วันที่ปรับปรุง</th>
    </thead>
	<?php		$qs = dbQuery("SELECT * FROM tbl_order WHERE current_state = 10 ORDER BY id_order DESC"); ?>
  <?php  	$no = 1; ?>
	<?php	if(dbNumRows($qs) > 0) :   ?>
	<?php 	while($rs = dbFetchObject($qs)) : ?>
	<?php     $order = new order($rs->id_order); ?>
	<?php		  $amount = $order->total_amount - $order->getBillDiscount($rs->id_order); ?>
	<?php		  $customer_name = customer_name($order->id_customer);  ?>
	<?php     $online = getCustomerOnlineReference($order->id_order);  ?>
	<?php			$customer  = $order->payment != 'ออนไลน์' ? $customer_name : ( $online != '' ? $customer_name.' ( '.$online.' )' : $customer_name ); ?>
	<?php 	  $emp = new employee($order->id_employee); ?>
	<?php 		$link = "style='cursor:pointer;' onclick=\"document.location='index.php?content=bill&id_order=".$order->id_order."&view_detail=y'\""; ?>

		        <tr style="font-size:12px;">
  						<td align="center" <?php echo $link; ?>><?php echo $no; ?></td>
  						<td <?php echo $link; ?>><?php echo $order->reference; ?></td>
  						<td <?php echo $link; ?>><?php echo $customer; ?></td>
  						<td align="center" <?php echo $link; ?>><?php echo number_format($amount,2); ?></td>
  						<td align="center" <?php echo $link; ?>><?php echo $order->current_state_name; ?></td>
  						<td <?php echo $link; ?>><?php echo $emp->full_name; ?></td>
  						<td align="center" <?php echo $link; ?>><?php echo thaiDate($order->date_add); ?></td>
  						<td align="center" <?php echo $link; ?>><?php echo thaiDate($order->date_upd); ?></td>
  					</tr>
	<?php    $no++; ?>
  <?php   endwhile; ?>
  <?php else : ?>
			     <tr>
             <td colspan="9" align="center"><h3><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;ไม่มีรายการในช่วงนี้</h3></td>
           </tr>
	<?php endif; ?>
		</table>
  </div>
</div>
