<?php
$vt_c					= getCookie('viewType') ? getCookie('viewType') : '';
$user_id 			= $_COOKIE['user_id'];
$id_order			= isset($_GET['id_order']) ? $_GET['id_order'] : '';
$active 	= isset($_GET['id_order']) ? 'disabled' : '';
$order 				= isset($_GET['id_order']) ? new order($id_order) : '';
$new_ref 			= isset($_GET['id_order']) ? $order->reference: get_max_role_reference("PREFIX_ORDER",1);
$customer 			= isset($_GET['id_order']) ? new customer($order->id_customer) : '';
$id_customer 		= isset($_GET['id_order']) ? $customer->id_customer : '';
$customer_name 	= isset($_GET['id_order']) ? $customer->full_name : '';
$comment 			= isset($_GET['id_order']) ? $order->comment : '';
$payment 			= isset($_GET['id_order']) ? $order->payment : '';
$onlineCustomer	= isset($_GET['id_order']) ? getCustomerOnlineReference($id_order) : '';
$date_add  = isset($_GET['id_order']) ? thaiDate($order->date_add) : date('d-m-Y');
$isCOD 	= isset($_GET['id_order']) ? $order->isCOD : 0;
$cod = $isCOD == 1 ? 'btn-success' : '';
$cod_text = $isCOD == 1 ? '<i class="fa fa-check"></i> เก็บเงินปลายทาง' : 'เก็บเงินปลายทาง';
?>
<form id='addForm'>
<div class='row'>
<input type='hidden' name='id_employee' value='<?php echo $user_id; ?>' />
  <input type='hidden' name='id_order' id='id_order' value='<?php echo $id_order; ?>' />
  <input type='hidden' name='id_customer' id='id_customer' value='<?php echo $id_customer; ?>' />
  <input type="hidden" name="role" id="role" value="1" />
  <input type="hidden" name="isCOD" id="isCOD" value="<?php echo $isCOD; ?>" />
<div class='col-sm-2'>
    <label>เลขที่เอกสาร</label>
      <input type='text' id='doc_id' class='form-control input-sm' value='<?php echo $new_ref; ?>' disabled='disabled'/>
  </div>
<div class='col-sm-2'>
  <label>วันที่</label>
  <input type='text' id='doc_date' name='doc_date' class='form-control input-sm text-center' value='<?php echo $date_add; ?>' <?php echo $active; ?> />
  </div>
<div class='col-sm-4'>
        <label>ชื่อลูกค้า</label>
          <input type='text' id='customer_name' class='form-control input-sm' value='<?php echo $customer_name; ?>' autocomplete='off' <?php echo $active; ?> />
  </div>
  <?php if( isset( $_GET['online'] ) OR $payment == 'ออนไลน์' ) : ?>
  <div class="col-sm-2">
    <label>อ้างอิงลูกค้า</label>
      <input type="text" name="online" id="online" class="form-control input-sm" value="<?php echo $onlineCustomer; ?>" <?php echo $active; ?> />
      <input type="hidden" name="payment" id="payment" value="ออนไลน์" />
  </div>
  <?php else : ?>
<div class='col-sm-2'>
      <label>การชำระเงิน</label>
      <select name='payment' id='payment' class='form-control input-sm' <?php echo $active; ?> ><?php echo paymentMethod($payment); ?></select>
  </div>
  <div class="col-sm-2">
    <label style="display:block; visibility:hidden;">COD</label>
    <button type="button" class="btn btn-sm <?php echo $cod; ?>" id="btn-cod" onclick="toggleCOD()" <?php echo $active; ?>><?php echo $cod_text; ?></button>
  </div>
  <?php endif; ?>
<div class='col-sm-10'>
  <label>หมายเหตุ</label>
    <input type='text' id='comment' name='comment' class='form-control input-sm' value='<?php echo $comment; ?>' autocomplete='off' <?php echo $active; ?> />
  </div>
<div class='col-sm-2'>
    <label style="display:block; visibility:hidden">button</label>
  <?php if( !isset( $_GET['id_order'] ) ) : ?>
    <?php 	if( $add ) : ?>
  <button class='btn btn-default btn-sm btn-block' type='button' id='btnAdd' onClick="newOrder()">สร้างออเดอร์</button>
  <?php 	endif; ?>
<?php else : ?>
  <?php if( $edit ) : ?>
        <button class='btn btn-default btn-sm btn-block' type='button' id='btnEdit' onClick="editOrder()"><i class="fa fa-pencil"></i> แก้ไขออเดอร์</button>
          <button type="button" class="btn btn-sm btn-success btn-block" id="btnUpdate" onClick="updateOrder(<?php echo $id_order; ?>)" style="display:none;"><i class="fa fa-save"></i> ปรับปรุง</button>
  <?php endif; ?>
<?php endif; ?>
  </div>
</div><!--/ row -->
</form>

<hr style='border-color:#CCC; margin-top: 15px; margin-bottom:15px;' />

<?php if( isset( $_GET['id_order'] ) ) :  ?>

<div class='row'>
<div class="col-sm-3">
    <input type="text" class="form-control input-sm text-center" id="sProduct" placeholder="ค้นหาสินค้า" />
  </div>
  <div class="col-sm-2">
    <button type="button" class="btn btn-primary btn-sm btn-block" onClick="getProduct()"><i class="fa fa-tags"></i> แสดงสินค้า</button>
  </div>
</div>

<hr style='border-color:#CCC; margin-top: 15px; margin-bottom:0px;' />

<!--- Category Menu ---------------------------------->
<div class='row'>
<div class='col-sm-12'>
  <ul class='nav navbar-nav' role='tablist' style='background-color:#EEE'>
  <?php echo categoryTabMenu('order'); ?>
  </ul>
</div><!---/ col-sm-12 ---->
</div><!---/ row -->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:0px;' />
<div class='row'>
<div class='col-sm-12'>
  <div class='tab-content' style="min-height:1px; padding:0px;">
  <?php echo getCategoryTab(); ?>
  </div>
</div>
</div>
<!--- End Category Menu ------------------------------------>

<form id="gridForm">
<div class='modal fade' id='order_grid' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog' id='modal'>
    <div class='modal-content'>
        <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h4 class='modal-title' id='modal_title'>title</h4>
                  <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
                  <input type="hidden" name="id_order" id="id_order" value="<?php echo $id_order; ?>" />
       </div>
       <div class='modal-body' id='modal_body'></div>
       <div class='modal-footer'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>ปิด</button>
        <button type='button' class='btn btn-primary' onClick="addToOrder(<?php echo $id_order; ?>)" >เพิ่มในรายการ</button>
       </div>
    </div>
  </div>
</div>
</form>

<div class='row'>
<div class='col-sm-12'>
<table class='table' id='order_detail' style="border: solid 1px #ddd;">
<thead>
  <tr style='font-size: 12px;'>
  <th stype='width:5%; text-align:center;'>ลำดับ</th>
      <th style='width:5%; text-align:center;'>รูป</th>
      <th style='width:10%;'>บาร์โค้ด</th>
      <th style='width:30%;'>สินค้า</th>
  <th style='width:10%; text-align:center;'>ราคา</th>
      <th style='width:10%; text-align:center;'>จำนวน</th>
  <th style='width:10%; text-align:center;'>ส่วนลด</th>
      <th style='width:10%; text-align:center;'>มูลค่า</th>
      <th style='text-align:center;'>การกระทำ</th>
</tr>
  </thead>
  <tbody id="orderProductTable">
<?php	$qs 		= dbQuery("SELECT * FROM tbl_order_detail WHERE id_order = ".$id_order." ORDER BY id_order_detail DESC"); 	?>
<?php	$n 		= 1;	?>
<?php	$tq 		= 0;	?>
<?php	if( dbNumRows($qs) > 0 ) :	?>
<?php		$product = new product();	?>
<?php		while( $rs = dbFetchArray($qs) ) :		?>
  <tr style='font-size: 12px;'>
        <td style='text-align:center; vertical-align:middle;'><?php echo $n; ?></td>
          <td style='text-align:center; vertical-align:middle;'><img src='<?php echo $product->get_product_attribute_image($rs['id_product_attribute'], 1); ?>' width='35px' height='35px' /> </td>
          <td style='vertical-align:middle;'><?php echo $rs['barcode']; ?></td>
          <td style='vertical-align:middle;'><?php echo $rs['product_reference']." : ".$rs['product_name']; ?></td>
          <td style='text-align:center; vertical-align:middle;'><?php echo number_format($rs['product_price'], 2); ?></td>
          <td style='text-align:center; vertical-align:middle;'><?php echo number_format($rs['product_qty']); ?></td>
          <td style='text-align:center; vertical-align:middle;'><?php echo discountLabel($rs['reduction_percent'], $rs['reduction_amount']);  ?></td>
          <td style='text-align:center; vertical-align:middle;'><?php echo number_format($rs['total_amount'], 2); ?></td>
          <td style='text-align:center; vertical-align:middle;'>
            <button type="button" class="btn btn-danger btn-xs" onClick="deleteRow(<?php echo $rs['id_order_detail']; ?>, '<?php echo $rs['product_reference']; ?>')"><i class="fa fa-trash"></i></button>
          </td>
      </tr>
<?php	$tq += $rs['product_qty'];	$n++;		?>
<?php endwhile; ?>
<tr>
  <td colspan='6'></td>
      <td><h4>จำนวน</h4></td>
      <td style='text-align:center; vertical-align:middle;'><h4><?php echo number_format($tq); ?></h4></td>
      <td><h4>ชิ้น<h4></td>
</tr>
<?php else : ?>
<tr>
    <td colspan='9' align='center'><h4>&nbsp;</h4></td>
  </tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
<?php endif; ?>
